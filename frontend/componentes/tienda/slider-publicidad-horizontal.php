<?php
/**
 * COMPONENTE: Slider Horizontal de Publicidad
 * Slider automático para banners publicitarios
 * Aparece debajo del banner principal (hero)
 * 
 * Variables esperadas:
 * - $bannersPublicidad: array de URLs de imágenes publicitarias
 * - $plan: 'basico' o 'premium'
 */

$maxBannersPublicidad = ($plan === 'premium') ? 6 : 3;
$bannersPublicidad = [
    ['url' => 'img/BannerVertical/1-BG.webp', 'titulo' => 'Promoción Especial 1'],
    ['url' => 'img/BannerVertical/2-BG.webp', 'titulo' => 'Promoción Especial 2'],
    ['url' => 'img/BannerVertical/3-BG.webp', 'titulo' => 'Promoción Especial 3'],
    ['url' => 'img/BannerVertical/1-BG.webp', 'titulo' => 'Ofertas Destacadas'],
    ['url' => 'img/BannerVertical/2-BG.webp', 'titulo' => 'Descuentos Increíbles'],
    ['url' => 'img/BannerVertical/3-BG.webp', 'titulo' => 'Nuevos Productos'],
];
$bannersPublicidadVisibles = array_slice($bannersPublicidad, 0, $maxBannersPublicidad);
?>

<div class="slider-publicidad-horizontal">
  <div class="slider-publicidad-container">
    <div class="slider-publicidad-track">
      <?php foreach ($bannersPublicidadVisibles as $index => $banner): ?>
      <div class="slider-publicidad-slide <?php echo $index === 0 ? 'active' : ''; ?>">
        <img 
          src="<?php echo htmlspecialchars($banner['url']); ?>" 
          alt="<?php echo htmlspecialchars($banner['titulo']); ?>"
          loading="<?php echo $index === 0 ? 'eager' : 'lazy'; ?>"
        >
      </div>
      <?php endforeach; ?>
    </div>
    
    <?php if (count($bannersPublicidadVisibles) > 1): ?>
    <!-- Indicadores -->
    <div class="slider-publicidad-dots">
      <?php foreach ($bannersPublicidadVisibles as $index => $banner): ?>
      <button 
        class="slider-publicidad-dot <?php echo $index === 0 ? 'active' : ''; ?>" 
        data-index="<?php echo $index; ?>"
        aria-label="Ir a slide <?php echo $index + 1; ?>"
      ></button>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>
  </div>
</div>
