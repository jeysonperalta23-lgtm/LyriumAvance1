<?php
/**
 * COMPONENTE: Sidebar Vertical de Productos - Trending Items Style
 * Diseño tipo lista vertical con estética consistente de la tienda
 */

// Carrusel: Mostrar 3 productos a la vez, rotar entre todos los disponibles
$itemsVisibles = 3; // Productos visibles simultáneamente
$productosSidebar = $productos ?? []; // Todos los productos disponibles

// Mapeo de stickers para consistencia global
$stickerTextos = [
    'oferta' => 'Oferta',
    'nuevo' => 'Nuevo',
    'promo' => 'Promo',
    'limitado' => 'Limitado'
];
?>

<div class="tienda-sidebar-trending">
    
    <!-- Header con título y navegación -->
    <div class="tienda-sidebar-trending-header">
        <h3 class="tienda-sidebar-trending-title">
            <i class="ph-fill ph-trend-up"></i>
            Artículos de tendencia
        </h3>
        <div class="tienda-sidebar-trending-nav">
            <button class="tienda-sidebar-nav-btn prev" type="button" aria-label="Anterior">
                <i class="ph ph-caret-left"></i>
            </button>
            <button class="tienda-sidebar-nav-btn next" type="button" aria-label="Siguiente">
                <i class="ph ph-caret-right"></i>
            </button>
        </div>
    </div>

    <!-- Lista de productos -->
    <div class="tienda-sidebar-trending-list" data-carousel="sidebar-trending">
        <?php foreach ($productosSidebar as $producto): ?>
        <div class="tienda-sidebar-trending-item">
            
            <!-- Imagen del producto -->
            <div class="tienda-sidebar-trending-img">
                <img src="<?php echo htmlspecialchars($producto['imagen']); ?>" 
                     alt="<?php echo htmlspecialchars($producto['nombre']); ?>">
                
                <!-- Sticker opcional -->
                <?php if (!empty($producto['sticker'])): ?>
                <span class="tienda-producto-sticker <?php echo htmlspecialchars($producto['sticker']); ?>">
                    <?php echo $stickerTextos[$producto['sticker']] ?? $producto['sticker']; ?>
                </span>
                <?php endif; ?>
            </div>

            <!-- Información del producto -->
            <div class="tienda-sidebar-trending-info">
                
                <!-- Nombre del producto -->
                <h4 class="tienda-sidebar-trending-name">
                    <?php echo htmlspecialchars($producto['nombre']); ?>
                </h4>
                
                <!-- Categoría o descripción -->
                <p class="tienda-sidebar-trending-category">
                    <?php echo htmlspecialchars($producto['categoria'] ?? 'Productos'); ?>
                </p>
                
                <!-- Precios -->
                <div class="tienda-sidebar-trending-prices">
                    <span class="tienda-sidebar-trending-price">
                        S/<?php echo number_format($producto['precio'], 2); ?>
                    </span>
                    <?php if (!empty($producto['precio_anterior'])): ?>
                    <span class="tienda-sidebar-trending-price-old">
                        S/<?php echo number_format($producto['precio_anterior'], 2); ?>
                    </span>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Botón de agregar al carrito -->
            <button class="tienda-sidebar-trending-cart-btn" type="button" aria-label="Agregar al carrito">
                <i class="ph ph-shopping-cart"></i>
            </button>

        </div>
        <?php endforeach; ?>
    </div>

</div>

<script>
/**
 * CARRUSEL AUTOMÁTICO - Sidebar Trending Products
 * Rotación automática con efecto de deslizamiento
 */
