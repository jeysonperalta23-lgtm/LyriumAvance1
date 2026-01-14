/**
 * SLIDER HORIZONTAL DE PUBLICIDAD
 * Autoplay automático
 */
function initSliderPublicidad() {
    const slides = document.querySelectorAll('.slider-publicidad-slide');
    const dots = document.querySelectorAll('.slider-publicidad-dot');

    if (slides.length <= 1) return;

    let currentSlide = 0;
    const slideInterval = 4000; // 4 segundos

    function showSlide(index) {
        // Remover active de todos
        slides.forEach(slide => slide.classList.remove('active'));
        dots.forEach(dot => dot.classList.remove('active'));

        // Activar el actual
        slides[index].classList.add('active');
        dots[index].classList.add('active');
    }

    function nextSlide() {
        currentSlide = (currentSlide + 1) % slides.length;
        showSlide(currentSlide);
    }

    // Autoplay
    setInterval(nextSlide, slideInterval);

    // Click en dots
    dots.forEach((dot, index) => {
        dot.addEventListener('click', () => {
            currentSlide = index;
            showSlide(currentSlide);
        });
    });
}

/**
 * SLIDER DE DESCUENTOS HORIZONTAL
 * Autoplay automático (igual que publicidad)
 */
function initSliderDescuentos() {
    const slides = document.querySelectorAll('.slider-descuentos-slide');
    const dots = document.querySelectorAll('.slider-descuentos-dot');

    if (slides.length <= 1) return;

    let currentSlide = 0;
    const slideInterval = 5000; // 5 segundos

    function showSlide(index) {
        // Remover active de todos
        slides.forEach(slide => slide.classList.remove('active'));
        dots.forEach(dot => dot.classList.remove('active'));

        // Activar el actual
        slides[index].classList.add('active');
        dots[index].classList.add('active');
    }

    function nextSlide() {
        currentSlide = (currentSlide + 1) % slides.length;
        showSlide(currentSlide);
    }

    // Autoplay
    setInterval(nextSlide, slideInterval);

    // Click en dots
    dots.forEach((dot, index) => {
        dot.addEventListener('click', () => {
            currentSlide = index;
            showSlide(currentSlide);
        });
    });
}

/**
 * BANNER PRINCIPAL (HERO)
 * Autoplay automático
 */
function initMainBanner() {
    const banner = document.querySelector('.tienda-banner');
    if (!banner) return;

    const slides = banner.querySelectorAll('.tienda-banner-slide');
    const dots = banner.querySelectorAll('.tienda-banner-dot');
    const prevBtn = banner.querySelector('.tienda-banner-nav.prev');
    const nextBtn = banner.querySelector('.tienda-banner-nav.next');

    if (slides.length <= 1) return;

    let currentSlide = 0;
    const slideInterval = 5000; // 5 segundos
    let autoPlayTimer = null;

    function showSlide(index) {
        currentSlide = (index + slides.length) % slides.length;

        slides.forEach((slide, i) => {
            slide.classList.toggle('active', i === currentSlide);
        });

        dots.forEach((dot, i) => {
            dot.classList.toggle('active', i === currentSlide);
        });
    }

    function nextSlide() {
        showSlide(currentSlide + 1);
    }

    function prevSlide() {
        showSlide(currentSlide - 1);
    }

    // Navegación
    if (nextBtn) nextBtn.addEventListener('click', () => {
        nextSlide();
        resetTimer();
    });

    if (prevBtn) prevBtn.addEventListener('click', () => {
        prevSlide();
        resetTimer();
    });

    // Dots
    dots.forEach((dot, index) => {
        dot.addEventListener('click', () => {
            showSlide(index);
            resetTimer();
        });
    });

    // Autoplay logic
    function startTimer() {
        stopTimer();
        autoPlayTimer = setInterval(nextSlide, slideInterval);
    }

    function stopTimer() {
        if (autoPlayTimer) {
            clearInterval(autoPlayTimer);
            autoPlayTimer = null;
        }
    }

    function resetTimer() {
        startTimer();
    }

    // Inicializar
    showSlide(0);
    startTimer();

    // Pausar en hover
    banner.addEventListener('mouseenter', stopTimer);
    banner.addEventListener('mouseleave', startTimer);
}

// Inicializar cuando el DOM esté listo
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => {
        initMainBanner();
        initSliderPublicidad();
        initSliderDescuentos();
    });
} else {
    initMainBanner();
    initSliderPublicidad();
    initSliderDescuentos();
}
