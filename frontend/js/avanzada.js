document.addEventListener('DOMContentLoaded', function () {
    const toggleButton = document.getElementById('toggleAvanzada');
    const avanzadaContainer = document.getElementById('avanzadaContainer');
    const priceRange = document.getElementById('priceRange');
    const priceValue = document.getElementById('priceValue');

    if (toggleButton) {
        toggleButton.addEventListener('click', () => {
            avanzadaContainer.classList.toggle('show');
        });
    }

    if (priceRange) {
        priceRange.addEventListener('input', () => {
            priceValue.textContent = priceRange.value;
        });
    }
});
