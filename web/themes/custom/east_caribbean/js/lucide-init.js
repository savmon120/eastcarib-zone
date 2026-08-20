(function (Drupal) {
    Drupal.behaviors.lucideIcons = {
        attach: function (context, settings) {
            if (typeof lucide !== 'undefined') {
                lucide.createIcons({
                    root: context !== document ? context : null
                });
            }
        }
    };
})(Drupal);