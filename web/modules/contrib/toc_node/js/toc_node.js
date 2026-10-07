(function (Drupal, once) {
  let listenerAttached = false;

  Drupal.behaviors.tocNodeBackToTop = {
    attach: function (context) {
      const buttons = once('toc-node-back-to-top', '#toc-back-to-top', context);
      if (!buttons.length) {
        return;
      }

      function toggleButton() {
        buttons.forEach(function (button) {
          if (window.scrollY > 0) {
            button.classList.add('visible');
          } else {
            button.classList.remove('visible');
          }
        });
      }

      function onScroll() {
        window.requestAnimationFrame(toggleButton);
      }

      if (!listenerAttached) {
        window.addEventListener('scroll', onScroll);
        listenerAttached = true;
      }

      buttons.forEach(function (button) {
        button.addEventListener('click', function () {
          window.scrollTo({ top: 0, behavior: 'smooth' });
        });
      });

      toggleButton();
    }
  };
})(Drupal, once);