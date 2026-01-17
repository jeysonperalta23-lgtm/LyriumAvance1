<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Lyrium Biomarketplace</title>

  <!-- TailwindCSS -->
  <script src="https://cdn.tailwindcss.com"></script>

  <!-- Google Fonts: Outfit -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100..900&display=swap" rel="stylesheet">

  <!-- Iconos Phosphor -->
  <script src="https://unpkg.com/phosphor-icons"></script>

  <!-- JS general -->
  <script src="utils/js/3.4.16.js?v=<?php echo time(); ?>"></script>

  <!-- CSS -->
  <link href="utils/css/index.css?v=<?php echo time(); ?>" rel="stylesheet" />
  <link href="utils/css/banner_superior.css?v=<?php echo time(); ?>" rel="stylesheet" />
  <link href="utils/css/filters.css?v=<?php echo time(); ?>" rel="stylesheet" />
  <link href="utils/css/live-search.css?v=<?php echo time(); ?>" rel="stylesheet" />
  <link href="utils/css/ui-components.css?v=<?php echo time(); ?>" rel="stylesheet" />
  <link href="utils/css/index-dark-mode.css?v=<?php echo time(); ?>" rel="stylesheet" />
  <link href="utils/css/contactanos.css?v=<?php echo time(); ?>" rel="stylesheet" />
  <link rel="icon" type="image/png" href="img/logo.png?v=<?php echo time(); ?>" />

  <!-- Scripts de Filtrado y Teclado -->
  <script src="utils/js/filters.js?v=<?php echo time(); ?>" defer></script>
  <script src="utils/js/keyboard.js?v=<?php echo time(); ?>" defer></script>
  <script src="js/live-search.js?v=<?php echo time(); ?>" defer></script>
   <script src="js/index.js?v=<?php echo time(); ?>" defer></script>
  
  <link rel='stylesheet' id='dashicons-css'
    href='bioblog/lyriumbiomarketplace.com/wp-includes/css/dashicons.mind4d0.css?ver=6.9' media='all' />
  <link rel='stylesheet' id='dokan-shipping-block-checkout-support-css'
    href='bioblog/lyriumbiomarketplace.com/wp-content/plugins/dokan-pro/assets/blocks/shipping/index9df7.css?ver=9799136811508b406b2e'
    media='all' />
  <link rel='stylesheet' id='dokan-fontawesome-css'
    href='bioblog/lyriumbiomarketplace.com/wp-content/plugins/dokan-lite/assets/vendors/font-awesome/css/font-awesome.min50fa.css?ver=4.2.1'
    media='all' />
  <link rel='stylesheet' id='astra-theme-css-css'
    href='bioblog/lyriumbiomarketplace.com/wp-content/themes/astra/assets/css/minified/main.mind5b9.css?ver=4.11.17'
    media='all' />
  <link rel='stylesheet' id='hfe-widgets-style-css'
    href='bioblog/lyriumbiomarketplace.com/wp-content/plugins/header-footer-elementor/inc/widgets-css/frontend4250.css?ver=2.7.0'
    media='all' />
  <link rel='stylesheet' id='hfe-woo-product-grid-css'
    href='bioblog/lyriumbiomarketplace.com/wp-content/plugins/header-footer-elementor/inc/widgets-css/woo-products4250.css?ver=2.7.0'
    media='all' />
  <link rel='stylesheet' id='pafe-extension-style-free-css'
    href='bioblog/lyriumbiomarketplace.com/wp-content/plugins/piotnet-addons-for-elementor/assets/css/minify/extension.min2a64.css?ver=2.4.36'
    media='all' />
  <link rel='stylesheet' id='rt-fontawsome-css'
    href='bioblog/lyriumbiomarketplace.com/wp-content/plugins/the-post-grid/assets/vendor/font-awesome/css/font-awesome.min0f63.css?ver=7.8.8'
    media='all' />
  <link rel='stylesheet' id='rt-tpg-css'
    href='bioblog/lyriumbiomarketplace.com/wp-content/plugins/the-post-grid/assets/css/thepostgrid.min0f63.css?ver=7.8.8'
    media='all' />
  <link rel='stylesheet' id='dokan-timepicker-css'
    href='bioblog/lyriumbiomarketplace.com/wp-content/plugins/dokan-lite/assets/vendors/jquery-ui/timepicker/timepicker.min50fa.css?ver=4.2.1'
    media='all' />
  <link rel='stylesheet' id='hfe-style-css'
    href='bioblog/lyriumbiomarketplace.com/wp-content/plugins/header-footer-elementor/assets/css/header-footer-elementor4250.css?ver=2.7.0'
    media='all' />
  <link rel='stylesheet' id='elementor-frontend-css'
    href='bioblog/lyriumbiomarketplace.com/wp-content/plugins/elementor/assets/css/frontend.min37de.css?ver=3.33.4'
    media='all' />
  <link rel='stylesheet' id='swiper-css'
    href='bioblog/lyriumbiomarketplace.com/wp-content/plugins/elementor/assets/lib/swiper/v8/css/swiper.min94a4.css?ver=8.4.5'
    media='all' />
  <link rel='stylesheet' id='e-swiper-css'
    href='bioblog/lyriumbiomarketplace.com/wp-content/plugins/elementor/assets/css/conditionals/e-swiper.min37de.css?ver=3.33.4'
    media='all' />
  <link rel='stylesheet' id='upk-snog-slider-css'
    href='bioblog/lyriumbiomarketplace.com/wp-content/plugins/ultimate-post-kit/assets/css/upk-snog-sliderd1c8.css?ver=4.0.17'
    media='all' />
  <link rel='stylesheet' id='upk-site-css'
    href='bioblog/lyriumbiomarketplace.com/wp-content/plugins/ultimate-post-kit/assets/css/upk-sited1c8.css?ver=4.0.17'
    media='all' />
