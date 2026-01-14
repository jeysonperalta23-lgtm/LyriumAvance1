document.addEventListener('DOMContentLoaded', () => {
    // Referencias al DOM
    const btn = document.getElementById('btnMobilePopup');
    const overlay = document.getElementById('overlayMobilePopup');
    const closeBtn = document.getElementById('btnCloseMobilePopup');
    const links = document.querySelectorAll('.mobile-popup-link');

    if (!btn || !overlay || !closeBtn) return;

    // Función para abrir
    const openPopup = () => {
        overlay.classList.add('active');
        document.body.style.overflow = 'hidden'; // Prevenir scroll del body
    };

    // Función para cerrar
    const closePopup = () => {
        overlay.classList.remove('active');
        document.body.style.overflow = ''; // Restaurar scroll
    };

    // Event Listeners
    btn.addEventListener('click', openPopup);
    closeBtn.addEventListener('click', closePopup);

    // Cerrar al clickear fuera del modal
    overlay.addEventListener('click', (e) => {
        if (e.target === overlay) {
            closePopup();
        }
    });

    // Delegación de eventos para enlaces (soporta contenido dinámico y estático)
    document.addEventListener('click', (e) => {
        // Buscar el enlace más cercano si se hizo click en icono o span
        const link = e.target.closest('.mobile-popup-link');

        // Verificar que el enlace exista y esté dentro del popup body
        // (o simplemente que sea un link de popup activo)
        if (link && overlay.contains(link)) {
            closePopup();
            // La navegación por ancla (#id) ocurre naturalmente
        }
    });

    // Dinamismo: Contraer botón al hacer scroll
    let lastScroll = 0;
    const scrollThreshold = 100; // px para empezar a contraer

    window.addEventListener('scroll', () => {
        const currentScroll = window.pageYOffset || document.documentElement.scrollTop;

        // Si bajamos más de 100px, contraer. Si estamos arriba (<100), expandir.
        if (currentScroll > scrollThreshold) {
            btn.classList.add('mini');
        } else {
            btn.classList.remove('mini');
        }

        lastScroll = currentScroll <= 0 ? 0 : currentScroll;
    }, { passive: true });
});
