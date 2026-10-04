(function () {
  const root = document.documentElement;
  const toggle = document.getElementById('theme-toggle');

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

    root.setAttribute('data-theme', next);
    localStorage.setItem('theme', next);
  });
})();
