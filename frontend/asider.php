<!-- C:\xampp\htdocs\schedule\frontend\asider.html -->

<style>
  :root {
    --lyrium-blue: #0288D1;   /* Azul principal */
    --lyrium-light: #E0F5FF;  /* Fondo claro ícono */
  }

  /* ================= ICONOS PRINCIPALES ================= */
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

  /* Hover: se invierten colores */
  .group:hover .sidebar-icon {
    background: var(--lyrium-blue);
    color: #ffffff;
    transform: scale(1.05);
    box-shadow: 0 4px 12px rgba(2, 136, 209, 0.35);
  }

  /* ================= OPCIÓN ACTIVA ================= */
  .menu-active > a {
    background-color: #e0f2fe; /* fondo celeste suave */
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

  /* ================= SUBMENÚ ================= */
  .sidebar-sub-link {
    transition:
      background-color 0.15s ease,
      color 0.15s ease,
      transform 0.15s ease;
  }

  .sidebar-sub-icon {
    color: var(--lyrium-blue);
    transition: all 0.15s ease;
  }

  .sidebar-sub-link:hover .sidebar-sub-icon {
    color: #ffffff;
    transform: translateX(2px);
  }

  .sidebar-sub-link:hover {
    background: var(--lyrium-blue);
    color: #ffffff;
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

  <!-- PERFIL -->
  <div class="p-4 border-b border-sky-100 bg-white/90 backdrop-blur flex items-center">
    <div class="flex items-center space-x-3 w-full">
      <div class="relative flex-shrink-0">
        <!-- 🔹 IMPORTANTE: id="avatarImg" -->
        <img id="avatarImg"
             src="../img/cuenta.png"
             alt="Usuario"
             class="w-11 h-11 rounded-full border-2 border-sky-200 shadow-sm object-cover">
        <!-- indicador online -->
        <span class="absolute bottom-0 right-0 w-3 h-3 bg-emerald-400 border-2 border-white rounded-full"></span>
      </div>
      <div class="flex flex-col min-w-0">
        <!-- Aquí se llena: “Usuario: admin | Rol: Administrador” -->
        <p id="info"
           class="font-semibold text-xs sm:text-sm text-gray-800 leading-tight truncate">
        </p>
        <p class="text-[10px] sm:text-[11px] text-sky-500 mt-0.5 uppercase tracking-wide">
          Panel de control
        </p>
      </div>
    </div>
  </div>

  <!-- CONTENIDO MENÚ -->
  <nav class="flex-1 flex flex-col">
    <ul class="p-3 space-y-1">

      <!-- Toggle sidebar (solo móvil) -->
      <li id="toggleSidebarItem"
          onclick="toggleSidebar()"
          class="hidden cursor-pointer rounded-lg px-4 py-2 hover:bg-sky-50 flex items-center gap-3 text-gray-600 text-xs sm:text-sm">
        <i id="menu-icon" class="fas fa-minus text-[11px]"></i>
        <span class="menu-text">Ocultar menú</span>
      </li>

      <!-- Etiqueta sección -->
      <li class="mt-1 mb-1">
        <p class="px-4 text-[10px] font-semibold uppercase tracking-wide text-sky-500/80">
          Navegación
        </p>
      </li>

      <!-- INICIO -->
      <li class="rounded-xl" data-link="home">
        <a href="home"
           class="group flex items-center gap-3 px-4 py-2 rounded-xl text-xs sm:text-sm text-gray-700
                  hover:bg-sky-50 hover:text-sky-700 transition">
          <span class="sidebar-icon flex items-center justify-center flex-shrink-0">
            <i class="fas fa-home text-sm"></i>
          </span>
          <span class="menu-text font-medium truncate">Inicio</span>
        </a>
      </li>

      <!-- USUARIOS -->
      <li class="rounded-xl" data-link="usuario">
        <a href="usuario"
           class="group flex items-center gap-3 px-4 py-2 rounded-xl text-xs sm:text-sm text-gray-700
                  hover:bg-sky-50 hover:text-sky-700 transition">
          <span class="sidebar-icon flex items-center justify-center flex-shrink-0">
            <i class="fas fa-users text-sm"></i>
          </span>
          <span class="menu-text font-medium truncate">Usuario</span>
        </a>
      </li>

      <!-- CLIENTES -->
      <li class="rounded-xl" data-link="cliente">
        <a href="cliente"
           class="group flex items-center gap-3 px-4 py-2 rounded-xl text-xs sm:text-sm text-gray-700
                  hover:bg-sky-50 hover:text-sky-700 transition">
          <span class="sidebar-icon flex items-center justify-center flex-shrink-0">
            <i class="fas fa-user text-sm"></i>
          </span>
          <span class="menu-text font-medium truncate">Clientes</span>
        </a>
      </li>

      <!-- PRODUCTOS -->
      <li class="rounded-xl" data-link="producto">
        <a href="producto"
           class="group flex items-center gap-3 px-4 py-2 rounded-xl text-xs sm:text-sm text-gray-700
                  hover:bg-sky-50 hover:text-sky-700 transition">
          <span class="sidebar-icon flex items-center justify-center flex-shrink-0">
            <i class="fas fa-bars text-sm"></i>
          </span>
          <span class="menu-text font-medium truncate">Productos</span>
        </a>
      </li>

      <!--
      <li class="rounded-xl" data-link="carrito">
        <a href="carrito"
           class="group flex items-center gap-3 px-4 py-2 rounded-xl text-xs sm:text-sm text-gray-700
                  hover:bg-sky-50 hover:text-sky-700 transition">
          <span class="sidebar-icon flex items-center justify-center flex-shrink-0">
            <i class="fas fa-shopping-cart text-sm"></i>
          </span>
          <span class="menu-text font-medium truncate">Carrito</span>
        </a>
      </li>
      -->

      <!-- ETIQUETA SUBSECCIÓN -->
      <li class="mt-3 mb-1">
        <p class="px-4 text-[10px] font-semibold uppercase tracking-wide text-sky-500/80">
          Guías & reportes
        </p>
      </li>

      <!-- SUBMENÚ GUIAS -->
      <li class="rounded-xl cursor-pointer flex flex-col">
        <button onclick="toggleSubmenu('submenu-reportes')"
                class="group flex justify-between items-center w-full px-4 py-2
                       hover:bg-sky-50 hover:text-sky-700 rounded-xl text-xs sm:text-sm text-gray-700 transition">
          <div class="flex items-center gap-3">
            <span class="sidebar-icon flex items-center justify-center flex-shrink-0">
              <i class="fas fa-chart-bar text-sm"></i>
            </span>
            <span class="menu-text font-medium truncate">Productos</span>
          </div>
          <i id="icon-submenu-reportes"
             class="fas fa-chevron-down text-[10px] text-gray-500 group-hover:text-sky-600
                    transition-transform duration-200 w-4 h-4 flex items-center justify-center"></i>
        </button>

        <ul id="submenu-reportes"
            class="mt-2 pl-5 space-y-1 hidden border-l border-sky-100">
          <li class="rounded-lg">
            <a href=""
               class="sidebar-sub-link flex items-center gap-2 px-3 py-2 text-[12.5px] text-gray-600
                      rounded-lg transition">
              <i class="sidebar-sub-icon fas fa-calendar-alt w-4 h-4"></i>
              <span>Belleza</span>
            </a>
          </li>
          <li class="rounded-lg">
            <a href=""
               class="sidebar-sub-link flex items-center gap-2 px-3 py-2 text-[12.5px] text-gray-600
                      rounded-lg transition">
              <i class="sidebar-sub-icon fas fa-calendar w-4 h-4"></i>
              <span>Mascotas</span>
            </a>
          </li>
          <li class="rounded-lg">
            <a href=""
               class="sidebar-sub-link flex items-center gap-2 px-3 py-2 text-[12.5px] text-gray-600
                      rounded-lg transition">
              <i class="sidebar-sub-icon fas fa-calendar w-4 h-4"></i>
              <span>Suplementos</span>
            </a>
          </li>
        </ul>
      </li>
    </ul>
  </nav>

  <!-- FOOTER -->
  <div class="mt-auto px-4 py-3 text-[10px] sm:text-[11px] text-sky-700 border-t border-sky-100
              bg-white/90 backdrop-blur flex items-center justify-between">
    <span class="flex items-center gap-1 text-sky-500">
      <i class="fas fa-bolt text-[11px]"></i>
      <span>&copy; 2025</span>
    </span>
    <span class="uppercase tracking-wide font-semibold text-[10px] text-sky-700">
      Batch Panel
    </span>
  </div>
</aside>

<!-- 🔹 SCRIPT PARA CARGAR AVATAR / TEXTO + MARCAR MENÚ ACTIVO -->
<script>
(function () {
  const avatarImg = document.getElementById("avatarImg");
  const infoEl    = document.getElementById("info");

  if (!avatarImg || !infoEl) return;

  const DEFAULT_AVATAR   = "../img/cuenta.png";
  const BASE_AVATAR_PATH = "../../uploads/avatars/";

  // 1) Intentar primero con localStorage.usuario
  let usuario = null;
  try {
    usuario = JSON.parse(localStorage.getItem("usuario") || "{}");
  } catch (e) {
    console.error("Error parseando localStorage.usuario:", e);
    usuario = {};
  }

  console.log("Usuario desde localStorage:", usuario);

  if (usuario && usuario.id) {
    const nombre = usuario.username || usuario.correo || "Usuario";
    const rol    = usuario.rol || "Sin rol";
    infoEl.textContent = `Usuario: ${nombre} | Rol: ${rol}`;

    let nuevaSrc = DEFAULT_AVATAR;

    if (usuario.avatar_filename && usuario.avatar_filename.trim() !== "") {
      nuevaSrc = BASE_AVATAR_PATH + usuario.avatar_filename.trim();
    } else if (usuario.avatar_color) {
      avatarImg.style.borderColor = usuario.avatar_color;
    }

    avatarImg.onerror = function () {
      console.warn("No se pudo cargar el avatar, usando por defecto (localStorage).");
      avatarImg.src = DEFAULT_AVATAR;
    };

    avatarImg.src = nuevaSrc;
    console.log("Avatar elegido (localStorage):", nuevaSrc);
    return;
  }

  // 2) Backup: token JWT (por si no hay usuario en localStorage)
  const token = localStorage.getItem("token");
  if (!token) {
    console.warn("No hay token ni usuario en localStorage");
    avatarImg.src = DEFAULT_AVATAR;
    infoEl.textContent = "Usuario no identificado";
    return;
  }

  let payload = null;
  try {
    const base64Url = token.split(".")[1];
    const base64 = base64Url.replace(/-/g, "+").replace(/_/g, "/");
    const jsonPayload = decodeURIComponent(
      atob(base64)
        .split("")
        .map(function (c) {
          return "%" + ("00" + c.charCodeAt(0).toString(16)).slice(-2);
        })
        .join("")
    );
    payload = JSON.parse(jsonPayload);
  } catch (e) {
    console.error("Error decodificando el token JWT:", e);
  }

  console.log("Payload token (asider):", payload);

  if (!payload || !payload.data) {
    avatarImg.src = DEFAULT_AVATAR;
    infoEl.textContent = "Usuario no identificado";
    return;
  }

  const data = payload.data;

  const nombre = data.username || data.correo || "Usuario";
  const rol    = data.rol || "Sin rol";
  infoEl.textContent = `Usuario: ${nombre} | Rol: ${rol}`;

  let nuevaSrc = DEFAULT_AVATAR;

  if (data.avatar_filename && data.avatar_filename.trim() !== "") {
    nuevaSrc = BASE_AVATAR_PATH + data.avatar_filename.trim();
  } else if (data.avatar_color) {
    avatarImg.style.borderColor = data.avatar_color;
  }

  avatarImg.onerror = function () {
    console.warn("No se pudo cargar el avatar, usando por defecto (token).");
    avatarImg.src = DEFAULT_AVATAR;
  };

  avatarImg.src = nuevaSrc;
  console.log("Avatar elegido (token):", nuevaSrc);
})();

/* 🔹 Mantener opción del menú marcada (activo) */
document.addEventListener("DOMContentLoaded", function () {
  const menuItems = document.querySelectorAll("li[data-link]");
  const saved = localStorage.getItem("schedule_menu_active");

  // Restaurar elemento activo
  if (saved) {
    const activeItem = document.querySelector(`li[data-link="${saved}"]`);
    if (activeItem) {
      activeItem.classList.add("menu-active");
    }
  }

  // Asignar click a cada item
  menuItems.forEach(item => {
    item.addEventListener("click", function () {
      menuItems.forEach(li => li.classList.remove("menu-active"));
      item.classList.add("menu-active");
      localStorage.setItem("schedule_menu_active", item.getAttribute("data-link"));
    });
  });
});
</script>
