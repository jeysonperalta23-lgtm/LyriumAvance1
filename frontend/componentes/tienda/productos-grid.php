<?php
/**
 * PRODUCTOS-GRID.PHP - Grid de productos
 * 
 * Variables esperadas:
 * @var array $productos - Array de productos a mostrar
 * @var string $titulo (opcional) - Título de la sección
 * @var string $icono (opcional) - Clase del icono Phosphor
 * @var string $grid_cols (opcional) - Variante del grid ('3' o '4' columnas)
 * @var bool $mostrar_titulo (opcional) - Mostrar título de sección
 * 
 * Uso:
 * <?php 
 *   $titulo = 'Todos los Productos';
 *   $icono = 'ph-package';
 *   $grid_cols = '4'; // Para 4 columnas
 *   include 'componentes/tienda/productos-grid.php'; 
 * ?>
 */

// Validar datos requeridos
if (!isset($productos) || !is_array($productos) || empty($productos)) {
    return;
}

$titulo = $titulo ?? 'Todos los Productos';
$icono = $icono ?? 'ph-package';
$grid_cols = $grid_cols ?? '3';
$mostrar_titulo = $mostrar_titulo ?? true;
$clase_extra = $clase_extra ?? '';

// Clase para grid de 4 columnas
$gridClass = $grid_cols === '5' ? 'tienda-productos-grid tienda-productos-grid-5' : 'tienda-productos-grid';
?>
<section class="<?php echo $clase_extra ? htmlspecialchars($clase_extra) : 'mt-6'; ?>">
  <?php if ($mostrar_titulo): ?>
  <h3 class="tienda-scroll-title mb-4">
    <i class="<?php echo htmlspecialchars($icono); ?>"></i>
    <?php echo htmlspecialchars($titulo); ?>
  </h3>
  <?php endif; ?>
  <div class="<?php echo $gridClass; ?>">
    <?php foreach ($productos as $producto): 
      include __DIR__ . '/producto-card.php';
    endforeach; ?>
  </div>
</section>
<?php
// Limpiar variables
unset($titulo, $icono, $grid_cols, $mostrar_titulo, $clase_extra);
?>
