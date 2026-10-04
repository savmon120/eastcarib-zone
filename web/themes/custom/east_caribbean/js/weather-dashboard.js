(function (Drupal, drupalSettings) {
    'use strict';

    const SVG_NS = 'http://www.w3.org/2000/svg';
    const GAUGE_MAX = 40;      // kt, top of the speed gauge
    const GAUGE_STEP = 2;      // kt per gauge segment
    const XWIND_MAX = 25;      // kt, each end of the crosswind bar
    const XWIND_CELLS = 21;    // odd, so there is a centre cell for zero

    const pad = (n, len = 2) => String(n).padStart(len, '0');

    function polar(deg, r) {
        const rad = (deg - 90) * Math.PI / 180;
        return [100 + r * Math.cos(rad), 100 + r * Math.sin(rad)];
    }

    function sectorPath(from, to, r1, r2) {
        const [ax, ay] = polar(from, r2);
        const [bx, by] = polar(to, r2);
        const [cx, cy] = polar(to, r1);
        const [dx, dy] = polar(from, r1);
        const f = (n) => n.toFixed(2);
        return `M${f(ax)} ${f(ay)} A${r2} ${r2} 0 0 1 ${f(bx)} ${f(by)} L${f(cx)} ${f(cy)} A${r1} ${r1} 0 0 0 ${f(dx)} ${f(dy)} Z`;
    }

    function svg(tag, attrs) {
        const node = document.createElementNS(SVG_NS, tag);
        Object.entries(attrs).forEach(([k, v]) => node.setAttribute(k, v));
        return node;
    }

    const angleDiff = (a, b) => Math.abs(((a - b + 540) % 360) - 180);

    const inSector = (deg, from, to) => ((deg - from + 360) % 360) <= ((to - from + 360) % 360);

    function speedZone(kt) {
        if (kt > 25) return 'red';
        if (kt > 15) return 'amber';
        return 'green';
    }

    function formatVisibility(vis) {
        if (vis.meters !== null) {
            return vis.meters >= 9999 ? '>10 KM' : `${vis.meters} M`;
        }
        return vis.statute_miles ? `${vis.statute_miles} SM` : '--';
    }

    function cloudGroups(rawMetar) {
        const groups = rawMetar.match(/\b(FEW|SCT|BKN|OVC|VV)(\d{3}|\/{3})(CB|TCU)?\b/g);
        if (groups) return groups.join(' ');
        const nil = rawMetar.match(/\b(CAVOK|NSC|NCD|SKC|CLR)\b/);
        return nil ? nil[1] : '--';
    }

    function formatWind(wind) {
        if (wind.speed === 0) return 'CALM';
        const dir = (wind.variable || wind.direction === null) ? 'VRB' : pad(wind.direction, 3);
        return `${dir}/${pad(wind.speed)}KT`;
    }

    function formatTaf(taf) {
        return taf.replace(/\s+(?=FM\d{6}|BECMG|PROB\d{2}|(?<!PROB\d{2}\s)TEMPO)/g, '\n    ');
    }

    Drupal.behaviors.eczWeatherDashboard = {
        attach: function (context) {
            const root = context.querySelector('.wx-monitor');
            if (!root || root.dataset.initialized) {
                return;
            }
            root.dataset.initialized = 'true';

            const settings = drupalSettings.eczWeather || {};
            let icao = settings.icao || root.dataset.icao;
            const refreshRate = (settings.refreshRate || 300) * 1000;
            const el = (name) => root.querySelector(`[data-wx="${name}"]`);

            let observedAt = null;
            let lastWeather = null;
            let lastVatsim = null;
            let lastOverview = null;
            let map = null;
            let marker = null;

            // ---- Static parts of the instruments --------------------------

            const ringSegments = [];
            for (let i = 0; i < 36; i++) {
                const seg = svg('path', {
                    d: sectorPath(i * 10 - 4, i * 10 + 4, 78, 94),
                    class: 'wx-dial__seg',
                });
                el('ring').appendChild(seg);
                ringSegments.push({ seg, bearing: i * 10 });
            }
            for (let deg = 0; deg < 360; deg += 30) {
                const [x, y] = polar(deg, 66);
                const label = svg('text', { x: x.toFixed(1), y: (y + 3.5).toFixed(1), class: 'wx-dial__label' });
                label.textContent = deg === 0 ? '360' : pad(deg, 3);
                el('labels').appendChild(label);
            }

            const gaugeCells = [];
            for (let kt = GAUGE_MAX; kt > 0; kt -= GAUGE_STEP) {
                const cell = document.createElement('span');
                cell.className = `wx-gauge__cell is-${speedZone(kt)}`;
                el('speed-gauge').appendChild(cell);
                gaugeCells.push({ cell, kt });
            }

            const xwindCells = [];
            const half = (XWIND_CELLS - 1) / 2;
            for (let i = 0; i < XWIND_CELLS; i++) {
                const offset = i - half;
                const kt = Math.abs(offset) * XWIND_MAX / half;
                const cell = document.createElement('span');
                cell.className = `wx-xwind__cell is-${kt > 20 ? 'red' : kt > 10 ? 'amber' : 'green'}`;
                el('xwind-bar').appendChild(cell);
                xwindCells.push({ cell, offset });
            }

            const fullscreen = el('fullscreen');
            if (document.fullscreenEnabled) {
                fullscreen.hidden = false;
                fullscreen.addEventListener('click', () => {
                    document.fullscreenElement ? document.exitFullscreen() : root.requestFullscreen();
                });
                document.addEventListener('fullscreenchange', () => {
                    el('fullscreen-label').textContent = document.fullscreenElement
                        ? Drupal.t('Exit fullscreen')
                        : Drupal.t('Fullscreen');
                    // Leaflet needs to re-measure after the container resizes.
                    if (map) setTimeout(() => map.invalidateSize(), 200);
                });
            }

            const select = root.querySelector('#weather-airport-select');
            if (select) {
                select.addEventListener('change', () => switchAirport(select.value));
            }

            function switchAirport(next, historyMode = 'push') {
                if (!next || next === icao) return;
                const previous = icao;
                icao = next;
                root.dataset.icao = icao;
                if (select) select.value = icao;
                el('icao').textContent = icao;
                el('name').textContent = '';
                el('observed').textContent = Drupal.t('Loading…');
                observedAt = null;
                root.classList.add('is-switching');
                document.title = document.title.replace(previous, icao);
                if (historyMode === 'push') {
                    window.history.pushState({ icao }, '', Drupal.url(`dashboard/${icao}`));
                } else if (historyMode === 'replace') {
                    window.history.replaceState({ icao }, '', Drupal.url(`dashboard/${icao}`));
                }
                if (lastOverview) renderOverview(lastOverview);
                stopPolling();
                startPolling();
            }

            window.history.replaceState({ icao }, '');
            window.addEventListener('popstate', (event) => {
                if (event.state && event.state.icao) {
                    switchAirport(event.state.icao, 'none');
                }
            });

            // ---- Rendering ------------------------------------------------

            function tickClock() {
                const now = new Date();
                el('clock').textContent = `${pad(now.getUTCHours())}:${pad(now.getUTCMinutes())}:${pad(now.getUTCSeconds())}Z`;
                if (observedAt) {
                    const mins = Math.max(0, Math.round((now.getTime() / 1000 - observedAt) / 60));
                    const obs = new Date(observedAt * 1000);
                    el('observed').textContent = Drupal.t('Observed @time (@mins min ago)', {
                        '@time': `${pad(obs.getUTCHours())}${pad(obs.getUTCMinutes())}Z`,
                        '@mins': mins,
                    });
                    el('observed').classList.toggle('is-stale', mins > 90);
                }
            }

            function renderWind(wind, runway) {
                const hasDirection = wind.speed > 0 && !wind.variable && wind.direction !== null;
                const range = wind.variable_range;

                el('wind').textContent = formatWind(wind);
                el('gust').textContent = wind.gust ? `G${wind.gust}` : '';
                el('runway').textContent = runway ? `R${runway.id}` : '';

                ringSegments.forEach(({ seg, bearing }) => {
                    let state = '';
                    if (hasDirection && angleDiff(bearing, wind.direction) <= 15) {
                        state = 'is-red';
                    } else if (range && inSector(bearing, range.from, range.to)) {
                        state = 'is-amber';
                    }
                    seg.setAttribute('class', `wx-dial__seg ${state}`);
                });

                const pointer = el('pointer');
                pointer.style.display = hasDirection ? '' : 'none';
                if (hasDirection) {
                    pointer.setAttribute('transform', `rotate(${wind.direction} 100 100)`);
                }

                gaugeCells.forEach(({ cell, kt }) => {
                    cell.classList.toggle('is-lit', kt <= wind.speed);
                    cell.classList.toggle('is-gust', !!wind.gust && kt > wind.speed && kt <= wind.gust);
                });

                const xw = runway ? runway.crosswind : 0;
                const litCells = Math.round(Math.min(Math.abs(xw), XWIND_MAX) * half / XWIND_MAX);
                xwindCells.forEach(({ cell, offset }) => {
                    const lit = offset === 0
                        || (xw > 0 && offset > 0 && offset <= litCells)
                        || (xw < 0 && offset < 0 && -offset <= litCells);
                    cell.classList.toggle('is-lit', !!runway && lit);
                });

                if (runway) {
                    const hw = runway.headwind >= 0 ? `HW ${runway.headwind}KT` : `TW ${-runway.headwind}KT`;
                    const side = xw === 0 ? '' : (xw > 0 ? ' R' : ' L');
                    el('xwind-label').textContent = `RWY ${runway.id} · ${hw} · XW ${Math.abs(xw)}KT${side}`;
                } else {
                    el('xwind-label').textContent = Drupal.t('Runway data unavailable');
                }
            }

            function renderMap(data) {
                const container = el('map');
                if (data.lat === null || data.lon === null) return;
                if (typeof window.L === 'undefined') {
                    container.textContent = `${data.lat.toFixed(3)}, ${data.lon.toFixed(3)}`;
                    return;
                }
                if (map) {
                    const position = window.L.latLng(data.lat, data.lon);
                    if (!marker.getLatLng().equals(position)) {
                        map.setView(position, 10);
                        marker.setLatLng(position).setTooltipContent(data.icao);
                    }
                    return;
                }

                map = window.L.map(container, {
                    center: [data.lat, data.lon],
                    zoom: 10,
                    scrollWheelZoom: false,
                    attributionControl: true,
                });
                window.L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
                    maxZoom: 17,
                    attribution: 'Imagery &copy; Esri',
                }).addTo(map);
                const css = getComputedStyle(root);
                marker = window.L.circleMarker([data.lat, data.lon], {
                    radius: 7,
                    color: css.getPropertyValue('--wx-on-color').trim(),
                    weight: 2,
                    fillColor: css.getPropertyValue('--wx-red').trim(),
                    fillOpacity: 1,
                }).addTo(map).bindTooltip(data.icao);
            }

            function renderMessages() {
                const lines = [];
                if (lastWeather) {
                    lines.push(lastWeather.raw_taf ? formatTaf(lastWeather.raw_taf) : `NO TAF ISSUED FOR ${icao}`);
                }
                if (lastVatsim) {
                    const { atis } = coverage();
                    if (atis) {
                        lines.push(`ATIS ${atis.code || '-'} ${atis.callsign} ${atis.frequency} ${atis.text}`.trim());
                    }
                }
                el('messages').textContent = lines.join('\n') || '…';
            }

            function coverage() {
                const prefixes = [`${icao}_`];
                if (lastWeather && lastWeather.fir) {
                    prefixes.push(`${lastWeather.fir}_`);
                }
                const covers = (c) => prefixes.some(p => c.callsign.toUpperCase().startsWith(p));
                const controllers = (lastVatsim.controllers || []).filter(covers);
                const atis = controllers.length
                    ? (lastVatsim.atis || []).find(a => a.callsign.toUpperCase().startsWith(`${icao}_`)) || null
                    : null;
                return { atis, controllers };
            }

            function renderStation() {
                if (!lastVatsim) return;
                const { atis, controllers } = coverage();
                el('atis-code').textContent = atis && atis.code ? atis.code : '–';

                const online = {};
                controllers.forEach(c => {
                    const suffix = c.callsign.toUpperCase().split('_').pop();
                    (online[suffix] = online[suffix] || []).push(c);
                });
                root.querySelectorAll('.wx-positions__item').forEach(item => {
                    const list = online[item.dataset.position] || [];
                    item.classList.toggle('is-online', list.length > 0);
                    item.querySelector('.wx-positions__freq').textContent = list.length ? list[0].frequency : '–';
                    item.title = list.map(c => `${c.callsign} ${c.frequency}`).join('\n');
                });
            }

            function render(data) {
                el('error').hidden = true;
                root.classList.remove('is-switching');
                observedAt = data.observed;
                lastWeather = data;

                el('name').textContent = data.name || '';
                const category = el('category');
                category.textContent = data.flight_category || '';
                category.className = 'wx-monitor__category';
                if (data.flight_category) {
                    category.classList.add(`is-${data.flight_category.toLowerCase()}`);
                }

                renderWind(data.wind, data.runway);

                el('visibility').textContent = formatVisibility(data.visibility);
                el('ceiling').textContent = data.ceiling !== null ? `${data.ceiling} FT` : Drupal.t('NIL');
                el('temperature').textContent = data.temperature !== null ? `${data.temperature}°C` : '--';
                el('dewpoint').textContent = data.dewpoint !== null ? `${data.dewpoint}°C` : '--';
                el('qnh-hpa').textContent = data.qnh_hpa !== null ? `${data.qnh_hpa}hPa` : '--';
                el('qnh-inhg').textContent = data.qnh_inhg !== null ? `${data.qnh_inhg.toFixed(2)}inHg` : '--';
                el('qfe-hpa').textContent = data.qfe_hpa !== null ? `${data.qfe_hpa.toFixed(1)}hPa` : '--';
                el('qfe-inhg').textContent = data.qfe_inhg !== null ? `${data.qfe_inhg.toFixed(2)}inHg` : '--';
                el('clouds').textContent = cloudGroups(data.raw_metar || '');
                el('weather').textContent = data.weather || 'NIL';
                el('metar').textContent = data.raw_metar || '--';

                renderMap(data);
                renderStation();
                renderMessages();
                tickClock();
            }

            function renderVatsim(data) {
                lastVatsim = data;
                followController(data);
                renderStation();
                renderMessages();
            }

            const myCid = String((drupalSettings.eczUser || {}).cid || '');
            const airports = select ? [...select.options].map(o => o.value) : [];
            let followedAirport = null;

            function followController(data) {
                if (!myCid) return;
                const mine = (data.controllers || []).find(c => String(c.cid) === myCid);
                if (!mine) {
                    followedAirport = null;
                    return;
                }
                const callsign = mine.callsign.toUpperCase();

                const parts = callsign.split('_');
                const isEnroute = ['CTR', 'FSS'].includes(parts[parts.length - 1]);
                const airport = parts[0];
                if (isEnroute || !airports.includes(airport) || airport === followedAirport) return;

                followedAirport = airport;
                switchAirport(airport, 'replace');
            }

            function renderOverview(data) {
                lastOverview = data;
                const body = el('overview');
                body.replaceChildren();
                (data.airports || []).forEach(a => {
                    const tr = document.createElement('tr');
                    if (a.icao === icao) tr.className = 'is-current';
                    const cells = [
                        a.icao,
                        formatWind(a.wind) + (a.wind.gust ? ` G${a.wind.gust}` : ''),
                        formatVisibility(a.visibility),
                        a.clouds.length ? a.clouds.map(c => `${c.cover}${c.base !== null ? pad(Math.round(c.base / 100), 3) : ''}`)[0] : '--',
                        a.temperature !== null ? `${a.temperature}°` : '--',
                        a.qnh_hpa !== null ? a.qnh_hpa : '--',
                    ];
                    cells.forEach((text, i) => {
                        const td = document.createElement(i === 0 ? 'th' : 'td');
                        if (i === 0) {
                            td.scope = 'row';
                            const cat = document.createElement('span');
                            cat.className = `wx-table__cat is-${(a.flight_category || '').toLowerCase()}`;
                            cat.title = a.flight_category || '';
                            td.appendChild(cat);
                        }
                        td.append(String(text));
                        tr.appendChild(td);
                    });
                    tr.addEventListener('click', () => switchAirport(a.icao));
                    body.appendChild(tr);
                });
            }

            // ---- Data -----------------------------------------------------

            async function getJson(path) {
                const response = await fetch(Drupal.url(path));
                const data = await response.json();
                if (!response.ok) throw new Error(data.error || response.statusText);
                return data;
            }

            async function fetchWeather() {
                const requested = icao;
                try {
                    const data = await getJson(`api/weather/${requested}`);
                    if (requested === icao) render(data);
                } catch (error) {
                    if (requested !== icao) return;
                    root.classList.remove('is-switching');
                    const box = el('error');
                    box.textContent = Drupal.t('Weather unavailable: @msg', { '@msg': error.message });
                    box.hidden = false;
                }
            }

            function refresh() {
                fetchWeather();
                getJson('api/weather-overview').then(renderOverview).catch(e => console.error('Error fetching METAR overview:', e));
            }

            function fetchVatsim() {
                getJson('api/vatsim/live').then(renderVatsim).catch(e => console.error('Error fetching VATSIM data:', e));
            }

            const vatsimRate = (parseInt((drupalSettings.eczVatsim || {}).refreshRate, 10) || 60) * 1000;
            let intervalId = null;
            let vatsimIntervalId = null;

            function startPolling() {
                refresh();
                fetchVatsim();
                if (!intervalId) {
                    intervalId = setInterval(refresh, refreshRate);
                }
                if (!vatsimIntervalId) {
                    vatsimIntervalId = setInterval(fetchVatsim, vatsimRate);
                }
            }

            function stopPolling() {
                clearInterval(intervalId);
                clearInterval(vatsimIntervalId);
                intervalId = null;
                vatsimIntervalId = null;
            }

            setInterval(tickClock, 1000);
            startPolling();

            document.addEventListener('visibilitychange', () => {
                document.hidden ? stopPolling() : startPolling();
            });
        }
    };
})(Drupal, drupalSettings);
