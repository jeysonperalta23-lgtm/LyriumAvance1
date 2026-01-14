/**
 * preguntasfrecuentes.js
 * Lógica de interacción para la página de Preguntas Frecuentes.
 */

document.addEventListener('DOMContentLoaded', () => {

    // =========================================================
    // 1. Smooth Scroll para los Tabs
    // =========================================================
    const tabLinks = document.querySelectorAll('.tab-card[href^="#"]');

    tabLinks.forEach(link => {
        link.addEventListener('click', (e) => {
            e.preventDefault();
            const targetId = link.getAttribute('href');
            const targetEl = document.querySelector(targetId);

            if (targetEl) {
                // Offset para el header fijo
                const headerOffset = 180;
                const elementPosition = targetEl.getBoundingClientRect().top;
                const offsetPosition = elementPosition + window.pageYOffset - headerOffset;

                window.scrollTo({
                    top: offsetPosition,
                    behavior: 'smooth'
                });

                // Efecto visual de resaltado (opcional)
                targetEl.classList.add('ring-4', 'ring-sky-200', 'ring-opacity-50');
                setTimeout(() => {
                    targetEl.classList.remove('ring-4', 'ring-sky-200', 'ring-opacity-50');
                }, 1500);
            }
        });
    });

    // =========================================================
    // 2. Acordeón Exclusivo (cierra otros al abrir uno)
    // =========================================================
    const details = document.querySelectorAll("details.faq-item");

    details.forEach((targetDetail) => {
        targetDetail.addEventListener("click", () => {
            // Si se está abriendo...
            if (!targetDetail.open) {
                // Cerrar los demás
                details.forEach((detail) => {
                    if (detail !== targetDetail) {
                        detail.removeAttribute("open");
                    }
                });
            }
        });
    });

});
