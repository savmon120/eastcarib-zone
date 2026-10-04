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

    const formatTime = (date) => `${pad(date.getUTCHours())}${pad(date.getUTCMinutes())}Z`;

    function setCategory(node, category) {
        node.textContent = category || '';
        node.className = 'wx-monitor__category';
        if (category) node.classList.add(`is-${category.toLowerCase()}`);
    }

    /**
     * One airport's instruments (wind dial, conditions, ATIS/positions,
     * clouds), cloned from the template into `slot`. The normal view has one;
     * the CTR multi-airport view has one per column.
     */
    function createAirportView(template, slot) {
        const scope = template.content.firstElementChild.cloneNode(true);
        slot.appendChild(scope);
        const q = (name) => scope.querySelector(`[data-wx="${name}"]`);

        const ringSegments = [];
        for (let i = 0; i < 36; i++) {
            const seg = svg('path', { d: sectorPath(i * 10 - 4, i * 10 + 4, 78, 94), class: 'wx-dial__seg' });
            q('ring').appendChild(seg);
            ringSegments.push({ seg, bearing: i * 10 });
        }
        for (let deg = 0; deg < 360; deg += 30) {
            const [x, y] = polar(deg, 66);
            const label = svg('text', { x: x.toFixed(1), y: (y + 3.5).toFixed(1), class: 'wx-dial__label' });
            label.textContent = deg === 0 ? '360' : pad(deg, 3);
            q('labels').appendChild(label);
        }

        const gaugeCells = [];
        for (let kt = GAUGE_MAX; kt > 0; kt -= GAUGE_STEP) {
            const cell = document.createElement('span');
            cell.className = `wx-gauge__cell is-${speedZone(kt)}`;
            q('speed-gauge').appendChild(cell);
            gaugeCells.push({ cell, kt });
        }

        const xwindCells = [];
        const half = (XWIND_CELLS - 1) / 2;
        for (let i = 0; i < XWIND_CELLS; i++) {
            const offset = i - half;
            const kt = Math.abs(offset) * XWIND_MAX / half;
            const cell = document.createElement('span');
            cell.className = `wx-xwind__cell is-${kt > 20 ? 'red' : kt > 10 ? 'amber' : 'green'}`;
            q('xwind-bar').appendChild(cell);
            xwindCells.push({ cell, offset });
        }

        function renderWind(wind, runway) {
            const hasDirection = wind.speed > 0 && !wind.variable && wind.direction !== null;
            const range = wind.variable_range;

            q('wind').textContent = formatWind(wind);
            q('gust').textContent = wind.gust ? `G${wind.gust}` : '';
            q('runway').textContent = runway ? `R${runway.id}` : '';

            ringSegments.forEach(({ seg, bearing }) => {
                let state = '';
                if (hasDirection && angleDiff(bearing, wind.direction) <= 15) {
                    state = 'is-red';
                } else if (range && inSector(bearing, range.from, range.to)) {
                    state = 'is-amber';
                }
                seg.setAttribute('class', `wx-dial__seg ${state}`);
            });

            const pointer = q('pointer');
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
                q('xwind-label').textContent = `RWY ${runway.id} · ${hw} · XW ${Math.abs(xw)}KT${side}`;
            } else {
                q('xwind-label').textContent = Drupal.t('Runway data unavailable');
            }
        }

        return {
            scope,

            // Column header (only visible in the multi-airport view).
            setHeading(icao) {
                q('col-icao').textContent = icao;
                q('col-name').textContent = '';
                q('col-observed').textContent = '';
                setCategory(q('col-category'), null);
            },

            renderWeather(data) {
                q('col-icao').textContent = data.icao;
                q('col-name').textContent = data.name || '';
                q('col-observed').textContent = data.observed ? formatTime(new Date(data.observed * 1000)) : '';
                setCategory(q('col-category'), data.flight_category);

                renderWind(data.wind, data.runway);

                q('visibility').textContent = formatVisibility(data.visibility);
                q('ceiling').textContent = data.ceiling !== null ? `${data.ceiling} FT` : Drupal.t('NIL');
                q('temperature').textContent = data.temperature !== null ? `${data.temperature}°C` : '--';
                q('dewpoint').textContent = data.dewpoint !== null ? `${data.dewpoint}°C` : '--';
                q('qnh-hpa').textContent = data.qnh_hpa !== null ? `${data.qnh_hpa}hPa` : '--';
                q('qnh-inhg').textContent = data.qnh_inhg !== null ? `${data.qnh_inhg.toFixed(2)}inHg` : '--';
                q('qfe-hpa').textContent = data.qfe_hpa !== null ? `${data.qfe_hpa.toFixed(1)}hPa` : '--';
                q('qfe-inhg').textContent = data.qfe_inhg !== null ? `${data.qfe_inhg.toFixed(2)}inHg` : '--';
                q('clouds').textContent = cloudGroups(data.raw_metar || '');
                q('weather').textContent = data.weather || 'NIL';
            },

            renderStation({ atis, controllers }) {
                q('atis-code').textContent = atis && atis.code ? atis.code : '–';

                // Light each position by its callsign suffix, e.g. TNCC_TWR or
                // TNCC_N_APP → TWR / APP. CTR also covers the FIR's centre.
                const online = {};
                controllers.forEach(c => {
                    const suffix = c.callsign.toUpperCase().split('_').pop();
                    (online[suffix] = online[suffix] || []).push(c);
                });
                scope.querySelectorAll('.wx-positions__item').forEach(item => {
                    const list = online[item.dataset.position] || [];
                    item.classList.toggle('is-online', list.length > 0);
                    item.querySelector('.wx-positions__freq').textContent = list.length ? list[0].frequency : '–';
                    item.title = list.map(c => `${c.callsign} ${c.frequency}`).join('\n');
                });
            },
        };
    }

    Drupal.behaviors.eczWeatherDashboard = {
        attach: function (context) {
            const root = context.querySelector('.wx-monitor');
            if (!root || root.dataset.initialized) {
                return;
            }
            root.dataset.initialized = 'true';

            const settings = drupalSettings.eczWeather || {};
            // Changes when switching airports in place (see switchAirport()).
            let icao = settings.icao || root.dataset.icao;
            const refreshRate = (settings.refreshRate || 300) * 1000;
            const firAirports = settings.firAirports || {};
            const el = (name) => root.querySelector(`[data-wx="${name}"]`);
            const template = el('airport-template');

            const mainView = createAirportView(template, el('main-airport'));

            let observedAt = null;
            let lastWeather = null;
            let lastVatsim = null;
            let lastOverview = null;
            let map = null;
            let marker = null;

            // CTR multi-airport view state.
            let enrouteFir = null;      // e.g. 'TNCF' while the user is on TNCF_CTR
            let multiFir = null;        // the FIR the multi view is showing
            let multiActive = false;
            let multiViews = [];        // [{ icao, view }]
            let multiWeather = {};      // icao → weather data

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
                    updateMode();
                    // Leaflet needs to re-measure after the container resizes.
                    if (map) setTimeout(() => map.invalidateSize(), 200);
                });
            }

            const select = root.querySelector('#weather-airport-select');
            if (select) {
                select.addEventListener('change', () => switchAirport(select.value));
            }

            // Switches airport without reloading the page. A page load would
            // drop the browser out of fullscreen, so update everything in
            // place and only change the URL (back/forward still work).
            // historyMode: 'push' (visitor's choice, Back returns to it),
            // 'replace' (automatic switch, no extra history entry) or 'none'.
            function switchAirport(next, historyMode = 'push') {
                if (!next || next === icao) return;
                const previous = icao;
                icao = next;
                root.dataset.icao = icao;
                if (select) select.value = icao;
                // Picking a FIR code (e.g. TNCF) shows its airports when
                // fullscreen, or leaves that view when picking an airport.
                root.classList.toggle('is-fir-selected', !!firAirports[icao]);
                updateMode();
                if (!multiActive) {
                    el('icao').textContent = icao;
                    el('name').textContent = '';
                    el('observed').textContent = Drupal.t('Loading…');
                    observedAt = null;
                    // Dim the previous airport's readings until the new ones arrive.
                    root.classList.add('is-switching');
                }
                document.title = document.title.replace(previous, icao);
                if (historyMode === 'push') {
                    window.history.pushState({ icao }, '', Drupal.url(`dashboard/${icao}`));
                } else if (historyMode === 'replace') {
                    window.history.replaceState({ icao }, '', Drupal.url(`dashboard/${icao}`));
                }
                if (lastOverview) renderOverview(lastOverview);
                // Restart the refresh timer so the new airport gets a full interval.
                stopPolling();
                startPolling();
            }

            window.history.replaceState({ icao }, '');
            window.addEventListener('popstate', (event) => {
                if (event.state && event.state.icao) {
                    switchAirport(event.state.icao, 'none');
                }
            });

            // ---- Multi-airport (CTR) view ---------------------------------
            // A FIR's airports (from the "FIR airports" setting) side by side,
            // no map or table. Shown while the dashboard is fullscreen AND
            // either a FIR code is picked in the dropdown, or the logged-in
            // controller is on that FIR's centre position.

            root.classList.toggle('is-fir-selected', !!firAirports[icao]);

            function activeFir() {
                return firAirports[icao] ? icao : enrouteFir;
            }

            // The columns: the airports where the centre controller runs an
            // ATIS (up to 4), so a controller with 2 ATISes gets 2 columns.
            // In CTR mode that's the logged-in user's own ATISes; with a FIR
            // picked from the dropdown, those of whoever is on its centre.
            // Falls back to the "FIR airports" setting when there are none.
            function multiAirportsFor(fir) {
                const configured = firAirports[fir] || [];
                if (!lastVatsim) return configured;

                let owners;
                if (!firAirports[icao]) {
                    owners = myCid ? [myCid] : [];
                } else {
                    owners = (lastVatsim.controllers || [])
                        .filter(c => {
                            const parts = c.callsign.toUpperCase().split('_');
                            return parts[0] === fir && ['CTR', 'FSS'].includes(parts[parts.length - 1]);
                        })
                        .map(c => String(c.cid));
                }

                const own = [];
                (lastVatsim.atis || []).forEach(a => {
                    if (!owners.includes(String(a.cid))) return;
                    const code = a.callsign.toUpperCase().split('_')[0];
                    if (!own.includes(code)) own.push(code);
                });
                if (!own.length) return configured;

                // Configured airports first, in the setting's order; then the rest A–Z.
                const rank = (code) => (configured.includes(code) ? configured.indexOf(code) : configured.length);
                own.sort((a, b) => rank(a) - rank(b) || a.localeCompare(b));
                return own.slice(0, 4);
            }

            function updateMode() {
                const fir = activeFir();
                if (!fir || document.fullscreenElement !== root) {
                    if (multiActive) exitMulti();
                    return;
                }
                const codes = multiAirportsFor(fir);
                // (Re)build when entering, switching FIR, or when the set of
                // airports changes (an ATIS opened or closed).
                if (!multiActive || fir !== multiFir || codes.join() !== multiViews.map(v => v.icao).join()) {
                    enterMulti(fir, codes);
                }
            }

            function enterMulti(fir, codes) {
                multiActive = true;
                multiFir = fir;
                const container = el('multi');
                container.replaceChildren();
                multiWeather = {};
                multiViews = codes.map(code => {
                    const view = createAirportView(template, container);
                    view.setHeading(code);
                    return { icao: code, view };
                });
                root.classList.add('is-multi');
                root.classList.remove('is-switching');
                el('error').hidden = true;
                el('icao').textContent = multiFir;
                el('name').textContent = '';
                observedAt = null;
                el('observed').textContent = Drupal.t('Loading…');
                fetchMultiWeather();
                renderAllStations();
                renderMessages();
            }

            function exitMulti() {
                multiActive = false;
                multiViews = [];
                multiWeather = {};
                el('multi').replaceChildren();
                root.classList.remove('is-multi');
                el('icao').textContent = icao;
                if (lastWeather && lastWeather.icao === icao) {
                    render(lastWeather);
                } else {
                    fetchWeather();
                }
                if (map) setTimeout(() => map.invalidateSize(), 200);
            }

            function fetchMultiWeather() {
                const codes = multiViews.map(v => v.icao);
                codes.forEach(code => {
                    getJson(`api/weather/${code}`)
                        .then(data => {
                            const entry = multiViews.find(v => v.icao === code);
                            if (!multiActive || !entry) return;
                            multiWeather[code] = data;
                            entry.view.renderWeather(data);
                            entry.view.renderStation(coverageFor(code, multiFir));
                            // Footer shows the oldest observation of the set.
                            const times = Object.values(multiWeather).map(d => d.observed).filter(Boolean);
                            observedAt = times.length ? Math.min(...times) : null;
                            renderMessages();
                            tickClock();
                        })
                        .catch(e => console.error(`Error fetching weather for ${code}:`, e));
                });
            }

            // ---- Rendering ------------------------------------------------

            function tickClock() {
                const now = new Date();
                el('clock').textContent = `${pad(now.getUTCHours())}:${pad(now.getUTCMinutes())}:${pad(now.getUTCSeconds())}Z`;
                if (observedAt) {
                    const mins = Math.max(0, Math.round((now.getTime() / 1000 - observedAt) / 60));
                    el('observed').textContent = Drupal.t('Observed @time (@mins min ago)', {
                        '@time': formatTime(new Date(observedAt * 1000)),
                        '@mins': mins,
                    });
                    el('observed').classList.toggle('is-stale', mins > 90);
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
                    // Only recentre when the airport changed, so a refresh
                    // doesn't undo the visitor's own panning.
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
                // Marker colours come from the theme (base/_light.scss).
                const css = getComputedStyle(root);
                marker = window.L.circleMarker([data.lat, data.lon], {
                    radius: 7,
                    color: css.getPropertyValue('--wx-on-color').trim(),
                    weight: 2,
                    fillColor: css.getPropertyValue('--wx-red').trim(),
                    fillOpacity: 1,
                }).addTo(map).bindTooltip(data.icao);
            }

            // METAR strip and system messages: the current airport, or every
            // airport of the multi-airport view.
            function renderMessages() {
                const entries = multiActive
                    ? multiViews.map(v => ({ code: v.icao, data: multiWeather[v.icao], fir: multiFir }))
                    : [{ code: icao, data: lastWeather && lastWeather.icao === icao ? lastWeather : null, fir: lastWeather && lastWeather.fir }];

                el('metar').textContent = entries
                    .map(e => (e.data ? e.data.raw_metar : null) || (multiActive ? `${e.code} …` : '--'))
                    .join('\n');

                const lines = [];
                entries.forEach(e => {
                    if (e.data) {
                        lines.push(e.data.raw_taf ? formatTaf(e.data.raw_taf) : `NO TAF ISSUED FOR ${e.code}`);
                    }
                });
                if (lastVatsim) {
                    entries.forEach(e => {
                        const { atis } = coverageFor(e.code, e.fir);
                        if (atis) {
                            lines.push(`ATIS ${atis.code || '-'} ${atis.callsign} ${atis.frequency} ${atis.text}`.trim());
                        }
                    });
                }
                el('messages').textContent = lines.join('\n') || '…';
            }

            // Controllers covering an airport: its own positions plus its FIR's
            // centre. ATIS only counts while one of them is online.
            function coverageFor(code, fir) {
                if (!lastVatsim) return { atis: null, controllers: [] };
                const prefixes = [`${code}_`];
                if (fir) prefixes.push(`${fir}_`);
                const covers = (c) => prefixes.some(p => c.callsign.toUpperCase().startsWith(p));
                const controllers = (lastVatsim.controllers || []).filter(covers);
                const atis = controllers.length
                    ? (lastVatsim.atis || []).find(a => a.callsign.toUpperCase().startsWith(`${code}_`)) || null
                    : null;
                return { atis, controllers };
            }

            function renderAllStations() {
                if (!lastVatsim) return;
                if (multiActive) {
                    multiViews.forEach(({ icao: code, view }) => view.renderStation(coverageFor(code, multiFir)));
                } else {
                    mainView.renderStation(coverageFor(icao, lastWeather && lastWeather.fir));
                }
            }

            function render(data) {
                lastWeather = data;
                if (multiActive) return;
                el('error').hidden = true;
                root.classList.remove('is-switching');
                observedAt = data.observed;

                el('name').textContent = data.name || '';
                setCategory(el('category'), data.flight_category);

                mainView.renderWeather(data);
                renderMap(data);
                renderAllStations();
                renderMessages();
                tickClock();
            }

            function renderVatsim(data) {
                lastVatsim = data;
                followController(data);
                // The FIR view's columns depend on which ATISes are open.
                updateMode();
                renderAllStations();
                renderMessages();
            }

            // Follow the logged-in controller: when they're connected at one of
            // our airports, switch the dashboard there. Only switches when their
            // position changes, so picking another airport by hand sticks.
            // Centre/FSS positions instead enable the multi-airport view.
            const myCid = String((drupalSettings.eczUser || {}).cid || '');
            const airports = select ? [...select.options].map(o => o.value) : [];
            let followedAirport = null;

            function followController(data) {
                if (!myCid) return;
                const mine = (data.controllers || []).find(c => String(c.cid) === myCid);
                if (!mine) {
                    followedAirport = null;
                    setEnrouteFir(null);
                    return;
                }
                const parts = mine.callsign.toUpperCase().split('_');
                const isEnroute = ['CTR', 'FSS'].includes(parts[parts.length - 1]);
                if (isEnroute) {
                    // e.g. TTZP_E_CTR → TTZP, if that FIR has airports configured.
                    setEnrouteFir(firAirports[parts[0]] ? parts[0] : null);
                    return;
                }
                setEnrouteFir(null);

                const airport = parts[0];
                if (!airports.includes(airport) || airport === followedAirport) return;
                followedAirport = airport;
                switchAirport(airport, 'replace');
            }

            function setEnrouteFir(fir) {
                if (fir === enrouteFir) return;
                enrouteFir = fir;
                updateMode();
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
                // A FIR code has no METAR of its own; its airports only show
                // in the fullscreen multi-airport view.
                if (firAirports[requested] && !multiActive) {
                    root.classList.remove('is-switching');
                    const box = el('error');
                    box.textContent = Drupal.t('@fir is a FIR. Go fullscreen to see its airports side by side.', { '@fir': requested });
                    box.classList.add('is-info');
                    box.hidden = false;
                    return;
                }
                try {
                    const data = await getJson(`api/weather/${requested}`);
                    // Ignore a late response for an airport we've since left.
                    if (requested === icao) render(data);
                } catch (error) {
                    if (requested !== icao || multiActive) return;
                    root.classList.remove('is-switching');
                    const box = el('error');
                    box.textContent = Drupal.t('Weather unavailable: @msg', { '@msg': error.message });
                    box.classList.remove('is-info');
                    box.hidden = false;
                }
            }

            function refresh() {
                if (multiActive) {
                    fetchMultiWeather();
                } else {
                    fetchWeather();
                }
                getJson('api/weather-overview').then(renderOverview).catch(e => console.error('Error fetching METAR overview:', e));
            }

            function fetchVatsim() {
                getJson('api/vatsim/live').then(renderVatsim).catch(e => console.error('Error fetching VATSIM data:', e));
            }

            // VATSIM changes faster than the weather, and drives following the
            // controller, so it polls on its own (shorter) interval.
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