</head>

<body class="bg-gray-50 min-h-screen flex flex-col overflow-x-hidden">

  <!-- Línea superior -->
  <div class="w-full h-1 bg-lime-400"></div>

  <!-- BANNER CON TEXTO EN CARRUSEL -->
  <div class="top-banner-lyrium">
    <div class="max-w-7xl mx-auto px-4 top-banner-inner">
      <div class="marquee-track">
        <span class="marquee-item">
          EL PRIMER BIOMARKETPLACE ONLINE DEL PERÚ: MÉDICO, ORGÁNICO Y NATURAL, CON EMPRESAS SELECCIONADAS PARA TU
          BIENESTAR TOTAL.
        </span>
        <span class="marquee-item">
          EL PRIMER BIOMARKETPLACE ONLINE DEL PERÚ: MÉDICO, ORGÁNICO Y NATURAL, CON EMPRESAS SELECCIONADAS PARA TU
          BIENESTAR TOTAL.
        </span>
      </div>
    </div>
  </div>

  <!-- HEADER -->
  <header class="bg-white shadow-sm sticky top-0 z-50">

    <!-- FILA 1: LOGO + SESIÓN/CARRITO + HAMBURGUESA -->
    <div class="max-w-7xl mx-auto flex items-center justify-between px-4 py-3 gap-6">
      <!-- LOGO -->
      <a href="index.php" class="flex items-center gap-3">
        <img src="img/logo.png?v=<?php echo time(); ?>" alt="Logo" class="h-16 md:h-20 w-auto" />
      </a>

      <div class="flex items-center gap-4">
        <!-- Sesión / Carrito (DESKTOP) -->
        <div class="hidden md:flex items-center gap-5 text-xs lg:text-[13px] text-sky-600">
          <a href="login.php" class="flex items-center gap-1 hover:underline">
            <i class="ph-user-circle text-[18px]"></i>
            <span class="whitespace-nowrap">Iniciar Sesión | Registrarse</span>
          </a>

          <a href="#" class="flex items-center gap-1 hover:underline">
            <i class="ph-shopping-cart text-[18px]"></i>
            <span>Carrito</span>
            <span class="bg-sky-500 text-white text-[11px] rounded-full px-2 py-0.5">0</span>
          </a>
        </div>

        <!-- HAMBURGUESA (MÓVIL/TABLET) -->
        <button id="btnMenu" class="lg:hidden text-3xl text-sky-600" aria-label="Menú">
          <i class="ph-list"></i>
        </button>
      </div>
    </div>

    <!-- OVERLAY PARA MENÚ MÓVIL -->
    <div id="mobileMenuOverlay" class="fixed inset-0 bg-black bg-opacity-50 z-[100] hidden transition-opacity duration-300 lg:hidden"></div>

    <!-- MENÚ MÓVIL LATERAL (ESTILO ORGANA) -->
    <div id="mobileMenu" class="fixed top-0 left-0 h-full w-[280px] bg-white z-[101] transform -translate-x-full transition-transform duration-300 ease-out shadow-2xl lg:hidden overflow-y-auto">
      <!-- Header del menú con logo -->
      <div class="p-4 border-b border-gray-100">
        <div class="flex items-center justify-between">
          <img src="img/logo.png?v=<?php echo time(); ?>" alt="Lyrium Logo" class="h-12 w-auto" />
          <button id="closeMobileMenu" class="text-gray-600 hover:text-gray-800 text-2xl" aria-label="Cerrar menú">
            <i class="ph-x"></i>
          </button>
        </div>
      </div>

      <!-- Sección Inicio -->
      <div class="px-4 py-3 border-b border-gray-100">
        <a href="index.php" class="flex items-center gap-3 text-gray-800 font-medium hover:text-green-600 transition">
          <span class="text-sm">Inicio</span>
        </a>
      </div>

      <!-- Sesión y Carrito -->
      <div class="px-4 py-3 border-b border-gray-100 space-y-3">
        <a href="login.php" class="flex items-center gap-2 text-sky-600 font-semibold hover:text-sky-700 transition">
          <i class="ph-user-circle text-lg"></i>
          <span class="text-sm">Iniciar Sesión | Registrarse</span>
        </a>

        <a href="#" class="flex items-center gap-2 text-sky-600 font-semibold hover:text-sky-700 transition">
          <i class="ph-shopping-cart text-lg"></i>
          <span class="text-sm">Carrito</span>
          <span class="bg-sky-500 text-white text-[11px] rounded-full px-2 py-0.5">0</span>
        </a>
      </div>

      <!-- Categorías principales (igual que desktop) -->
      <div class="py-2">
        <a href="#" class="flex items-center justify-between px-4 py-3 hover:bg-gray-50 transition group">
          <div class="flex items-center gap-3">
            <div class="w-6 h-6 flex items-center justify-center">
              <i class="ph-shopping-bag text-xl text-green-600 group-hover:scale-110 transition-transform"></i>
            </div>
            <span class="text-gray-800 text-sm font-medium">PRODUCTOS</span>
          </div>
          <i class="ph-caret-right text-gray-400 text-sm"></i>
        </a>

        <a href="#" class="flex items-center justify-between px-4 py-3 hover:bg-gray-50 transition group">
          <div class="flex items-center gap-3">
            <div class="w-6 h-6 flex items-center justify-center">
              <i class="ph-headset text-xl text-green-600 group-hover:scale-110 transition-transform"></i>
            </div>
            <span class="text-gray-800 text-sm font-medium">SERVICIOS</span>
          </div>
          <i class="ph-caret-right text-gray-400 text-sm"></i>
        </a>

        <a href="nosotros.php" class="flex items-center justify-between px-4 py-3 hover:bg-gray-50 transition group">
          <div class="flex items-center gap-3">
            <div class="w-6 h-6 flex items-center justify-center">
              <i class="ph-info text-xl text-green-600 group-hover:scale-110 transition-transform"></i>
            </div>
            <span class="text-gray-800 text-sm font-medium">NOSOTROS</span>
          </div>
          <i class="ph-caret-right text-gray-400 text-sm"></i>
        </a>

        <a href="registrar-tienda/login.php" class="flex items-center justify-between px-4 py-3 hover:bg-gray-50 transition group">
          <div class="flex items-center gap-3">
            <div class="w-6 h-6 flex items-center justify-center">
              <i class="ph-storefront text-xl text-green-600 group-hover:scale-110 transition-transform"></i>
            </div>
            <span class="text-gray-800 text-sm font-medium">REGISTRA TU TIENDA</span>
          </div>
          <i class="ph-caret-right text-gray-400 text-sm"></i>
        </a>

        <a href="tiendasregistradas.php" class="flex items-center justify-between px-4 py-3 hover:bg-gray-50 transition group">
          <div class="flex items-center gap-3">
            <div class="w-6 h-6 flex items-center justify-center">
              <i class="ph-buildings text-xl text-green-600 group-hover:scale-110 transition-transform"></i>
            </div>
            <span class="text-gray-800 text-sm font-medium">TIENDAS REGISTRADAS</span>
          </div>
          <i class="ph-caret-right text-gray-400 text-sm"></i>
        </a>

        <a href="contactanos.php" class="flex items-center justify-between px-4 py-3 hover:bg-gray-50 transition group">
          <div class="flex items-center gap-3">
            <div class="w-6 h-6 flex items-center justify-center">
              <i class="ph-phone-call text-xl text-green-600 group-hover:scale-110 transition-transform"></i>
            </div>
            <span class="text-gray-800 text-sm font-medium">CONTÁCTANOS</span>
          </div>
          <i class="ph-caret-right text-gray-400 text-sm"></i>
        </a>

        <a href="bioblog.php" class="flex items-center justify-between px-4 py-3 hover:bg-gray-50 transition group">
          <div class="flex items-center gap-3">
            <div class="w-6 h-6 flex items-center justify-center">
              <i class="ph-newspaper text-xl text-green-600 group-hover:scale-110 transition-transform"></i>
            </div>
            <span class="text-gray-800 text-sm font-medium">BIOBLOG</span>
          </div>
          <i class="ph-caret-right text-gray-400 text-sm"></i>
        </a>

        <a href="bioforo.php" class="flex items-center justify-between px-4 py-3 hover:bg-gray-50 transition group">
          <div class="flex items-center gap-3">
            <div class="w-6 h-6 flex items-center justify-center">
              <i class="ph-chats-circle text-xl text-green-600 group-hover:scale-110 transition-transform"></i>
            </div>
            <span class="text-gray-800 text-sm font-medium">BIOFORO</span>
          </div>
          <i class="ph-caret-right text-gray-400 text-sm"></i>
        </a>
      </div>
    </div>

    <!-- Botón flotante de WhatsApp (solo móvil) -->
    <a href="https://wa.me/51999999999?text=Hola,%20tengo%20una%20consulta" target="_blank" class="fixed bottom-6 right-6 z-50 lg:hidden whatsapp-btn">
      <div class="bg-green-500 text-white rounded-full w-14 h-14 flex items-center justify-center shadow-lg hover:bg-green-600 transition-all duration-300 hover:scale-110 whatsapp-pulse">
        <i class="ph-whatsapp-logo text-3xl"></i>
      </div>
    </a>

    <!-- MENÚ DESKTOP -->
    <div class="border-t border-gray-100">
      <nav
        class="max-w-7xl mx-auto px-4 py-2 hidden lg:flex items-center justify-center gap-6 text-[13px] font-medium text-gray-800 tracking-tight">

        <!-- PRODUCTOS (MEGA MENU) -->
        <div class="relative">
          <button id="productosTrigger" type="button"
            class="flex items-center gap-1 hover:text-sky-500 transition whitespace-nowrap">
            <i class="ph-shopping-bag text-[17px]"></i>
            PRODUCTOS
            <i class="ph-caret-down text-[11px]"></i>
          </button>

          <div id="menu-productos" class="fixed left-1/2 -translate-x-1/2 mt-3 w-[min(1100px,95vw)]
                      bg-white shadow-2xl rounded-3xl border border-sky-50
                      opacity-0 pointer-events-none translate-y-2 transition-all duration-150 ease-out
                      z-[99999]">
            <div class="grid grid-cols-12 gap-0 overflow-hidden rounded-3xl">
              <aside class="col-span-12 md:col-span-4 lg:col-span-3 bg-gray-50/70 border-r border-gray-100 p-3">
                <button type="button"
                  class="w-full flex items-center justify-between px-4 py-3 rounded-full bg-lime-500 text-white text-sm font-semibold"
                  id="catHeaderBtn">
                  <span id="catHeaderTitle">Bebés y recién nacidos</span>
                  <i class="ph-caret-down text-base"></i>
                </button>

                <ul id="megaCats" class="mt-3 space-y-1 text-sm"></ul>
              </aside>

              <section class="col-span-12 md:col-span-8 lg:col-span-9 p-5">
                <div id="megaIcons" class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4"></div>
                <div class="mt-5 h-px bg-lime-400/80"></div>
                <div id="megaCols" class="mt-5 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-6 text-sm"></div>
              </section>
            </div>
          </div>
        </div>

        <!-- SERVICIOS (MEGA MENU) -->
        <div class="relative">
          <button id="serviciosTrigger" type="button"
            class="flex items-center gap-1 hover:text-sky-500 transition whitespace-nowrap">
            <i class="ph-headset text-[17px]"></i>
            SERVICIOS
            <i class="ph-caret-down text-[11px]"></i>
          </button>

          <div id="menu-servicios" class="fixed left-1/2 -translate-x-1/2 mt-3 w-[min(1200px,96vw)]
                      bg-white shadow-2xl rounded-3xl border border-sky-50
                      opacity-0 pointer-events-none translate-y-2 transition-all duration-150 ease-out
                      z-[99999]">
            <div class="grid grid-cols-12 overflow-hidden rounded-3xl">
              <aside class="col-span-12 md:col-span-4 lg:col-span-3 bg-gray-50/80 border-r p-4">
                <ul id="servCats" class="space-y-1 text-sm"></ul>
              </aside>

              <section class="col-span-12 md:col-span-8 lg:col-span-9 p-6">
                <div id="servIcons" class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4"></div>
                <div class="my-5 h-px bg-lime-400"></div>
                <div id="servCols" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-6 text-sm"></div>
              </section>
            </div>
          </div>
        </div>

        <a href="nosotros.php" class="flex items-center gap-1 hover:text-sky-500 transition whitespace-nowrap">
          <i class="ph-info text-[17px]"></i> NOSOTROS
        </a>

        <a href="registrar-tienda/login.php" class="flex items-center gap-1 hover:text-sky-500 transition whitespace-nowrap">
          <i class="ph-storefront text-[17px]"></i>
          <span class="whitespace-nowrap">REGISTRA TU TIENDA</span>
        </a>

        <a href="tiendasregistradas.php"
          class="flex items-center gap-1 hover:text-sky-500 transition whitespace-nowrap">
          <i class="ph-buildings text-[17px]"></i>
          <span class="whitespace-nowrap">TIENDAS REGISTRADAS</span>
        </a>

        <a href="contactanos.php" class="flex items-center gap-1 hover:text-sky-500 transition whitespace-nowrap">
          <i class="ph-phone-call text-[17px]"></i> CONTÁCTANOS
        </a>

        <a href="bioblog.php" class="flex items-center gap-1 hover:text-sky-500 transition whitespace-nowrap">
          <i class="ph-newspaper text-[17px]"></i> BIOBLOG
        </a>

        <a href="bioforo.php" class="flex items-center gap-1 hover:text-sky-500 transition whitespace-nowrap">
          <i class="ph-chats-circle text-[17px]"></i> BIOFORO
        </a>
      </nav>
    </div>

  </header>