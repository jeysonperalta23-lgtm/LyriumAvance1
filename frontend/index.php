<?php
session_start();
session_destroy();
?>

<?php
require_once __DIR__ . '/../backend/config/Conexion.php';
// Obtener categorías reales para los filtros
try {
  $stmtCats = $conn->query("SELECT id, nombre FROM productos_categorias WHERE estado = 1 ORDER BY nombre ASC");
  $categoriasReales = $stmtCats->fetchAll();
} catch (PDOException $e) {
  $categoriasReales = [];
}
include 'header.php';
?>

<!-- BUSCADOR HEADER -->
<!-- BUSCADOR LYRIUM DESIGN (DROPDOWN MODIFIED) -->
<div class="border-t border-gray-100 bg-white z-10 shadow-sm">
  <div class="max-w-7xl mx-auto px-4 py-4">
    <form action="buscar.php" method="GET" class="w-full relative" id="searchForm">
      <input type="hidden" name="category" id="filterCategory" value="">
      <input type="hidden" name="max_price" id="filterMaxPrice" value="2000">

      <div class="relative w-full">
        <!-- Input Principal -->
        <input type="text" name="q" id="searchInput" placeholder="¿Qué buscas para tu salud?"
          class="w-full h-12 md:h-14 pl-4 pr-36 md:pl-6 md:pr-56 rounded-full border border-gray-200 text-xs sm:text-sm md:text-base focus:outline-none focus:ring-2 focus:ring-sky-400 focus:border-sky-400 transition-all shadow-inner bg-white"
          autocomplete="off" />

        <!-- Botones de Acción -->
        <div class="absolute right-1 top-1 bottom-1 flex items-center gap-1 md:gap-2">
          <button type="button" id="voiceBtn"
            class="h-full w-10 md:w-14 rounded-full bg-sky-500 hover:bg-sky-600 text-white font-semibold flex items-center justify-center transition-all duration-200"
            title="Buscar por voz">
            <i class="ph-microphone text-lg md:text-xl"></i>
          </button>

          <button type="button" id="btnFiltros"
            class="flex h-full w-10 md:w-auto md:px-7 rounded-full bg-sky-500 text-white hover:bg-sky-600 font-bold items-center justify-center gap-2 transition-all border border-sky-200">
            <i class="ph-funnel text-xl"></i>
            <span class="hidden md:inline">Filtros</span>
          </button>

          <button type="submit"
            class="h-full w-10 md:w-auto md:px-7 rounded-full  bg-sky-500 text-white hover:bg-sky-600 font-bold flex items-center justify-center gap-2 transition-all shadow-md">
            <i class="ph-magnifying-glass text-lg md:text-xl"></i>
            <span class="hidden md:inline">Buscar</span>
          </button>
        </div>
      </div>

      <!-- DROPDOWN DE FILTROS (DISEÑO PREMIUM) -->
      <div id="filterDropdown"
        class="absolute top-[calc(100%+1rem)] left-0 right-0 bg-white/95 backdrop-blur-2xl border border-gray-200 rounded-[2.5rem] shadow-[0_30px_70px_rgba(0,0,0,0.15)] p-0 z-[100] opacity-0 translate-y-[-20px] pointer-events-none transition-all duration-500 ease-[cubic-bezier(0.34,1.56,0.64,1)] [&.active]:opacity-100 [&.active]:translate-y-0 [&.active]:pointer-events-auto overflow-hidden">

        <!-- Indicadores de estado actual -->
        <div class="bg-gray-50/80 px-8 py-5 border-b border-gray-100">
          <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
            <div
              class="js-indicator-item bg-white p-2.5 rounded-2xl text-center text-[10px] font-bold text-gray-500 shadow-sm border border-gray-100 flex flex-col items-center gap-1 transition-all [&.active]:bg-sky-50 [&.active]:border-sky-300 [&.active]:text-sky-700"
              data-indicator="buscador">
              <i class="ph-magnifying-glass text-lg text-sky-400"></i>
              <span class="indicator-val">Buscador</span>
            </div>
            <div
              class="js-indicator-item bg-white p-2.5 rounded-xl text-center text-[10px] font-bold text-gray-500 shadow-sm border border-gray-100 flex flex-col items-center gap-1 transition-all [&.active]:bg-sky-50 [&.active]:border-sky-300 [&.active]:text-sky-700"
              data-indicator="teclado">
              <i class="ph-keyboard text-lg text-sky-400"></i>
              <span class="indicator-val">Teclado: OFF</span>
            </div>
            <div
              class="js-indicator-item bg-white p-2.5 rounded-xl text-center text-[10px] font-bold text-gray-500 shadow-sm border border-gray-100 flex flex-col items-center gap-1 transition-all [&.active]:bg-sky-50 [&.active]:border-sky-300 [&.active]:text-sky-700"
              data-indicator="precio">
              <i class="ph-currency-circle-dollar text-lg text-sky-400"></i>
              <span class="indicator-val">Precio</span>
            </div>
            <div
              class="js-indicator-item bg-white p-2.5 rounded-xl text-center text-[10px] font-bold text-gray-500 shadow-sm border border-gray-100 flex flex-col items-center gap-1 transition-all [&.active]:bg-sky-50 [&.active]:border-sky-300 [&.active]:text-sky-700"
              data-indicator="categoria">
              <i class="ph-tag text-lg text-sky-400"></i>
              <span class="indicator-val">Categoría</span>
            </div>
            <div
              class="js-indicator-item bg-white p-2.5 rounded-xl text-center text-[10px] font-bold text-gray-500 shadow-sm border border-gray-100 flex flex-col items-center gap-1 transition-all [&.active]:bg-sky-50 [&.active]:border-sky-300 [&.active]:text-sky-700"
              data-indicator="oferta">
              <i class="ph-sparkle text-lg text-sky-400"></i>
              <span class="indicator-val">Ofertas</span>
            </div>
          </div>
        </div>

        <div class="p-8">
          <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <!-- Herramientas -->
            <div class="filter-card p-5 bg-gray-50/50">
              <p class="text-[11px] uppercase tracking-wider text-muted font-black inline-flex items-center gap-2 mb-4">
                <i class="ph-gear-six icon-brand text-lg"></i> Utilidades
              </p>
              <div class="flex items-center justify-between p-4 bg-white rounded-2xl border border-gray-100 shadow-sm">
                <div class="flex items-center gap-3">
                  <div class="w-10 h-10 rounded-xl bg-sky-100 flex items-center justify-center text-sky-600 shadow-sm">
                    <i class="ph-keyboard-bold text-xl"></i>
                  </div>
                  <div>
                    <p class="text-xs font-bold text-gray-800">Teclado Digital</p>
                    <p class="text-[10px] text-gray-400 italic">Escribe sin teclado físico</p>
                  </div>
                </div>
                <label class="relative inline-block w-11 h-6 cursor-pointer">
                  <input type="checkbox" id="keyboardToggle" class="sr-only peer">
                  <span
                    class="absolute inset-0 bg-gray-200 rounded-full transition-colors peer-checked:bg-sky-500"></span>
                  <span
                    class="absolute left-1 bottom-1 bg-white w-4 h-4 rounded-full transition-transform peer-checked:translate-x-5 shadow-sm"></span>
                </label>
              </div>
              <p class="mt-4 text-[10px] text-gray-400 leading-relaxed px-1">
                Activa el teclado digital si estás usando una pantalla táctil o no tienes un teclado disponible.
              </p>
            </div>

            <!-- Categorías -->
            <div class="filter-card p-5 bg-gray-50/50">
              <p class="text-[11px] uppercase tracking-wider text-muted font-black inline-flex items-center gap-2 mb-4">
                <i class="ph-tag icon-brand text-lg text-sky-500"></i> Categorías
              </p>
              <div class="flex flex-wrap gap-2 max-h-[120px] overflow-y-auto pr-2 custom-scrollbar">
                <?php if (!empty($categoriasReales)): ?>
                  <?php foreach ($categoriasReales as $cat): ?>
                    <button type="button"
                      class="filter-item-chip px-3.5 py-2.5 rounded-full bg-white border border-gray-100 text-[11px] font-bold text-gray-600 hover:border-sky-300 hover:bg-sky-50 transition-all [&.active]:bg-sky-500 [&.active]:text-white [&.active]:border-sky-500 [&.active]:shadow-md"
                      data-type="categoria" data-id="<?= $cat['id'] ?>">
                      <?= htmlspecialchars($cat['nombre']) ?>
                    </button>
                  <?php endforeach; ?>
                <?php endif; ?>
              </div>

              <!-- Ofertas y Promociones -->
              <p
                class="text-[11px] uppercase tracking-wider text-muted font-black inline-flex items-center gap-2 mt-6 mb-4">
                <i class="ph-sparkle icon-brand text-lg text-sky-500"></i> Ofertas Especiales
              </p>
              <div class="flex flex-wrap gap-2">
                <button type="button"
                  class="filter-item-chip px-3.5 py-2.5 rounded-full bg-white border border-gray-100 text-[11px] font-bold text-sky-600 hover:border-sky-300 hover:bg-sky-50 transition-all [&.active]:bg-sky-500 [&.active]:text-white [&.active]:border-sky-500 [&.active]:shadow-md"
                  data-type="oferta" data-id="descuento">
                  Descuentos
                </button>
                <button type="button"
                  class="filter-item-chip px-3.5 py-2.5 rounded-full bg-white border border-gray-100 text-[11px] font-bold text-sky-600 hover:border-sky-300 hover:bg-sky-50 transition-all [&.active]:bg-sky-500 [&.active]:text-white [&.active]:border-sky-500 [&.active]:shadow-md"
                  data-type="oferta" data-id="promocion">
                  Promociones
                </button>
                <button type="button"
                  class="filter-item-chip px-3.5 py-2.5 rounded-full bg-white border border-gray-100 text-[11px] font-bold text-sky-600 hover:border-sky-300 hover:bg-sky-50 transition-all [&.active]:bg-sky-500 [&.active]:text-white [&.active]:border-sky-500 [&.active]:shadow-md"
                  data-type="oferta" data-id="oferta">
                  Ofertas
                </button>
              </div>
            </div>

            <!-- Precio -->
            <div class="filter-card p-5 bg-gray-50/50 flex flex-col justify-between">
              <div>
                <div class="flex items-center justify-between mb-5 px-1">
                  <p class="text-[11px] uppercase tracking-wider text-muted font-black inline-flex items-center gap-2">
                    <i class="ph-currency-circle-dollar icon-brand text-lg"></i> Rango de Precio
                  </p>
                  <button id="btnPriceReset" type="button"
                    class="text-[10px] px-3 py-1.5 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-600 font-bold inline-flex items-center gap-1 transition-all">
                    <i class="ph-arrow-counter-clockwise"></i> Reset
                  </button>
                </div>

                <div class="range-wrap px-2">
                  <div class="range-row mb-4">
                    <span class="range-pill"><i class="ph-minus text-sky-500"></i> Min: <b id="lblPriceMin">S/
                        0</b></span>
                    <span class="range-pill"><i class="ph-plus text-sky-500"></i> Max: <b id="lblPriceMax">S/
                        2000</b></span>
                  </div>

                  <div class="range-sliders">
                    <div id="rangeFill" class="range-fill"></div>
                    <input id="priceMin" type="range" min="0" max="2000" step="1" value="0">
                    <input id="priceMax" type="range" min="0" max="2000" step="1" value="2000">
                  </div>

                  <p class="mt-4 text-[10px] text-gray-400 italic flex items-center gap-2">
                    <i class="ph-mouse"></i> Arrastra para filtrar (en tiempo real)
                  </p>
                </div>
              </div>

              <button type="button" id="applyFilters"
                class="mt-8 w-full py-4 rounded-2xl bg-sky-500 hover:bg-sky-600 text-white text-[11px] font-black tracking-[0.15em] transition-all shadow-lg active:scale-[0.97] uppercase">
                Aplicar Filtros
              </button>
            </div>

          </div>
        </div>
      </div>
    </form>
  </div>
