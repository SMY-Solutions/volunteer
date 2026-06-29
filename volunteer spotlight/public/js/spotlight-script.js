/**
 * Volunteer Spotlight - Frontend Script
 * Initializes the Swiper slider
 */
document.addEventListener('DOMContentLoaded', function () {
    const settings = window.vspSettings || {};
    const autoplay = settings.autoplay !== false;
    const speed = settings.speed || 4000;

    const swiperConfig = {
        loop: true,
        slidesPerView: 1,
        spaceBetween: 30,
        grabCursor: true,
        effect: 'slide',
        speed: 600,

        // Pagination
        pagination: {
            el: '.vsp-pagination',
            clickable: true,
        },

        // Navigation arrows
        navigation: {
            nextEl: '.vsp-nav-next',
            prevEl: '.vsp-nav-prev',
        },

        // Responsive breakpoints
        breakpoints: {
            1024: {
                slidesPerView: 1,
                spaceBetween: 30,
            },
        },

        // Keyboard navigation
        keyboard: {
            enabled: true,
            onlyInViewport: true,
        },
    };

    // Add autoplay if enabled
    if (autoplay) {
        swiperConfig.autoplay = {
            delay: speed,
            disableOnInteraction: false,
            pauseOnMouseEnter: true,
        };
    }

    // Initialize Swiper
    const swiper = new Swiper('.vsp-swiper', swiperConfig);
});
