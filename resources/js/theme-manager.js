function updateTheme() {
    const theme = localStorage.getItem('theme') || 'system';
    const isDark = theme === 'dark' || (theme === 'system' && window.matchMedia('(prefers-color-scheme: dark)').matches);

    if (isDark) {
        document.documentElement.classList.add('dark');
    } else {
        document.documentElement.classList.remove('dark');
    }

    document.querySelectorAll('.app-logo').forEach(img => {
        const src = isDark ? img.dataset.srcDark : img.dataset.srcLight;
        if (src) {
            img.src = src;
        }
    });

    const themeSelects = document.querySelectorAll('#theme-select, #theme-select-responsive');
    themeSelects.forEach(select => {
        if (select.value !== theme) {
            select.value = theme;
        }
    });
}

document.addEventListener('DOMContentLoaded', () => {
    updateTheme();

    const themeSelects = document.querySelectorAll('#theme-select, #theme-select-responsive');
    themeSelects.forEach(select => {
        select.addEventListener('change', (e) => {
            const newTheme = e.target.value;
            localStorage.setItem('theme', newTheme);
            updateTheme();
        });
    });
});

window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', () => {
    const theme = localStorage.getItem('theme') || 'system';
    if (theme === 'system') {
        updateTheme();
    }
});
