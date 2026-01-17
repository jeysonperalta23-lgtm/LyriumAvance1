/**
 * BioForo - Logic
 */
document.addEventListener('DOMContentLoaded', () => {
    // Admin: Toggle create forum form
    const btnToggleForo = document.querySelector('[data-toggle-foro]');
    const formCrearForo = document.getElementById('formCrearForo');

    if (btnToggleForo && formCrearForo) {
        btnToggleForo.addEventListener('click', () => {
            formCrearForo.classList.toggle('hidden');
        });
    }

    // Smooth scroll for anchors
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth'
                });
            }
        });
    });

    // Auto-expand textarea (optional, for better UX)
    const textareas = document.querySelectorAll('textarea');
    textareas.forEach(textarea => {
        textarea.addEventListener('input', () => {
            textarea.style.height = 'auto';
            textarea.style.height = (textarea.scrollHeight) + 'px';
        });
    });
});
