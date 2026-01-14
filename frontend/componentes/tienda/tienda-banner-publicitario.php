<?php
/**
 * SLIDER DE DESCUENTOS/OFERTAS
 * Slider horizontal simple como el de publicidad
 * Mismo tamaño: 200px de altura
 */

$maxDescuentos = ($plan === 'premium') ? 5 : 3;
$bannersDescuentos = [
    ['url' => 'img/BannerVertical/1-BG.webp', 'titulo' => 'Venta de Temporada'],
    ['url' => 'img/BannerVertical/2-BG.webp', 'titulo' => 'Mega Sale'],
    ['url' => 'img/BannerVertical/3-BG.webp', 'titulo' => 'Envío Gratis'],
    ['url' => 'img/BannerVertical/1-BG.webp', 'titulo' => 'Black Friday'],
    ['url' => 'img/BannerVertical/2-BG.webp', 'titulo' => 'Cyber Monday'],
];
$descuentosVisibles = array_slice($bannersDescuentos, 0, $maxDescuentos);
?>

<div class="slider-descuentos-horizontal">
  <div class="slider-descuentos-container">
    <div class="slider-descuentos-track">
      <?php foreach ($descuentosVisibles as $index => $banner): ?>
      <div class="slider-descuentos-slide <?php echo $index === 0 ? 'active' : ''; ?>">
        <img 
          src="<?php echo htmlspecialchars($banner['url']); ?>" 
          alt="<?php echo htmlspecialchars($banner['titulo']); ?>"
          loading="<?php echo $index === 0 ? 'eager' : 'lazy'; ?>"
        >
      </div>
      <?php endforeach; ?>
    </div>
    
    <?php if (count($descuentosVisibles) > 1): ?>
    <!-- Indicadores -->
    <div class="slider-descuentos-dots">
      <?php foreach ($descuentosVisibles as $index => $banner): ?>
      <button 
        class="slider-descuentos-dot <?php echo $index === 0 ? 'active' : ''; ?>" 
        data-index="<?php echo $index; ?>"
        aria-label="Ir a slide <?php echo $index + 1; ?>"
      ></button>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>
  </div>
</div>

