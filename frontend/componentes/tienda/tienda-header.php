<?php
/**
 * TIENDA-HEADER.PHP - Header de tienda estilo barra
 * 
 * Barra superior con:
 * - Logo, nombre y badge de verificación
 * - Información del negocio (ubicación, categoría, estado, contacto)
 * - Botones de acción (seguir, contactar)
 * - Buscador dentro de la tienda
 * 
 * Variables esperadas:
 * @var array $tienda - Datos de la tienda
 * @var string $plan - Plan de la tienda ('basico' o 'premium')
 * @var array $productos - Productos de la tienda (para contar)
 */

// Validar datos requeridos
if (!isset($tienda) || !is_array($tienda)) {
    return;
}

$plan = $plan ?? 'basico';

// Contar productos
$total_productos = isset($productos) ? count($productos) : 0;

// Calcular calificación promedio de opiniones
$calificacion_promedio = 0;
if (!empty($opiniones)) {
    $suma = array_sum(array_column($opiniones, 'rating'));
    $calificacion_promedio = round($suma / count($opiniones), 1);
}

// Badge según plan
$badge_texto = $plan === 'premium' ? 'Premium' : 'Verificado';
?>
<!-- HEADER DE TIENDA - Barra con info del negocio (Sticky on scroll) -->
<div id="tiendaHeaderSticky" class="tienda-header-sticky">
  <div class="tienda-container">
    <div class="rounded-xl shadow-lg tienda-header-bg">
      <div class="px-4 lg:px-6">
      <div class="flex items-center justify-between py-3 gap-4">
        
        <!-- Logo + Nombre + Badge -->
        <div class="flex items-center gap-3">
          <!-- Logo -->
          <div class="w-10 h-10 rounded-full overflow-hidden bg-white flex-shrink-0 ring-2 ring-white/30 shadow-md">
            <img 
              src="<?php echo htmlspecialchars($tienda['logo']); ?>" 
              alt="<?php echo htmlspecialchars($tienda['nombre']); ?>"
              class="w-full h-full object-cover"
            >
          </div>
          
          <!-- Nombre y badge -->
          <div class="flex items-center gap-2">
            <span class="font-semibold text-base text-white hover:text-white/80 transition-colors cursor-pointer">
              <?php echo htmlspecialchars($tienda['nombre']); ?>
            </span>
            <?php if ($plan === 'premium'): ?>
            <span class="bg-amber-400 text-amber-900 text-xs px-2 py-0.5 rounded font-semibold flex items-center gap-1 shadow-sm">
              <i class="ph-seal-check-fill text-sm"></i>
              <?php echo $badge_texto; ?>
            </span>
            <?php endif; ?>
          </div>
        </div>

        <!-- Info del Negocio - Badges Mejorados -->
        <div class="hidden md:flex items-center gap-2.5 text-sm">
          <!-- Estado Abierto/Cerrado -->
          <?php if ($tienda['abierto']): ?>
          <span class="flex items-center gap-1.5 px-3 py-1.5 bg-emerald-500/20 border border-emerald-400/30 rounded-lg backdrop-blur-sm">
            <span class="w-2 h-2 bg-emerald-400 rounded-full animate-pulse shadow-lg shadow-emerald-400/50"></span>
            <span class="text-emerald-200 font-semibold">Abierto</span>
          </span>
          <?php else: ?>
          <span class="flex items-center gap-1.5 px-3 py-1.5 bg-red-500/20 border border-red-400/30 rounded-lg backdrop-blur-sm">
            <span class="w-2 h-2 bg-red-400 rounded-full"></span>
            <span class="text-red-200 font-semibold">Cerrado</span>
          </span>
          <?php endif; ?>
          
          <!-- Ubicación -->
          <?php if (!empty($tienda['direccion'])): ?>
          <span class="flex items-center gap-1.5 px-3 py-1.5 bg-white/10 border border-white/20 rounded-lg backdrop-blur-sm hover:bg-white/15 transition-colors">
            <i class="ph-fill ph-map-pin text-sky-300"></i>
            <span class="text-white/95 font-medium truncate max-w-40"><?php echo htmlspecialchars(explode(',', $tienda['direccion'])[0]); ?></span>
          </span>
          <?php endif; ?>
          
          <!-- Categoría -->
          <span class="flex items-center gap-1.5 px-3 py-1.5 bg-white/10 border border-white/20 rounded-lg backdrop-blur-sm hover:bg-white/15 transition-colors">
            <i class="ph-fill ph-tag text-purple-300"></i>
            <span class="text-white/95 font-medium"><?php echo htmlspecialchars($tienda['categoria'] ?? 'Sin categoría'); ?></span>
          </span>
          
          <!-- Calificación -->
          <?php if ($calificacion_promedio > 0): ?>
          <span class="flex items-center gap-1.5 px-3 py-1.5 bg-amber-500/20 border border-amber-400/30 rounded-lg backdrop-blur-sm">
            <i class="ph-fill ph-star text-amber-300"></i>
            <span class="text-amber-100 font-bold"><?php echo $calificacion_promedio; ?></span>
          </span>
          <?php endif; ?>
          
          <!-- Productos -->
          <span class="flex items-center gap-1.5 px-3 py-1.5 bg-blue-500/20 border border-blue-400/30 rounded-lg backdrop-blur-sm">
            <i class="ph-fill ph-package text-blue-300"></i>
            <span class="text-blue-100 font-bold"><?php echo $total_productos; ?></span>
            <span class="text-blue-200/80 text-xs">productos</span>
          </span>
        </div>

    

        <!-- Buscador en tienda -->
        <div class="hidden lg:flex items-center relative">
          <i class="ph-magnifying-glass absolute left-3 text-white/60"></i>
          <input 
            type="text" 
            placeholder="Buscar en esta tienda"
            id="tiendaSearchHeader"
            class="w-48 xl:w-56 pl-9 pr-3 py-1.5 rounded-full bg-white/20 border border-white/30 
                   text-sm placeholder-white/70 focus:outline-none focus:bg-white focus:text-slate-900 
                   focus:placeholder-slate-500 transition-all duration-200"
          >
        </div>

      </div>
      </div>
    </div>
  </div>
</div> 
<!-- Cierre de barra -->
<?php if ($plan === 'premium'): ?>
<?php endif; ?>

<!-- Script para sticky header -->
<script>
(function() {
  const tiendaHeader = document.getElementById('tiendaHeaderSticky');
  const mainHeader = document.querySelector('header');
  
  if (!tiendaHeader) return;
  
  const headerOffset = tiendaHeader.offsetTop;
  
  function handleScroll() {
    const currentScroll = window.pageYOffset;
    
    if (currentScroll > headerOffset) {
      tiendaHeader.classList.add('is-sticky');
      document.body.classList.add('tienda-header-active');
      if (mainHeader) {
        mainHeader.style.transform = 'translateY(-100%)';
        mainHeader.style.transition = 'transform 0.3s ease';
      }
    } else {
      tiendaHeader.classList.remove('is-sticky');
      document.body.classList.remove('tienda-header-active');
      if (mainHeader) {
        mainHeader.style.transform = 'translateY(0)';
      }
    }
  }
  
  window.addEventListener('scroll', handleScroll, { passive: true });
  handleScroll();
})();
</script>