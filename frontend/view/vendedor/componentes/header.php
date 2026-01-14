<!-- Header del Panel de Tienda -->
<header class="bg-white border-b border-gray-200 sticky top-0 z-30">
  <div class="px-6 py-4">
    <div class="flex items-center justify-between">
      
      <!-- Botón de menú móvil + Breadcrumb -->
      <div class="flex items-center gap-4">
        <!-- Botón menú móvil -->
        <button onclick="toggleSidebar()" class="lg:hidden text-gray-600 hover:text-gray-800">
          <i class="ph ph-list text-2xl"></i>
        </button>
        
        <!-- Breadcrumb -->
        <div class="hidden md:flex items-center gap-2 text-sm text-gray-500">
          <i class="ph ph-house"></i>
          <span>Panel</span>
          <i class="ph ph-caret-right text-xs"></i>
          <span class="text-gray-800 font-medium">
            <?php 
              $page = basename($_SERVER['PHP_SELF'], '.php');
              $titles = [
                'index' => 'Dashboard',
                'productos' => 'Productos',
                'pedidos' => 'Pedidos',
                'categorias' => 'Categorías',
                'estadisticas' => 'Estadísticas',
                'configuracion' => 'Configuración'
              ];
              echo $titles[$page] ?? ucfirst($page);
            ?>
          </span>
        </div>
      </div>
      
      <!-- Acciones del Header -->
      <div class="flex items-center gap-3">
        
        <!-- Notificaciones -->
        <button class="relative p-2 text-gray-600 hover:text-gray-800 hover:bg-gray-100 rounded-lg transition-colors">
          <i class="ph ph-bell text-2xl"></i>
          <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full"></span>
        </button>
        
        <!-- Ayuda -->
        <button class="p-2 text-gray-600 hover:text-gray-800 hover:bg-gray-100 rounded-lg transition-colors">
          <i class="ph ph-question text-2xl"></i>
        </button>
        
        <!-- Separador -->
        <div class="w-px h-8 bg-gray-200"></div>
        
        <!-- Perfil -->
        <div class="flex items-center gap-3">
          <div class="hidden sm:block text-right">
            <p class="text-sm font-medium text-gray-800"><?php echo htmlspecialchars($tienda_nombre); ?></p>
            <p class="text-xs text-gray-500"><?php echo $tienda_plan === 'premium' ? 'Plan Premium' : 'Plan Básico'; ?></p>
          </div>
          <div class="w-10 h-10 bg-gradient-to-br from-sky-500 to-sky-600 rounded-full flex items-center justify-center text-white font-bold">
            <?php echo strtoupper(substr($tienda_nombre, 0, 1)); ?>
          </div>
        </div>
        
      </div>
      
    </div>
  </div>
</header>
