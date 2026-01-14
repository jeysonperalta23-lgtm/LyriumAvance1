<?php
/**
 * AD-BANNER-GRANDE.PHP - Banner publicitario grande (final de página)
 * 
 * Variables esperadas:
 * @var string $titulo (opcional) - Título del banner
 * @var string $subtitulo (opcional) - Subtítulo/descripción
 * @var string $btn_texto (opcional) - Texto del botón
 * @var string $btn_url (opcional) - URL del botón
 * @var string $btn_icono (opcional) - Clase del icono del botón
 * @var string $logo_url (opcional) - URL del logo
 * @var string $clase_extra (opcional) - Clases CSS adicionales
 * 
 * Uso:
 * <?php include 'componentes/tienda/ad-banner-grande.php'; ?>
 */

$titulo = $titulo ?? 'Vende tus productos en Lyrium';
$subtitulo = $subtitulo ?? 'Crea tu tienda gratis y llega a miles de clientes';
$btn_texto = $btn_texto ?? 'Crear mi tienda';
$btn_url = $btn_url ?? 'registro.php?tipo=vendedor';
$btn_icono = $btn_icono ?? 'ph-storefront';
$logo_url = $logo_url ?? 'img/logo_lyrium_blanco_01-scaled.webp';
$clase_extra = $clase_extra ?? 'mt-6';
?>
<div class="tienda-ad-banner-large <?php echo htmlspecialchars($clase_extra); ?>">
  <div class="tienda-ad-content-large">
    <div class="tienda-ad-logo-large">
      <img src="<?php echo htmlspecialchars($logo_url); ?>" alt="Lyrium">
    </div>
    <h3><?php echo htmlspecialchars($titulo); ?></h3>
    <p><?php echo htmlspecialchars($subtitulo); ?></p>
    <a href="<?php echo htmlspecialchars($btn_url); ?>" class="tienda-ad-btn-large">
      <i class="<?php echo htmlspecialchars($btn_icono); ?>"></i>
      <span><?php echo htmlspecialchars($btn_texto); ?></span>
    </a>
  </div>
</div>
<?php
// Limpiar variables
unset($titulo, $subtitulo, $btn_texto, $btn_url, $btn_icono, $logo_url, $clase_extra);
?>
