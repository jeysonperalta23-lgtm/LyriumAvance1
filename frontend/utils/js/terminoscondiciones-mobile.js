// Extensión para terminoscondiciones.js - Actualizar índice móvil
(function () {
    // Esperar a que el DOM esté listo
    const originalRender = window.renderTerminos || function () { };

    // Interceptar cuando se renderiza el contenido
    document.addEventListener('DOMContentLoaded', function () {
        // Observar cambios en el contenido para actualizar el índice móvil
        const observer = new MutationObserver(function () {
            updateMobileTOC();
        });

        const tcContent = document.getElementById('tcContent');
        if (tcContent) {
            observer.observe(tcContent, { childList: true, subtree: true });
        }

        // Actualizar inicialmente
        setTimeout(updateMobileTOC, 500);
    });

    function updateMobileTOC() {
        const elMobileTOC = document.getElementById('mobileTOC');
        const sections = document.querySelectorAll('.pp-section');

        if (!elMobileTOC || sections.length === 0) return;

        const links = Array.from(sections).map(section => {
            const id = section.getAttribute('id');
            const title = section.querySelector('.pp-h span')?.textContent || '';

            return `
                <a href="#${id}" class="mobile-index-link" data-id="${id}">
                    <span>${title}</span>
                    <i class="ph-caret-right mobile-index-arrow"></i>
                </a>
            `;
        }).join('');

        elMobileTOC.innerHTML = links;
    }
})();
