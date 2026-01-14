document.addEventListener('DOMContentLoaded', () => {
    const filterBtn = document.getElementById('btnFiltros');
    const filterDropdown = document.getElementById('filterDropdown');
    const priceSlider = document.getElementById('priceRange');
    const priceValue = document.getElementById('priceValue');
    const keyboardToggle = document.getElementById('keyboardToggle');
    const filterCategory = document.getElementById('filterCategory');
    const filterMaxPrice = document.getElementById('filterMaxPrice');

    // 1. Toggle Filter Dropdown (Simple y Robusto)
    if (filterBtn && filterDropdown) {
        filterBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            const isActive = filterDropdown.classList.toggle('active');
            filterBtn.classList.toggle('bg-sky-600', isActive);
        });

        // Cerrar al hacer clic fuera
        document.addEventListener('click', (e) => {
            if (!filterDropdown.contains(e.target) && !filterBtn.contains(e.target)) {
                if (filterDropdown.classList.contains('active')) {
                    filterDropdown.classList.remove('active');
                    filterBtn.classList.remove('bg-sky-600');
                }
            }
        });
    }

    // 2. Lógica de Rango de Precio
    if (priceSlider && priceValue) {
        priceSlider.addEventListener('input', (e) => {
            const val = e.target.value;
            priceValue.textContent = `S/ ${val}`;
            if (filterMaxPrice) filterMaxPrice.value = val;
            updateIndicator('precio', `Máx: S/ ${val}`);
        });
    }

    // 3. Manejo de Categorías (Con ID real para la búsqueda)
    const filterItems = document.querySelectorAll('.filter-item-chip');
    filterItems.forEach(item => {
        item.addEventListener('click', () => {
            const type = item.dataset.type;
            const categoryId = item.dataset.id;
            const value = item.textContent.trim();

            if (type === 'categoria') {
                const isAlreadyActive = item.classList.contains('active');

                // Limpiar otros chips de categoría
                document.querySelectorAll('.filter-item-chip[data-type="categoria"]').forEach(c => {
                    c.classList.remove('active', 'bg-sky-500', 'text-white', 'border-sky-500');
                });

                if (isAlreadyActive) {
                    // Deseleccionar
                    if (filterCategory) filterCategory.value = "";
                    updateIndicator('categoria', 'Categoría: Todas', false);
                } else {
                    // Seleccionar
                    item.classList.add('active', 'bg-sky-500', 'text-white', 'border-sky-500');
                    if (filterCategory) filterCategory.value = categoryId;
                    updateIndicator('categoria', `Categoría: ${value}`, true);
                }
            } else if (type === 'marca') {
                // Lógica para marcas si la necesitas a futuro
                item.classList.toggle('active');
                updateIndicator(type, value, item.classList.contains('active'));
            }
        });
    });

    // 4. Teclado Virtual
    if (keyboardToggle) {
        keyboardToggle.addEventListener('change', () => {
            const isActive = keyboardToggle.checked;
            window.isVirtualKeyboardEnabled = isActive;
            updateIndicator('teclado', isActive ? 'Teclado: ON' : 'Teclado: OFF', isActive);

            if (!isActive && window.hideVirtualKeyboard) {
                window.hideVirtualKeyboard();
            }
        });
    }

    function updateIndicator(type, value, active = true) {
        const indicator = document.querySelector(`.js-indicator-item[data-indicator="${type}"] .indicator-val`);
        if (indicator) {
            indicator.textContent = value;
            const parent = indicator.closest('.js-indicator-item');
            if (parent) {
                if (active) parent.classList.add('active');
                else parent.classList.remove('active');
            }
        }
    }

    // 5. Aplicar Filtros (Funcionalidad de Rango de Precio)
    const applyFiltersBtn = document.getElementById('applyFilters');
    if (applyFiltersBtn) {
        applyFiltersBtn.addEventListener('click', () => {
            applyPriceFilter();
            // Cerrar el dropdown después de aplicar
            if (filterDropdown) {
                filterDropdown.classList.remove('active');
                if (filterBtn) filterBtn.classList.remove('bg-sky-600');
            }
        });
    }

    function applyPriceFilter() {
        const maxPrice = parseFloat(priceSlider ? priceSlider.value : 2000);
        const products = document.querySelectorAll('.js-product-card');

        products.forEach(product => {
            const price = parseFloat(product.dataset.price);
            if (isNaN(price)) return;

            if (price <= maxPrice) {
                product.style.display = ''; // O el display original
                product.style.opacity = '1';
                product.style.visibility = 'visible';
            } else {
                product.style.display = 'none';
                product.style.opacity = '0';
                product.style.visibility = 'hidden';
            }
        });

        console.log(`Filtro aplicado: Máx S/ ${maxPrice}. Productos filtrados.`);
    }
});
