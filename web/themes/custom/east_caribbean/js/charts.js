(function (Drupal, once) {
    'use strict';

    // Charts page (/pilots/charts): switches airports without a reload and
    // shows the selected chart with PDF.js. Markup: ecz-charts.html.twig.

    const WORKER_SRC = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';
    const ZOOM_STEP = 1.25;
    const ZOOM_MIN = 0.25;
    const ZOOM_MAX = 6;
    // The column whose first chart opens with an airport (the airport diagram).
    const DEFAULT_CATEGORY = 'TAXI';

    // Fills the screen below the navbar (the page has no footer).
    function fitToScreen(root) {
        const top = root.getBoundingClientRect().top + window.scrollY;
        root.style.setProperty('--charts-top', `${Math.round(top)}px`);
    }

    // Draggable divider between the chart list and the viewer. The width is
    // remembered in this browser; double-click resets it.
    function initDivider(root) {
        const body = root.querySelector('.charts__body');
        const lists = root.querySelector('.charts__lists');
        const divider = root.querySelector('[data-charts-divider]');
        if (!body || !lists || !divider) return;

        const STORAGE_KEY = 'eczChartsListWidth';
        const MIN_LIST = 280;
        // Matches the calc(100% - 368px) cap in _charts.scss.
        const MIN_VIEWER = 360;
        const KEY_STEP = 24;

        function setWidth(px, save) {
            const max = body.clientWidth - divider.offsetWidth - MIN_VIEWER;
            const width = Math.round(Math.max(MIN_LIST, Math.min(max, px)));
            body.style.setProperty('--charts-left', `${width}px`);
            if (save) {
                try { localStorage.setItem(STORAGE_KEY, width); }
                catch (e) { /* storage blocked */ }
            }
        }

        function reset() {
            body.style.removeProperty('--charts-left');
            try { localStorage.removeItem(STORAGE_KEY); }
            catch (e) { /* storage blocked */ }
        }

        try {
            const saved = parseInt(localStorage.getItem(STORAGE_KEY), 10);
            if (saved > 0) body.style.setProperty('--charts-left', `${saved}px`);
        }
        catch (e) { /* storage blocked */ }

        let drag = null;
        divider.addEventListener('pointerdown', (event) => {
            if (event.button !== 0) return;
            event.preventDefault();
            drag = { x: event.clientX, width: lists.getBoundingClientRect().width };
            divider.setPointerCapture(event.pointerId);
            root.classList.add('is-resizing');
        });
        divider.addEventListener('pointermove', (event) => {
            if (drag) setWidth(drag.width + event.clientX - drag.x, false);
        });
        const endDrag = () => {
            if (!drag) return;
            drag = null;
            root.classList.remove('is-resizing');
            setWidth(lists.getBoundingClientRect().width, true);
        };
        divider.addEventListener('pointerup', endDrag);
        divider.addEventListener('pointercancel', endDrag);
        divider.addEventListener('dblclick', reset);

        divider.addEventListener('keydown', (event) => {
            const step = { ArrowLeft: -KEY_STEP, ArrowRight: KEY_STEP }[event.key];
            if (step) {
                event.preventDefault();
                setWidth(lists.getBoundingClientRect().width + step, true);
            }
            else if (event.key === 'Home') {
                event.preventDefault();
                reset();
            }
        });
    }

    function initViewer(root) {
        const stage = root.querySelector('[data-charts-stage]');
        const canvas = root.querySelector('[data-charts-canvas]');
        const message = root.querySelector('[data-charts-message]');
        const title = root.querySelector('[data-charts-title]');
        const tools = root.querySelector('[data-charts-tools]');
        const pager = root.querySelector('[data-charts-pager]');
        const pageLabel = root.querySelector('[data-charts-page]');
        const zoomLabel = root.querySelector('[data-charts-zoom]');
        const openLink = root.querySelector('[data-charts-open]');
        const invertButton = root.querySelector('[data-charts-action="invert"]');

        const state = {
            doc: null,
            loading: null,
            render: null,
            generation: 0,
            page: 1,
            // 'width', 'page', or a fixed scale.
            fit: 'page',
            scale: 1,
            rotation: 0,
            // null: follow the site theme; true/false once toggled by hand.
            invert: null,
        };

        function showMessage(text) {
            message.textContent = text;
            message.hidden = false;
            canvas.hidden = true;
        }

        // Charts are white paper: invert them in dark mode unless toggled.
        function applyInvert() {
            const dark = document.documentElement.getAttribute('data-theme') === 'dark';
            const on = state.invert === null ? dark : state.invert;
            stage.classList.toggle('is-inverted', on);
            invertButton.setAttribute('aria-pressed', on ? 'true' : 'false');
        }

        async function render() {
            if (!state.doc) return;
            // Only the latest call draws (zoom clicks can overlap).
            const generation = ++state.generation;
            let page;
            try {
                page = await state.doc.getPage(state.page);
            }
            catch (e) {
                // The chart was switched (and this document closed) meanwhile.
                if (generation !== state.generation) return;
                throw e;
            }
            if (generation !== state.generation) return;
            const base = page.getViewport({ scale: 1, rotation: state.rotation });
            const style = getComputedStyle(stage);
            const room = {
                width: stage.clientWidth - parseFloat(style.paddingLeft) - parseFloat(style.paddingRight),
                height: stage.clientHeight - parseFloat(style.paddingTop) - parseFloat(style.paddingBottom),
            };
            if (state.fit === 'width') {
                state.scale = room.width / base.width;
            }
            else if (state.fit === 'page') {
                state.scale = Math.min(room.width / base.width, room.height / base.height);
            }
            state.scale = Math.min(ZOOM_MAX, Math.max(ZOOM_MIN, state.scale));

            // Draw at the screen's pixel density so the chart stays sharp.
            const ratio = window.devicePixelRatio || 1;
            const viewport = page.getViewport({ scale: state.scale * ratio, rotation: state.rotation });

            if (state.render) state.render.cancel();
            // Draw off-screen, then swap in, so zooming doesn't flash blank.
            const buffer = document.createElement('canvas');
            buffer.width = Math.floor(viewport.width);
            buffer.height = Math.floor(viewport.height);
            state.render = page.render({ canvasContext: buffer.getContext('2d'), viewport });
            try {
                await state.render.promise;
            }
            catch (e) {
                // Cancelled by a newer render, or the chart was switched.
                if (generation !== state.generation || (e && e.name === 'RenderingCancelledException')) return;
                throw e;
            }
            if (generation !== state.generation) return;
            state.render = null;

            canvas.width = buffer.width;
            canvas.height = buffer.height;
            canvas.getContext('2d').drawImage(buffer, 0, 0);
            canvas.style.width = `${Math.floor(viewport.width / ratio)}px`;
            canvas.style.height = `${Math.floor(viewport.height / ratio)}px`;
            canvas.hidden = false;
            message.hidden = true;

            zoomLabel.textContent = `${Math.round(state.scale * 100)}%`;
            pageLabel.textContent = `${state.page} / ${state.doc.numPages}`;
            pager.hidden = state.doc.numPages < 2;
        }

        async function open(link) {
            const url = link.getAttribute('href');
            title.textContent = link.textContent.trim();
            title.title = title.textContent;
            openLink.href = url;
            tools.hidden = false;
            showMessage(Drupal.t('Loading chart…'));

            // Stops any render of the previous chart from drawing.
            state.generation++;
            if (state.loading) state.loading.destroy();
            if (state.doc) state.doc.destroy();
            state.doc = null;
            state.page = 1;
            state.fit = 'page';
            state.rotation = 0;

            const loading = window.pdfjsLib.getDocument(url);
            state.loading = loading;
            try {
                const doc = await loading.promise;
                // Another chart was picked while this one loaded.
                if (state.loading !== loading) return;
                state.doc = doc;
                state.loading = null;
                await render();
                stage.scrollTo(0, 0);
            }
            catch (e) {
                if (state.loading !== loading) return;
                showMessage(Drupal.t('This chart could not be displayed. Use "Open PDF in a new tab" instead.'));
            }
        }

        function zoom(factor) {
            state.fit = null;
            state.scale *= factor;
            render();
        }

        const actions = {
            'prev': () => { if (state.page > 1) { state.page--; render(); } },
            'next': () => { if (state.doc && state.page < state.doc.numPages) { state.page++; render(); } },
            'zoom-in': () => zoom(ZOOM_STEP),
            'zoom-out': () => zoom(1 / ZOOM_STEP),
            'fit-width': () => { state.fit = 'width'; render(); },
            'fit-page': () => { state.fit = 'page'; render(); },
            'rotate-ccw': () => { state.rotation = (state.rotation + 270) % 360; render(); },
            'rotate': () => { state.rotation = (state.rotation + 90) % 360; render(); },
            'invert': () => {
                state.invert = invertButton.getAttribute('aria-pressed') !== 'true';
                applyInvert();
            },
        };
        root.querySelectorAll('[data-charts-action]').forEach(button => {
            button.addEventListener('click', () => actions[button.dataset.chartsAction]());
        });

        // Ctrl/Cmd + wheel (and trackpad pinch) zooms the chart, not the page.
        stage.addEventListener('wheel', (event) => {
            if (!state.doc || !(event.ctrlKey || event.metaKey)) return;
            event.preventDefault();
            zoom(event.deltaY < 0 ? 1.1 : 1 / 1.1);
        }, { passive: false });

        // Drag to pan when zoomed in (touch screens already scroll natively).
        let drag = null;
        stage.addEventListener('pointerdown', (event) => {
            if (event.pointerType !== 'mouse' || event.button !== 0 || canvas.hidden) return;
            drag = { x: event.clientX, y: event.clientY, left: stage.scrollLeft, top: stage.scrollTop };
            stage.classList.add('is-dragging');
            stage.setPointerCapture(event.pointerId);
        });
        stage.addEventListener('pointermove', (event) => {
            if (!drag) return;
            stage.scrollLeft = drag.left - (event.clientX - drag.x);
            stage.scrollTop = drag.top - (event.clientY - drag.y);
        });
        const endDrag = () => { drag = null; stage.classList.remove('is-dragging'); };
        stage.addEventListener('pointerup', endDrag);
        stage.addEventListener('pointercancel', endDrag);

        // Keep a fitted chart fitted as the window (or layout) resizes.
        let resizeTimer = null;
        new ResizeObserver(() => {
            if (!state.fit) return;
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(render, 150);
        }).observe(stage);

        // Follow the site's light/dark toggle until night mode is set by hand.
        new MutationObserver(applyInvert).observe(document.documentElement, { attributes: true, attributeFilter: ['data-theme'] });
        applyInvert();

        return { open };
    }

    Drupal.behaviors.eczCharts = {
        attach: function (context) {
            once('ecz-charts', '[data-charts]', context).forEach(root => {
                const airports = root.querySelectorAll('[data-charts-airport]');
                if (!airports.length) return;

                initDivider(root);
                fitToScreen(root);
                window.addEventListener('resize', () => fitToScreen(root));

                if (!window.pdfjsLib) return;
                window.pdfjsLib.GlobalWorkerOptions.workerSrc = WORKER_SRC;
                const viewer = initViewer(root);

                // The URL keeps the airport and chart, so it can be shared:
                // /pilots/charts/TNCM#chart-12.
                function selectChart(link) {
                    root.querySelectorAll('.charts__chart.is-active').forEach(el => el.classList.remove('is-active'));
                    link.classList.add('is-active');
                    window.history.replaceState(null, '', `${window.location.pathname}#chart-${link.dataset.chartId}`);
                    viewer.open(link);
                }

                function selectAirport(icao, openDefault) {
                    airports.forEach(link => {
                        const active = link.dataset.chartsAirport === icao;
                        link.classList.toggle('is-active', active);
                        if (active) link.setAttribute('aria-current', 'page');
                        else link.removeAttribute('aria-current');
                    });
                    let panel = null;
                    root.querySelectorAll('[data-charts-panel]').forEach(el => {
                        el.hidden = el.dataset.chartsPanel !== icao;
                        if (!el.hidden) panel = el;
                    });
                    const url = root.querySelector(`[data-charts-airport="${icao}"]`).getAttribute('href');
                    window.history.replaceState(null, '', url);

                    const first = openDefault && panel && panel.querySelector(`[data-chart-category="${DEFAULT_CATEGORY}"]`);
                    if (first) selectChart(first);
                }

                airports.forEach(link => {
                    link.addEventListener('click', (event) => {
                        event.preventDefault();
                        selectAirport(link.dataset.chartsAirport, true);
                    });
                });

                root.querySelectorAll('[data-chart-id]').forEach(link => {
                    link.addEventListener('click', (event) => {
                        // Let modified clicks open the PDF in a new tab.
                        if (event.ctrlKey || event.metaKey || event.shiftKey || event.button !== 0) return;
                        event.preventDefault();
                        selectChart(link);
                    });
                });

                // On load: the chart in the URL, else the airport's diagram.
                const match = window.location.hash.match(/^#chart-(\d+)$/);
                const linked = match && root.querySelector(`[data-charts-panel]:not([hidden]) [data-chart-id="${match[1]}"]`);
                if (linked) {
                    selectChart(linked);
                }
                else {
                    const active = root.querySelector('.charts__airport.is-active');
                    selectAirport(active.dataset.chartsAirport, true);
                }
            });
        }
    };
})(Drupal, once);
