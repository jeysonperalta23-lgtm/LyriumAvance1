<?php
/**
 * PRODUCTOS-FILTROS.PHP - Barra de filtros para productos
 * 
 * Variables esperadas:
 * @var array $categorias - Categorías disponibles para filtrar
 * @var string $categoria_activa (opcional) - Slug de la categoría actualmente seleccionada
 * @var array $opciones_orden (opcional) - Opciones de ordenamiento
 * 
 * Uso:
 * <?php 
 *   $categorias = [['nombre' => 'Vitaminas', 'slug' => 'vitaminas'], ...];
 *   include 'componentes/tienda/productos-filtros.php'; 
 * ?>
 */

// Valores por defecto
$categorias = $categorias ?? [];
$categoria_activa = $categoria_activa ?? '';
$opciones_orden = $opciones_orden ?? [
  ['value' => 'relevancia', 'label' => 'Más relevantes'],
  ['value' => 'precio-asc', 'label' => 'Precio: menor a mayor'],
  ['value' => 'precio-desc', 'label' => 'Precio: mayor a menor'],
  ['value' => 'nombre-asc', 'label' => 'Nombre: A-Z'],
  ['value' => 'nombre-desc', 'label' => 'Nombre: Z-A'],
  ['value' => 'recientes', 'label' => 'Más recientes'],
];
?>
<div class="tienda-filtros">
  <div class="tienda-filtros-container">
    <!-- Filtro por categorías -->
    <div class="tienda-filtros-categorias">
      <button class="tienda-filtro-btn <?php echo empty($categoria_activa) ? 'active' : ''; ?>" data-categoria="">
        <i class="ph-squares-four"></i>
        <span>Todos</span>
      </button>
      <?php foreach ($categorias as $cat): ?>
      <button class="tienda-filtro-btn <?php echo $categoria_activa === $cat['slug'] ? 'active' : ''; ?>" data-categoria="<?php echo htmlspecialchars($cat['slug']); ?>">
        <?php if (!empty($cat['icono'])): ?>
        <i class="<?php echo htmlspecialchars($cat['icono']); ?>"></i>
        <?php endif; ?>
        <span><?php echo htmlspecialchars($cat['nombre']); ?></span>
      </button>
      <?php endforeach; ?>
    </div>
    
    <!-- Ordenar por -->
    <div class="tienda-filtros-orden">
      <label for="tienda-orden" class="tienda-orden-label">
        <i class="ph-sort-ascending"></i>
        <span>Ordenar:</span>
      </label>
      <select id="tienda-orden" class="tienda-orden-select">
        <?php foreach ($opciones_orden as $opcion): ?>
        <option value="<?php echo htmlspecialchars($opcion['value']); ?>">
          <?php echo htmlspecialchars($opcion['label']); ?>
        </option>
        <?php endforeach; ?>
      </select>
    </div>
  </div>
  
  <!-- Búsqueda rápida en tienda -->
  <div class="tienda-filtros-busqueda">
    <div class="tienda-busqueda-input">
      <i class="ph-magnifying-glass"></i>
      <input type="text" placeholder="Buscar en esta tienda..." id="tienda-buscar">
    </div>
  </div>
</div>
<?php
// Limpiar variables
unset($categoria_activa, $opciones_orden);
?>
