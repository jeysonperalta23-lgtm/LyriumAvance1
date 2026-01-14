<script src="../js/logout.js?v=<?php echo time();?>"></script>
<style>
.min-h-screen{
  min-height:92.8vh !important;
}
</style>
<head>
    <meta charset="UTF-8">
    <title>Panel de Administración</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="../utils/js/3.4.16.js"></script>
    <script src="../utils/js/ag-grid-community.min.js"></script>
    <link href="../utils/css/fontawesome-free-6.7.2-web/css/all.min.css" rel="stylesheet">
    <link rel="icon" type="image/png" href="../img/logo.png?v=<?php echo time();?>" />
</head>
<header
  class="px-4 py-3 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2
         shadow-md border-b border-white/40
         bg-gradient-to-r from-lime-300 via-teal-300 to-sky-400 text-white"
>
    <!-- Título + botón menú -->
  <h1 class="text-base sm:text-lg font-bold flex items-center space-x-3"
      style="color:#0c5e3b; text-shadow:0 1px 2px rgba(255,255,255,0.6);">
      
      <!-- Botón toggle sidebar -->
      <button
        onclick="toggleSidebar()"
        class="flex items-center justify-center w-8 h-8 rounded-full
               bg-white/85 text-sky-600 shadow-md
               hover:bg-white hover:shadow-lg
               transition-all duration-200 ease-in-out">
        <i id="sidebar-menu-icon" class="fas fa-minus text-xs sm:text-sm"></i>
      </button>

      <span class="flex flex-col leading-tight">
        <span>Panel de Biomarketplace</span>
        <span id="info" class="text-[11px] font-semibold" style="color:#0c5e3b;"></span>
      </span>
  </h1>


  <!-- Menú principal (usuario) -->
  <div id="dropdownContainer" class="relative inline-block text-left">
    <button
      id="dropdownButton"
      class="inline-flex items-center gap-2 px-4 py-2 text-xs sm:text-sm font-medium
             bg-white/90 text-teal-700 rounded-full shadow-sm
             border border-emerald-100
             hover:bg-white hover:shadow-md hover:border-emerald-200
             transition-all duration-200"
      type="button"
    >
      <i class="fas fa-user-circle text-base"></i>
      <span>Usuario</span>
      <svg class="w-4 h-4 ml-1" fill="currentColor" viewBox="0 0 20 20">
        <path fill-rule="evenodd"
          d="M5.23 7.21a.75.75 0 011.06.02L10 11.292l3.71-4.06a.75.75 0 111.08 1.04l-4.25 4.65a.75.75 0 01-1.08 0l-4.25-4.65a.75.75 0 01.02-1.06z"
          clip-rule="evenodd" />
      </svg>
    </button>

    <!-- Menú desplegable -->
    <div
      id="dropdownMenu"
      class="absolute right-0 mt-2 w-56 bg-white/95 backdrop-blur
             border border-teal-50 rounded-xl shadow-lg hidden z-50
             text-gray-700"
    >
      <a
        href="#"
        class="block px-4 py-2 text-sm hover:bg-teal-50 hover:text-teal-700 rounded-t-xl"
      >
        <i class="fas fa-user mr-2 text-teal-500"></i> Perfil
      </a>

      <!-- Submenú: Configuración -->
      <div class="relative group">
        <button
          class="w-full text-left flex items-center justify-between px-4 py-2 text-sm
                 hover:bg-teal-50 hover:text-teal-700"
          type="button"
        >
          <span><i class="fas fa-cog mr-2 text-teal-500"></i> Configuración</span>
          <i class="fas fa-chevron-left text-xs text-gray-400 group-hover:text-teal-500"></i>
        </button>
        <div
          class="absolute top-0 right-full mt-0 mr-1 w-48 bg-white/95 border border-teal-50 rounded-lg
                 shadow-lg hidden group-hover:block z-50"
        >
          <a href="#" class="block px-4 py-2 text-sm hover:bg-teal-50 hover:text-teal-700">
            Cuenta
          </a>
          <a href="#" class="block px-4 py-2 text-sm hover:bg-teal-50 hover:text-teal-700">
            Notificaciones
          </a>
        </div>
      </div>

      <!-- Submenú: Ayuda -->
<div class="relative group">
  <button
    class="w-full text-left flex items-center justify-between px-4 py-2 text-sm
           hover:bg-teal-50 hover:text-teal-700"
    type="button"
  >
    <span><i class="fas fa-cog mr-2 text-teal-500"></i> Configuración</span>
    <i class="fas fa-chevron-left text-xs text-gray-400 group-hover:text-teal-500"></i>
  </button>

  <div
    class="absolute hidden group-hover:block z-50
           top-full right-0 mt-1 w-48
           sm:top-0 sm:right-full sm:mt-0 sm:mr-1
           bg-white/95 border border-teal-50 rounded-lg shadow-lg"
  >
    <a href="#" class="block px-4 py-2 text-sm hover:bg-teal-50 hover:text-teal-700">Cuenta</a>
    <a href="#" class="block px-4 py-2 text-sm hover:bg-teal-50 hover:text-teal-700">Notificaciones</a>
  </div>
</div>


      <div class="border-t my-1 border-teal-50"></div>

      <a
        href="#"
        id="btnLogout"
        class="block px-4 py-2 text-sm text-red-600 hover:bg-red-50 rounded-b-xl"
      >
        <i class="fas fa-sign-out-alt mr-2"></i> Cerrar Sesión
      </a>
    </div>
  </div>
</header>
