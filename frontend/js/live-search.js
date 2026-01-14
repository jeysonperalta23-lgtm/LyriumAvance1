/**
 * Lyrium Live Search Script
 * Handles real-time search suggestions.
 */

document.addEventListener("DOMContentLoaded", () => {
    const searchInput = document.getElementById("searchInput");
    const searchForm = document.getElementById("searchForm");

    if (!searchInput) {
        console.warn("Live Search: searchInput not found.");
        return;
    }

    console.log("Live Search: Initialized for #searchInput");

    // Create Dropdown Container if it doesn't exist
    let dropdown = document.getElementById("liveSearchDropdown");
    if (!dropdown) {
        dropdown = document.createElement("div");
        dropdown.id = "liveSearchDropdown";
        // Append to parent, or to body as absolute if parent is tricky
        searchInput.parentElement.appendChild(dropdown);
        console.log("Live Search: Dropdown container created and appended.");
    }

    let debounceTimer;

    let currentData = null;

    const performSearch = async (query) => {
        if (query.trim().length < 2) {
            dropdown.classList.remove("active");
            return;
        }

        console.log("Live Search: Searching for", query);

        try {
            // First try relative to frontend, if not try from root
            // Usually if we are in frontend/index.php, ../backend works.
            const apiUrl = `../backend/api/live_search.php?q=${encodeURIComponent(query)}`;
            const response = await fetch(apiUrl);

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            currentData = await response.json();
            console.log("Live Search: Data received", currentData);

            if (currentData.error) {
                console.error("Live Search: API Error:", currentData.error);
                dropdown.innerHTML = `<div class="no-results text-red-500">Error en la búsqueda: ${currentData.error}</div>`;
                dropdown.classList.add("active");
                return;
            }

            renderDropdown(currentData, query);
        } catch (error) {
            console.error("Live Search: Fetch Error:", error);
            // Try fallback path if the first one failed (e.g. if accessed from root)
            try {
                const fallbackUrl = `backend/api/live_search.php?q=${encodeURIComponent(query)}`;
                const response = await fetch(fallbackUrl);
                if (response.ok) {
                    currentData = await response.json();
                    renderDropdown(currentData, query);
                } else {
                    console.error("Live Search: Fallback fetch failed with status:", response.status);
                }
            } catch (e) {
                console.error("Live Search: Fallback fetch error:", e);
            }
        }
    };

    const renderProducts = (products, title = "Productos") => {
        if (!products || products.length === 0) {
            return `<div class="text-xs text-gray-400 p-4">Sin resultados en esta categoría</div>`;
        }

        const productsHtml = products.map(prod => `
            <a href="buscar.php?q=${encodeURIComponent(prod.nombre)}" class="product-suggestion-item">
                <img src="${prod.url_imagen || 'img/placeholder.png'}" alt="${prod.nombre}" class="product-suggestion-img" onerror="this.src='img/placeholder.png'">
                <div class="product-suggestion-info">
                    <div class="product-suggestion-name">${prod.nombre}</div>
                    ${prod.categoria_nombre ? `<div class="product-suggestion-category">Categoría: ${prod.categoria_nombre}</div>` : ''}
                    <div class="product-suggestion-price">S/ ${parseFloat(prod.precio_oferta || prod.precio).toFixed(2)}</div>
                </div>
            </a>
        `).join('');

        return `
            <div class="live-search-title">${title}</div>
            <div class="products-scroll-area">
                ${productsHtml}
            </div>
        `;
    };

    const renderDropdown = (data, query) => {
        if (!data || (!data.categories && !data.products_match)) {
            console.warn("Live Search: Invalid data format", data);
            return;
        }

        if (!data.categories?.length && !data.products_match?.length) {
            dropdown.innerHTML = `<div class="no-results">No se encontraron resultados para "${query}"</div>`;
            dropdown.classList.add("active");
            return;
        }

        let categoriesHtml = (data.categories || []).map((cat, idx) => `
            <a href="buscar.php?q=${encodeURIComponent(cat.nombre)}" 
               class="category-suggestion ${idx === 0 ? 'active' : ''}" 
               data-cat-id="${cat.id}">
                ${cat.nombre.replace(new RegExp(query, 'gi'), str => `<strong>${str}</strong>`)}
            </a>
        `).join('');

        dropdown.innerHTML = `
            <div class="live-search-container">
                <div class="live-search-left">
                    <div class="live-search-title">Categorías</div>
                    <div class="categories-list">
                        ${categoriesHtml || '<div class="text-xs text-gray-400">Sin categorías</div>'}
                    </div>
                </div>
                <div class="live-search-right" id="liveSearchRight">
                    <!-- Productos se cargan aquí -->
                </div>
            </div>
        `;

        // Mostrar productos de la primera categoría por defecto o productos generales
        const rightCol = document.getElementById("liveSearchRight");
        if (data.categories && data.categories.length > 0) {
            const firstCatId = data.categories[0].id;
            const categoryProducts = (data.category_products && data.category_products[firstCatId]) || [];
            rightCol.innerHTML = renderProducts(categoryProducts, `Categoría: ${data.categories[0].nombre}`);
        } else if (data.products_match) {
            rightCol.innerHTML = renderProducts(data.products_match, "Resultados directos");
        }

        dropdown.classList.add("active");

        // Agregar eventos de hover a las categorías
        const catLinks = dropdown.querySelectorAll(".category-suggestion");
        catLinks.forEach(link => {
            link.addEventListener("mouseover", () => {
                const catId = link.getAttribute("data-cat-id");
                const catName = link.textContent.trim();

                // Actualizar clase activa
                catLinks.forEach(l => l.classList.remove("active"));
                link.classList.add("active");

                // Actualizar productos
                const categoryProducts = (data.category_products && data.category_products[catId]) || [];
                rightCol.innerHTML = renderProducts(categoryProducts, `Categoría: ${catName}`);
            });
        });
    };

    searchInput.addEventListener("input", (e) => {
        clearTimeout(debounceTimer);
        const val = e.target.value;
        if (val.trim().length < 2) {
            dropdown.classList.remove("active");
            return;
        }
        debounceTimer = setTimeout(() => {
            performSearch(val);
        }, 300);
    });

    // Handle clicks outside the dropdown to close it
    document.addEventListener("click", (e) => {
        if (!searchInput.contains(e.target) && !dropdown.contains(e.target)) {
            dropdown.classList.remove("active");
        }
    });

    // Re-trigger search when user clicks back into input
    searchInput.addEventListener("focus", () => {
        if (searchInput.value.length >= 2) {
            dropdown.classList.add("active");
        }
    });
});
