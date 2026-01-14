document.addEventListener('DOMContentLoaded', function () {
    const navLinks = document.querySelectorAll('.pp-nav a');
    const sections = document.querySelectorAll('.pp-section');

    // CLICK BEHAVIOR: Smooth Scroll + Highlight
    navLinks.forEach(link => {
        link.addEventListener('click', function (e) {
            e.preventDefault();
            const targetId = this.getAttribute('href').substring(1);
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

    // SCROLL BEHAVIOR: Intersection Observer for TOC highlight
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
                    navLinks.forEach(l => l.classList.remove('selected'));
                    activeLink.classList.add('selected');
                }
            }
        });
    }, observerOptions);

    sections.forEach(section => observer.observe(section));
});
