// ===================== NAVEGACIÓN MÓVIL PARA PÁGINAS LEGALES =====================

document.addEventListener('DOMContentLoaded', function () {
    const mobileIndexBtn = document.getElementById('mobileIndexBtn');
    const mobileIndexPanel = document.getElementById('mobileIndexPanel');
    const mobileIndexOverlay = document.getElementById('mobileIndexOverlay');
    const closeMobileIndex = document.getElementById('closeMobileIndex');

    if (!mobileIndexBtn || !mobileIndexPanel || !mobileIndexOverlay) return;

    // Función para abrir el panel de índice
    function openMobileIndex() {
        mobileIndexOverlay.classList.remove('hidden');
        mobileIndexOverlay.classList.add('opacity-100');

        setTimeout(() => {
            mobileIndexPanel.classList.remove('translate-x-full');
            mobileIndexPanel.classList.add('translate-x-0');
        }, 10);

        document.body.style.overflow = 'hidden';
    }

    // Función para cerrar el panel de índice
    function closeMobileIndexFunc() {
        mobileIndexPanel.classList.add('translate-x-full');
        mobileIndexPanel.classList.remove('translate-x-0');

        setTimeout(() => {
            mobileIndexOverlay.classList.add('hidden');
            mobileIndexOverlay.classList.remove('opacity-100');
        }, 300);

        document.body.style.overflow = '';
    }

    // Event listeners
    mobileIndexBtn.addEventListener('click', openMobileIndex);

    if (closeMobileIndex) {
        closeMobileIndex.addEventListener('click', closeMobileIndexFunc);
    }

    mobileIndexOverlay.addEventListener('click', closeMobileIndexFunc);

    // Cerrar al hacer clic en un enlace del índice
    const indexLinks = mobileIndexPanel.querySelectorAll('a');
    indexLinks.forEach(link => {
        link.addEventListener('click', () => {
            setTimeout(closeMobileIndexFunc, 200);
        });
    });

    // Highlight de sección activa al hacer scroll
    const sections = document.querySelectorAll('[id^="pp-"]');
    const navLinks = document.querySelectorAll('.mobile-index-link');

    function highlightActiveSection() {
        let current = '';

        sections.forEach(section => {
            const sectionTop = section.offsetTop;
            const sectionHeight = section.clientHeight;

            if (window.pageYOffset >= sectionTop - 100) {
                current = section.getAttribute('id');
            }
        });

        navLinks.forEach(link => {
            link.classList.remove('active');
            if (link.getAttribute('href') === '#' + current) {
                link.classList.add('active');
            }
        });
    }

    window.addEventListener('scroll', highlightActiveSection);
    highlightActiveSection(); // Ejecutar al cargar
});
