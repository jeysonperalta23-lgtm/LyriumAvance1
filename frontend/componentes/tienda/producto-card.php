<?php
/**
 * PRODUCTO-CARD.PHP - Tarjeta de producto reutilizable
 * 
 * Variables esperadas:
 * @var array $producto - Datos del producto
 *   - id: int
 *   - nombre: string
 *   - precio: float
 *   - precio_anterior: float|null (opcional)
 *   - imagen: string
 *   - sticker: string (oferta|promo|nuevo|limitado|'')
 * 
 * @var string $minWidth (opcional) - Ancho mínimo para scroll horizontal (ej: '200px')
 * @var float $descuento (opcional) - Porcentaje de descuento a aplicar (ej: 0.8 para 20% off)
 * 
 * Uso:
 * <?php 
 *   $producto = ['id' => 1, 'nombre' => 'Producto', 'precio' => 45.90, ...];
 *   include 'componentes/tienda/producto-card.php'; 
 * ?>
 */

// Validar que existe el producto
if (!isset($producto) || !is_array($producto)) {
    return;
}

// Valores por defecto
$minWidth = $minWidth ?? null;
$descuento = $descuento ?? null;

// Calcular precios si hay descuento
$precioMostrar = $descuento ? $producto['precio'] * $descuento : $producto['precio'];
$precioAnterior = $descuento ? $producto['precio'] : ($producto['precio_anterior'] ?? null);

// Sticker especial para descuento
$stickerMostrar = $producto['sticker'] ?? '';
if ($descuento) {
    $porcentaje = round((1 - $descuento) * 100);
    $stickerMostrar = "-{$porcentaje}%";
    $stickerClase = 'oferta';
} else {
    $stickerClase = $stickerMostrar;
}

// Estilo inline para width
$styleAttr = $minWidth ? "min-width: {$minWidth};" : '';
?>
<div class="tienda-producto-card"<?php echo $styleAttr ? " style=\"{$styleAttr}\"" : ''; ?>>
  <div class="tienda-producto-img">
    <img src="<?php echo htmlspecialchars($producto['imagen']); ?>" alt="<?php echo htmlspecialchars($producto['nombre']); ?>">
    <?php if (!empty($stickerMostrar)): ?>
    <span class="tienda-producto-sticker <?php echo htmlspecialchars($stickerClase); ?>">
      <?php echo htmlspecialchars(ucfirst($stickerMostrar)); ?>
    </span>
    <?php endif; ?>
  </div>
  <div class="tienda-producto-body">
    <h4 class="tienda-producto-nombre"><?php echo htmlspecialchars($producto['nombre']); ?></h4>
    <div class="flex items-center">
      <span class="tienda-producto-precio">S/ <?php echo number_format($precioMostrar, 2); ?></span>
      <?php if (!empty($precioAnterior)): ?>
      <span class="tienda-producto-precio-old">S/ <?php echo number_format($precioAnterior, 2); ?></span>
      <?php endif; ?>
    </div>
  </div>
</div>
<?php
// Limpiar variables opcionales para evitar conflictos en siguiente iteración
unset($minWidth, $descuento);
?>
