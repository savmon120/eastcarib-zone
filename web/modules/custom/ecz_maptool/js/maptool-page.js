(function (Drupal, once) {
    'use strict';

    // Map tool page (/maptool): fills the screen below the navbar, as the
    // charts page does. The map itself is js/app.js.

    function fitToScreen(root) {
        const top = root.getBoundingClientRect().top + window.scrollY;
        root.style.setProperty('--maptool-top', `${Math.round(top)}px`);
    }

    Drupal.behaviors.eczMaptoolPage = {
        attach: function (context) {
            once('ecz-maptool-page', '[data-maptool]', context).forEach(root => {
                fitToScreen(root);
                window.addEventListener('resize', () => fitToScreen(root));
            });
        }
    };
})(Drupal, once);
