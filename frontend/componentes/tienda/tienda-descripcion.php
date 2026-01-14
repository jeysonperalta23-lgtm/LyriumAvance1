<?php
/**
 * TIENDA-DESCRIPCION.PHP - Caja de descripción e información de la tienda
 * 
 * Variables esperadas:
 * @var array $tienda - Datos de la tienda
 *   - nombre: string
 *   - logo: string (URL)
 *   - categoria: string
 *   - descripcion: string
 *   - abierto: bool
 * @var string $plan - Plan de la tienda ('basico' o 'premium')
 * 
 * Uso:
 * <?php include 'componentes/tienda/tienda-descripcion.php'; ?>
 */

// Validar datos requeridos
if (!isset($tienda) || !is_array($tienda)) {
    return;
}

$plan = $plan ?? 'basico';
?>
<div class="tienda-descripcion">
  <div class="tienda-header-info">
    <div class="tienda-logo-main hidden lg:flex">
      <img src="<?php echo htmlspecialchars($tienda['logo']); ?>" alt="<?php echo htmlspecialchars($tienda['nombre']); ?>">
    </div>
    <div class="tienda-titulo-wrap">
      <h1 class="tienda-nombre"><?php echo htmlspecialchars($tienda['nombre']); ?></h1>
      <span class="tienda-categoria">
        <i class="ph-tag"></i>
        <?php echo htmlspecialchars($tienda['categoria']); ?>
      </span>
    </div>
    <span class="tienda-estado <?php echo $tienda['abierto'] ? 'abierto' : 'cerrado'; ?>">
      <span class="tienda-estado-dot"></span>
      <span class="tienda-estado-texto"><?php echo $tienda['abierto'] ? 'Abierto' : 'Cerrado'; ?></span>
    </span>
  </div>
  
  <p class="tienda-descripcion-texto">
    <?php echo htmlspecialchars($tienda['descripcion']); ?>
  </p>
  
  <div class="tienda-badges">
    <?php if ($plan === 'premium'): ?>
    <span class="tienda-badge premium">
      <i class="ph-crown-fill"></i>
      Premium
    </span>
    <span class="tienda-badge premium">
      <i class="ph-medal-fill"></i>
      Mejor Vendedor
    </span>
    <span class="tienda-badge premium">
      <i class="ph-seal-check-fill"></i>
      Verificado
    </span>
    <?php endif; ?>
    <span class="tienda-badge">
      <i class="ph-calendar-check"></i>
      10+ años
    </span>
    <span class="tienda-badge">
      <i class="ph-map-pin"></i>
      Piura
    </span>
  </div>
</div>
