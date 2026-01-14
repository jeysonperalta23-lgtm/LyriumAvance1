<!-- C:\xampp\htdocs\schedule\frontend\view\cliente.php -->
<!DOCTYPE html>
<html lang="es">
  <?php include '../headlogin.php'; ?>
  <body class="bg-[#03A9F4] text-gray-800">
    <!-- Contenedor principal -->
    <div class="flex flex-col md:flex-row min-h-screen">
      <?php include '../asider.php'; ?>

      <!-- Contenedor de contenido -->
      <div id="main-content" class="flex flex-col flex-1 min-h-screen transition-all duration-300 ease-in-out">

        <style>
          :root {
            --dominant-blue: #03A9F4;
            --dominant-blue-light: #4FC3F7;
            --dominant-blue-dark: #0288D1;
          }

          /* ======================= ESTILO TARJETAS ======================= */
          .cliente-card-hover {
            transition: transform 0.18s ease, box-shadow 0.18s ease,
                        background-color 0.18s ease, border-color 0.18s ease;
          }
          .cliente-card-hover:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(15, 118, 178, 0.20);
          }
        </style>

        <!-- Contenido -->
        <main class="flex-grow p-3 sm:p-4 lg:p-6 bg-gray-50 overflow-hidden">
          <div class="bg-white shadow-lg rounded-3xl p-3 sm:p-4 lg:p-6 border border-sky-200 w-full">
            <div class="space-y-6">

              <!-- PANEL SUPERIOR COLAPSABLE (Filtros + Acciones) -->
              <div id="panelControlesClientes" class="space-y-6 hidden">
                <!-- Título + acciones -->
                <section>
                  <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                    <div>
                      <h2 class="text-lg md:text-xl font-semibold flex items-center gap-2 text-sky-800">
                        <span class="flex items-center justify-center w-9 h-9 rounded-full 
                                     bg-gradient-to-br from-sky-400 to-sky-700
                                     text-white shadow-md">
                          <i class="fas fa-address-card text-sm"></i>
                        </span>
                        Clientes
                      </h2>
                      <p class="text-xs text-gray-500 mt-1">
                        Registro y administración de clientes del marketplace.
                      </p>
                    </div>

                    <div class="flex flex-row flex-wrap gap-2">
                      <!-- Exportar CSV -->
                      <button onclick="exportarCSVClientes()"
                              class="flex items-center gap-2 bg-sky-500 hover:bg-sky-600 text-white 
                                     px-4 py-2 rounded-full text-xs sm:text-sm shadow-md transition">
                        <i class="fas fa-file-excel text-base"></i>
                        Exportar CSV
                      </button>

                      <!-- Nuevo cliente -->
                      <button id="btnAbrirModalCliente"
                              class="flex items-center gap-2 bg-sky-600 hover:bg-sky-700 text-white 
                                     px-4 py-2 rounded-full text-xs sm:text-sm shadow-md transition">
                        <i class="fas fa-plus-circle text-base"></i>
                        Nuevo cliente
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
                      Busca por nombre, documento o correo y limita el número de resultados.
                    </span>
                  </div>

                  <div class="flex flex-col lg:flex-row lg:flex-wrap lg:items-center gap-3 w-full">
                    <!-- Buscador -->
                    <form autocomplete="off" onsubmit="return false;" class="w-full sm:w-96 lg:w-[32rem]">
                      <div class="relative w-full">
                        <input
                          type="search"
                          id="inputBuscarCliente"
                          name="buscador_cliente"
                          placeholder="Buscar por nombre, documento o correo..."
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
                      <label for="selectPaginationCliente" class="text-xs text-gray-600">
                        Resultados por página
                      </label>
                      <select id="selectPaginationCliente"
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

              <!-- LISTADO COMO CATÁLOGO DE TARJETAS -->
              <section class="space-y-3">
                <div class="flex items-center justify-between gap-3">
                  <div>
                    <h3 class="text-sm font-semibold text-sky-800 flex items-center gap-2">
                      <span class="inline-flex items-center justify-center w-8 h-8 rounded-full 
                                   bg-gradient-to-br from-sky-400 to-sky-700 text-white shadow-md">
                        <i class="fas fa-th-large text-xs"></i>
                      </span>
                      Catálogo de clientes
                    </h3>
                    <span class="text-[11px] text-gray-500 hidden sm:inline">
                      Visualiza los clientes como tarjetas, con tipo, contacto y estado.
                    </span>
                  </div>

                  <!-- Botón mostrar/ocultar filtros -->
                  <div class="flex items-center gap-2">
                    <button id="btnTogglePanelClientes"
                            type="button"
                            class="flex items-center gap-1 px-3 py-1.5 rounded-full border border-sky-200 bg-white 
                                   text-[11px] sm:text-xs text-sky-800 hover:bg-sky-50 hover:border-sky-300 
                                   hover:text-sky-800 transition shadow-sm">
                      <i class="fas fa-chevron-down text-[10px]"></i>
                      Mostrar/Ocultar filtros
                    </button>
                  </div>
                </div>

                <!-- Tarjetas -->
                <div class="w-full mt-1">
                  <div id="clientesCardsContainer"
                       class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                    <!-- Skeleton inicial -->
                    <div id="clientesCardsSkeleton"
                         class="col-span-1 sm:col-span-2 lg:col-span-3 xl:col-span-4">
                      <div class="w-full border border-dashed border-sky-200 rounded-2xl bg-sky-50/60 p-4 flex items-center justify-center">
                        <div class="flex items-center gap-3 text-sky-700 text-sm">
                          <i class="fas fa-circle-notch fa-spin"></i>
                          <span>Cargando clientes...</span>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </section>

            </div>
          </div>

          <!-- MODAL CLIENTE -->
          <div id="modalCliente"
               class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50 hidden px-2 py-3">
            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-xl max-h-[90vh] overflow-hidden flex flex-col 
                        border border-sky-200">
              <!-- Header -->
              <div class="px-4 py-3 border-b flex justify-between items-center 
                          bg-gradient-to-r from-sky-500 via-sky-400 to-sky-600">
                <div class="flex flex-col">
                  <h3 class="text-white font-semibold text-base flex items-center gap-2">
                    <i class="fas fa-address-card text-white text-sm"></i>
                    <span id="modalTituloCliente">Nuevo cliente</span>
                  </h3>
                  <span class="text-[11px] text-sky-100/90">
                    Completa los datos generales y de contacto del cliente.
                  </span>
                </div>

                <button id="btnCerrarModalCliente"
                        class="text-white hover:text-sky-100 text-lg px-2 py-1 rounded-full hover:bg-white/10 transition">
                  <i class="fas fa-times"></i>
                </button>
              </div>

              <!-- Contenido -->
              <div class="flex-1 overflow-auto p-4 space-y-4 text-[13px]">
                <!-- Sección: Datos básicos -->
                <div class="space-y-2">
                  <div class="flex items-center justify-between cursor-pointer select-none"
                       onclick="toggleSeccionCliente('basico')">
                    <div class="flex items-center gap-2">
                      <i class="fas fa-user text-sky-500 text-sm"></i>
                      <h4 class="text-sm font-semibold text-gray-700">
                        Datos básicos
                      </h4>
                    </div>
                    <button type="button"
                            class="text-[11px] text-sky-600 flex items-center gap-1">
                      <span id="labelClienteBasico" class="hidden sm:inline">Ocultar</span>
                      <i id="iconClienteBasico" class="fas fa-chevron-up text-[10px]"></i>
                    </button>
                  </div>

                  <div id="cuerpoClienteBasico"
                       class="grid grid-cols-1 md:grid-cols-2 gap-3 bg-sky-50 border border-sky-200 rounded-2xl p-3">
                    <!-- Nombre / Razón social -->
                    <div class="md:col-span-2">
                      <label for="clienteNombre" class="block text-xs font-medium text-gray-700 mb-1">
                        Nombre / Razón social <span class="text-red-500">*</span>
                      </label>
                      <input id="clienteNombre"
                             type="text"
                             placeholder="Ej: Juan Abad Yacila"
                             class="border border-sky-200 rounded-full w-full px-3 py-1.5 text-sm bg-white 
                                    focus:outline-none focus:ring-2 focus:ring-sky-400 focus:border-sky-400 transition">
                    </div>

                    <!-- Tipo de cliente -->
                    <div>
                      <label for="clienteTipo" class="block text-xs font-medium text-gray-700 mb-1">
                        Tipo de cliente <span class="text-red-500">*</span>
                      </label>
                      <select id="clienteTipo"
                              class="border border-sky-200 rounded-full w-full px-3 py-1.5 text-sm bg-white 
                                     focus:outline-none focus:ring-2 focus:ring-sky-400 focus:border-sky-400 transition">
                        <option value="">Seleccione</option>
                        <option value="Persona">Persona</option>
                        <option value="Empresa">Empresa</option>
                      </select>
                    </div>

                    <!-- Tipo documento -->
                    <div>
                      <label for="clienteDocumentoTipo" class="block text-xs font-medium text-gray-700 mb-1">
                        Tipo documento <span class="text-red-500">*</span>
                      </label>
                      <select id="clienteDocumentoTipo"
                              class="border border-sky-200 rounded-full w-full px-3 py-1.5 text-sm bg-white 
                                     focus:outline-none focus:ring-2 focus:ring-sky-400 focus:border-sky-400 transition">
                        <option value="">Seleccione</option>
                        <option value="DNI">DNI</option>
                        <option value="CE">Carné de extranjería</option>
                        <option value="RUC">RUC</option>
                        <option value="PASAPORTE">Pasaporte</option>
                      </select>
                    </div>

                    <!-- N° documento -->
                    <div>
                      <label for="clienteDocumentoNumero" class="block text-xs font-medium text-gray-700 mb-1">
                        N° documento <span class="text-red-500">*</span>
                      </label>
                      <input id="clienteDocumentoNumero"
                             type="text"
                             maxlength="20"
                             placeholder="Ej: 75418704"
                             class="border border-sky-200 rounded-full w-full px-3 py-1.5 text-sm bg-white 
                                    focus:outline-none focus:ring-2 focus:ring-sky-400 focus:border-sky-400 transition">
                    </div>
                  </div>
                </div>

                <!-- Sección: Contacto -->
                <div class="space-y-2">
                  <div class="flex items-center justify-between cursor-pointer select-none"
                       onclick="toggleSeccionCliente('contacto')">
                    <div class="flex items-center gap-2">
                      <i class="fas fa-phone-alt text-sky-500 text-sm"></i>
                      <h4 class="text-sm font-semibold text-gray-700">
                        Datos de contacto
                      </h4>
                    </div>
                    <button type="button"
                            class="text-[11px] text-sky-600 flex items-center gap-1">
                      <span id="labelClienteContacto" class="hidden sm:inline">Ocultar</span>
                      <i id="iconClienteContacto" class="fas fa-chevron-up text-[10px]"></i>
                    </button>
                  </div>

                  <div id="cuerpoClienteContacto"
                       class="grid grid-cols-1 md:grid-cols-2 gap-3 bg-sky-50 border border-sky-200 rounded-2xl p-3">
                    <!-- Correo -->
                    <div>
                      <label for="clienteCorreo" class="block text-xs font-medium text-gray-700 mb-1">
                        Correo electrónico
                      </label>
                      <input id="clienteCorreo"
                             type="email"
                             placeholder="Ej: cliente@correo.com"
                             class="border border-sky-200 rounded-full w-full px-3 py-1.5 text-sm bg-white 
                                    focus:outline-none focus:ring-2 focus:ring-sky-400 focus:border-sky-400 transition">
                    </div>

                    <!-- Teléfono -->
                    <div>
                      <label for="clienteTelefono" class="block text-xs font-medium text-gray-700 mb-1">
                        Teléfono / Celular
                      </label>
                      <input id="clienteTelefono"
                             type="text"
                             placeholder="Ej: 987654321"
                             class="border border-sky-200 rounded-full w-full px-3 py-1.5 text-sm bg-white 
                                    focus:outline-none focus:ring-2 focus:ring-sky-400 focus:border-sky-400 transition">
                    </div>

                    <!-- Dirección -->
                    <div class="md:col-span-2">
                      <label for="clienteDireccion" class="block text-xs font-medium text-gray-700 mb-1">
                        Dirección
                      </label>
                      <input id="clienteDireccion"
                             type="text"
                             placeholder="Calle, número, referencia..."
                             class="border border-sky-200 rounded-full w-full px-3 py-1.5 text-sm bg-white 
                                    focus:outline-none focus:ring-2 focus:ring-sky-400 focus:border-sky-400 transition">
                    </div>

                    <!-- Ciudad -->
                    <div>
                      <label for="clienteCiudad" class="block text-xs font-medium text-gray-700 mb-1">
                        Ciudad
                      </label>
                      <input id="clienteCiudad"
                             type="text"
                             placeholder="Ej: Piura"
                             class="border border-sky-200 rounded-full w-full px-3 py-1.5 text-sm bg-white 
                                    focus:outline-none focus:ring-2 focus:ring-sky-400 focus:border-sky-400 transition">
                    </div>

                    <!-- Observaciones -->
                    <div class="md:col-span-2">
                      <label for="clienteObservaciones" class="block text-xs font-medium text-gray-700 mb-1">
                        Observaciones
                      </label>
                      <textarea id="clienteObservaciones"
                                rows="2"
                                class="border border-sky-200 rounded-2xl w-full px-3 py-1.5 text-sm bg-white 
                                       focus:outline-none focus:ring-2 focus:ring-sky-400 focus:border-sky-400 transition resize-none"
                                placeholder="Notas internas sobre el cliente, condiciones especiales, etc."></textarea>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Footer modal -->
              <div class="px-4 py-3 border-t bg-gradient-to-r from-sky-50 to-sky-100 flex justify-end gap-2">
                <button id="btnCancelarCliente"
                        type="button"
                        class="px-3 py-1.5 rounded-full border border-gray-200 text-sm hover:bg-white 
                               flex items-center gap-2 text-gray-700">
                  <i class="fas fa-times-circle text-sm"></i>
                  Cancelar
                </button>

                <button id="btnGuardarCliente"
                        class="bg-gradient-to-r from-sky-500 to-sky-600 hover:from-sky-600 hover:to-sky-700 
                               text-white px-3 py-1.5 rounded-full transition flex items-center gap-2 shadow-md text-sm">
                  <i class="fas fa-save text-sm"></i>
                  Guardar
                </button>
              </div>
            </div>
          </div>
        </main>
        <?php include '../footerlogin.php'; ?>
      </div>
    </div>

    <script src="../js/logout.js?v=<?php echo time(); ?>"></script>
    <script src="../js/cliente.js?v=<?php echo time(); ?>"></script>
    <script src="../js/config.js?v=<?php echo time(); ?>"></script>
  </body>
</html>
