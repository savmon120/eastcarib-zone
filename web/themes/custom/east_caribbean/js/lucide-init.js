(function (Drupal) {
    Drupal.behaviors.lucideIcons = {
        attach: function (context, settings) {
            if (typeof lucide !== 'undefined') {
                // Only pass a root for partial attaches (e.g. AJAX); on the
                // full page lucide defaults to the whole document. Passing
                // root: null makes lucide call null.querySelectorAll().
                lucide.createIcons(context instanceof Element ? { root: context } : {});
            }
        }
    };
})(Drupal);
