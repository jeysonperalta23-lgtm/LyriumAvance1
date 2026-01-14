// Script para alternar el menú en dispositivos pequeños
const sidebar = document.getElementById('sidebar');
const mainContent = document.getElementById('main-content');
const dropdownButton = document.getElementById('dropdownButton');
const dropdownMenu = document.getElementById('dropdownMenu');
const icon = document.getElementById('sidebar-menu-icon'); // Usando el nuevo id
let sidebarVisible = true;

// Alternar la visibilidad del dropdown en dispositivos pequeños
document.addEventListener('click', function(event) {
  const isClickInside = dropdownButton.contains(event.target) || dropdownMenu.contains(event.target);
  if (!isClickInside) {
    dropdownMenu.classList.add('hidden');
  }
});
dropdownButton.addEventListener('click', function(event) {
  event.stopPropagation(); // Evita que cierre el dropdown inmediatamente
  dropdownMenu.classList.toggle('hidden');
});

// Función para ajustar la visibilidad del menú en función del tamaño de la ventana
function toggleDropdownVisibility() {
  const dropdownContainer = document.getElementById('dropdownContainer');
  if (window.innerWidth < 768) {
    dropdownContainer.classList.add('hidden'); // Ocultar en pantallas pequeñas
  } else {
    dropdownContainer.classList.remove('hidden'); // Mostrar en pantallas grandes
  }
}

// Ejecutar al cargar y al redimensionar
window.addEventListener('load', toggleDropdownVisibility);
window.addEventListener('resize', toggleDropdownVisibility);

// Alternar la visibilidad de la barra lateral
function toggleSidebar() {
  sidebarVisible = !sidebarVisible;
  sidebar.classList.toggle('hidden', !sidebarVisible);
  sidebar.classList.toggle('-translate-x-full');

  if (sidebarVisible) {
    sidebar.classList.remove('-translate-x-full');
    sidebar.classList.add('translate-x-0');
  } else {
    sidebar.classList.remove('translate-x-0');
    sidebar.classList.add('-translate-x-full');
  }

  // Ajustar margen izquierdo del contenido principal
  mainContent.classList.toggle('ml-1', !sidebarVisible);

  // Cambiar ícono correctamente
  icon.classList.toggle('fa-bars', !sidebarVisible);
  icon.classList.toggle('fa-minus', sidebarVisible);
}

// Detectar clics fuera de la barra lateral y ocultarla en pantallas pequeñas
document.addEventListener('click', function(event) {
  const menuBtn = document.querySelector('button[onclick="toggleSidebar()"]');
  if (window.innerWidth < 768 && !sidebar.contains(event.target) && !menuBtn.contains(event.target)) {
    sidebar.classList.add('-translate-x-full');
    sidebarVisible = false; // Ocultar sidebar en pantallas pequeñas
    icon.classList.add('fa-bars');
    icon.classList.remove('fa-minus');
  }
});

// Función para alternar los submenús
function toggleSubmenu(id) {
  const submenu = document.getElementById(id);
  const icon = document.getElementById('icon-' + id);
  submenu.classList.toggle('hidden');
  icon.classList.toggle('fa-chevron-down');
  icon.classList.toggle('fa-chevron-up');
}

// Ajustar la barra lateral para pantallas pequeñas
function ajustarSidebarResponsive() {
  if (window.innerWidth < 768) {
    // Pantallas pequeñas
    sidebar.classList.add('w-full', 'h-screen', '-translate-x-full');
    sidebar.classList.remove('w-64', 'translate-x-0');
    sidebarVisible = false;

    // Icono hamburguesa
    if (icon) {
      icon.classList.add('fa-bars');
      icon.classList.remove('fa-minus');
    }
  } else {
    // Pantallas grandes
    sidebar.classList.remove('w-full', 'h-screen', 'hidden', '-translate-x-full');
    sidebar.classList.add('w-64', 'translate-x-0');
    sidebarVisible = true;

    // Icono menos
    if (icon) {
      icon.classList.remove('fa-bars');
      icon.classList.add('fa-minus');
    }
  }
}

// Ejecutar al cargar y al redimensionar
window.addEventListener('load', ajustarSidebarResponsive);
window.addEventListener('resize', ajustarSidebarResponsive);

// Actualizar la visibilidad del elemento de alternar la barra lateral
function updateToggleSidebarVisibility() {
  const toggleItem = document.getElementById("toggleSidebarItem");
  if (window.innerWidth < 768) {
    sidebarVisible = false;
    toggleItem.classList.remove("hidden");
  } else {
    sidebarVisible = true;
    toggleItem.classList.add("hidden");
  }
}

// Ejecutar al cargar la página y al redimensionar
window.addEventListener("DOMContentLoaded", updateToggleSidebarVisibility);
window.addEventListener("resize", updateToggleSidebarVisibility);

// Script para alternar la clase 'active' al <li> completo cuando se haga clic en un <a>
const menuLinks = document.querySelectorAll('#sidebar a');

menuLinks.forEach(link => {
  link.addEventListener('click', function() {
    // Quitar 'active' de todos los <li>
    document.querySelectorAll('#sidebar ul li').forEach(li => li.classList.remove('active'));

    // Agregar 'active' al <li> padre del <a> clicado
    this.closest('li').classList.add('active');
  });
});

// Función para cambiar el cursor a 'pointer'
function addHandCursor(event) {
  event.currentTarget.style.cursor = 'pointer';
}

// Función para restaurar el cursor
function restoreCursor(event) {
  event.currentTarget.style.cursor = '';
}

// Aplicar los eventos de hover a todos los <li>
document.querySelectorAll('#sidebar ul li').forEach(item => {
  item.addEventListener('mouseenter', addHandCursor);
  item.addEventListener('mouseleave', restoreCursor);
});