</div>



<!-- CONTENIDO -->
<main class="flex-1 pb-6 md:pb-10 space-y-10 md:space-y-16">

  <!-- FLOR SUPERIOR  -->
  <div
    class="hidden md:block w-full elementor-element elementor-element-eab7742 ajuste-banner-top e-con-full e-flex wpr-particle-no wpr-jarallax-no wpr-parallax-no wpr-sticky-section-no e-con e-child"
    data-id="eab7742" data-element_type="container"
    data-settings="{&quot;background_background&quot;:&quot;classic&quot;}">
    <div class="elementor-element elementor-element-30914b6 elementor-widget elementor-widget-image" data-id="30914b6"
      data-element_type="widget" data-widget_type="image.default">
      <div class="elementor-widget-container">
        <img loading="lazy" decoding="async" width="1600" height="270"
          src="https://lyriumbiomarketplace.com/wp-content/uploads/2025/10/BANNER_SUPERIOR.png"
          class="attachment-full size-full wp-image-173873 w-full h-auto object-cover min-h-[80px]"
          alt="Banner Superior"
          srcset="https://lyriumbiomarketplace.com/wp-content/uploads/2025/10/BANNER_SUPERIOR.png 1600w, https://lyriumbiomarketplace.com/wp-content/uploads/2025/10/BANNER_SUPERIOR-300x51.png 300w, https://lyriumbiomarketplace.com/wp-content/uploads/2025/10/BANNER_SUPERIOR-1024x173.png 1024w, https://lyriumbiomarketplace.com/wp-content/uploads/2025/10/BANNER_SUPERIOR-768x130.png 768w, https://lyriumbiomarketplace.com/wp-content/uploads/2025/10/BANNER_SUPERIOR-1536x259.png 1536w, https://lyriumbiomarketplace.com/wp-content/uploads/2025/10/BANNER_SUPERIOR-600x101.png 600w, https://lyriumbiomarketplace.com/wp-content/uploads/2025/10/BANNER_SUPERIOR-64x11.png 64w"
          sizes="(max-width: 1600px) 100vw, 1600px">
      </div>
    </div>
  </div>

  <!-- BANNERS FULL WIDTH  -->
  <section class="!mt-0 w-full">
    <div class="relative overflow-hidden">
      <div id="bannersTrack" class="flex transition-transform duration-700">
        <div class="min-w-full">
          <picture>
            <source srcset="img/Inicio/movil/1.webp" media="(max-width: 767px)">
            <img src="img/Inicio/1.png" alt="Banner publicitario 1"
              class="w-full h-auto md:h-[420px] lg:h-[520px] object-cover cursor-pointer" data-modal="producto"
              data-title="Campaña Especial 1" data-price="" data-desc="Promoción especial de temporada." />
          </picture>
        </div>

        <div class="min-w-full">
          <picture>
            <source srcset="img/Inicio/movil/2.webp" media="(max-width: 767px)">
            <img src="img/Inicio/2.png" alt="Banner publicitario 2"
              class="w-full h-auto md:h-[420px] lg:h-[520px] object-cover cursor-pointer" data-modal="producto"
              data-title="Campaña Especial 2" data-price="" data-desc="Descubre nuestras ofertas exclusivas." />
          </picture>
        </div>

        <div class="min-w-full">
          <picture>
            <source srcset="img/Inicio/movil/3.webp" media="(max-width: 767px)">
            <img src="img/Inicio/3.png" alt="Banner publicitario 3"
              class="w-full h-auto md:h-[420px] lg:h-[520px] object-cover cursor-pointer" data-modal="producto"
              data-title="Campaña Especial 3" data-price="" data-desc="Novedades para tu bienestar." />
          </picture>
        </div>

        <div class="min-w-full">
          <picture>
            <source srcset="img/Inicio/movil/4.webp" media="(max-width: 767px)">
            <img src="img/Inicio/4.png" alt="Banner publicitario 4"
              class="w-full h-auto md:h-[420px] lg:h-[520px] object-cover cursor-pointer" data-modal="producto"
              data-title="Campaña Especial 4" data-price="" data-desc="Ofertas por tiempo limitado." />
          </picture>
        </div>

        <div class="min-w-full">
          <picture>
            <source srcset="img/Inicio/movil/5.webp" media="(max-width: 767px)">
            <img src="img/Inicio/5.png" alt="Banner publicitario 5"
              class="w-full h-auto md:h-[420px] lg:h-[520px] object-cover cursor-pointer" data-modal="producto"
              data-title="Campaña Especial 5" data-price="" data-desc="Marcas destacadas." />
          </picture>
        </div>

        <div class="min-w-full">
          <picture>
            <source srcset="img/Inicio/movil/6.webp" media="(max-width: 767px)">
            <img src="img/Inicio/6.png" alt="Banner publicitario 6"
              class="w-full h-auto md:h-[420px] lg:h-[520px] object-cover cursor-pointer" data-modal="producto"
              data-title="Campaña Especial 6" data-price="" data-desc="Compra fácil y segura." />
          </picture>
        </div>
      </div>

      <button id="bannersPrev"
        class="absolute left-4 top-1/2 -translate-y-1/2 bg-black/40 hover:bg-black/60 text-white w-10 h-10 rounded-full flex items-center justify-center"
        type="button">
        <i class="ph-caret-left-bold text-xl"></i>
      </button>

      <button id="bannersNext"
        class="absolute right-4 top-1/2 -translate-y-1/2 bg-black/40 hover:bg-black/60 text-white w-10 h-10 rounded-full flex items-center justify-center"
        type="button">
        <i class="ph-caret-right-bold text-xl"></i>
      </button>
    </div>
  </section>

  <!-- BANNER INFERIOR  -->
  <div
    class="w-full elementor-element elementor-element-eab7742 ajuste-banner-bottom e-con-full e-flex wpr-particle-no wpr-jarallax-no wpr-parallax-no wpr-sticky-section-no e-con e-child hidden md:block"
    data-id="276400e" data-element_type="widget" data-widget_type="image.default">
    <div class="elementor-widget-container">
      <img loading="lazy" decoding="async" width="1600" height="270"
        src="https://lyriumbiomarketplace.com/wp-content/uploads/2025/10/BANNER_INFERIOR.png"
        class="attachment-full size-full wp-image-173875 w-full h-auto object-cover min-h-[80px]" alt="Banner Inferior"
        srcset="https://lyriumbiomarketplace.com/wp-content/uploads/2025/10/BANNER_INFERIOR.png 1600w, https://lyriumbiomarketplace.com/wp-content/uploads/2025/10/BANNER_INFERIOR-300x51.png 300w, https://lyriumbiomarketplace.com/wp-content/uploads/2025/10/BANNER_INFERIOR-1024x173.png 1024w, https://lyriumbiomarketplace.com/wp-content/uploads/2025/10/BANNER_INFERIOR-768x130.png 768w, https://lyriumbiomarketplace.com/wp-content/uploads/2025/10/BANNER_INFERIOR-1536x259.png 1536w, https://lyriumbiomarketplace.com/wp-content/uploads/2025/10/BANNER_INFERIOR-600x101.png 600w, https://lyriumbiomarketplace.com/wp-content/uploads/2025/10/BANNER_INFERIOR-64x11.png 64w"
        sizes="(max-width: 1600px) 100vw, 1600px">
    </div>
  </div>

  <!-- CARRUSEL Categorías de servicios saludables -->
  <section class="!mt-0 space-y-4 md:space-y-6 max-w-7xl mx-auto px-4">
    <h2 class="text-xl md:text-2xl font-semibold text-gray-900">Categorías de servicios saludables</h2>

    <div class="relative overflow-hidden">
      <div id="categoriasLimpiezaTrack" class="flex transition-transform duration-700">
        <article
          class="cat-item-main rounded-3xl overflow-hidden shadow-md bg-sky-400/90 flex-shrink-0 w-full sm:w-[calc(33.333%-1rem)] mr-4">
          <div class="h-44 md:h-52 bg-sky-500/40">
            <img src="img/Inicio/1/1.png" alt="Categorías de servicios saludables"
              class="w-full h-full object-cover cursor-pointer" data-modal="producto"
              data-title="Categorías de servicios saludables" data-price=""
              data-desc="Explora servicios y tratamientos orientados al bienestar y la salud integral." />
          </div>
          <div class="bg-white py-3 text-center">
            <p class="text-[11px] md:text-xs font-semibold tracking-tight text-gray-900 uppercase">CATEGORÍAS DE
              SERVICIOS</p>
          </div>
        </article>

        <article
          class="cat-item-main rounded-3xl overflow-hidden shadow-md bg-sky-400/90 flex-shrink-0 w-full sm:w-[calc(33.333%-1rem)] mr-4">
          <div class="h-44 md:h-52 bg-sky-500/40">
            <img src="img/Inicio/1/2.png" alt="Las mejores ofertas de productos"
              class="w-full h-full object-cover cursor-pointer" data-modal="producto"
              data-title="Las mejores ofertas de productos" data-price=""
              data-desc="Encuentra productos saludables con descuentos y promociones especiales." />
          </div>
          <div class="bg-white py-3 text-center">
            <p class="text-[11px] md:text-xs font-semibold tracking-tight text-gray-900 uppercase">MEJORES OFERTAS</p>
          </div>
        </article>

        <article
          class="cat-item-main rounded-3xl overflow-hidden shadow-md bg-sky-400/90 flex-shrink-0 w-full sm:w-[calc(33.333%-1rem)] mr-4">
          <div class="h-44 md:h-52 bg-sky-500/40">
            <img src="img/Inicio/1/3.png" alt="Las mejores ofertas de servicios"
              class="w-full h-full object-cover cursor-pointer" data-modal="producto"
              data-title="Las mejores ofertas de servicios" data-price=""
              data-desc="Servicios en medicina natural y bienestar con precios promocionales." />
          </div>
          <div class="bg-white py-3 text-center">
            <p class="text-[11px] md:text-xs font-semibold tracking-tight text-gray-900 uppercase">OFERTAS DE SERVICIOS
            </p>
          </div>
        </article>

        <article
          class="cat-item-main rounded-3xl overflow-hidden shadow-md bg-sky-400/90 flex-shrink-0 w-full sm:w-[calc(33.333%-1rem)] mr-4">
          <div class="h-44 md:h-52 bg-sky-500/40">
            <img src="img/Inicio/1/4.png" alt="Productos nuevos" class="w-full h-full object-cover cursor-pointer"
              data-modal="producto" data-title="Productos nuevos" data-price=""
              data-desc="Descubre los lanzamientos más recientes en productos saludables." />
          </div>
          <div class="bg-white py-3 text-center">
            <p class="text-[11px] md:text-xs font-semibold tracking-tight text-gray-900 uppercase">PRODUCTOS NUEVOS</p>
          </div>
        </article>

        <article
          class="cat-item-main rounded-3xl overflow-hidden shadow-md bg-sky-400/90 flex-shrink-0 w-full sm:w-[calc(33.333%-1rem)] mr-4">
          <div class="h-44 md:h-52 bg-sky-500/40">
            <img src="img/Inicio/1/1.png" alt="Marcas publicitadas" class="w-full h-full object-cover cursor-pointer"
              data-modal="producto" data-title="Marcas publicitadas" data-price=""
              data-desc="Marcas aliadas que impulsan un estilo de vida saludable." />
          </div>
          <div class="bg-white py-3 text-center">
            <p class="text-[11px] md:text-xs font-semibold tracking-tight text-gray-900 uppercase">MARCAS</p>
          </div>
        </article>

        <article
          class="cat-item-main rounded-3xl overflow-hidden shadow-md bg-sky-400/90 flex-shrink-0 w-full sm:w-[calc(33.333%-1rem)] mr-4">
          <div class="h-44 md:h-52 bg-sky-500/40">
            <img src="img/Inicio/1/2.png" alt="Digestión saludable" class="w-full h-full object-cover cursor-pointer"
              data-modal="producto" data-title="Digestión saludable" data-price=""
              data-desc="Productos y servicios orientados a mejorar tu salud digestiva." />
          </div>
          <div class="bg-white py-3 text-center">
            <p class="text-[11px] md:text-xs font-semibold tracking-tight text-gray-900 uppercase">DIGESTIÓN SALUDABLE
            </p>
          </div>
        </article>
      </div>

      <button id="categoriasLimpiezaPrev"
        class="absolute left-3 top-1/2 -translate-y-1/2 bg-white/80 hover:bg-white text-sky-600 p-1.5 rounded-full shadow"
        type="button">
        <i class="ph-caret-left-bold text-sm"></i>
      </button>
      <button id="categoriasLimpiezaNext"
        class="absolute right-3 top-1/2 -translate-y-1/2 bg-white/80 hover:bg-white text-sky-600 p-1.5 rounded-full shadow"
        type="button">
        <i class="ph-caret-right-bold text-sm"></i>
      </button>
    </div>
  </section>

  <!-- CARRUSEL Categorías de productos saludables -->
  <section class="space-y-4 md:space-y-6 max-w-7xl mx-auto px-4">
    <h2 class="text-xl md:text-2xl font-semibold text-gray-900">Categorías de productos saludables</h2>

    <div class="relative overflow-hidden">
      <div id="categoriasEquiposTrack" class="flex transition-transform duration-700">
        <article
          class="cat-item-main rounded-3xl overflow-hidden shadow-md bg-sky-400/90 flex-shrink-0 w-full sm:w-[calc(33.333%-1rem)] mr-4">
          <div class="h-44 md:h-52 bg-sky-500/40">
            <img src="img/Inicio/2/1.png" alt="Categorías" class="w-full h-full object-cover cursor-pointer"
              data-modal="producto" data-title="Categorías de productos" data-price=""
              data-desc="Explora productos orientados al bienestar y la salud integral." />
          </div>
          <div class="bg-white py-3 text-center">
            <p class="text-[11px] md:text-xs font-semibold tracking-tight text-gray-900 uppercase">CATEGORÍAS</p>
          </div>
        </article>

        <article
          class="cat-item-main rounded-3xl overflow-hidden shadow-md bg-sky-400/90 flex-shrink-0 w-full sm:w-[calc(33.333%-1rem)] mr-4">
          <div class="h-44 md:h-52 bg-sky-500/40">
            <img src="img/Inicio/2/2.png" alt="Ofertas" class="w-full h-full object-cover cursor-pointer"
              data-modal="producto" data-title="Mejores ofertas" data-price=""
              data-desc="Encuentra productos saludables con descuentos y promociones especiales." />
          </div>
          <div class="bg-white py-3 text-center">
            <p class="text-[11px] md:text-xs font-semibold tracking-tight text-gray-900 uppercase">MEJORES OFERTAS</p>
          </div>
        </article>

        <article
          class="cat-item-main rounded-3xl overflow-hidden shadow-md bg-sky-400/90 flex-shrink-0 w-full sm:w-[calc(33.333%-1rem)] mr-4">
          <div class="h-44 md:h-52 bg-sky-500/40">
            <img src="img/Inicio/2/3.png" alt="Servicios" class="w-full h-full object-cover cursor-pointer"
              data-modal="producto" data-title="Servicios destacados" data-price=""
              data-desc="Servicios en medicina natural y bienestar con precios promocionales." />
          </div>
          <div class="bg-white py-3 text-center">
            <p class="text-[11px] md:text-xs font-semibold tracking-tight text-gray-900 uppercase">SERVICIOS</p>
          </div>
        </article>

        <article
          class="cat-item-main rounded-3xl overflow-hidden shadow-md bg-sky-400/90 flex-shrink-0 w-full sm:w-[calc(33.333%-1rem)] mr-4">
          <div class="h-44 md:h-52 bg-sky-500/40">
            <img src="img/Inicio/2/4.png" alt="Nuevos" class="w-full h-full object-cover cursor-pointer"
              data-modal="producto" data-title="Productos nuevos" data-price=""
              data-desc="Descubre los lanzamientos más recientes." />
          </div>
          <div class="bg-white py-3 text-center">
            <p class="text-[11px] md:text-xs font-semibold tracking-tight text-gray-900 uppercase">NUEVOS</p>
          </div>
        </article>

        <article
          class="cat-item-main rounded-3xl overflow-hidden shadow-md bg-sky-400/90 flex-shrink-0 w-full sm:w-[calc(33.333%-1rem)] mr-4">
          <div class="h-44 md:h-52 bg-sky-500/40">
            <img src="img/Inicio/2/5.png" alt="Marcas" class="w-full h-full object-cover cursor-pointer"
              data-modal="producto" data-title="Marcas publicitadas" data-price=""
              data-desc="Marcas aliadas que impulsan un estilo de vida saludable." />
          </div>
          <div class="bg-white py-3 text-center">
            <p class="text-[11px] md:text-xs font-semibold tracking-tight text-gray-900 uppercase">MARCAS</p>
          </div>
        </article>

        <article
          class="cat-item-main rounded-3xl overflow-hidden shadow-md bg-sky-400/90 flex-shrink-0 w-full sm:w-[calc(33.333%-1rem)] mr-4">
          <div class="h-44 md:h-52 bg-sky-500/40">
            <img src="img/Inicio/2/6.png" alt="Digestión" class="w-full h-full object-cover cursor-pointer"
              data-modal="producto" data-title="Digestión saludable" data-price=""
              data-desc="Productos orientados a mejorar tu salud digestiva." />
          </div>
          <div class="bg-white py-3 text-center">
            <p class="text-[11px] md:text-xs font-semibold tracking-tight text-gray-900 uppercase">DIGESTIÓN</p>
          </div>
        </article>
      </div>

      <button id="categoriasEquiposPrev"
        class="absolute left-3 top-1/2 -translate-y-1/2 bg-white/80 hover:bg-white text-sky-600 p-1.5 rounded-full shadow"
        type="button">
        <i class="ph-caret-left-bold text-sm"></i>
      </button>
      <button id="categoriasEquiposNext"
        class="absolute right-3 top-1/2 -translate-y-1/2 bg-white/80 hover:bg-white text-sky-600 p-1.5 rounded-full shadow"
        type="button">
        <i class="ph-caret-right-bold text-sm"></i>
      </button>
    </div>
  </section>

  <!-- CARRUSEL Nuestras marcas -->
  <section class="space-y-4 md:space-y-6 max-w-7xl mx-auto px-4">
    <h2 class="text-xl md:text-2xl font-semibold text-gray-900">Nuestras marcas</h2>

    <div class="relative overflow-hidden">
      <div id="categoriasSuplementosTrack" class="flex transition-transform duration-700">
        <article
          class="cat-item-main rounded-3xl overflow-hidden shadow-md bg-sky-400/90 flex-shrink-0 w-full sm:w-[calc(33.333%-1rem)] mr-4">
          <div class="h-44 md:h-52 bg-sky-500/40">
            <img src="img/Inicio/3/1.png" alt="Marca 1" class="w-full h-full object-cover cursor-pointer"
              data-modal="producto" data-title="Marca 1" data-price="" data-desc="Marca destacada." />
          </div>
          <div class="bg-white py-3 text-center">
            <p class="text-[11px] md:text-xs font-semibold tracking-tight text-gray-900 uppercase">MARCA 1</p>
          </div>
        </article>

        <article
          class="cat-item-main rounded-3xl overflow-hidden shadow-md bg-sky-400/90 flex-shrink-0 w-full sm:w-[calc(33.333%-1rem)] mr-4">
          <div class="h-44 md:h-52 bg-sky-500/40">
            <img src="img/Inicio/3/2.png" alt="Marca 2" class="w-full h-full object-cover cursor-pointer"
              data-modal="producto" data-title="Marca 2" data-price="" data-desc="Marca destacada." />
          </div>
          <div class="bg-white py-3 text-center">
            <p class="text-[11px] md:text-xs font-semibold tracking-tight text-gray-900 uppercase">MARCA 2</p>
          </div>
        </article>

        <article
          class="cat-item-main rounded-3xl overflow-hidden shadow-md bg-sky-400/90 flex-shrink-0 w-full sm:w-[calc(33.333%-1rem)] mr-4">
          <div class="h-44 md:h-52 bg-sky-500/40">
            <img src="img/Inicio/3/3.png" alt="Marca 3" class="w-full h-full object-cover cursor-pointer"
              data-modal="producto" data-title="Marca 3" data-price="" data-desc="Marca destacada." />
          </div>
          <div class="bg-white py-3 text-center">
            <p class="text-[11px] md:text-xs font-semibold tracking-tight text-gray-900 uppercase">MARCA 3</p>
          </div>
        </article>

        <article
          class="cat-item-main rounded-3xl overflow-hidden shadow-md bg-sky-400/90 flex-shrink-0 w-full sm:w-[calc(33.333%-1rem)] mr-4">
          <div class="h-44 md:h-52 bg-sky-500/40">
            <img src="img/Inicio/3/4.png" alt="Marca 4" class="w-full h-full object-cover cursor-pointer"
              data-modal="producto" data-title="Marca 4" data-price="" data-desc="Marca destacada." />
          </div>
          <div class="bg-white py-3 text-center">
            <p class="text-[11px] md:text-xs font-semibold tracking-tight text-gray-900 uppercase">MARCA 4</p>
          </div>
        </article>

        <article
          class="cat-item-main rounded-3xl overflow-hidden shadow-md bg-sky-400/90 flex-shrink-0 w-full sm:w-[calc(33.333%-1rem)] mr-4">
          <div class="h-44 md:h-52 bg-sky-500/40">
            <img src="img/Inicio/3/5.png" alt="Marca 5" class="w-full h-full object-cover cursor-pointer"
              data-modal="producto" data-title="Marca 5" data-price="" data-desc="Marca destacada." />
          </div>
          <div class="bg-white py-3 text-center">
            <p class="text-[11px] md:text-xs font-semibold tracking-tight text-gray-900 uppercase">MARCA 5</p>
          </div>
        </article>

        <article
          class="cat-item-main rounded-3xl overflow-hidden shadow-md bg-sky-400/90 flex-shrink-0 w-full sm:w-[calc(33.333%-1rem)] mr-4">
          <div class="h-44 md:h-52 bg-sky-500/40">
            <img src="img/Inicio/3/6.png" alt="Marca 6" class="w-full h-full object-cover cursor-pointer"
              data-modal="producto" data-title="Marca 6" data-price="" data-desc="Marca destacada." />
          </div>
          <div class="bg-white py-3 text-center">
            <p class="text-[11px] md:text-xs font-semibold tracking-tight text-gray-900 uppercase">MARCA 6</p>
          </div>
        </article>
      </div>

      <button id="categoriasSuplementosPrev"
        class="absolute left-3 top-1/2 -translate-y-1/2 bg-white/80 hover:bg-white text-sky-600 p-1.5 rounded-full shadow"
        type="button">
        <i class="ph-caret-left-bold text-sm"></i>
      </button>
      <button id="categoriasSuplementosNext"
        class="absolute right-3 top-1/2 -translate-y-1/2 bg-white/80 hover:bg-white text-sky-600 p-1.5 rounded-full shadow"
        type="button">
        <i class="ph-caret-right-bold text-sm"></i>
      </button>
    </div>
  </section>

  <!-- OFERTAS (3 wrappers) -->
  <section class="space-y-4 md:space-y-6 max-w-7xl mx-auto px-4">
    <h2 class="text-xl md:text-2xl font-semibold text-gray-900">Las mejores ofertas de productos</h2>

    <div class="ofertas-wrapper">
      <div class="ofertas-bg ofertas-bg-productos"></div>

      <div class="ofertas-inner">
        <div class="ofertas-viewport">
          <div class="ofertas-track">
            <article class="oferta-card js-product-card" data-price="38">
              <div class="oferta-image-wrapper">
                <img src="img/Inicio/4/1.png" alt="Extracto de algarrobo">
                <div class="oferta-actions">
                  <button class="oferta-action-btn" title="Agregar al carrito"><i
                      class="ph-shopping-cart-bold"></i></button>
                  <button class="oferta-action-btn" title="Vista rápida" data-modal="producto"
                    data-title="EXTRACTO DE ALGARROBO" data-price="S/ 38.00"
                    data-desc="Extracto natural de alta calidad."><i class="ph-eye-bold"></i></button>
                  <button class="oferta-action-btn" title="Ver producto"><i class="ph-link-bold"></i></button>
                </div>
              </div>
              <div class="oferta-info">
                <h3 class="oferta-title">EXTRACTO DE ALGARROBO</h3>
                <p class="oferta-price">S/ 38.00</p>
                <div class="oferta-stars">★★★★★</div>
              </div>
            </article>

            <article class="oferta-card js-product-card" data-price="50">
              <div class="oferta-image-wrapper">
                <img src="img/Inicio/4/2.png" alt="Profilaxis / Destartraje">
                <div class="oferta-actions">
                  <button class="oferta-action-btn" title="Agregar al carrito"><i
                      class="ph-shopping-cart-bold"></i></button>
                  <button class="oferta-action-btn" title="Vista rápida" data-modal="producto"
                    data-title="Profilaxis / Destartraje" data-price="S/ 50.00"
                    data-desc="Servicio dental especializado."><i class="ph-eye-bold"></i></button>
                  <button class="oferta-action-btn" title="Ver producto"><i class="ph-link-bold"></i></button>
                </div>
              </div>
              <div class="oferta-info">
                <h3 class="oferta-title">Profilaxis / Destartraje</h3>
                <p class="oferta-price">S/ 50.00</p>
                <div class="oferta-stars">★★★★★</div>
              </div>
            </article>

            <article class="oferta-card js-product-card" data-price="80">
              <div class="oferta-image-wrapper">
                <img src="img/Inicio/4/3.png" alt="Ecografía obstétrica">
                <div class="oferta-actions">
                  <button class="oferta-action-btn" title="Agregar al carrito"><i
                      class="ph-shopping-cart-bold"></i></button>
                  <button class="oferta-action-btn" title="Vista rápida" data-modal="producto"
                    data-title="Ecografía obstétrica" data-price="S/ 80.00"
                    data-desc="Ecografía obstétrica de alta precisión."><i class="ph-eye-bold"></i></button>
                  <button class="oferta-action-btn" title="Ver producto"><i class="ph-link-bold"></i></button>
                </div>
              </div>
              <div class="oferta-info">
                <h3 class="oferta-title">Ecografía obstétrica</h3>
                <p class="oferta-price">S/ 80.00</p>
                <div class="oferta-stars">★★★★★</div>
              </div>
            </article>

            <article class="oferta-card js-product-card" data-price="38">
              <div class="oferta-image-wrapper">
                <img src="img/Inicio/4/1.png" alt="Extracto de algarrobo">
                <div class="oferta-actions">
                  <button class="oferta-action-btn" title="Agregar al carrito"><i
                      class="ph-shopping-cart-bold"></i></button>
                  <button class="oferta-action-btn" title="Vista rápida"><i class="ph-eye-bold"></i></button>
                  <button class="oferta-action-btn" title="Ver producto"><i class="ph-link-bold"></i></button>
                </div>
              </div>
              <div class="oferta-info">
                <h3 class="oferta-title">EXTRACTO DE ALGARROBO</h3>
                <p class="oferta-price">S/ 38.00</p>
                <div class="oferta-stars">★★★★★</div>
              </div>
            </article>

            <article class="oferta-card js-product-card" data-price="50">
              <div class="oferta-image-wrapper">
                <img src="img/Inicio/4/2.png" alt="Profilaxis / Destartraje">
                <div class="oferta-actions">
                  <button class="oferta-action-btn" title="Agregar al carrito"><i
                      class="ph-shopping-cart-bold"></i></button>
                  <button class="oferta-action-btn" title="Vista rápida"><i class="ph-eye-bold"></i></button>
                  <button class="oferta-action-btn" title="Ver producto"><i class="ph-link-bold"></i></button>
                </div>
              </div>
              <div class="oferta-info">
                <h3 class="oferta-title">Profilaxis / Destartraje</h3>
                <p class="oferta-price">S/ 50.00</p>
                <div class="oferta-stars">★★★★★</div>
              </div>
            </article>
          </div> <!-- /ofertas-track -->
        </div> <!-- /ofertas-viewport -->

        <div class="ofertas-dots">
          <button type="button" data-oferta-dot="0" class="is-active"></button>
          <button type="button" data-oferta-dot="1"></button>
        </div>
      </div>
  </section>

  <section class="space-y-4 md:space-y-6 max-w-7xl mx-auto px-4">
    <h2 class="text-xl md:text-2xl font-semibold text-gray-900">Las mejores ofertas de Servicios</h2>

    <div class="ofertas-wrapper">
      <div class="ofertas-bg ofertas-bg-servicios"></div>

      <div class="ofertas-inner">
        <div class="ofertas-viewport">
          <div class="ofertas-track">
            <article class="oferta-card js-product-card" data-price="45">
              <div class="oferta-image-wrapper">
                <img src="img/Inicio/5/1.png" alt="Servicio 1">
                <div class="oferta-actions">
                  <button class="oferta-action-btn" title="Agregar al carrito"><i
                      class="ph-shopping-cart-bold"></i></button>
                  <button class="oferta-action-btn" title="Vista rápida"><i class="ph-eye-bold"></i></button>
                  <button class="oferta-action-btn" title="Ver producto"><i class="ph-link-bold"></i></button>
                </div>
              </div>
              <div class="oferta-info">
                <h3 class="oferta-title">Limpieza Profunda</h3>
                <p class="oferta-price">S/ 45.00</p>
                <div class="oferta-stars">★★★★★</div>
              </div>
            </article>

            <article class="oferta-card js-product-card" data-price="60">
              <div class="oferta-image-wrapper">
                <img src="img/Inicio/5/2.png" alt="Servicio 2">
                <div class="oferta-actions">
                  <button class="oferta-action-btn" title="Agregar al carrito"><i
                      class="ph-shopping-cart-bold"></i></button>
                  <button class="oferta-action-btn" title="Vista rápida"><i class="ph-eye-bold"></i></button>
                  <button class="oferta-action-btn" title="Ver producto"><i class="ph-link-bold"></i></button>
                </div>
              </div>
              <div class="oferta-info">
                <h3 class="oferta-title">Consulta Nutricional</h3>
                <p class="oferta-price">S/ 60.00</p>
                <div class="oferta-stars">★★★★★</div>
              </div>
            </article>

            <article class="oferta-card js-product-card" data-price="70">
              <div class="oferta-image-wrapper">
                <img src="img/Inicio/5/3.png" alt="Servicio 3">
                <div class="oferta-actions">
                  <button class="oferta-action-btn" title="Agregar al carrito"><i
                      class="ph-shopping-cart-bold"></i></button>
                  <button class="oferta-action-btn" title="Vista rápida"><i class="ph-eye-bold"></i></button>
                  <button class="oferta-action-btn" title="Ver producto"><i class="ph-link-bold"></i></button>
                </div>
              </div>
              <div class="oferta-info">
                <h3 class="oferta-title">Masaje Relajante</h3>
                <p class="oferta-price">S/ 70.00</p>
                <div class="oferta-stars">★★★★★</div>
              </div>
            </article>
          </div> <!-- /ofertas-track -->
        </div> <!-- /ofertas-viewport -->

        <div class="ofertas-dots">
          <button type="button" data-oferta-dot="0" class="is-active"></button>
          <button type="button" data-oferta-dot="1"></button>
        </div>
      </div>
  </section>

  <section class="space-y-4 md:space-y-6 max-w-7xl mx-auto px-4">
    <h2 class="text-xl md:text-2xl font-semibold text-gray-900">Productos Nuevos</h2>

    <div class="ofertas-wrapper">
      <div class="ofertas-bg ofertas-bg-nuevos"></div>

      <div class="ofertas-inner">
        <div class="ofertas-viewport">
          <div class="ofertas-track">
            <article class="oferta-card js-product-card" data-price="25">
              <div class="oferta-image-wrapper">
                <img src="img/Inicio/6/1.png" alt="Producto nuevo 1">
                <div class="oferta-actions">
                  <button class="oferta-action-btn" title="Agregar al carrito"><i
                      class="ph-shopping-cart-bold"></i></button>
                  <button class="oferta-action-btn" title="Vista rápida"><i class="ph-eye-bold"></i></button>
                  <button class="oferta-action-btn" title="Ver producto"><i class="ph-link-bold"></i></button>
                </div>
              </div>
              <div class="oferta-info">
                <h3 class="oferta-title">Suplemento Vitamínico</h3>
                <p class="oferta-price">S/ 25.00</p>
                <div class="oferta-stars">★★★★★</div>
              </div>
            </article>

            <article class="oferta-card js-product-card" data-price="90">
              <div class="oferta-image-wrapper">
                <img src="img/Inicio/6/2.png" alt="Producto nuevo 2">
                <div class="oferta-actions">
                  <button class="oferta-action-btn" title="Agregar al carrito"><i
                      class="ph-shopping-cart-bold"></i></button>
                  <button class="oferta-action-btn" title="Vista rápida"><i class="ph-eye-bold"></i></button>
                  <button class="oferta-action-btn" title="Ver producto"><i class="ph-link-bold"></i></button>
                </div>
              </div>
              <div class="oferta-info">
                <h3 class="oferta-title">Proteína Vegetal</h3>
                <p class="oferta-price">S/ 90.00</p>
                <div class="oferta-stars">★★★★★</div>
              </div>
            </article>

            <article class="oferta-card js-product-card" data-price="35">
              <div class="oferta-image-wrapper">
                <img src="img/Inicio/6/3.png" alt="Producto nuevo 3">
                <div class="oferta-actions">
                  <button class="oferta-action-btn" title="Agregar al carrito"><i
                      class="ph-shopping-cart-bold"></i></button>
                  <button class="oferta-action-btn" title="Vista rápida"><i class="ph-eye-bold"></i></button>
                  <button class="oferta-action-btn" title="Ver producto"><i class="ph-link-bold"></i></button>
                </div>
              </div>
              <div class="oferta-info">
                <h3 class="oferta-title">Aceite de Coco</h3>
                <p class="oferta-price">S/ 35.00</p>
                <div class="oferta-stars">★★★★★</div>
              </div>
            </article>
          </div> <!-- /ofertas-track -->
        </div> <!-- /ofertas-viewport -->

        <div class="ofertas-dots">
          <button type="button" data-oferta-dot="0" class="is-active"></button>
          <button type="button" data-oferta-dot="1"></button>
        </div>
      </div>
  </section>

  <!-- BANNERS PUBLICITARIOS (Secuencia Slider -> Estático -> Slider -> Estático) -->
  <section class="mt-10 space-y-8 w-full overflow-hidden">
    <div class="max-w-7xl mx-auto px-4">
      <h2 class="text-xl md:text-2xl font-semibold text-gray-900">Banners publicitarios</h2>
    </div>

    <!-- BLOQUE 1: Slider Grandes (Contenido en contenedor) -->
    <div class="max-w-7xl mx-auto px-4">
      <div class="banner-wrapper">
        <div class="banner-track" data-banner-track>
          <!-- Slide 1 -->
          <div class="banner-slide" data-banner-slide>
            <div class="banner-grid-top">
              <article class="banner-card banner-card-lg">
                <img src="img/Inicio/7/1.png" alt="Banner 1">
              </article>
              <article class="banner-card banner-card-lg">
                <img src="img/Inicio/7/2.png" alt="Banner 2">
              </article>
            </div>
          </div>
          <!-- Slide 2 -->
          <div class="banner-slide" data-banner-slide>
            <div class="banner-grid-top">
              <article class="banner-card banner-card-lg">
                <img src="img/Inicio/7/3.png" alt="Banner 3">
              </article>
              <article class="banner-card banner-card-lg">
                <img src="img/Inicio/7/4.png" alt="Banner 4">
              </article>
            </div>
          </div>
        </div>
        <div class="banner-dots"></div>
      </div>
    </div>

    <!-- BLOQUE 2: Banners Estáticos (3 Pequeños) -->
    <div class="max-w-7xl mx-auto px-4">
      <div class="cat-carousel" data-items="3">
        <div class="cat-track">
          <div class="cat-item-banner hover:scale-[1.02] transition-transform">
            <img src="img/banners_publicitarios/banner_pequeno/banner_pequeno_2.1.webp" alt="Banner Pequeno 4.1">
          </div>
          <div class="cat-item-banner hover:scale-[1.02] transition-transform">
            <img src="img/banners_publicitarios/banner_pequeno/banner_pequeno_2.2.webp" alt="Banner Pequeno 4.2">
          </div>
          <div class="cat-item-banner hover:scale-[1.02] transition-transform">
            <img src="img/banners_publicitarios/banner_pequeno/banner_pequeno_2.3.webp" alt="Banner Pequeno 4.3">
          </div>
          <div class="cat-item-banner hover:scale-[1.02] transition-transform">
            <img src="img/banners_publicitarios/banner_pequeno/banner_pequeno_2.4.webp" alt="Banner Pequeno 4.4">
          </div>
        </div>
      </div>
    </div>

    <!-- BLOQUE 3: Otro Slider Grandes -->
    <div class="max-w-7xl mx-auto px-4">
      <div class="banner-wrapper">
        <div class="banner-track" data-banner-track>
          <div class="banner-slide" data-banner-slide>
            <div class="banner-grid-top">
              <article class="banner-card banner-card-lg">
                <img src="img/banners_publicitarios/banner_mediano/banner_mediano_3.1.webp" alt="Banner 3-alt">
              </article>
              <article class="banner-card banner-card-lg">
                <img src="img/banners_publicitarios/banner_mediano/banner_mediano_3.2.webp" alt="Banner 4-alt">
              </article>
            </div>
          </div>
          <div class="banner-slide" data-banner-slide>
            <div class="banner-grid-top">
              <article class="banner-card banner-card-lg">
                <img src="img/banners_publicitarios/banner_mediano/banner_mediano_3.3.webp" alt="Banner 1-alt">
              </article>
              <article class="banner-card banner-card-lg">
                <img src="img/banners_publicitarios/banner_mediano/banner_mediano_3.1.webp" alt="Banner 2-alt">
              </article>
            </div>
          </div>
        </div>
        <div class="banner-dots"></div>
      </div>
    </div>

    <!-- BLOQUE 4: Slider Banners Pequeños (Draggable, sin puntos) -->
    <div class="max-w-7xl mx-auto px-4">
      <div class="cat-carousel" data-items="3">
        <div class="cat-track">
          <div class="cat-item-banner hover:scale-[1.02] transition-transform">
            <img src="img/banners_publicitarios/banner_pequeno/banner_pequeno_4.1.webp" alt="Banner Pequeno 4.1">
          </div>
          <div class="cat-item-banner hover:scale-[1.02] transition-transform">
            <img src="img/banners_publicitarios/banner_pequeno/banner_pequeno_4.2.webp" alt="Banner Pequeno 4.2">
          </div>
          <div class="cat-item-banner hover:scale-[1.02] transition-transform">
            <img src="img/banners_publicitarios/banner_pequeno/banner_pequeno_4.3.webp" alt="Banner Pequeno 4.3">
          </div>
          <div class="cat-item-banner hover:scale-[1.02] transition-transform">
            <img src="img/banners_publicitarios/banner_pequeno/banner_pequeno_4.4.webp" alt="Banner Pequeno 4.4">
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- DIGESTIÓN / BELLEZA / SERVICIOS (cat-carousel) -->
  <section class="section-digestion max-w-7xl mx-auto px-4">
    <h2 class="section-title">Digestión saludable</h2>

    <div class="category-wrapper">
      <div class="category-left">
        <img src="img/Inicio/8/1.png" alt="Digestión saludable" class="category-banner">
      </div>

      <div class="cat-carousel" data-items="3" data-carousel="digestion">
        <div class="cat-track" id="track-digestion">
          <div class="cat-item">
            <img src="img/Inicio/8/2.png" alt="Colágeno Marino" class="cat-img">
            <h3 class="cat-title">Colágeno Marino</h3>
            <p class="cat-price">S/ 100.00</p>
            <p class="cat-stars">★★★★★</p>
          </div>

          <div class="cat-item">
            <img src="img/Inicio/8/3.png" alt="Bebida instantánea de habas" class="cat-img">
            <h3 class="cat-title">BEBIDA INSTANTÁNEA DE HABAS</h3>
            <p class="cat-price">S/ 25.00</p>
            <p class="cat-stars">★★★★★</p>
          </div>

          <div class="cat-item">
            <img src="img/Inicio/8/4.png" alt="Melatonina de 10mg" class="cat-img">
            <h3 class="cat-title">Melatonina de liberación rápida de 10 mg</h3>
            <p class="cat-price">S/ 130.00</p>
            <p class="cat-stars">★★★★★</p>
          </div>

          <div class="cat-item">
            <img src="img/Inicio/8/1.png" alt="Colágeno Marino" class="cat-img">
            <h3 class="cat-title">Colágeno Marino</h3>
            <p class="cat-price">S/ 100.00</p>
            <p class="cat-stars">★★★★★</p>
          </div>

          <div class="cat-item">
            <img src="img/Inicio/8/2.png" alt="Bebida instantánea de habas" class="cat-img">
            <h3 class="cat-title">BEBIDA INSTANTÁNEA DE HABAS</h3>
            <p class="cat-price">S/ 25.00</p>
            <p class="cat-stars">★★★★★</p>
          </div>

          <div class="cat-item">
            <img src="img/Inicio/8/3.png" alt="Melatonina de 10mg" class="cat-img">
            <h3 class="cat-title">Melatonina de liberación rápida de 10 mg</h3>
            <p class="cat-price">S/ 130.00</p>
            <p class="cat-stars">★★★★★</p>
          </div>
        </div>

        <div class="cat-dots" id="dots-digestion"></div>
      </div>
    </div>
  </section>

  <section class="section-belleza max-w-7xl mx-auto px-4">
    <h2 class="section-title">Belleza</h2>

    <div class="category-wrapper">
      <div class="category-left">
        <img src="img/Inicio/9/1.png" alt="Belleza" class="category-banner">
      </div>

      <div class="cat-carousel" data-items="3">
        <div class="cat-track" id="track-belleza">
          <div class="cat-item">
            <img src="img/Inicio/9/2.png" alt="Espuma Limpiadora" class="cat-img">
            <h3 class="cat-title">Espuma Limpiadora</h3>
            <p class="cat-price">S/ 55.00</p>
            <p class="cat-stars">★★★★★</p>
          </div>

          <div class="cat-item">
            <img src="img/Inicio/9/2.png" alt="Espuma Limpiadora" class="cat-img">
            <h3 class="cat-title">Espuma Limpiadora</h3>
            <p class="cat-price">S/ 55.00</p>
            <p class="cat-stars">★★★★★</p>
          </div>

          <div class="cat-item">
            <img src="img/Inicio/9/2.png" alt="Espuma Limpiadora" class="cat-img">
            <h3 class="cat-title">Espuma Limpiadora</h3>
            <p class="cat-price">S/ 55.00</p>
            <p class="cat-stars">★★★★★</p>
          </div>

          <div class="cat-item">
            <img src="img/Inicio/9/2.png" alt="Espuma Limpiadora" class="cat-img">
            <h3 class="cat-title">Espuma Limpiadora</h3>
            <p class="cat-price">S/ 55.00</p>
            <p class="cat-stars">★★★★★</p>
          </div>
        </div>

        <div class="cat-dots" id="dots-belleza"></div>
      </div>
    </div>
  </section>

  <!-- Servicios médicos -->
  <section class="section-servicios max-w-7xl mx-auto px-4">
    <h2 class="section-title">Servicios médicos</h2>

    <div class="category-wrapper">
      <div class="category-left">
        <img src="img/Inicio/10/1.png" alt="Servicios médicos" class="category-banner">
      </div>

      <div class="cat-carousel" data-items="3">
        <div class="cat-track" id="track-servicios">
          <div class="cat-item">
            <img src="img/Inicio/10/3.png" alt="Masajes Corporales" class="cat-img">
            <h3 class="cat-title">Masajes Corporales</h3>
            <p class="cat-price">S/ 30.00</p>
            <p class="cat-stars">★★★★★</p>
          </div>

          <div class="cat-item">
            <img src="img/Inicio/10/2.png" alt="Blanqueamiento Dental" class="cat-img">
            <h3 class="cat-title">Blanqueamiento Dental</h3>
            <p class="cat-price">S/ 120.00</p>
            <p class="cat-stars">★★★★★</p>
          </div>

          <div class="cat-item">
            <img src="img/Inicio/10/4.png" alt="Diagnóstico unipolar" class="cat-img">
            <h3 class="cat-title">Diagnóstico unipolar</h3>
            <p class="cat-price">S/ 120.00</p>
            <p class="cat-stars">★★★★★</p>
          </div>

          <div class="cat-item">
            <img src="img/Inicio/10/3.png" alt="Masajes Corporales" class="cat-img">
            <h3 class="cat-title">Masajes Corporales</h3>
            <p class="cat-price">S/ 30.00</p>
            <p class="cat-stars">★★★★★</p>
          </div>

          <div class="cat-item">
            <img src="img/Inicio/10/2.png" alt="Blanqueamiento Dental" class="cat-img">
            <h3 class="cat-title">Blanqueamiento Dental</h3>
            <p class="cat-price">S/ 120.00</p>
            <p class="cat-stars">★★★★★</p>
          </div>

          <div class="cat-item">
            <img src="img/Inicio/10/4.png" alt="Diagnóstico unipolar" class="cat-img">
            <h3 class="cat-title">Diagnóstico unipolar</h3>
            <p class="cat-price">S/ 120.00</p>
            <p class="cat-stars">★★★★★</p>
          </div>
        </div>

        <div class="cat-dots" id="dots-servicios"></div>
      </div>
    </div>
  </section>

  <!-- Servicios en medicina natural -->
  <section class="section-servicios max-w-7xl mx-auto px-4">
    <h2 class="section-title">Servicios en medicina natural</h2>

    <div class="category-wrapper">
      <div class="category-left">
        <img src="img/Servicios/MedicinaNatural/SERVICIOS DE MEDICINA NATURAL.webp" alt="Servicios en medicina natural"
          class="category-banner">
      </div>

      <div class="cat-carousel" data-items="3">
        <div class="cat-track" id="track-servicios">
          <div class="cat-item">
            <img src="img/Servicios/MedicinaNatural/MASAJE CORPORAL.webp" alt="Masajes Corporales" class="cat-img">
            <h3 class="cat-title">Masajes Corporales</h3>
            <p class="cat-price">S/ 30.00</p>
            <p class="cat-stars">★★★★★</p>
          </div>

          <div class="cat-item">
            <img src="img/Servicios/MedicinaNatural/4-PEDICURA-1-300x300.webp" alt="Blanqueamiento Dental"
              class="cat-img">
            <h3 class="cat-title">Pedicura</h3>
            <p class="cat-price">S/ 40.00</p>
            <p class="cat-stars">★★★★★</p>
          </div>

          <div class="cat-item">
            <img src="img/Servicios/MedicinaNatural/4-EXFOLIACION-CORPORAL-1-300x300.webp" alt="Diagnóstico unipolar"
              class="cat-img">
            <h3 class="cat-title">Exfoliacion Corporal</h3>
            <p class="cat-price">S/ 150.00</p>
            <p class="cat-stars">★★★★★</p>
          </div>

          
        </div>

        <div class="cat-dots" id="dots-servicios"></div>
      </div>
    </div>
  </section>

  <!-- BENEFICIOS -->
  <section class="beneficios-section max-w-7xl mx-auto px-4">
    <h2 class="beneficios-title">Beneficios</h2>

    <div class="beneficios-slider" data-beneficios-slider>
      <div class="beneficios-track" data-beneficios-track>
        <div class="beneficios-slide">
          <div class="beneficios-bg-layer" data-beneficios-parallax
            style="background-image:url('img/Inicio/11/1.png');"></div>

          <div class="beneficios-content">
            <div class="beneficios-grid">
              <div class="beneficio-item">
                <div class="beneficio-circle"><img src="img/Inicio/11/2.png" alt="Tiendas selectas"></div>
                <div class="beneficio-title">TIENDAS SELECTAS</div>
                <div class="beneficio-sub">TIENDAS DE CALIDAD SELECCIONADAS</div>
              </div>

              <div class="beneficio-item">
                <div class="beneficio-circle"><img src="img/Inicio/11/3.png" alt="Mejores precios"></div>
                <div class="beneficio-title">MEJORES PRECIOS</div>
                <div class="beneficio-sub">OFERTAS, PROMOCIONES Y DESCUENTOS</div>
              </div>

              <div class="beneficio-item">
                <div class="beneficio-circle"><img src="img/Inicio/11/4.png" alt="Seguridad"></div>
                <div class="beneficio-title">SEGURIDAD</div>
                <div class="beneficio-sub">BIOMARKETPLACE 100% SEGURO</div>
              </div>

              <div class="beneficio-item">
                <div class="beneficio-circle"><img src="img/Inicio/11/5.png" alt="Rapidez"></div>
                <div class="beneficio-title">RAPIDEZ</div>
                <div class="beneficio-sub">MAYOR RAPIDEZ EN TUS COMPRAS</div>
              </div>

              <div class="beneficio-item">
                <div class="beneficio-circle"><img src="img/Inicio/11/6.png" alt="Más tiempo"></div>
                <div class="beneficio-title">MÁS TIEMPO</div>
                <div class="beneficio-sub">AHORRA TIEMPO EN COLAS Y TRASLADOS</div>
              </div>

              <div class="beneficio-item">
                <div class="beneficio-circle"><img src="img/Inicio/11/7.png" alt="Donde quieras"></div>
                <div class="beneficio-title">DONDE QUIERAS</div>
                <div class="beneficio-sub">ENVÍOS A TODO EL PERÚ</div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="beneficios-dots" data-beneficios-dots></div>
  </section>

  <!-- SUSCRIPCIÓN -->
  <section class="rounded-3xl bg-teal-300 text-white px-6 md:px-10 py-8 md:py-10 max-w-7xl mx-auto">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-center">
      <div>
        <h2 class="text-lg md:text-xl font-semibold mb-2">¡SUSCRÍBETE Y RECIBE LAS MEJORES OFERTAS!</h2>
        <p class="text-sm md:text[15px]">Obtén nuestras últimas novedades, ofertas y tips para llevar una vida
          saludable.</p>
      </div>

      <form class="space-y-3">
        <div class="flex flex-col sm:flex-row gap-3">
          <input type="email" placeholder="Correo electrónico"
            class="flex-1 px-4 py-2.5 rounded-full text-gray-800 text-sm border border-teal-200 focus:outline-none focus:ring-2 focus:ring-teal-500"
            required />
          <button type="submit"
            class="px-6 py-2.5 rounded-full bg-sky-500 hover:bg-sky-600 text-sm font-semibold shadow-md">
            Suscribirme
          </button>
        </div>

        <label class="flex items-start gap-2 text-xs">
          <input type="checkbox" class="mt-1">
          <span class="leading-tight">
            He leído y acepto la
            <a href="politicas.php" class="underline">Política de Privacidad.</a>
          </span>
        </label>
      </form>
    </div>
  </section>

</main>

<?php include 'footer.php'; ?>