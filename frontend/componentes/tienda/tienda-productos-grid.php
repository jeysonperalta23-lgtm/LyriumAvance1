<?php
/**
 * COMPONENTE: Grid de Productos de Tienda - Estilo Temu/AliExpress
 * 
 * Muestra un grid responsivo de productos con diseño limpio y minimalista
 * El número de productos y columnas varía según el plan
 * 
 * GRID: 5 columnas × 5 filas = 25 productos (Premium)
 *       5 columnas × 3 filas = 15 productos (Básico)
 * 
 * Variables esperadas:
 * - $productos: array de productos
 * - $tienda: datos de la tienda
 * - $plan: 'basico' o 'premium'
 */

// Configuración según plan
// 🔗 Backend: Estos límites deben coincidir con los de GUIA-BACKEND-TIENDA.md
$maxProductosGrid = ($plan === 'premium') ? 25 : 15; // Premium: 5 filas × 5 cols, Básico: 3 filas × 5 cols
$productosGrid = array_slice($productos ?? [], 0, $maxProductosGrid);

?>

<!-- ========================================= -->
<!-- GRID DE PRODUCTOS - Estilo Temu -->
<!-- ========================================= -->
<div class="tienda-productos-grid-section">
  <!-- Header del grid - Minimalista -->
  <div class="tienda-grid-header">
    <h3 class="tienda-grid-titulo">
      Selecciones destacadas para ti
    </h3>
    
    <div class="tienda-grid-filtros">
      <!-- Ordenar -->
      <div class="tienda-filtro-dropdown">
        <button class="tienda-filtro-btn" id="filtroOrden">
          <span>Ordenar</span>
          <i class="ph ph-caret-down"></i>
        </button>
      </div>
      
      <!-- Vista Grid/Lista -->
      <div class="tienda-vista-toggle">
        <button class="tienda-vista-btn active" data-vista="grid" title="Vista cuadrícula">
          <i class="ph ph-squares-four"></i>
        </button>
        <button class="tienda-vista-btn" data-vista="lista" title="Vista lista">
          <i class="ph ph-list"></i>
        </button>
      </div>
    </div>
  </div>
  
  <!-- Grid de productos -->
  <div class="tienda-productos-grid" id="productosGrid">
    <?php if (!empty($productosGrid)): ?>
      <?php foreach ($productosGrid as $producto): ?>
      <div class="producto-grid-card" data-producto-id="<?php echo $producto['id']; ?>">
        <!-- Imagen del producto -->
        <div class="producto-grid-imagen">
          <img 
            src="<?php echo htmlspecialchars($producto['imagen']); ?>" 
            alt="<?php echo htmlspecialchars($producto['nombre']); ?>"
            loading="lazy"
          >
          
          <!-- Sticker de estado -->
          <?php if (!empty($producto['sticker'])): ?>
          <span class="producto-grid-sticker <?php echo htmlspecialchars($producto['sticker']); ?>">
            <?php 
              $stickerTextos = [
                'oferta' => 'Oferta',
                'nuevo' => 'Nuevo',
                'promo' => 'Promo',
                'limitado' => 'Limitado'
              ];
              echo $stickerTextos[$producto['sticker']] ?? ucfirst($producto['sticker']);
            ?>
          </span>
          <?php endif; ?>
          
          <!-- Descuento badge -->
          <?php if (!empty($producto['precio_anterior']) && $producto['precio_anterior'] > $producto['precio']): ?>
          <?php 
            $descuento = round((($producto['precio_anterior'] - $producto['precio']) / $producto['precio_anterior']) * 100);
          ?>
          <span class="producto-grid-descuento">-<?php echo $descuento; ?>%</span>
          <?php endif; ?>
          
          <!-- Acciones hover -->
          <div class="producto-grid-acciones">
            <button class="producto-grid-accion" title="Añadir a favoritos">
              <i class="ph ph-heart"></i>
            </button>
          </div>
          
          <!-- Icono carrito flotante -->
          <button class="producto-grid-cart-icon" title="Añadir al carrito">
            <i class="ph ph-shopping-cart-simple"></i>
          </button>
        </div>
        
        <!-- Info del producto -->
        <div class="producto-grid-info">
          <!-- Nombre -->
          <h4 class="producto-grid-nombre">
            <a href="producto.php?id=<?php echo $producto['id']; ?>">
              <?php echo htmlspecialchars($producto['nombre']); ?>
            </a>
          </h4>
          
          <!-- Precios -->
          <div class="producto-grid-precios">
            <span class="producto-grid-precio-actual">
              <?php echo number_format($producto['precio'], 2); ?>
            </span>
            <?php if (!empty($producto['precio_anterior'])): ?>
            <span class="producto-grid-precio-anterior">
              S/ <?php echo number_format($producto['precio_anterior'], 2); ?>
            </span>
            <?php endif; ?>
          </div>
          
          <!-- Rating -->
          <div class="producto-grid-rating">
            <div class="producto-grid-stars">
              <?php 
                $rating = $producto['rating'] ?? 5;
                for ($i = 1; $i <= 5; $i++): 
              ?>
              <i class="ph-fill ph-star"></i>
              <?php endfor; ?>
            </div>
            <span class="producto-grid-reviews"><?php echo $producto['ventas'] ?? rand(100, 5000); ?></span>
          </div>
          
          <!-- Botones de hover - Desktop -->
          <div class="producto-grid-hover-btns hidden md:flex">
            <button class="producto-hover-btn" onclick="vistaRapidaProducto(<?php echo $producto['id']; ?>)">
              <i class="ph ph-eye"></i>
              Previsualizar
            </button>
            <button class="producto-hover-btn producto-hover-btn-outline">
              <i class="ph ph-squares-four"></i>
              Artículos similares
            </button>
          </div>
          
          <!-- Barra de iconos - Móvil (siempre visible) -->
          <div class="producto-grid-mobile-actions flex md:hidden">
            <button class="producto-mobile-action" onclick="vistaRapidaProducto(<?php echo $producto['id']; ?>)" title="Vista rápida">
              <i class="ph ph-eye"></i>
            </button>
            <button class="producto-mobile-action" title="Añadir al carrito">
              <i class="ph ph-shopping-cart-simple"></i>
            </button>
            <button class="producto-mobile-action" title="Añadir a favoritos">
              <i class="ph ph-heart"></i>
            </button>
            <button class="producto-mobile-action" title="Comparar">
              <i class="ph ph-shuffle"></i>
            </button>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    <?php else: ?>
    <div class="tienda-grid-empty">
      <i class="ph ph-package"></i>
      <p>No hay productos disponibles</p>
    </div>
    <?php endif; ?>
  </div>
  
  <!-- Ver más -->
  <?php if (count($productos ?? []) > $maxProductosGrid): ?>
  <div class="tienda-grid-footer">
    <button class="tienda-grid-ver-mas" id="btnVerMasProductos">
      Ver más productos
      <span class="tienda-grid-contador">
        (<?php echo count($productos) - $maxProductosGrid; ?> más)
      </span>
    </button>
  </div>
  <?php endif; ?>
</div>
