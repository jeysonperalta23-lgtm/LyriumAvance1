function initTermsPage() {
    // Ahora leemos los datos desde el atributo data- del contenedor principal
    const elWrap = document.querySelector('.pp-wrap');
    if (!elWrap) {
        console.error('Lyrium: .pp-wrap no encontrado');
        return;
    }

    let DATA = {};
    let MODE_CONF = {};

    try {
        DATA = JSON.parse(elWrap.getAttribute('data-terms') || '{}');
        MODE_CONF = JSON.parse(elWrap.getAttribute('data-config') || '{}');
    } catch (e) {
        console.error('Lyrium: Error parseando JSON de términos', e);
        return;
    }

    const elTabs = Array.from(document.querySelectorAll('[data-mode]'));
    const elSubtitle = document.getElementById('tcSubtitle');
    const elPdfBtn = document.getElementById('tcPdfBtn');
    const elPdfLabel = document.getElementById('tcPdfLabel');
    const elContent = document.getElementById('tcContent');
    const elTOC = document.getElementById('tcTOC');

    // Referencia al pop-up móvil
    const mobileBody = document.querySelector('#overlayMobilePopup .mobile-popup-body');

    let currentSelected = null;

    function setupNavLinks() {
        // Seleccionamos tanto links de escritorio como móviles
        const navLinksDesktop = document.querySelectorAll('.pp-nav a');
        const navLinksMobile = document.querySelectorAll('.mobile-popup-link');
        const navLinks = [...navLinksDesktop, ...navLinksMobile];

        const sections = document.querySelectorAll('.pp-section');

        // CLICK BEHAVIOR: Smooth Scroll + Highlight
        navLinks.forEach(link => {
            // Clonamos nodos para quitar listeners anteriores si se llama multiple veces
            // Pero aquí usamos delegación o gestión simple.
            // Para simplificar, asumimos que se regenera el DOM o se gestiona bien.

            link.addEventListener('click', function (e) {
                // Si es móvil, cerrar popup
                if (this.classList.contains('mobile-popup-link')) {
                    const overlay = document.getElementById('overlayMobilePopup');
                    const btn = document.getElementById('btnMobilePopup');
                    if (overlay) overlay.classList.remove('active');
                    if (btn) btn.classList.remove('active');
                    document.body.style.overflow = '';
                }

                const href = this.getAttribute('href');
                if (!href || href === '#') return;

                e.preventDefault();
                const targetId = href.substring(1);
                const targetEl = document.getElementById(targetId);

                if (targetEl) {
                    // Remove highlight from previous
                    document.querySelectorAll('.highlight-target').forEach(el => el.classList.remove('highlight-target'));

                    // Add highlight
                    targetEl.classList.add('highlight-target');

                    // Scroll
                    const offset = 160;
                    const bodyRect = document.body.getBoundingClientRect().top;
                    const elementRect = targetEl.getBoundingClientRect().top;
                    const elementPosition = elementRect - bodyRect;
                    const offsetPosition = elementPosition - offset;

                    window.scrollTo({
                        top: offsetPosition,
                        behavior: 'smooth'
                    });

                    // Remove highlight class after animation finish
                    setTimeout(() => {
                        targetEl.classList.remove('highlight-target');
                    }, 2500);
                }
            });
        });

        // SCROLL BEHAVIOR: Intersection Observer for TOC highlight (Solo Desktop TOC por ahora)
        if ('IntersectionObserver' in window) {
            const observerOptions = {
                root: null,
                rootMargin: '-150px 0px -70% 0px',
                threshold: 0
            };

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const id = entry.target.getAttribute('id');
                        const activeLink = document.querySelector(`.pp-nav a[href="#${id}"]`);

                        if (activeLink) {
                            navLinksDesktop.forEach(l => l.classList.remove('selected'));
                            activeLink.classList.add('selected');
                        }
                    }
                });
            }, observerOptions);

            sections.forEach(section => observer.observe(section));
        }
    }

    function render(mode) {
        if (!MODE_CONF[mode]) return;

        elTabs.forEach(b => b.classList.toggle('active', b.dataset.mode === mode));
        if (elSubtitle) elSubtitle.textContent = MODE_CONF[mode].subtitle;
        if (elPdfLabel) elPdfLabel.textContent = MODE_CONF[mode].pdfLabel;

        if (elPdfBtn) {
            elPdfBtn.setAttribute('href', MODE_CONF[mode].pdfHref);
            elPdfBtn.setAttribute('download', MODE_CONF[mode].pdfName);
        }

        const sections = DATA[mode] || [];

        if (elContent) {
            elContent.innerHTML = sections.map(s => `
            <div class="pp-section" id="${s.id}">
                <div class="pp-h"><span>${s.title}</span></div>
                ${s.html}
            </div>
            `).join('');
        }

        if (elTOC) {
            elTOC.innerHTML = sections.map(s => `
            <a href="#${s.id}" class="pp-nav-link" data-id="${s.id}">
                <span class="flex-1">${s.title}</span>
                <i class="ph ph-caret-right text-xs opacity-0 -translate-x-2 transition-all"></i>
            </a>
            `).join('');
        }

        // Actualizar también el cuerpo del menú móvil POP-UP
        const mobileContent = document.getElementById('mobilePopupContent');
        if (mobileContent) {
            // Título para el móvil (Opcional, hace que sea más atractivo)
            const modeTitle = mode === 'cliente' ? 'Índice de Clientes' : 'Índice de Vendedores';

            // Log para depuración interna
            console.log("Lyrium Terms [v1.1]: Renderizando modo " + mode);

            mobileContent.innerHTML = `
                <div class="px-4 py-2 text-xs font-bold text-gray-400 uppercase tracking-widest bg-gray-50 border-b border-gray-100 mb-2">
                    ${modeTitle}
                </div>
                <div class="animate-in">
                    ${sections.map(s => `
                        <a href="#${s.id}" class="mobile-popup-link">
                            <span>${s.title}</span><i class="ph ph-caret-right"></i>
                        </a>
                    `).join('')}
                </div>
            `;
        } else {
            console.error('Lyrium Terms [v1.1]: No se encontró #mobilePopupContent en el DOM');
        }

        // Re-asignar listeners
        setTimeout(setupNavLinks, 100);
    }

    elTabs.forEach(btn => btn.addEventListener('click', () => {
        render(btn.dataset.mode);
        // Volver arriba al cambiar de pestaña
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }));

    // Renderizado inicial: SOPORTE PARA DEEP-LINKING (#cliente o #vendedor)
    let initialMode = 'cliente';
    const hash = window.location.hash.substring(1).toLowerCase();
    if (hash === 'vendedor') initialMode = 'vendedor';

    if (elTabs.length > 0) {
        render(initialMode);
    }
}

// Ejecución robusta: Si DOM ya está listo, ejecutar directo. Si no, esperar.
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initTermsPage);
} else {
    initTermsPage();
}
