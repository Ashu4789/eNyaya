document.addEventListener('DOMContentLoaded', () => {
    const root = document.documentElement;
    const stored = localStorage.getItem('enyaya-theme');
    if (stored) root.dataset.theme = stored;

    document.getElementById('darkModeToggle')?.addEventListener('click', () => {
        root.dataset.theme = root.dataset.theme === 'dark' ? 'light' : 'dark';
        localStorage.setItem('enyaya-theme', root.dataset.theme);
    });

    document.querySelectorAll('.toast').forEach((toast) => new bootstrap.Toast(toast, {delay: 3500}).show());
});