(function() {
    'use strict';
    
    const carousel = document.querySelector('[data-carousel="sidebar-trending"]');
    if (!carousel) return;
    
    const items = Array.from(carousel.querySelectorAll('.tienda-sidebar-trending-item'));
    const prevBtn = carousel.closest('.tienda-sidebar-trending').querySelector('.tienda-sidebar-nav-btn.prev');
    const nextBtn = carousel.closest('.tienda-sidebar-trending').querySelector('.tienda-sidebar-nav-btn.next');
    
    if (items.length <= 1) return; // No hay suficientes items para rotar
    
    // Configuración
    const config = {
        autoPlayInterval: 5000, // 5 segundos
        itemsToShow: <?php echo $itemsVisibles; ?>, // Productos visibles simultáneamente
        animationDuration: 600 // ms
    };
    
    let currentIndex = 0;
    let autoPlayTimer = null;
    let isAnimating = false;
    
    // Inicializar visibilidad de items
    function initCarousel() {
        items.forEach((item, index) => {
            if (index < config.itemsToShow) {
                item.style.display = 'flex';
                item.style.opacity = '1';
                item.style.transform = 'translateX(0)';
            } else {
                item.style.display = 'none';
                item.style.opacity = '0';
            }
        });
    }
    
    // Función de animación de deslizamiento
    function slideItems(direction) {
        if (isAnimating) return;
        isAnimating = true;
        
        const oldIndex = currentIndex;
        
        // Calcular nuevo índice
        if (direction === 'next') {
            currentIndex = (currentIndex + 1) % items.length;
        } else {
            currentIndex = (currentIndex - 1 + items.length) % items.length;
        }
        
        // Obtener items visibles actuales y nuevos
        const currentVisibleIndices = [];
        const newVisibleIndices = [];
        
        for (let i = 0; i < config.itemsToShow; i++) {
            currentVisibleIndices.push((oldIndex + i) % items.length);
            newVisibleIndices.push((currentIndex + i) % items.length);
        }
        
        // Items que salen
        const exitingIndices = currentVisibleIndices.filter(i => !newVisibleIndices.includes(i));
        // Items que entran
        const enteringIndices = newVisibleIndices.filter(i => !currentVisibleIndices.includes(i));
        
        // Animación de salida (deslizamiento más pronunciado)
        exitingIndices.forEach(index => {
            const item = items[index];
            item.style.transition = `all ${config.animationDuration}ms cubic-bezier(0.4, 0, 0.2, 1)`;
            item.style.opacity = '0';
            item.style.transform = direction === 'next' ? 'translateX(-100%)' : 'translateX(100%)';
        });
        
        // Preparar items entrantes (fuera de vista con mayor desplazamiento)
        enteringIndices.forEach(index => {
            const item = items[index];
            item.style.display = 'flex';
            item.style.opacity = '0';
            item.style.transform = direction === 'next' ? 'translateX(100%)' : 'translateX(-100%)';
            item.style.transition = 'none';
        });
        
        // Forzar reflow
        carousel.offsetHeight;
        
        // Animación de entrada
        setTimeout(() => {
            enteringIndices.forEach(index => {
                const item = items[index];
                item.style.transition = `all ${config.animationDuration}ms cubic-bezier(0.4, 0, 0.2, 1)`;
                item.style.opacity = '1';
                item.style.transform = 'translateX(0)';
            });
        }, 50);
        
        // Limpiar después de la animación
        setTimeout(() => {
            exitingIndices.forEach(index => {
                items[index].style.display = 'none';
            });
            
            items.forEach(item => {
                item.style.transition = '';
            });
            
            isAnimating = false;
        }, config.animationDuration + 100);
    }
    
    // Navegación
    function goNext() {
        slideItems('next');
        resetAutoPlay();
    }
    
    function goPrev() {
        slideItems('prev');
        resetAutoPlay();
    }
    
    // Auto-play
    function startAutoPlay() {
        stopAutoPlay();
        autoPlayTimer = setInterval(() => {
            slideItems('next');
        }, config.autoPlayInterval);
    }
    
    function stopAutoPlay() {
        if (autoPlayTimer) {
            clearInterval(autoPlayTimer);
            autoPlayTimer = null;
        }
    }
    
    function resetAutoPlay() {
        stopAutoPlay();
        startAutoPlay();
    }
    
    // Event listeners
    if (nextBtn) {
        nextBtn.addEventListener('click', goNext);
    }
    
    if (prevBtn) {
        prevBtn.addEventListener('click', goPrev);
    }
    
    // Pausar al hacer hover
    carousel.addEventListener('mouseenter', stopAutoPlay);
    carousel.addEventListener('mouseleave', startAutoPlay);
    
    // Inicializar
    initCarousel();
    startAutoPlay();
    
    // Limpiar al salir
    window.addEventListener('beforeunload', stopAutoPlay);
})();
</script>

<style>
/* ========================================
   SIDEBAR TRENDING ITEMS
   Estilo lista vertical con estética de la tienda
   ======================================== */

.tienda-sidebar-trending {
    background: #fff;
    border: 1px solid var(--tienda-border);
    border-radius: 14px;
    padding: 1.25rem;
    box-shadow: 0 8px 30px var(--tienda-shadow);
}

/* === HEADER === */
.tienda-sidebar-trending-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 1.25rem;
    padding-bottom: 0.75rem;
    border-bottom: 2px solid var(--tienda-border);
}

.tienda-sidebar-trending-title {
    font-size: 1rem;
    font-weight: 700;
    color: #1e293b;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin: 0;
}

.tienda-sidebar-trending-title i {
    color: var(--tienda-primary);
    font-size: 1.1rem;
}

/* Botones de navegación */
.tienda-sidebar-trending-nav {
    display: flex;
    gap: 0.375rem;
}

