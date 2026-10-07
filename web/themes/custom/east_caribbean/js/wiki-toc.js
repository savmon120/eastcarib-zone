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

    Drupal.behaviors.eczWikiToc = {
        attach: function (context) {
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
