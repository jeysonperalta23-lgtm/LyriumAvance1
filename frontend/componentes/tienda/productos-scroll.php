<?php
/**
 * PRODUCTOS-SCROLL.PHP - Sección de productos con scroll horizontal
 * 
 * Variables esperadas:
 * @var array $productos - Array de productos a mostrar
 * @var string $titulo - Título de la sección
 * @var string $icono - Clase del icono Phosphor (ej: 'ph-star-fill')
 * @var float $descuento (opcional) - Porcentaje de descuento a aplicar
 * @var string $clase_extra (opcional) - Clases CSS adicionales para la sección
 * 
 * Uso:
 * <?php 
 *   $titulo = 'Productos Destacados';
 *   $icono = 'ph-star-fill';
 *   include 'componentes/tienda/productos-scroll.php'; 
 * ?>
 */

// Validar datos requeridos
if (!isset($productos) || !is_array($productos) || empty($productos)) {
    return;
}

$titulo = $titulo ?? 'Productos';
$icono = $icono ?? 'ph-package';
$descuento = $descuento ?? null;
$clase_extra = $clase_extra ?? '';
?>
<section class="tienda-scroll-section <?php echo htmlspecialchars($clase_extra); ?>">
  <h3 class="tienda-scroll-title">
    <i class="<?php echo htmlspecialchars($icono); ?>"></i>
    <?php echo htmlspecialchars($titulo); ?>
  </h3>
  <div class="tienda-scroll-wrapper">
    <button class="tienda-scroll-nav prev" aria-label="Anterior">
      <i class="ph-caret-left"></i>
    </button>
    <div class="tienda-scroll-container">
      <?php 
      $minWidth = '200px';
      foreach ($productos as $producto): 
        include __DIR__ . '/producto-card.php';
      endforeach; 
      ?>
    </div>
    <button class="tienda-scroll-nav next" aria-label="Siguiente">
      <i class="ph-caret-right"></i>
    </button>
  </div>
</section>
<?php
// Limpiar variables
unset($titulo, $icono, $descuento, $clase_extra, $minWidth);
?>
