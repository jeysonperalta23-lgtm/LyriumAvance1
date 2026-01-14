<?php
/**
 * AD-BANNER-HORIZONTAL.PHP - Banner publicitario horizontal
 * 
 * Variables esperadas:
 * @var string $titulo (opcional) - Título del banner
 * @var string $subtitulo (opcional) - Subtítulo/descripción
 * @var string $btn_texto (opcional) - Texto del botón
 * @var string $btn_url (opcional) - URL del botón
 * @var string $icono (opcional) - Clase del icono principal
 * @var string $btn_icono (opcional) - Clase del icono del botón
 * 
 * Uso:
 * <?php include 'componentes/tienda/ad-banner-horizontal.php'; ?>
 */

$titulo = $titulo ?? '¿Tienes productos naturales? ¡Véndelos aquí!';
$subtitulo = $subtitulo ?? 'Únete a +500 emprendedores que ya venden en Lyrium';
$btn_texto = $btn_texto ?? 'Crear mi tienda';
$btn_url = $btn_url ?? 'registro.php?tipo=vendedor';
$icono = $icono ?? 'ph-storefront-fill';
$btn_icono = $btn_icono ?? 'ph-rocket-launch';
?>
<div class="tienda-ad-banner">
  <div class="tienda-ad-content">
    <div class="tienda-ad-icon">
      <i class="<?php echo htmlspecialchars($icono); ?>"></i>
    </div>
    <div class="tienda-ad-text">
      <h4><?php echo htmlspecialchars($titulo); ?></h4>
      <p><?php echo htmlspecialchars($subtitulo); ?></p>
    </div>
  </div>
  <a href="<?php echo htmlspecialchars($btn_url); ?>" class="tienda-ad-btn">
    <i class="<?php echo htmlspecialchars($btn_icono); ?>"></i>
    <span><?php echo htmlspecialchars($btn_texto); ?></span>
  </a>
</div>
<?php
// Limpiar variables
unset($titulo, $subtitulo, $btn_texto, $btn_url, $icono, $btn_icono);
?>
