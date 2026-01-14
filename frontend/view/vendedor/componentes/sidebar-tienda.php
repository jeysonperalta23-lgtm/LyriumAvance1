<!-- Sidebar para Panel de Tienda -->

<style>
  :root {
    --lyrium-blue: #0288D1;
    --lyrium-light: #E0F5FF;
  }

  .sidebar-icon {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: var(--lyrium-light);
    color: var(--lyrium-blue);
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all .18s ease;
    box-shadow: 0 2px 6px rgba(2, 136, 209, 0.15);
  }

  .group:hover .sidebar-icon {
    background: var(--lyrium-blue);
    color: #ffffff;
    transform: scale(1.05);
    box-shadow: 0 4px 12px rgba(2, 136, 209, 0.35);
  }

  .menu-active > a {
    background-color: #e0f2fe;
    color: var(--lyrium-blue);
  }

  .menu-active .sidebar-icon {
    background: var(--lyrium-blue) !important;
    color: #ffffff !important;
    box-shadow: 0 4px 12px rgba(2, 136, 209, 0.35);
    transform: scale(1.05);
  }

  .menu-active .menu-text {
    color: var(--lyrium-blue) !important;
    font-weight: 600;
  }
</style>

<aside id="sidebar"
       class="text-black fixed top-0 left-0 z-40 flex flex-col
              transition-all duration-300 ease-in-out
              w-64 md:relative md:w-64 max-h-screen md:max-h-none
              overflow-y-auto shadow-lg border-r border-sky-100
              bg-gradient-to-b from-sky-50 via-white to-sky-50
              transform md:translate-x-0 -translate-x-full"
       style="font-family: 'Inter', sans-serif;">

  <!-- PERFIL DE TIENDA -->
  <div class="p-4 border-b border-sky-100 bg-white/90 backdrop-blur flex items-center">
    <div class="flex items-center space-x-3 w-full">
      <div class="relative flex-shrink-0">
        <img id="tiendaLogo"
             src="../../img/logo.png"
             alt="Tienda"
             class="w-11 h-11 rounded-full border-2 border-sky-200 shadow-sm object-cover">
        <span class="absolute bottom-0 right-0 w-3 h-3 bg-emerald-400 border-2 border-white rounded-full"></span>
      </div>
      <div class="flex flex-col min-w-0">
        <p id="tiendaNombre"
           class="font-semibold text-xs sm:text-sm text-gray-800 leading-tight truncate">
          Mi Tienda
        </p>
        <p class="text-[10px] sm:text-[11px] text-sky-500 mt-0.5 uppercase tracking-wide">
          Panel de Tienda
        </p>
      </div>
    </div>
  </div>

  <!-- CONTENIDO MENÚ -->
  <nav class="flex-1 flex flex-col">
    <ul class="p-3 space-y-1">

      <!-- Etiqueta sección -->
      <li class="mt-1 mb-1">
        <p class="px-4 text-[10px] font-semibold uppercase tracking-wide text-sky-500/80">
          Navegación
        </p>
      </li>

      <!-- DASHBOARD -->
      <li class="rounded-xl menu-active" data-link="index">
        <a href="index.php"
           class="group flex items-center gap-3 px-4 py-2 rounded-xl text-xs sm:text-sm text-gray-700
                  hover:bg-sky-50 hover:text-sky-700 transition">
          <span class="sidebar-icon flex items-center justify-center flex-shrink-0">
            <i class="fas fa-home text-sm"></i>
          </span>
          <span class="menu-text font-medium truncate">Dashboard</span>
        </a>
      </li>

      <!-- PRODUCTOS -->
      <li class="rounded-xl" data-link="productos">
        <a href="productos.php"
           class="group flex items-center gap-3 px-4 py-2 rounded-xl text-xs sm:text-sm text-gray-700
                  hover:bg-sky-50 hover:text-sky-700 transition">
          <span class="sidebar-icon flex items-center justify-center flex-shrink-0">
            <i class="fas fa-shopping-bag text-sm"></i>
          </span>
          <span class="menu-text font-medium truncate">Productos</span>
        </a>
      </li>

      <!-- PEDIDOS -->
      <li class="rounded-xl" data-link="pedidos">
        <a href="pedidos.php"
           class="group flex items-center gap-3 px-4 py-2 rounded-xl text-xs sm:text-sm text-gray-700
                  hover:bg-sky-50 hover:text-sky-700 transition">
          <span class="sidebar-icon flex items-center justify-center flex-shrink-0">
            <i class="fas fa-box text-sm"></i>
          </span>
          <span class="menu-text font-medium truncate">Pedidos</span>
          <span class="ml-auto bg-orange-500 text-white text-[10px] px-2 py-0.5 rounded-full">3</span>
        </a>
      </li>

      <!-- CATEGORÍAS -->
      <li class="rounded-xl" data-link="categorias">
        <a href="categorias.php"
           class="group flex items-center gap-3 px-4 py-2 rounded-xl text-xs sm:text-sm text-gray-700
                  hover:bg-sky-50 hover:text-sky-700 transition">
          <span class="sidebar-icon flex items-center justify-center flex-shrink-0">
            <i class="fas fa-tags text-sm"></i>
          </span>
          <span class="menu-text font-medium truncate">Categorías</span>
        </a>
      </li>

      <!-- ETIQUETA SUBSECCIÓN -->
      <li class="mt-3 mb-1">
        <p class="px-4 text-[10px] font-semibold uppercase tracking-wide text-sky-500/80">
          Reportes & Configuración
        </p>
      </li>

      <!-- ESTADÍSTICAS -->
      <li class="rounded-xl" data-link="estadisticas">
        <a href="estadisticas.php"
           class="group flex items-center gap-3 px-4 py-2 rounded-xl text-xs sm:text-sm text-gray-700
                  hover:bg-sky-50 hover:text-sky-700 transition">
          <span class="sidebar-icon flex items-center justify-center flex-shrink-0">
            <i class="fas fa-chart-bar text-sm"></i>
          </span>
          <span class="menu-text font-medium truncate">Estadísticas</span>
        </a>
      </li>

      <!-- CONFIGURACIÓN -->
      <li class="rounded-xl" data-link="configuracion">
        <a href="configuracion.php"
           class="group flex items-center gap-3 px-4 py-2 rounded-xl text-xs sm:text-sm text-gray-700
                  hover:bg-sky-50 hover:text-sky-700 transition">
          <span class="sidebar-icon flex items-center justify-center flex-shrink-0">
            <i class="fas fa-cog text-sm"></i>
          </span>
          <span class="menu-text font-medium truncate">Configuración</span>
        </a>
      </li>

      <!-- VER MI TIENDA -->
      <li class="rounded-xl">
        <a href="../../tienda.php?slug=vida-natural" target="_blank"
           class="group flex items-center gap-3 px-4 py-2 rounded-xl text-xs sm:text-sm text-gray-700
                  hover:bg-sky-50 hover:text-sky-700 transition">
          <span class="sidebar-icon flex items-center justify-center flex-shrink-0">
            <i class="fas fa-eye text-sm"></i>
          </span>
          <span class="menu-text font-medium truncate">Ver Mi Tienda</span>
          <i class="fas fa-external-link-alt text-[10px] ml-auto"></i>
        </a>
      </li>

      <!-- CERRAR SESIÓN -->
      <li class="rounded-xl mt-4">
        <a href="#" onclick="cerrarSesionTienda(); return false;"
           class="group flex items-center gap-3 px-4 py-2 rounded-xl text-xs sm:text-sm text-red-600
                  hover:bg-red-50 transition">
          <span class="sidebar-icon flex items-center justify-center flex-shrink-0 bg-red-100">
            <i class="fas fa-sign-out-alt text-sm text-red-600"></i>
          </span>
          <span class="menu-text font-medium truncate">Cerrar Sesión</span>
        </a>
      </li>

    </ul>
  </nav>

  <!-- FOOTER -->
  <div class="mt-auto px-4 py-3 text-[10px] sm:text-[11px] text-sky-700 border-t border-sky-100
              bg-white/90 backdrop-blur flex items-center justify-between">
    <span class="flex items-center gap-1 text-sky-500">
      <i class="fas fa-store text-[11px]"></i>
      <span>&copy; <?php echo date('Y'); ?></span>
    </span>
    <span class="uppercase tracking-wide font-semibold text-[10px] text-sky-700">
      Lyrium Tienda
    </span>
  </div>
