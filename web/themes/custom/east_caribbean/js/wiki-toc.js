(function (Drupal, once) {
    'use strict';

    // toc_node table of contents (wiki articles): some headings sit inside a
    // tab or accordion that isn't open, and the browser can't scroll to
    // something hidden. Open those first, then scroll to the heading.

    // The Bootstrap toggle (tab button / accordion header) for a pane.
    function toggleFor(pane) {
        const id = `#${CSS.escape(pane.id)}`;
        return document.querySelector(`[data-bs-toggle][href="${id}"], [data-bs-toggle][data-bs-target="${id}"]`);
    }

    // Opens every closed tab pane / collapse around the target, outermost
    // first, by clicking its Bootstrap toggle. Returns whether any opened.
    function reveal(target) {
        const closed = [];
        for (let el = target.parentElement; el; el = el.parentElement) {
            const isClosedTab = el.classList.contains('tab-pane') && !el.classList.contains('active');
            const isClosedCollapse = el.classList.contains('collapse') && !el.classList.contains('show');
            if ((isClosedTab || isClosedCollapse) && el.id) {
                closed.unshift(el);
            }
        }
        closed.forEach(pane => {
            const toggle = toggleFor(pane);
            if (toggle) toggle.click();
        });
        return closed.length > 0;
    }

    function goTo(hash, smooth) {
        const target = hash && document.getElementById(decodeURIComponent(hash.slice(1)));
        if (!target) return false;
        const opened = reveal(target);

        // A tab's own entry (the invisible .bp-tab-heading from
        // paragraph--bp-tabs.html.twig): land on the tab buttons instead, so
        // it's clear which tab is open.
        let scrollTarget = target;
        if (target.classList.contains('bp-tab-heading')) {
            const toggle = toggleFor(target.closest('.tab-pane'));
            scrollTarget = (toggle && (toggle.closest('nav') || toggle)) || target;
        }

        // Tabs fade in (and the previous one out), which shifts the page;
        // scroll once that has settled.
        setTimeout(() => {
            scrollTarget.scrollIntoView({ behavior: smooth ? 'smooth' : 'auto', block: 'start' });
            // Images loading on the way can push the content down mid-scroll:
            // snap to the exact spot once scrolling has finished.
            const settle = () => scrollTarget.scrollIntoView({ block: 'start' });
            if ('onscrollend' in window) {
                window.addEventListener('scrollend', settle, { once: true });
            } else {
                setTimeout(settle, 900);
            }
        }, opened ? 350 : 0);
        return true;
    }

    // ---- Sidebar / floating panel ----------------------------------------
    // 1900px and wider: a sticky sidebar in the left margin. Narrower: a
    // "Contents" button bottom-left that opens a panel over the page. Either
    // way the article keeps its full width (see _wiki-toc.scss).

    const SIDEBAR_KEY = 'wikiTocSidebar';
    const SIDEBAR_QUERY = '(min-width: 1900px)';
    const isSidebar = () => window.matchMedia(SIDEBAR_QUERY).matches;

    // Sidebar: restore the visitor's last choice (open by default).
    // Floating panel: always start closed, as it covers the page.
    function initToggle(nav, details) {
        let saved = null;
        try { saved = window.localStorage.getItem(SIDEBAR_KEY); } catch (e) { /* storage blocked */ }
        const applyMode = () => { details.open = isSidebar() ? saved !== 'closed' : false; };
        applyMode();
        // Resizing across the breakpoint switches between the two.
        window.matchMedia(SIDEBAR_QUERY).addEventListener('change', applyMode);

        details.addEventListener('toggle', () => {
            if (!isSidebar()) return;
            saved = details.open ? 'open' : 'closed';
            try { window.localStorage.setItem(SIDEBAR_KEY, saved); } catch (e) { /* storage blocked */ }
        });

        // Floating panel: close after picking a section, on a click outside
        // it, or on Escape.
        const closePanel = () => { if (!isSidebar()) details.open = false; };
        nav.addEventListener('click', (event) => {
            if (event.target.closest('a[href*="#"]')) closePanel();
        });
        document.addEventListener('click', (event) => {
            if (details.open && !nav.contains(event.target)) closePanel();
        });
        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape' && details.open) closePanel();
        });
    }

    // Highlights the entry for the section being read: the last heading
    // that has scrolled past the top of the screen. Headings in closed tabs
    // are skipped.
    function initScrollSpy(nav) {
        const items = [...nav.querySelectorAll('a[href*="#"]')]
            .map(link => ({ link, target: document.getElementById(decodeURIComponent(new URL(link.href, window.location.href).hash.slice(1))) }))
            .filter(item => item.target);
        if (!items.length) return;

        let current = null;
        let ticking = false;

        function update() {
            ticking = false;
            let active = null;
            for (const item of items) {
                if (item.target.offsetParent === null) continue;
                if (item.target.getBoundingClientRect().top > 120) break;
                active = item;
            }
            if (active === current) return;
            if (current) current.link.classList.remove('is-active');
            current = active;
            if (!current) return;
            current.link.classList.add('is-active');

            // Keep the highlighted entry visible when the sidebar scrolls.
            const link = current.link.getBoundingClientRect();
            const box = nav.getBoundingClientRect();
            if (link.top < box.top) nav.scrollTop -= box.top - link.top + 8;
            else if (link.bottom > box.bottom) nav.scrollTop += link.bottom - box.bottom + 8;
        }

        window.addEventListener('scroll', () => {
            if (!ticking) {
                ticking = true;
                window.requestAnimationFrame(update);
            }
        }, { passive: true });
        // Switching tabs changes which headings are visible.
        document.addEventListener('shown.bs.tab', () => window.requestAnimationFrame(update));
        update();
    }

    Drupal.behaviors.eczWikiToc = {
        attach: function (context) {
            once('ecz-wiki-toc-sidebar', '.wiki-toc', context).forEach(nav => {
                const details = nav.querySelector('.wiki-toc__details');
                if (details) initToggle(nav, details);
                initScrollSpy(nav);
            });

            once('ecz-wiki-toc', '.table-of-contents-links a[href*="#"]', context).forEach(link => {
                link.addEventListener('click', (event) => {
                    const hash = new URL(link.href, window.location.href).hash;
                    if (goTo(hash, true)) {
                        event.preventDefault();
                        window.history.pushState(null, '', hash);
                    }
                });
            });

            // Arriving with a #toc-N link, e.g. shared by someone.
            once('ecz-wiki-toc-hash', 'body', context).forEach(() => {
                if (window.location.hash.startsWith('#toc-')) {
                    goTo(window.location.hash, false);
                }
            });
        }
    };
})(Drupal, once);
