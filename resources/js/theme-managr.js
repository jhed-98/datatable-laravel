document.addEventListener('DOMContentLoaded', (event) => {
    const toggleDarkModeBtn = document.getElementById('toggle-dark-mode');

    // Recuperar el modo oscuro de localStorage
    if (localStorage.getItem('darkMode') === 'enabled') {
        document.documentElement.classList.add('dark');
    }

    toggleDarkModeBtn.addEventListener('click', () => {
        document.documentElement.classList.toggle('dark');

        // Guardar el estado del modo oscuro en localStorage
        if (document.documentElement.classList.contains('dark')) {
            localStorage.setItem('darkMode', 'enabled');
        } else {
            localStorage.setItem('darkMode', 'disabled');
        }
    });
});
