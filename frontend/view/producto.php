<!-- C:\xampp\htdocs\schedule\frontend\view\producto.php -->
<!DOCTYPE html>
<html lang="es">
  <?php include '../headlogin.php'; ?>
  <body class="bg-[#03A9F4] text-gray-800">
    <div class="flex flex-col md:flex-row min-h-screen">
      <?php include '../asider.php'; ?>

      <div id="main-content" class="flex flex-col flex-1 min-h-screen transition-all duration-300 ease-in-out">

        <style>
          :root {
            --dominant-blue: #03A9F4;
            --dominant-blue-light: #4FC3F7;
            --dominant-blue-dark: #0288D1;
          }
          .product-card-hover {
            transition: transform 0.18s ease, box-shadow 0.18s ease;
          }
          .product-card-hover:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(15, 118, 178, 0.20);
          }
        </style>

        <main class="flex-grow p-3 sm:p-4 lg:p-6 bg-gray-50 overflow-hidden">
          <div class="bg-white shadow-lg rounded-3xl p-3 sm:p-4 lg:p-6 border border-sky-200 w-full">
            <div class="space-y-6">

              <!-- PANEL SUPERIOR (COLAPSABLE) -->
              <div id="panelControlesProductos" class="space-y-6 hidden">
                <!-- Título -->
                <section>
                  <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                    <div>
                      <h2 class="text-lg md:text-xl font-semibold flex items-center gap-2 text-sky-800">
                        <span class="flex items-center justify-center w-9 h-9 rounded-full 
                                     bg-gradient-to-br from-sky-400 to-sky-700
                                     text-white shadow-md">
                          <i class="fas fa-boxes text-sm"></i>
                        </span>
                        Productos
                      </h2>
                      <p class="text-xs text-gray-500 mt-1">
                        Configuración del catálogo de productos / servicios.
                      </p>
                    </div>

                    <div class="flex flex-row flex-wrap gap-2">
                      <button onclick="exportarCSVProductos()"
                              class="flex items-center gap-2 bg-sky-500 hover:bg-sky-600 text-white 
                                     px-4 py-2 rounded-full text-xs sm:text-sm shadow-md transition">
                        <i class="fas fa-file-excel text-base"></i>
                        Exportar CSV
                      </button>

                      <button id="btnAbrirModalProducto"
                              class="flex items-center gap-2 bg-sky-600 hover:bg-sky-700 text-white 
                                     px-4 py-2 rounded-full text-xs sm:text-sm shadow-md transition">
                        <i class="fas fa-plus-circle text-base"></i>
                        Nuevo producto
                      </button>
                    </div>
                  </div>
                </section>

                <!-- Filtros -->
                <section class="border border-sky-200 rounded-2xl bg-sky-50/60 px-4 py-3">
                  <div class="flex items-center justify-between mb-3 gap-2">
                    <h3 class="text-sm font-semibold text-sky-800 flex items-center gap-2">
                      <i class="fas fa-filter text-sky-500"></i>
                      Filtros de búsqueda
                    </h3>
                    <span class="text-[11px] text-gray-500 hidden sm:inline">
                      Busca por nombre, SKU o tipo de producto.
                    </span>
                  </div>

                  <div class="flex flex-col lg:flex-row lg:flex-wrap lg:items-center gap-3 w-full">
                    <!-- Buscador -->
                    <form autocomplete="off" onsubmit="return false;" class="w-full sm:w-96 lg:w-[32rem]">
                      <div class="relative w-full">
                        <input
                          type="search"
                          id="inputBuscarProducto"
                          placeholder="Buscar por nombre, SKU, tipo..."
                          class="w-full pl-10 pr-4 py-2 rounded-full border border-sky-200 shadow-sm bg-white 
                                 focus:outline-none focus:ring-2 focus:ring-sky-400 focus:border-transparent 
                                 text-sm transition"
                          autocomplete="off"
                          autocorrect="off"
                          autocapitalize="off"
                          spellcheck="false"
                        >
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-sky-500">
                          <i class="fas fa-search text-xs"></i>
                        </div>
                      </div>
                    </form>

                    <!-- Paginación -->
                    <div class="flex items-center gap-2">
                      <label for="selectPaginationProducto" class="text-xs text-gray-600">
                        Resultados por página
                      </label>
                      <select id="selectPaginationProducto"
                              class="border border-sky-200 rounded-full px-3 py-1.5 text-xs sm:text-sm bg-white 
                                     focus:outline-none focus:ring-2 focus:ring-sky-400">
                        <option value="all">Mostrando todo</option>
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                      </select>
                    </div>
                  </div>
                </section>
              </div>

              <!-- Catálogo tarjetas -->
              <section class="space-y-3">
                <div class="flex items-center justify-between gap-3">
                  <div>
                    <h3 class="text-sm font-semibold text-sky-800 flex items-center gap-2">
                      <span class="inline-flex items-center justify-center w-8 h-8 rounded-full 
                                   bg-gradient-to-br from-sky-400 to-sky-700 text-white shadow-md">
                        <i class="fas fa-th-large text-xs"></i>
                      </span>
                      Catálogo de productos
                    </h3>
                    <span class="text-[11px] text-gray-500 hidden sm:inline">
                      Visualiza precio, stock y estado de publicación.
                    </span>
                  </div>

                  <button id="btnTogglePanelProductos"
                          type="button"
                          class="flex items-center gap-1 px-3 py-1.5 rounded-full border border-sky-200 bg-white 
                                 text-[11px] sm:text-xs text-sky-800 hover:bg-sky-50 hover:border-sky-300 
                                 hover:text-sky-800 transition shadow-sm">
                    <i class="fas fa-chevron-down text-[10px]"></i>
                    Mostrar/Ocultar filtros
                  </button>
                </div>

                <div class="w-full mt-1">
                  <div id="productosCardsContainer"
                       class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                    <div id="productosCardsSkeleton"
                         class="col-span-1 sm:col-span-2 lg:col-span-3 xl:col-span-4">
                      <div class="w-full border border-dashed border-sky-200 rounded-2xl bg-sky-50/60 p-4 flex items-center justify-center">
                        <div class="flex items-center gap-3 text-sky-700 text-sm">
                          <i class="fas fa-circle-notch fa-spin"></i>
                          <span>Cargando productos...</span>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </section>

            </div>
          </div>

          <!-- MODAL NUEVO / EDITAR PRODUCTO -->
          <div id="modalProducto"
               class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50 hidden px-2 py-3">
            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-xl max-h-[90vh] overflow-hidden flex flex-col 
                        border border-sky-200">

              <!-- HEADER -->
              <div class="px-4 py-3 border-b flex justify-between items-center 
                          bg-gradient-to-r from-sky-500 via-sky-400 to-sky-600">
                <div class="flex flex-col">
                  <h3 class="text-white font-semibold text-base flex items-center gap-2">
                    <i class="fas fa-box-open text-white text-sm"></i>
                    <span id="modalProductoTitulo">Nuevo producto</span>
                  </h3>
                  <span class="text-[11px] text-sky-100/90">
                    Nombre, precio, stock y estado de publicación.
                  </span>
                </div>

                <button id="btnCerrarModalProducto"
                        class="text-white hover:text-sky-100 text-lg px-2 py-1 rounded-full hover:bg-white/10 transition">
                  <i class="fas fa-times"></i>
                </button>
              </div>

              <!-- CONTENIDO -->
              <div class="flex-1 overflow-auto p-4 space-y-4 text-[13px]">
                <section class="space-y-3">
                  <div class="grid grid-cols-1 md:grid-cols-2 gap-3 bg-sky-50 border border-sky-200 rounded-2xl p-3">
                    <!-- Nombre -->
                    <div class="md:col-span-2">
                      <label for="modalProductoNombre" class="block text-xs font-medium text-gray-700 mb-1">
                        Nombre del producto <span class="text-red-500">*</span>
                      </label>
                      <input id="modalProductoNombre"
                             type="text"
                             placeholder="Ej: Café en grano 250g"
                             class="border border-sky-200 rounded-full w-full px-3 py-1.5 text-sm bg-white 
                                    focus:outline-none focus:ring-2 focus:ring-sky-400 focus:border-sky-400 transition">
                    </div>

                    <!-- SKU -->
                    <div>
                      <label for="modalProductoSku" class="block text-xs font-medium text-gray-700 mb-1">
                        SKU / Código interno
                      </label>
                      <input id="modalProductoSku"
                             type="text"
                             placeholder="Ej: SKU-001"
                             class="border border-sky-200 rounded-full w-full px-3 py-1.5 text-sm bg-white 
                                    focus:outline-none focus:ring-2 focus:ring-sky-400 focus:border-sky-400 transition">
                    </div>

                    <!-- Precio -->
                    <div>
                      <label for="modalProductoPrecio" class="block text-xs font-medium text-gray-700 mb-1">
                        Precio <span class="text-red-500">*</span>
                      </label>
                      <input id="modalProductoPrecio"
                             type="number"
                             step="0.01"
                             min="0"
                             placeholder="Ej: 35.90"
                             class="border border-sky-200 rounded-full w-full px-3 py-1.5 text-sm bg-white 
                                    focus:outline-none focus:ring-2 focus:ring-sky-400 focus:border-sky-400 transition">
                    </div>

                    <!-- Precio oferta -->
                    <div>
                      <label for="modalProductoPrecioOferta" class="block text-xs font-medium text-gray-700 mb-1">
                        Precio oferta (opcional)
                      </label>
                      <input id="modalProductoPrecioOferta"
                             type="number"
                             step="0.01"
                             min="0"
                             placeholder="Ej: 29.90"
                             class="border border-sky-200 rounded-full w-full px-3 py-1.5 text-sm bg-white 
                                    focus:outline-none focus:ring-2 focus:ring-sky-400 focus:border-sky-400 transition">
                    </div>

                    <!-- Moneda -->
                    <div>
                      <label for="modalProductoMoneda" class="block text-xs font-medium text-gray-700 mb-1">
                        Moneda
                      </label>
                      <select id="modalProductoMoneda"
                              class="border border-sky-200 rounded-full w-full px-3 py-1.5 text-sm bg-white 
                                     focus:outline-none focus:ring-2 focus:ring-sky-400 focus:border-sky-400 transition">
                        <option value="PEN">PEN - Soles</option>
                        <option value="USD">USD - Dólares</option>
                      </select>
                    </div>

                    <!-- Stock -->
                    <div>
                      <label for="modalProductoStock" class="block text-xs font-medium text-gray-700 mb-1">
                        Stock
                      </label>
                      <input id="modalProductoStock"
                             type="number"
                             min="0"
                             placeholder="Ej: 100"
                             class="border border-sky-200 rounded-full w-full px-3 py-1.5 text-sm bg-white 
                                    focus:outline-none focus:ring-2 focus:ring-sky-400 focus:border-sky-400 transition">
                    </div>

                    <!-- Tipo producto -->
                    <div>
                      <label for="modalProductoTipo" class="block text-xs font-medium text-gray-700 mb-1">
                        Tipo de producto
                      </label>
                      <select id="modalProductoTipo"
                              class="border border-sky-200 rounded-full w-full px-3 py-1.5 text-sm bg-white 
                                     focus:outline-none focus:ring-2 focus:ring-sky-400 focus:border-sky-400 transition">
                        <option value="simple">Simple</option>
                        <option value="variable">Variable</option>
                        <option value="servicio">Servicio</option>
                      </select>
                    </div>

                    <!-- Estado -->
                    <div>
                      <label for="modalProductoEstado" class="block text-xs font-medium text-gray-700 mb-1">
                        Estado
                      </label>
                      <select id="modalProductoEstado"
                              class="border border-sky-200 rounded-full w-full px-3 py-1.5 text-sm bg-white 
                                     focus:outline-none focus:ring-2 focus:ring-sky-400 focus:border-sky-400 transition">
                        <option value="1">Activo</option>
                        <option value="0">Inactivo</option>
                      </select>
                    </div>

                    <!-- Publicado -->
                    <div>
                      <label for="modalProductoPublicado" class="block text-xs font-medium text-gray-700 mb-1">
                        Publicado
                      </label>
                      <select id="modalProductoPublicado"
                              class="border border-sky-200 rounded-full w-full px-3 py-1.5 text-sm bg-white 
                                     focus:outline-none focus:ring-2 focus:ring-sky-400 focus:border-sky-400 transition">
                        <option value="1">Sí</option>
                        <option value="0">No</option>
                      </select>
                    </div>

                    <!-- Destacado -->
                    <div>
                      <label for="modalProductoDestacado" class="block text-xs font-medium text-gray-700 mb-1">
                        Destacado
                      </label>
                      <select id="modalProductoDestacado"
                              class="border border-sky-200 rounded-full w-full px-3 py-1.5 text-sm bg-white 
                                     focus:outline-none focus:ring-2 focus:ring-sky-400 focus:border-sky-400 transition">
                        <option value="0">No</option>
                        <option value="1">Sí</option>
                      </select>
                    </div>
                  </div>

                  <!-- Descripción corta -->
                  <div>
                    <label for="modalProductoDescripcionCorta" class="block text-xs font-medium text-gray-700 mb-1">
                      Descripción corta
                    </label>
                    <textarea id="modalProductoDescripcionCorta"
                              rows="3"
                              class="border border-sky-200 rounded-2xl w-full px-3 py-2 text-sm bg-white 
                                     focus:outline-none focus:ring-2 focus:ring-sky-400 focus:border-sky-400 transition resize-none"
                              placeholder="Breve descripción para mostrar en el catálogo."></textarea>
                  </div>
                </section>
              </div>

              <!-- FOOTER -->
              <div class="px-4 py-3 border-t bg-gradient-to-r from-sky-50 to-sky-100 flex justify-end gap-2">
                <button id="btnCancelarProducto"
                        type="button"
                        class="px-3 py-1.5 rounded-full border border-gray-200 text-sm hover:bg-white 
                               flex items-center gap-2 text-gray-700">
                  <i class="fas fa-times-circle text-sm"></i>
                  Cancelar
                </button>

                <button id="btnGuardarProducto"
                        class="bg-gradient-to-r from-sky-500 to-sky-600 hover:from-sky-600 hover:to-sky-700 
                               text-white px-3 py-1.5 rounded-full transition flex items-center gap-2 shadow-md text-sm">
                  <i class="fas fa-save text-sm"></i>
                  Guardar
                </button>
              </div>
            </div>
          </div>

                    <!-- MODAL IMÁGENES DE PRODUCTO -->
          <div id="modalProductoImagenes"
               class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50 hidden px-2 py-3">
            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-3xl max-h-[90vh] overflow-hidden flex flex-col 
                        border border-sky-200">

              <!-- HEADER -->
              <div class="px-4 py-3 border-b flex justify-between items-center 
                          bg-gradient-to-r from-sky-500 via-sky-400 to-sky-600">
                <div class="flex flex-col">
                  <h3 class="text-white font-semibold text-base flex items-center gap-2">
                    <i class="fas fa-images text-white text-sm"></i>
                    <span id="modalProductoImagenesTitulo">Imágenes del producto</span>
                  </h3>
                  <span class="text-[11px] text-sky-100/90">
                    Gestiona la galería, imagen principal y orden de las imágenes.
                  </span>
                </div>

                <button id="btnCerrarModalProductoImagenes"
                        class="text-white hover:text-sky-100 text-lg px-2 py-1 rounded-full hover:bg-white/10 transition">
                  <i class="fas fa-times"></i>
                </button>
              </div>

              <!-- CONTENIDO -->
              <div class="flex-1 overflow-auto p-4 space-y-4 text-[13px]">
                <!-- Formulario para agregar nueva imagen -->
                <section class="border border-sky-200 bg-sky-50/60 rounded-2xl p-3 space-y-3">
                  <h4 class="text-xs font-semibold text-sky-800 flex items-center gap-2">
                    <i class="fas fa-plus-circle text-sky-500 text-sm"></i>
                    Agregar imagen
                  </h4>

                  <div class="grid grid-cols-1 md:grid-cols-3 gap-3 items-end">
                    <div class="md:col-span-2">
                      <label for="inputNuevaImagenUrl" class="block text-xs font-medium text-gray-700 mb-1">
                        URL de la imagen
                      </label>
                      <input id="inputNuevaImagenUrl"
                             type="text"
                             placeholder="https://mi-cdn.com/imagen.jpg"
                             class="border border-sky-200 rounded-full w-full px-3 py-1.5 text-sm bg-white 
                                    focus:outline-none focus:ring-2 focus:ring-sky-400 focus:border-sky-400 transition">
                    </div>

                    <div class="flex flex-col gap-2">
                      <label class="inline-flex items-center gap-2 text-xs text-gray-700">
                        <input id="chkNuevaImagenPrincipal" type="checkbox"
                               class="rounded border-sky-300 text-sky-600 focus:ring-sky-500">
                        <span>Marcar como principal</span>
                      </label>

                      <button id="btnAgregarImagenProducto"
                              class="bg-gradient-to-r from-sky-500 to-sky-600 hover:from-sky-600 hover:to-sky-700 
                                     text-white px-3 py-1.5 rounded-full transition flex items-center gap-2 shadow-md text-xs">
                        <i class="fas fa-save text-sm"></i>
                        Agregar
                      </button>
                    </div>
                  </div>
                </section>

                <!-- Lista de imágenes -->
                <section class="space-y-2">
                  <div class="flex items-center justify-between">
                    <h4 class="text-xs font-semibold text-sky-800 flex items-center gap-2">
                      <i class="fas fa-photo-video text-sky-500 text-sm"></i>
                      Galería de imágenes
                    </h4>
                  </div>

                  <div id="contenedorImagenesProducto"
                       class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
                    <!-- Aquí se insertarán las tarjetas de imágenes por JS -->
                  </div>

                  <div id="mensajeSinImagenesProducto" class="hidden">
                    <div class="border border-dashed border-sky-200 rounded-2xl bg-sky-50/60 p-4 flex items-center justify-center">
                      <div class="flex items-center gap-3 text-sky-700 text-sm">
                        <i class="fas fa-info-circle"></i>
                        <span>No hay imágenes registradas para este producto.</span>
                      </div>
                    </div>
                  </div>
                </section>
              </div>

              <!-- FOOTER -->
              <div class="px-4 py-3 border-t bg-gradient-to-r from-sky-50 to-sky-100 flex justify-end gap-2">
                <button id="btnCerrarModalProductoImagenesFooter"
                        type="button"
                        class="px-3 py-1.5 rounded-full border border-gray-200 text-sm hover:bg-white 
                               flex items-center gap-2 text-gray-700">
                  <i class="fas fa-times-circle text-sm"></i>
                  Cerrar
                </button>
              </div>
            </div>
          </div>

        </main>
         <?php include '../footerlogin.php'; ?>
      </div>
    </div>

    <script src="../js/logout.js?v=<?php echo time(); ?>"></script>
    <script src="../js/producto.js?v=<?php echo time(); ?>"></script>
    <script src="../js/config.js?v=<?php echo time(); ?>"></script>
  </body>
</html>