</aside>

<script>
// Cargar información de la tienda
(function () {
  const tiendaNombre = document.getElementById("tiendaNombre");
  const tiendaLogo = document.getElementById("tiendaLogo");

  // TODO: Obtener de sesión o API
  const tienda = {
    nombre: "<?php echo $_SESSION['tienda_nombre'] ?? 'Mi Tienda'; ?>",
    logo: "../../img/logo.png"
  };

  if (tiendaNombre) {
    tiendaNombre.textContent = tienda.nombre;
  }

  if (tiendaLogo && tienda.logo) {
    tiendaLogo.src = tienda.logo;
  }
})();

// Marcar menú activo
document.addEventListener("DOMContentLoaded", function () {
  const menuItems = document.querySelectorAll("li[data-link]");
  const currentPage = window.location.pathname.split('/').pop().replace('.php', '') || 'index';

  menuItems.forEach(item => {
    const link = item.getAttribute("data-link");
    if (link === currentPage) {
      item.classList.add("menu-active");
    }

    item.addEventListener("click", function () {
      menuItems.forEach(li => li.classList.remove("menu-active"));
      item.classList.add("menu-active");
    });
  });
});

// Cerrar sesión
async function cerrarSesionTienda() {
  if (!confirm('¿Estás seguro de cerrar sesión?')) return;

  try {
    const response = await fetch('../../backend/api/tienda.php?op=logout', {
      method: 'POST'
    });

    const data = await response.json();

    if (data.success) {
      window.location.href = '../../registrar-tienda/index.php';
    }
  } catch (error) {
    console.error('Error al cerrar sesión:', error);
    window.location.href = '../../registrar-tienda/index.php';
  }
}
</script>
