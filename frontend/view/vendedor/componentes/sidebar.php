<!-- Sidebar del Panel de Tienda -->
<aside class="fixed left-0 top-0 h-screen w-56 bg-gradient-to-b from-sky-900 to-sky-800 text-white transform -translate-x-full lg:translate-x-0 transition-transform duration-300 z-50" id="sidebar">
  
  <!-- Logo y Nombre de Tienda -->
  <div class="p-4 border-b border-sky-700">
    <div class="flex items-center gap-2">
      <div class="w-10 h-10 bg-white rounded-lg flex items-center justify-center">
        <i class="ph-fill ph-storefront text-xl text-sky-600"></i>
      </div>
      <div class="flex-1 min-w-0">
        <h2 class="font-bold text-sm truncate"><?php echo htmlspecialchars($tienda_nombre); ?></h2>
        <span class="text-xs text-sky-300">
          <?php echo $tienda_plan === 'premium' ? '👑 Premium' : '📦 Básico'; ?>
        </span>
      </div>
    </div>
  </div>
  
  <!-- Menú de Navegación -->
  <nav class="p-3 space-y-1">
    
    <!-- Dashboard -->
    <a href="index.php" class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-white/10 transition-colors text-sm <?php echo basename($_SERVER['PHP_SELF']) === 'index.php' ? 'bg-white/20' : ''; ?>">
      <i class="ph-fill ph-house text-lg"></i>
      <span class="font-medium">Dashboard</span>
    </a>
    
    <!-- Productos -->
    <a href="productos.php" class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-white/10 transition-colors text-sm <?php echo basename($_SERVER['PHP_SELF']) === 'productos.php' ? 'bg-white/20' : ''; ?>">
      <i class="ph-fill ph-shopping-bag text-lg"></i>
      <span class="font-medium">Productos</span>
    </a>
    
    <!-- Pedidos -->
    <a href="pedidos.php" class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-white/10 transition-colors text-sm <?php echo basename($_SERVER['PHP_SELF']) === 'pedidos.php' ? 'bg-white/20' : ''; ?>">
      <i class="ph-fill ph-package text-lg"></i>
      <span class="font-medium">Pedidos</span>
      <span class="ml-auto bg-orange-500 text-white text-xs px-1.5 py-0.5 rounded-full">3</span>
    </a>
    
    <!-- Categorías -->
    <a href="categorias.php" class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-white/10 transition-colors text-sm <?php echo basename($_SERVER['PHP_SELF']) === 'categorias.php' ? 'bg-white/20' : ''; ?>">
      <i class="ph-fill ph-tag text-lg"></i>
      <span class="font-medium">Categorías</span>
    </a>
    
    <!-- Estadísticas -->
    <a href="estadisticas.php" class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-white/10 transition-colors text-sm <?php echo basename($_SERVER['PHP_SELF']) === 'estadisticas.php' ? 'bg-white/20' : ''; ?>">
      <i class="ph-fill ph-chart-line text-lg"></i>
      <span class="font-medium">Estadísticas</span>
    </a>
    
    <!-- Separador -->
    <div class="border-t border-sky-700 my-2"></div>
    
    <!-- Configuración -->
    <a href="configuracion.php" class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-white/10 transition-colors text-sm <?php echo basename($_SERVER['PHP_SELF']) === 'configuracion.php' ? 'bg-white/20' : ''; ?>">
      <i class="ph-fill ph-gear text-lg"></i>
      <span class="font-medium">Configuración</span>
    </a>
    
    <!-- Banners (Solo Premium) -->
    <?php if ($tienda_plan === 'premium'): ?>
    <a href="banners.php" class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-white/10 transition-colors text-sm <?php echo basename($_SERVER['PHP_SELF']) === 'banners.php' ? 'bg-white/20' : ''; ?>">
      <i class="ph-fill ph-image text-lg"></i>
      <span class="font-medium">Banners</span>
      <span class="ml-auto bg-lime-500 text-white text-xs px-1.5 py-0.5 rounded-full">PRO</span>
    </a>
    <?php endif; ?>
    
    <!-- Contenido de Tabs -->
    <a href="tabs.php" class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-white/10 transition-colors text-sm <?php echo basename($_SERVER['PHP_SELF']) === 'tabs.php' ? 'bg-white/20' : ''; ?>">
      <i class="ph-fill ph-tabs text-lg"></i>
      <span class="font-medium">Contenido de Tabs</span>
    </a>
    
    <!-- Mi Tienda (Vista Pública) -->
    <a href="../../tienda.php?slug=<?php echo $tienda_slug; ?>" target="_blank" class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-white/10 transition-colors text-sm">
      <i class="ph-fill ph-eye text-lg"></i>
      <span class="font-medium">Ver Mi Tienda</span>
      <i class="ph ph-arrow-square-out text-xs ml-auto"></i>
    </a>
    
  </nav>
  
  <!-- Footer del Sidebar -->
  <div class="absolute bottom-0 left-0 right-0 p-3 border-t border-sky-700">
    <button onclick="cerrarSesion()" class="w-full flex items-center justify-center gap-2 px-3 py-2 bg-red-500/20 hover:bg-red-500/30 rounded-lg transition-colors text-sm">
      <i class="ph ph-sign-out text-lg"></i>
      <span class="font-medium">Cerrar Sesión</span>
    </button>
  </div>
  
</aside>

<!-- Overlay para móvil -->
<div id="sidebarOverlay" class="fixed inset-0 bg-black/50 z-40 lg:hidden hidden" onclick="toggleSidebar()"></div>

<script>
  // Toggle sidebar en móvil
  function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebarOverlay');
    
    sidebar.classList.toggle('-translate-x-full');
    overlay.classList.toggle('hidden');
  }
  
  // Cerrar sesión
  async function cerrarSesion() {
    if (!confirm('¿Estás seguro de cerrar sesión?')) return;
    
    try {
      const response = await fetch('../../backend/api/tienda.php?op=logout', {
        method: 'POST'
      });
      
      const data = await response.json();
      
      if (data.success) {
        window.location.href = '/lyrium/frontend/registrar-tienda/login.php';
      }
    } catch (error) {
      console.error('Error al cerrar sesión:', error);
      window.location.href = '/lyrium/frontend/registrar-tienda/login.php';
    }
  }
</script>
