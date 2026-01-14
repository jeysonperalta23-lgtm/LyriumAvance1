<?php
/**
 * COMPONENTE: Sidebar de Tienda - Banner Vertical con Autoplay
 * Usando TailwindCSS
 * 
 * Variables esperadas:
 * - $tienda: datos de la tienda
 * - $plan: 'basico' o 'premium'
 */

// Banners promocionales de la tienda (demo data)
$bannersPromo = [
  [
    'imagen' => 'img/banners-tienda/banner-02.jpg',
    'titulo' => 'Ofertas Especiales',
    'subtitulo' => 'Hasta 50% de descuento',
    'cta' => 'Ver ofertas',
    'link' => '#ofertas',
    'badge' => '-50%'
  ],
  [
    'imagen' => 'img/banners-tienda/banner-03.jpg',
    'titulo' => 'Nuevos Productos',
    'subtitulo' => 'Descubre lo último en bienestar',
    'cta' => 'Explorar',
    'link' => '#nuevos'
  ],
  [
    'imagen' => 'img/banners-tienda/banner-04.jpg',
    'titulo' => 'Envío Gratis',
    'subtitulo' => 'En pedidos mayores a S/100',
    'cta' => 'Comprar ahora',
    'link' => '#envio'
  ]
];
?>

<aside class="tienda-sidebar-banner">
  
  <?php if ($plan === 'premium'): ?>
  <!-- SIDEBAR VERTICAL DE PRODUCTOS - Solo Premium -->
  <!-- Regla: Vertical = Productos (NO publicidad) -->
  <?php include 'componentes/tienda/sidebar-productos-vertical.php'; ?>
  
  <?php else: ?>
  <!-- Información del Negocio - Plan Básico (Cambiamos anuncios por utilidad) -->
  <div class="flex flex-col gap-4">
    
    <!-- Card: Envíos -->
    <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-sm">
      <div class="flex items-center gap-3 mb-4">
        <div class="w-10 h-10 bg-orange-50 rounded-lg flex items-center justify-center text-orange-600">
          <i class="ph-fill ph-truck text-xl"></i>
        </div>
        <h4 class="font-bold text-slate-800 text-sm">Envíos Nacionales</h4>
      </div>
      <p class="text-slate-500 text-xs mb-4">Gestionamos tus pedidos con los mejores operadores logísticos del país.</p>
      <div class="flex flex-wrap gap-2">
        <span class="px-2 py-1 bg-slate-100 text-slate-600 text-[10px] font-bold rounded">Shalom</span>
        <span class="px-2 py-1 bg-slate-100 text-slate-600 text-[10px] font-bold rounded">Olva</span>
        <span class="px-2 py-1 bg-slate-100 text-slate-600 text-[10px] font-bold rounded">Cruz del Sur</span>
      </div>
    </div>

    <!-- Card: Contacto Rápido -->
    <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-sm">
      <div class="flex items-center gap-3 mb-4">
        <div class="w-10 h-10 bg-blue-50 rounded-lg flex items-center justify-center text-blue-600">
          <i class="ph-fill ph-headset text-xl"></i>
        </div>
        <h4 class="font-bold text-slate-800 text-sm">Atención al Cliente</h4>
      </div>
      <div class="space-y-3">
        <div class="flex items-center gap-3">
          <i class="ph ph-phone text-slate-400"></i>
          <span class="text-xs text-slate-600"><?php echo htmlspecialchars($tienda['telefono']); ?></span>
        </div>
        <div class="flex items-center gap-3">
          <i class="ph ph-envelope-simple text-slate-400"></i>
          <span class="text-xs text-slate-600 truncate"><?php echo htmlspecialchars($tienda['correo']); ?></span>
        </div>
      </div>
    </div>

    <!-- Card: Horarios -->
    <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-sm">
      <div class="flex items-center gap-3 mb-4">
        <div class="w-10 h-10 bg-emerald-50 rounded-lg flex items-center justify-center text-emerald-600">
          <i class="ph-fill ph-clock text-xl"></i>
        </div>
        <h4 class="font-bold text-slate-800 text-sm">Horario de Hoy</h4>
      </div>
      <?php 
        $dias = ['domingo', 'lunes', 'martes', 'miercoles', 'jueves', 'viernes', 'sabado'];
        $dia_actual = $dias[date('w')];
        $horario = $horarios[$dia_actual] ?? null;
      ?>
      <div class="flex flex-col gap-1">
        <?php if ($horario && !$horario['cerrado']): ?>
          <span class="text-xs text-slate-600 font-medium capitalize"><?php echo $dia_actual; ?></span>
          <span class="text-lg font-bold text-slate-800"><?php echo $horario['apertura']; ?> - <?php echo $horario['cierre']; ?></span>
        <?php else: ?>
          <span class="text-lg font-bold text-rose-600">Cerrado ahora</span>
        <?php endif; ?>
      </div>
      <a href="#tab-sucursales" class="text-sky-600 text-xs font-bold mt-4 block hover:underline tienda-tab-link">Ver todas las sedes</a>
    </div>

  </div>
  <?php endif; ?>

</aside>

<?php if ($plan === 'premium'): ?>
<script>
// Banner Vertical con Autoplay (fade effect)
(function() {
  const container = document.getElementById('bannerVertical');
  if (!container) return;
  
  const slides = container.querySelectorAll('.banner-slide');
  const dots = container.querySelectorAll('.banner-dot');
  const total = slides.length;
  let current = 0;
  let interval;
  
  function showSlide(index) {
    current = (index + total) % total;
    
    slides.forEach((slide, i) => {
      slide.classList.toggle('opacity-100', i === current);
      slide.classList.toggle('opacity-0', i !== current);
    });
    
    dots.forEach((dot, i) => {
      if (i === current) {
        dot.classList.add('bg-white', 'w-6');
        dot.classList.remove('bg-white/50');
      } else {
        dot.classList.remove('bg-white', 'w-6');
        dot.classList.add('bg-white/50');
      }
    });
  }
  
  function next() {
    showSlide(current + 1);
  }
  
  function start() {
    interval = setInterval(next, 4000);
  }
  
  function stop() {
    clearInterval(interval);
  }
  
  // Click en dots
  dots.forEach((dot, i) => {
    dot.addEventListener('click', () => {
      showSlide(i);
      stop();
      start();
    });
  });
  
  // Pausar en hover
  container.addEventListener('mouseenter', stop);
  container.addEventListener('mouseleave', start);
  
  // Iniciar
  start();
})();
</script>
<?php endif; ?>
