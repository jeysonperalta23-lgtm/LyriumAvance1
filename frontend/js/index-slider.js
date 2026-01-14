
document.addEventListener('DOMContentLoaded', function () {
    const snogSlider = document.querySelector('.upk-snog-slider');
    if (snogSlider) {
        const settings = JSON.parse(snogSlider.getAttribute('data-settings'));

        const thumbs = new Swiper('.upk-snog-thumbs', {
            loop: settings.loop,
            spaceBetween: 10,
            slidesPerView: settings.loopedSlides,
            freeMode: true,
            watchSlidesProgress: true,
        });

        const contentSlider = new Swiper('.upk-content-slider', {
            loop: settings.loop,
            speed: settings.speed,
            effect: 'slide',
            parallax: true,
            loopedSlides: settings.loopedSlides,
        });

        const mainSlider = new Swiper('.upk-main-slider', {
            loop: settings.loop,
            speed: settings.speed,
            effect: settings.effect,
            lazy: settings.lazy,
            slidesPerView: settings.slidesPerView,
            loopedSlides: settings.loopedSlides,
            pagination: {
                el: '.upk-pagination',
                clickable: true,
            },
            navigation: {
                nextEl: '.upk-navigation-next',
                prevEl: '.upk-navigation-prev',
            },
            thumbs: {
                swiper: thumbs,
            },
        });
        
        mainSlider.controller.control = contentSlider;
        contentSlider.controller.control = mainSlider;
    }
});
