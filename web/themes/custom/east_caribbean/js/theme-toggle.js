(function () {
  const root = document.documentElement;
  const toggle = document.getElementById('theme-toggle');

  const applyTheme = (theme) => {
    root.setAttribute('data-theme', theme);
    localStorage.setItem('theme', theme);
    window.dispatchEvent(new CustomEvent('theme:change', { detail: theme }));
  };

  const saved = localStorage.getItem('theme');
  if (saved) {
    root.setAttribute('data-theme', saved);
  }

  if (!toggle) {
    return;
  }

  toggle.addEventListener('click', () => {
    const current = root.getAttribute('data-theme') || 'light';
    const next = current === 'light' ? 'dark' : 'light';

    applyTheme(next);
  });
})();