.tienda-sidebar-nav-btn {
    width: 28px;
    height: 28px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f8fafc;
    border: 1px solid var(--tienda-border);
    border-radius: 6px;
    color: #64748b;
    cursor: pointer;
    transition: all 0.2s ease;
}

.tienda-sidebar-nav-btn:hover {
    background: var(--tienda-primary);
    border-color: var(--tienda-primary);
    color: #fff;
    transform: scale(1.05);
}

.tienda-sidebar-nav-btn i {
    font-size: 0.875rem;
}

/* === LISTA DE PRODUCTOS === */
.tienda-sidebar-trending-list {
    display: flex;
    flex-direction: column;
    gap: 1rem;
    overflow: hidden; /* Ocultar productos que se deslizan fuera */
    position: relative;
}

/* === ITEM INDIVIDUAL === */
.tienda-sidebar-trending-item {
    display: flex;
    align-items: center;
    gap: 0.875rem;
    padding: 0.75rem;
    background: #fff;
    border: 1px solid var(--tienda-border);
    border-radius: 12px;
    transition: all 0.3s ease;
    position: relative;
}

.tienda-sidebar-trending-item:hover {
    border-color: var(--tienda-primary);
    box-shadow: 0 4px 16px rgba(14, 165, 233, 0.12);
    transform: translateX(4px);
}

/* === IMAGEN === */
.tienda-sidebar-trending-img {
    position: relative;
    width: 70px;
    height: 70px;
    flex-shrink: 0;
    background: #f8fafc;
    border-radius: 10px;
    overflow: hidden;
    border: 1px solid #e2e8f0;
}

.tienda-sidebar-trending-img img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.4s ease;
}

.tienda-sidebar-trending-item:hover .tienda-sidebar-trending-img img {
    transform: scale(1.1);
}

/* === INFORMACIÓN === */
.tienda-sidebar-trending-info {
    flex: 1;
    min-width: 0;
}

.tienda-sidebar-trending-name {
    font-size: 0.875rem;
    font-weight: 600;
    color: #1e293b;
    margin: 0 0 0.25rem 0;
    line-height: 1.3;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    transition: color 0.2s ease;
}

.tienda-sidebar-trending-item:hover .tienda-sidebar-trending-name {
    color: var(--tienda-primary);
}

.tienda-sidebar-trending-category {
    font-size: 0.75rem;
    color: #64748b;
    margin: 0 0 0.375rem 0;
}

/* === PRECIOS === */
.tienda-sidebar-trending-prices {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.tienda-sidebar-trending-price {
    font-size: 1rem;
    font-weight: 800;
    color: var(--tienda-primary);
}

.tienda-sidebar-trending-price-old {
    font-size: 0.75rem;
    color: #94a3b8;
    text-decoration: line-through;
}

/* === BOTÓN CARRITO === */
.tienda-sidebar-trending-cart-btn {
    width: 36px;
    height: 36px;
    flex-shrink: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f8fafc;
    border: 1px solid var(--tienda-border);
    border-radius: 8px;
    color: #64748b;
    cursor: pointer;
    transition: all 0.3s ease;
    opacity: 0;
    transform: translateX(-8px);
}

.tienda-sidebar-trending-item:hover .tienda-sidebar-trending-cart-btn {
    opacity: 1;
    transform: translateX(0);
}

.tienda-sidebar-trending-cart-btn:hover {
    background: var(--tienda-primary);
    border-color: var(--tienda-primary);
    color: #fff;
    transform: scale(1.1);
    box-shadow: 0 4px 12px rgba(14, 165, 233, 0.3);
}

.tienda-sidebar-trending-cart-btn:active {
    transform: scale(0.95);
}

.tienda-sidebar-trending-cart-btn i {
    font-size: 1rem;
}

/* === RESPONSIVE === */
@media (max-width: 1024px) {
    .tienda-sidebar-trending {
        margin-top: 1.5rem;
    }
}

@media (max-width: 640px) {
    .tienda-sidebar-trending {
        padding: 1rem;
    }
    
    .tienda-sidebar-trending-header {
        margin-bottom: 1rem;
    }
    
    .tienda-sidebar-trending-title {
        font-size: 0.875rem;
    }
    
    .tienda-sidebar-trending-img {
        width: 60px;
        height: 60px;
    }
    
    .tienda-sidebar-trending-name {
        font-size: 0.8125rem;
    }
    
    .tienda-sidebar-trending-price {
        font-size: 0.9375rem;
    }
    
    .tienda-sidebar-trending-cart-btn {
        opacity: 1;
        transform: translateX(0);
    }
}
</style>
