


(function () {
    'use strict';

    const TRANSITION_DURATION = 400; // ms

    class DarkModeToggle {
        constructor() {
            this.button = document.getElementById('darkModeToggle');
            this.isDark = false; // El modo oscuro siempre inicia desactivado

            if (!this.button) {
                console.warn('Dark mode toggle button not found');
                return;
            }
            this.init();
        }

        init() {
            // Aplicar tema inicial (claro) sin transición
            this.applyTheme(false);

            // Listener para el botón
            this.button.addEventListener('click', () => this.toggle());

            // Escuchar cambios de tema del sistema (opcional: podrías quitarlo si quieres 100% manual)
            if (window.matchMedia) {
                window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', (e) => {
                    // Solo responde si no se ha toggleado manualmente en esta sesión
                    // Pero para cumplir con la petición de "siempre desactivado al entrar",
                    // lo ignoramos o lo dejamos solo como sugerencia inicial si prefieres.
                    // Por ahora, lo mantenemos desactivado por defecto.
                });
            }
        }

        toggle() {
            this.isDark = !this.isDark;
            this.applyTheme(true);
        }

        applyTheme(withTransition = true) {
            const root = document.documentElement;

            if (withTransition) {
                root.style.setProperty('--theme-transition', `${TRANSITION_DURATION}ms`);
                setTimeout(() => {
                    root.style.removeProperty('--theme-transition');
                }, TRANSITION_DURATION);
            }

            if (this.isDark) {
                root.classList.add('dark-mode');
                this.updateButtonIcon('light');
            } else {
                root.classList.remove('dark-mode');
                this.updateButtonIcon('dark');
            }
        }

        updateButtonIcon(mode) {
            const moonIcon = this.button.querySelector('.moon-icon');
            const sunIcon = this.button.querySelector('.sun-icon');

            if (mode === 'dark') {
                moonIcon.style.opacity = '1';
                moonIcon.style.transform = 'rotate(0deg) scale(1)';
                sunIcon.style.opacity = '0';
                sunIcon.style.transform = 'rotate(180deg) scale(0)';
            } else {
                moonIcon.style.opacity = '0';
                moonIcon.style.transform = 'rotate(-180deg) scale(0)';
                sunIcon.style.opacity = '1';
                sunIcon.style.transform = 'rotate(0deg) scale(1)';
            }
        }
    }

    // Inicializar cuando el DOM esté listo
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', () => new DarkModeToggle());
    } else {
        new DarkModeToggle();
    }
})();

