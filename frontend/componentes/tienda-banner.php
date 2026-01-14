<?php
/**
 * COMPONENTE: Banner Carrusel de Tienda
 * 
 * Variables esperadas:
 * - $banners: array de URLs de imágenes
 * - $plan: 'basico' o 'premium'
 * - $redes: array de redes sociales (opcional, se pasa desde tienda.php)
 */

$maxBanners = ($plan === 'premium') ? 4 : 2;
$maxRedes = ($plan === 'premium') ? 10 : 6;
$bannersVisibles = array_slice($banners, 0, $maxBanners);

$redesDisponibles = [
  'instagram' => ['icon' => 'ph-instagram-logo-fill', 'label' => 'Instagram', 'color' => 'bg-gradient-to-br from-purple-500 via-pink-500 to-orange-400'],
  'facebook' => ['icon' => 'ph-facebook-logo-fill', 'label' => 'Facebook', 'color' => 'bg-blue-600'],
 
  'whatsapp' => ['icon' => 'ph-whatsapp-logo-fill', 'label' => 'WhatsApp', 'color' => 'bg-green-500'],
  'youtube' => ['icon' => 'ph-youtube-logo-fill', 'label' => 'YouTube', 'color' => 'bg-red-600'],
  'twitter' => ['icon' => 'ph-twitter-logo-fill', 'label' => 'Twitter', 'color' => 'bg-sky-500'],
  'linkedin' => ['icon' => 'ph-linkedin-logo-fill', 'label' => 'LinkedIn', 'color' => 'bg-blue-700'],
  'pinterest' => ['icon' => 'ph-pinterest-logo-fill', 'label' => 'Pinterest', 'color' => 'bg-red-600'],
  'telegram' => ['icon' => 'ph-telegram-logo-fill', 'label' => 'Telegram', 'color' => 'bg-sky-500'],
  'web' => ['icon' => 'ph-globe', 'label' => 'Sitio Web', 'color' => 'bg-sky-600'],
];
?>

<div class="tienda-banner-wrapper flex flex-col sm:flex-row gap-3 items-stretch">
  <!-- Slider del Banner -->
  <div class="tienda-banner flex-1 rounded-2xl overflow-hidden shadow-lg">
    <!-- Slides -->
    <div class="tienda-banner-slides">
      <?php foreach ($bannersVisibles as $index => $banner): ?>
      <div class="tienda-banner-slide" data-index="<?php echo $index; ?>">
        <img 
          src="<?php echo htmlspecialchars($banner['url']); ?>" 
          alt="<?php echo htmlspecialchars($banner['titulo'] ?? 'Banner ' . ($index + 1)); ?>"
          loading="<?php echo $index === 0 ? 'eager' : 'lazy'; ?>"
        >
      </div>
      <?php endforeach; ?>
    </div>
    
    <!-- Navegación -->
    <?php if (count($bannersVisibles) > 1): ?>
    <button class="tienda-banner-nav prev" aria-label="Anterior">
      <i class="ph-caret-left"></i>
    </button>
    <button class="tienda-banner-nav next" aria-label="Siguiente">
      <i class="ph-caret-right"></i>
    </button>
    
    <!-- Dots indicadores -->
    <div class="tienda-banner-dots">
      <?php foreach ($bannersVisibles as $index => $banner): ?>
      <button 
        class="tienda-banner-dot <?php echo $index === 0 ? 'active' : ''; ?>" 
        data-index="<?php echo $index; ?>"
        aria-label="Ir a slide <?php echo $index + 1; ?>"
      ></button>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>
  </div>
  
  <!-- Barra de Redes Sociales - Horizontal en móvil, vertical en desktop -->
  <?php if (!empty($redes)): ?>
  <div class="tienda-redes-sidebar flex flex-row sm:flex-col gap-2 p-2.5 bg-white/95 backdrop-blur-sm rounded-2xl shadow-lg justify-center sm:self-center order-first sm:order-last">
    <?php 
    $contador = 0;
    foreach ($redesDisponibles as $key => $config): 
      $redData = $redes[$key] ?? null;
      if (!$redData || empty($redData['url'])) continue;
      if ($contador >= $maxRedes) break;
      $contador++;
    ?>
    <a 
      href="<?php echo htmlspecialchars($redData['url']); ?>" 
      target="_blank" 
      rel="noopener noreferrer"
      class="w-9 h-9 sm:w-10 sm:h-10 rounded-full <?php echo $config['color']; ?> flex items-center justify-center text-white shadow-md hover:scale-110 hover:shadow-lg transition-all duration-200"
      title="<?php echo $config['label']; ?>"
    >
      <i class="<?php echo $config['icon']; ?> text-base sm:text-lg"></i>
    </a>
    <?php endforeach; ?>
  </div>
  <?php endif; ?>
</div>

<?php if ($plan === 'basico' && count($banners) > 2): ?>
<!-- Mensaje de upgrade para más banners -->

<?php endif; ?>
