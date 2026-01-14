<!-- C:\xampp\htdocs\schedule\frontend\view\usuario.php -->
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

          /* ======================= THEME LYRIUM PARA AG-GRID (por si se reutiliza) ======================= */
          .ag-theme-lyrium {
            font-size: 13px;
            --lyrium-lime: var(--dominant-blue);
            --lyrium-green: var(--dominant-blue-dark);
            --lyrium-cyan: var(--dominant-blue-light);
            --lyrium-bg-row: #f5fbff;
          }

          .ag-theme-lyrium .ag-header {
            background: linear-gradient(
              90deg,
              var(--dominant-blue),
              var(--dominant-blue-dark),
              var(--dominant-blue-light)
            );
            color: #ffffff;
            border-bottom: 1px solid rgba(255, 255, 255, 0.25);
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.08);
          }

          .ag-theme-lyrium .ag-header-viewport,
          .ag-theme-lyrium .ag-header-container {
            border-radius: 20px 20px 0 0;
            overflow: hidden;
          }

          .ag-theme-lyrium .ag-header-row {
            border-bottom: 0;
          }

          .ag-theme-lyrium .ag-header-cell,
          .ag-theme-lyrium .ag-header-group-cell {
            border-right: 1px solid rgba(255, 255, 255, 0.2);
            background: transparent;
          }

          .ag-theme-lyrium .ag-header-cell-label {
            display: flex;
            align-items: center;
            gap: 6px;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            font-size: 11px;
            font-weight: 600;
          }

          .ag-theme-lyrium .ag-header-cell-text {
            color: #ffffff;
          }

          .ag-theme-lyrium .ag-icon {
            filter: brightness(1.9);
          }

          .ag-theme-lyrium .ag-center-cols-container,
          .ag-theme-lyrium .ag-row {
            background-color: #ffffff;
            color: #022c22;
          }

          .ag-theme-lyrium .ag-row-odd {
            background: linear-gradient(90deg, #f5fbff 0%, #e8f6ff 100%);
          }

          .ag-theme-lyrium .ag-row-hover {
            background: radial-gradient(
              circle at left,
              rgba(3, 169, 244, 0.18),
              transparent 60%
            );
          }

          .ag-theme-lyrium .ag-row-selected {
            background: linear-gradient(
              90deg,
              rgba(2, 136, 209, 0.16),
              rgba(3, 169, 244, 0.16)
            ) !important;
            box-shadow: inset 0 0 0 1px rgba(2, 136, 209, 0.45);
          }

          .ag-theme-lyrium .ag-cell,
          .ag-theme-lyrium .ag-row {
            border-color: rgba(148, 163, 184, 0.35);
          }

          .ag-theme-lyrium .ag-cell {
            padding-top: 6px;
            padding-bottom: 6px;
          }

          .ag-theme-lyrium .ag-pinned-left-header,
          .ag-theme-lyrium .ag-pinned-right-header {
            background: linear-gradient(
              90deg,
              var(--dominant-blue),
              var(--dominant-blue-dark),
              var(--dominant-blue-light)
            );
          }

          .ag-theme-lyrium .ag-body-horizontal-scroll-viewport::-webkit-scrollbar,
          .ag-theme-lyrium .ag-body-vertical-scroll-viewport::-webkit-scrollbar {
            height: 7px;
            width: 7px;
          }
          .ag-theme-lyrium .ag-body-horizontal-scroll-viewport::-webkit-scrollbar-track,
          .ag-theme-lyrium .ag-body-vertical-scroll-viewport::-webkit-scrollbar-track {
            background: #e0f4ff;
          }
          .ag-theme-lyrium .ag-body-horizontal-scroll-viewport::-webkit-scrollbar-thumb,
          .ag-theme-lyrium .ag-body-vertical-scroll-viewport::-webkit-scrollbar-thumb {
            background: linear-gradient(
              180deg,
              var(--dominant-blue),
              var(--dominant-blue-dark)
            );
            border-radius: 999px;
          }

          .ag-theme-lyrium .ag-paging-panel {
            border-top: 1px solid rgba(148, 163, 184, 0.35);
            background: linear-gradient(90deg, #f2f9ff, #e6f4ff);
            font-size: 12px;
            color: #0c4a6e;
          }

          .ag-theme-lyrium .ag-paging-button {
            border-radius: 999px;
            border-color: rgba(148, 163, 184, 0.6);
          }

          .ag-theme-lyrium .ag-paging-button:hover {
            background: rgba(3, 169, 244, 0.12);
            border-color: var(--dominant-blue);
          }

          /* ======================= ESTILOS TARJETAS USUARIOS ======================= */
          .user-card-hover {
            transition: transform 0.18s ease, box-shadow 0.18s ease,
                        background-color 0.18s ease, border-color 0.18s ease;
          }
          .user-card-hover:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(15, 118, 178, 0.20);
          }
        </style>

        <!-- Contenido -->
        <main class="flex-grow p-3 sm:p-4 lg:p-6 bg-gray-50 overflow-hidden">
          <div class="bg-white shadow-lg rounded-3xl p-3 sm:p-4 lg:p-6 border border-sky-200 w-full">

            <div class="space-y-6">

              <!-- ========== PANEL SUPERIOR: USUARIOS + FILTROS (COLAPSABLE) ========== -->
              <div id="panelControlesUsuarios" class="space-y-6 hidden">
                <!-- ================== 1) TÍTULO Y ACCIONES ================== -->
                <section>
                  <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                    <div>
                      <h2 class="text-lg md:text-xl font-semibold flex items-center gap-2 text-sky-800">
                        <span class="flex items-center justify-center w-9 h-9 rounded-full 
                                     bg-gradient-to-br from-sky-400 to-sky-700
                                     text-white shadow-md">
                          <i class="fas fa-users text-sm"></i>
                        </span>
                        Usuarios
                      </h2>
                      <p class="text-xs text-gray-500 mt-1">
                        Gestión y administración de usuarios del sistema.
                      </p>
                    </div>

                    <!-- Botones alineados a la derecha -->
                    <div class="flex flex-row flex-wrap gap-2">
                      <!-- Exportar CSV -->
                      <button onclick="exportarCSV()"
                              class="flex items-center gap-2 bg-sky-500 hover:bg-sky-600 text-white 
                                     px-4 py-2 rounded-full text-xs sm:text-sm shadow-md transition">
                        <i class="fas fa-file-excel text-base"></i>
                        Exportar CSV
                      </button>

                      <!-- Nuevo usuario -->
                      <button id="btnAbrirModal"
                              class="flex items-center gap-2 bg-sky-600 hover:bg-sky-700 text-white 
                                     px-4 py-2 rounded-full text-xs sm:text-sm shadow-md transition">
                        <i class="fas fa-plus-circle text-base"></i>
                        Nuevo usuario
                      </button>
                    </div>
                  </div>
                </section>

                <!-- ================== 2) FILTROS ================== -->
                <section class="border border-sky-200 rounded-2xl bg-sky-50/60 px-4 py-3">
                  <div class="flex items-center justify-between mb-3 gap-2">
                    <h3 class="text-sm font-semibold text-sky-800 flex items-center gap-2">
                      <i class="fas fa-filter text-sky-500"></i>
                      Filtros de búsqueda
                    </h3>
                    <span class="text-[11px] text-gray-500 hidden sm:inline">
                      Busca por correo, usuario u otros campos y limita el número de resultados.
                    </span>
                  </div>

                  <div class="flex flex-col lg:flex-row lg:flex-wrap lg:items-center gap-3 w-full">
                    <!-- Buscador -->
                    <form autocomplete="off" onsubmit="return false;" class="w-full sm:w-96 lg:w-[32rem]">
                      <div class="relative w-full">
                        <input
                          type="search"
                          id="inputBuscar"
                          name="buscador_usuario"
                          placeholder="Buscar por correo, usuario..."
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
                      <label for="selectPagination" class="text-xs text-gray-600">
                        Resultados por página
                      </label>
                      <select id="selectPagination"
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
              </div> <!-- /#panelControlesUsuarios -->

              <!-- ================== 3) LISTADO COMO CATÁLOGO DE TARJETAS ================== -->
              <section class="space-y-3">
                <div class="flex items-center justify-between gap-3">
                  <div>
                    <h3 class="text-sm font-semibold text-sky-800 flex items-center gap-2">
                      <span class="inline-flex items-center justify-center w-8 h-8 rounded-full 
                                   bg-gradient-to-br from-sky-400 to-sky-700 text-white shadow-md">
                        <i class="fas fa-th-large text-xs"></i>
                      </span>
                      Catálogo de usuarios
                    </h3>
                    <span class="text-[11px] text-gray-500 hidden sm:inline">
                      Visualiza los usuarios como tarjetas, con su avatar, rol y estado.
                    </span>
                  </div>

                  <!-- Botón para ocultar/mostrar panel superior -->
                  <div class="flex items-center gap-2">
                    <button id="btnTogglePanelUsuarios"
                            type="button"
                            class="flex items-center gap-1 px-3 py-1.5 rounded-full border border-sky-200 bg-white 
                                   text-[11px] sm:text-xs text-sky-800 hover:bg-sky-50 hover:border-sky-300 
                                   hover:text-sky-800 transition shadow-sm">
                      <i class="fas fa-chevron-down text-[10px]"></i>
                      Mostrar/Ocultar filtros
                    </button>
                  </div>
                </div>

                <!-- Contenedor de tarjetas de usuarios -->
                <div class="w-full mt-1">
                  <div id="usuariosCardsContainer"
                       class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                    <!-- Estado inicial mientras JS carga datos -->
                    <div id="usuariosCardsSkeleton"
                         class="col-span-1 sm:col-span-2 lg:col-span-3 xl:col-span-4">
                      <div class="w-full border border-dashed border-sky-200 rounded-2xl bg-sky-50/60 p-4 flex items-center justify-center">
                        <div class="flex items-center gap-3 text-sky-700 text-sm">
                          <i class="fas fa-circle-notch fa-spin"></i>
                          <span>Cargando usuarios...</span>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </section>

            </div>
          </div>

          <!-- ================== MODAL USUARIO (DATOS GENERALES) ================== -->
          <div id="modalUsuario"
               class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50 hidden px-2 py-3">
            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-xl max-h-[90vh] overflow-hidden flex flex-col 
                        border border-sky-200">

              <!-- HEADER tipo Guías (más compacto) -->
              <div class="px-4 py-3 border-b flex justify-between items-center 
                          bg-gradient-to-r from-sky-500 via-sky-400 to-sky-600">
                <div class="flex flex-col">
                  <h3 class="text-white font-semibold text-base flex items-center gap-2">
                    <i class="fas fa-user-cog text-white text-sm"></i>
                    <span id="modalTitulo">Nuevo usuario</span>
                  </h3>
                  <span class="text-[11px] text-sky-100/90">
                    Completa los datos de acceso y el perfil del usuario.
                  </span>
                </div>

                <button id="btnCerrarModal"
                        class="text-white hover:text-sky-100 text-lg px-2 py-1 rounded-full hover:bg-white/10 transition">
                  <i class="fas fa-times"></i>
                </button>
              </div>

              <!-- CONTENIDO (más compacto) -->
              <div class="flex-1 overflow-auto p-4 space-y-4 text-[13px]">

                <!-- Sección: Datos de acceso -->
                <div id="seccionDatosAcceso" class="space-y-2">
                  <div class="flex items-center justify-between cursor-pointer select-none"
                       onclick="toggleSeccionUsuario('acceso')">
                    <div class="flex items-center gap-2">
                      <i class="fas fa-key text-sky-500 text-sm"></i>
                      <h4 class="text-sm font-semibold text-gray-700">
                        Datos de acceso
                      </h4>
                    </div>
                    <button type="button"
                            class="text-[11px] text-sky-600 flex items-center gap-1">
                      <span id="labelAcceso" class="hidden sm:inline">Ocultar</span>
                      <i id="iconAcceso" class="fas fa-chevron-up text-[10px]"></i>
                    </button>
                  </div>

                  <div id="cuerpoDatosAcceso"
                       class="grid grid-cols-1 md:grid-cols-2 gap-3 bg-sky-50 border border-sky-200 rounded-2xl p-3">
                    <!-- Correo -->
                    <div>
                      <label for="modalCorreo" class="block text-xs font-medium text-gray-700 mb-1">
                        Correo electrónico <span class="text-red-500">*</span>
                      </label>
                      <input id="modalCorreo"
                             type="email"
                             placeholder="Ej: usuario@correo.com"
                             class="border border-sky-200 rounded-full w-full px-3 py-1.5 text-sm bg-white 
                                    focus:outline-none focus:ring-2 focus:ring-sky-400 focus:border-sky-400 transition">
                      <p class="text-[11px] text-gray-400 mt-1">
                        Se usará para el acceso y notificaciones.
                      </p>
                    </div>

                    <!-- Contraseña -->
                    <div>
                      <label for="modalContrasena" class="block text-xs font-medium text-gray-700 mb-1">
                        Contraseña <span class="text-red-500">*</span>
                      </label>
                      <input id="modalContrasena"
                             type="password"
                             placeholder="••••••••"
                             class="border border-sky-200 rounded-full w-full px-3 py-1.5 text-sm bg-white 
                                    focus:outline-none focus:ring-2 focus:ring-sky-400 focus:border-sky-400 transition">
                      <p class="text-[11px] text-gray-400 mt-1">
                        Mínimo 8 caracteres recomendados.
                      </p>
                    </div>
                  </div>
                </div>

                <!-- Sección: Datos de la persona -->
                <div id="seccionDatosPersona" class="space-y-2">
                  <div class="flex items-center justify-between cursor-pointer select-none"
                       onclick="toggleSeccionUsuario('persona')">
                    <div class="flex items-center gap-2">
                      <i class="fas fa-user text-sky-500 text-sm"></i>
                      <h4 class="text-sm font-semibold text-gray-700">
                        Datos de la persona
                      </h4>
                    </div>
                    <button type="button"
                            class="text-[11px] text-sky-600 flex items-center gap-1">
                      <span id="labelPersona" class="hidden sm:inline">Ocultar</span>
                      <i id="iconPersona" class="fas fa-chevron-up text-[10px]"></i>
                    </button>
                  </div>

                  <div id="cuerpoDatosPersona"
                       class="grid grid-cols-1 md:grid-cols-2 gap-3 bg-sky-50 border border-sky-200 rounded-2xl p-3">
                    <!-- Nombre / Razón social -->
                    <div>
                      <label for="modalNombrePersona" class="block text-xs font-medium text-gray-700 mb-1">
                        Nombre / Razón social <span class="text-red-500">*</span>
                      </label>
                      <input id="modalNombrePersona"
                             type="text"
                             placeholder="Ej: Juan Abad Yacila"
                             class="border border-sky-200 rounded-full w-full px-3 py-1.5 text-sm bg-white 
                                    focus:outline-none focus:ring-2 focus:ring-sky-400 focus:border-sky-400 transition">
                    </div>

                    <!-- Documento de identidad -->
                    <div>
                      <label for="modalDocumentoIdentidad" class="block text-xs font-medium text-gray-700 mb-1">
                        Documento de identidad <span class="text-red-500">*</span>
                      </label>
                      <input id="modalDocumentoIdentidad"
                             type="text"
                             maxlength="20"
                             placeholder="Ej: 75418704"
                             class="border border-sky-200 rounded-full w-full px-3 py-1.5 text-sm bg-white 
                                    focus:outline-none focus:ring-2 focus:ring-sky-400 focus:border-sky-400 transition">
                    </div>

                    <!-- Fecha de nacimiento -->
                    <div>
                      <label for="modalFechaNacimiento" class="block text-xs font-medium text-gray-700 mb-1">
                        Fecha de nacimiento
                      </label>
                      <input id="modalFechaNacimiento"
                             type="date"
                             class="border border-sky-200 rounded-full w-full px-3 py-1.5 text-sm bg-white 
                                    focus:outline-none focus:ring-2 focus:ring-sky-400 focus:border-sky-400 transition">
                    </div>

                    <!-- Sexo -->
                    <div>
                      <label for="modalSexo" class="block text-xs font-medium text-gray-700 mb-1">
                        Sexo
                      </label>
                      <select id="modalSexo"
                              class="border border-sky-200 rounded-full w-full px-3 py-1.5 text-sm bg-white 
                                     focus:outline-none focus:ring-2 focus:ring-sky-400 focus:border-sky-400 transition">
                        <option value="">No especifica</option>
                        <option value="M">Masculino</option>
                        <option value="F">Femenino</option>
                      </select>
                    </div>
                  </div>
                </div>

                <!-- Sección: Perfil del usuario + Avatar (general) -->
                <div id="seccionPerfilUsuario" class="space-y-2">
                  <div class="flex items-center justify-between cursor-pointer select-none"
                       onclick="toggleSeccionUsuario('perfil')">
                    <div class="flex items-center gap-2">
                      <i class="fas fa-id-badge text-sky-500 text-sm"></i>
                      <h4 class="text-sm font-semibold text-gray-700">
                        Perfil del usuario
                      </h4>
                    </div>
                  <button type="button"
                          class="text-[11px] text-sky-600 flex items-center gap-1">
                    <span id="labelPerfil" class="hidden sm:inline">Ocultar</span>
                    <i id="iconPerfil" class="fas fa-chevron-up text-[10px]"></i>
                  </button>
                  </div>

                  <div id="cuerpoPerfilUsuario" class="space-y-3">
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-3 bg-sky-50 border border-sky-200 rounded-2xl p-3">
                      <!-- Username -->
                      <div class="lg:col-span-2">
                        <label for="modalUsername" class="block text-xs font-medium text-gray-700 mb-1">
                          Nombre de usuario <span class="text-red-500">*</span>
                        </label>
                        <input id="modalUsername"
                               type="text"
                               placeholder="Ej: usuario123"
                               class="border border-sky-200 rounded-full w-full px-3 py-1.5 text-sm bg-white 
                                      focus:outline-none focus:ring-2 focus:ring-sky-400 focus:border-sky-400 transition">
                        <p class="text-[11px] text-gray-400 mt-1">
                          Alias que se mostrará en el sistema.
                        </p>
                      </div>

                      <!-- Rol -->
                      <div>
                        <label for="modalRol" class="block text-xs font-medium text-gray-700 mb-1">
                          Rol <span class="text-red-500">*</span>
                        </label>
                        <select id="modalRol"
                                class="border border-sky-200 rounded-full w-full px-3 py-1.5 text-sm bg-white 
                                       focus:outline-none focus:ring-2 focus:ring-sky-400 focus:border-sky-400 transition">
                          <option value="">Seleccione un rol</option>
                          <option value="Administrador">Administrador</option>
                          <option value="Cliente">Cliente</option>
                          <option value="Vendedor">Vendedor</option>
                        </select>
                        <p class="text-[11px] text-gray-400 mt-1">
                          Define los permisos y accesos del usuario.
                        </p>
                      </div>
                    </div>

                    <!-- Confirmar cambios Estado / Realizado por -->
                    <div class="mt-2 bg-sky-50 border border-sky-200 rounded-2xl p-3 flex items-start gap-3">
                      <div>
                        <input
                          id="modalConfirmarCambios"
                          type="checkbox"
                          class="rounded border-gray-300 mt-1"
                          checked
                        >
                      </div>
                      <div class="text-[12px] text-gray-700">
                        <p class="font-semibold">
                          Confirmar cambios de <span class="italic">Estado</span> y <span class="italic">Realizado por</span> en Permisos
                        </p>
                        <p class="mt-1 text-gray-500">
                          Si está marcado, al cambiar el estado o el usuario que realizó la tarea en el módulo
                          <span class="font-semibold">Permisos</span> se solicitará confirmación.
                        </p>
                      </div>
                    </div>

                    <!-- Avatar / Identidad visual -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3 bg-gradient-to-br from-sky-50 via-white to-sky-100 
                                border border-dashed border-sky-200 rounded-2xl p-3 mt-1">
                      <!-- Preview avatar -->
                      <div class="flex flex-col items-center justify-center gap-2">
                        <span class="text-[11px] font-semibold text-gray-600 uppercase tracking-wide">
                          Avatar
                        </span>
                        <div id="avatarPreviewWrapper"
                             class="relative w-16 h-16 rounded-full flex items-center justify-center shadow-inner 
                                    border border-sky-200 overflow-hidden 
                                    bg-gradient-to-br from-sky-400 via-sky-500 to-sky-600">
                          <span id="avatarPreviewInitials" class="text-base font-bold text-white select-none">
                            U
                          </span>
                          <img id="avatarPreviewImg"
                               src=""
                               alt="Avatar usuario"
                               class="absolute inset-0 w-full h-full object-cover hidden">
                        </div>
                        <p class="text-[10px] text-gray-500 text-center">
                          Si no se sube imagen, se mostrarán iniciales.
                        </p>
                      </div>

                      <!-- Subir imagen -->
                      <div class="md:col-span-2 space-y-2">
                        <div>
                          <label for="modalAvatar" class="block text-xs font-medium text-gray-700 mb-1">
                            Imagen de perfil
                          </label>
                          <input id="modalAvatar"
                                 type="file"
                                 accept="image/png,image/jpeg,image/jpg,image/webp"
                                 class="block w-full text-xs text-gray-600
                                        file:mr-3 file:py-1 file:px-3
                                        file:rounded-full file:border-0
                                        file:text-xs file:font-semibold
                                        file:bg-sky-100 file:text-sky-800
                                        hover:file:bg-sky-200">
                          <p class="text-[10px] text-gray-400 mt-1">
                            JPG, PNG o WebP. Máx. 2 MB.
                          </p>
                        </div>

                        <!-- Color identificador -->
                        <div class="flex items-center gap-3">
                          <div>
                            <label for="modalColorAvatar" class="block text-xs font-medium text-gray-700 mb-1">
                              Color identificador
                            </label>
                            <input id="modalColorAvatar"
                                   type="color"
                                   value="#03A9F4"
                                   class="w-9 h-9 p-1 rounded-lg border border-gray-300 cursor-pointer bg-white">
                          </div>
                          <div class="flex-1">
                            <p class="text-[10px] text-gray-500">
                              Se usará como fondo si no hay imagen.
                            </p>
                          </div>
                        </div>

                        <!-- Hidden -->
                        <input type="hidden" id="modalAvatarFilename">
                      </div>
                    </div>

                    <!-- Nota -->
                    <div class="mt-2 text-[11px] text-gray-600 bg-sky-50 border border-sky-200 rounded-lg p-2.5">
                      La configuración de <span class="font-semibold">Columnas visibles en Permisos</span>
                      se realiza desde la columna <span class="font-semibold">Permisos</span> del listado de usuarios.
                    </div>
                  </div>
                </div>

              </div>

              <!-- FOOTER / BOTONES (más compacto) -->
              <div class="px-4 py-3 border-t bg-gradient-to-r from-sky-50 to-sky-100 flex justify-end gap-2">
                <button id="btnCancelarUsuario"
                        type="button"
                        class="px-3 py-1.5 rounded-full border border-gray-200 text-sm hover:bg-white 
                               flex items-center gap-2 text-gray-700">
                  <i class="fas fa-times-circle text-sm"></i>
                  Cancelar
                </button>

                <button id="btnGuardarUsuario"
                        class="bg-gradient-to-r from-sky-500 to-sky-600 hover:from-sky-600 hover:to-sky-700 
                               text-white px-3 py-1.5 rounded-full transition flex items-center gap-2 shadow-md text-sm">
                  <i class="fas fa-save text-sm"></i>
                  Guardar
                </button>
              </div>
            </div>
          </div>

          <!-- ================== MODAL AVATAR (DESDE CATÁLOGO) ================== -->
          <div id="modalAvatarUsuario"
               class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50 hidden px-2 py-4">
            <div class="bg-white rounded-3xl shadow-2xl w-full max-w-md max-h-[90vh] overflow-hidden flex flex-col 
                        border border-sky-200">
              <!-- HEADER -->
              <div class="px-6 py-4 border-b flex justify-between items-center 
                          bg-gradient-to-r from-sky-500 via-sky-400 to-sky-600">
                <div class="flex flex-col">
                  <h3 class="text-white font-semibold text-lg flex items-center gap-2">
                    <i class="fas fa-user-circle text-white"></i>
                    Configurar avatar
                  </h3>
                  <span id="avatarUsuarioNombre" class="text-xs text-sky-100/90"></span>
                </div>
                <button id="btnCerrarModalAvatarUsuario"
                        class="text-white hover:text-sky-100 text-xl px-2 py-1 rounded-full hover:bg-white/10 transition">
                  <i class="fas fa-times"></i>
                </button>
              </div>

              <!-- CONTENIDO -->
              <div class="flex-1 overflow-auto p-6 space-y-5">
                <!-- Preview -->
                <div class="flex flex-col items-center gap-3">
                  <div id="avatarUserPreviewWrapper"
                       class="relative w-24 h-24 rounded-full flex items-center justify-center shadow-inner 
                              border border-sky-200 overflow-hidden 
                              bg-gradient-to-br from-sky-400 via-sky-500 to-sky-600">
                    <span id="avatarUserPreviewInitials"
                          class="text-2xl font-bold text-white select-none">
                      U
                    </span>
                    <img id="avatarUserPreviewImg"
                         src=""
                         alt="Avatar usuario"
                         class="absolute inset-0 w-full h-full object-cover hidden">
                  </div>
                  <p class="text-[11px] text-gray-500 text-center">
                    Haz clic en el color o sube una nueva imagen para actualizar el avatar del usuario.
                  </p>
                </div>

                <!-- Color + archivo -->
                <div class="space-y-4">
                  <div>
                    <label for="modalAvatarUsuarioColor" class="block text-sm font-medium text-gray-700 mb-1">
                      Color de fondo del avatar
                    </label>
                    <input id="modalAvatarUsuarioColor"
                           type="color"
                           value="#03A9F4"
                           class="w-12 h-12 p-1 rounded-lg border border-gray-300 cursor-pointer bg-white">
                  </div>

                  <div>
                    <label for="modalAvatarUsuarioFile" class="block text-sm font-medium text-gray-700 mb-1">
                      Imagen de avatar
                    </label>
                    <input id="modalAvatarUsuarioFile"
                           type="file"
                           accept="image/png,image/jpeg,image/jpg,image/webp"
                           class="block w-full text-xs text-gray-600
                                  file:mr-3 file:py-1.5 file:px-3
                                  file:rounded-full file:border-0
                                  file:text-xs file:font-semibold
                                  file:bg-sky-100 file:text-sky-800
                                  hover:file:bg-sky-200">
                    <p class="text-[11px] text-gray-400 mt-1">
                      Opcional. Tamaño máximo 2 MB. Si no se sube imagen, se mostrará el color con las iniciales.
                    </p>
                  </div>
                </div>
              </div>

              <!-- FOOTER -->
              <div class="px-6 py-4 border-t bg-gradient-to-r from-sky-50 to-sky-100 flex justify-end gap-2">
                <button id="btnCancelarModalAvatarUsuario"
                        type="button"
                        class="px-4 py-2 rounded-full border text-sm hover:bg-white flex items-center gap-2 text-gray-700">
                  <i class="fas fa-times-circle"></i>
                  Cancelar
                </button>

                <button id="btnGuardarModalAvatarUsuario"
                        class="bg-gradient-to-r from-sky-500 to-sky-600 hover:from-sky-600 hover:to-sky-700 
                               text-white px-4 py-2 rounded-full transition flex items-center gap-2 shadow-md">
                  <i class="fas fa-save"></i>
                  Guardar cambios
                </button>
              </div>
            </div>
          </div>

          <!-- ================== MODAL CONFIG COLUMNAS PERMISOS (DESDE CATÁLOGO) ================== -->
          <div id="modalScheduleConfig"
               class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50 hidden px-2 py-4">
            <div class="bg-white rounded-3xl shadow-2xl w-full max-w-2xl max-h-[95vh] overflow-hidden flex flex-col 
                        border border-sky-200">
              <!-- HEADER -->
              <div class="px-6 py-4 border-b flex justify-between items-center 
                          bg-gradient-to-r from-sky-500 via-sky-400 to-sky-600">
                <div class="flex flex-col">
                  <h3 class="text-white font-semibold text-lg flex items-center gap-2">
                    <i class="fas fa-columns text-white"></i>
                    Configurar columnas de Permisos
                  </h3>
                  <span id="scheduleConfigNombre" class="text-xs text-sky-100/90"></span>
                </div>
                <button id="btnCerrarModalScheduleConfig"
                        class="text-white hover:text-sky-100 text-xl px-2 py-1 rounded-full hover:bg-white/10 transition">
                  <i class="fas fa-times"></i>
                </button>
              </div>

              <!-- CONTENIDO -->
              <div class="flex-1 overflow-auto p-6 space-y-4">
                <p class="text-[12px] text-gray-600">
                  Selecciona las columnas que quieres ver en el módulo
                  <span class="font-semibold">Permisos</span> para este usuario.
                </p>

                <div class="space-y-2">
                  <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                      <i class="fas fa-columns text-sky-500"></i>
                      <h4 class="text-sm font-semibold text-gray-700">
                        Columnas visibles en Permisos
                      </h4>
                    </div>
                    <span class="text-[11px] text-gray-400">
                      Se aplican al ingresar al módulo de tareas.
                    </span>
                  </div>

                  <div class="grid grid-cols-1 md:grid-cols-3 gap-2 bg-sky-50 border border-sky-200 rounded-2xl p-3 text-[13px]">
                    <!-- # -->
                    <label class="inline-flex items-center gap-2">
                      <input type="checkbox" class="chkScheduleCol rounded border-gray-300"
                             value="orden" checked>
                      <span>#</span>
                    </label>

                    <!-- Descripción -->
                    <label class="inline-flex items-center gap-2">
                      <input type="checkbox" class="chkScheduleCol rounded border-gray-300"
                             value="descripcion" checked>
                      <span>Descripción</span>
                    </label>

                    <!-- Referencia -->
                    <label class="inline-flex items-center gap-2">
                      <input type="checkbox" class="chkScheduleCol rounded border-gray-300"
                             value="Referencia" checked>
                      <span>Procedimiento</span>
                    </label>

                    <!-- Job -->
                    <label class="inline-flex items-center gap-2">
                      <input type="checkbox" class="chkScheduleCol rounded border-gray-300"
                             value="job" checked>
                      <span>Job</span>
                    </label>

                    <!-- Frecuencia -->
                    <label class="inline-flex items-center gap-2">
                      <input type="checkbox" class="chkScheduleCol rounded border-gray-300"
                             value="frecuencia" checked>
                      <span>Frecuencia</span>
                    </label>

                    <!-- Estado -->
                    <label class="inline-flex items-center gap-2">
                      <input type="checkbox" class="chkScheduleCol rounded border-gray-300"
                             value="estado" checked>
                      <span>Estado</span>
                    </label>

                    <!-- Realizado por -->
                    <label class="inline-flex items-center gap-2">
                      <input type="checkbox" class="chkScheduleCol rounded border-gray-300"
                             value="usuario_realizado" checked>
                      <span>Realizado por</span>
                    </label>

                    <!-- Tiempo ejecución -->
                    <label class="inline-flex items-center gap-2">
                      <input type="checkbox" class="chkScheduleCol rounded border-gray-300"
                             value="fecha_hora_ejecucion" checked>
                      <span>Tiempo Ejecución</span>
                    </label>

                    <!-- Fecha generada -->
                    <label class="inline-flex items-center gap-2">
                      <input type="checkbox" class="chkScheduleCol rounded border-gray-300"
                             value="fecha_hora_generada" checked>
                      <span>Fecha Schedule</span>
                    </label>

                    <!-- Hora inicio -->
                    <label class="inline-flex items-center gap-2">
                      <input type="checkbox" class="chkScheduleCol rounded border-gray-300"
                             value="fecha_hora_ejecucion_inicio" checked>
                      <span>Hora inicio</span>
                    </label>

                    <!-- Hora fin -->
                    <label class="inline-flex items-center gap-2">
                      <input type="checkbox" class="chkScheduleCol rounded border-gray-300"
                             value="fecha_hora_ejecucion_fin" checked>
                      <span>Hora fin</span>
                    </label>

                    <!-- Tiempo Promedio -->
                    <label class="inline-flex items-center gap-2">
                      <input type="checkbox" class="chkScheduleCol rounded border-gray-300"
                             value="promedio_tiempo" checked>
                      <span>Tiempo Promedio</span>
                    </label>

                    <!-- Fecha Creado -->
                    <label class="inline-flex items-center gap-2">
                      <input type="checkbox" class="chkScheduleCol rounded border-gray-300"
                             value="fecha_hora_creado" checked>
                      <span>Fecha Creado</span>
                    </label>

                    <!-- Guías -->
                    <label class="inline-flex items-center gap-2">
                      <input type="checkbox" class="chkScheduleCol rounded border-gray-300"
                             value="id" checked>
                      <span>Guías</span>
                    </label>

                    <!-- Acciones -->
                    <label class="inline-flex items-center gap-2">
                      <input type="checkbox" class="chkScheduleCol rounded border-gray-300"
                             value="acciones" checked>
                      <span>Acciones</span>
                    </label>
                  </div>
                </div>
              </div>

              <!-- FOOTER -->
              <div class="px-6 py-4 border-t bg-gradient-to-r from-sky-50 to-sky-100 flex justify-end gap-2">
                <button id="btnCancelarModalScheduleConfig"
                        type="button"
                        class="px-4 py-2 rounded-full border text-sm hover:bg-white flex items-center gap-2 text-gray-700">
                  <i class="fas fa-times-circle"></i>
                  Cancelar
                </button>

                <button id="btnGuardarModalScheduleConfig"
                        class="bg-gradient-to-r from-sky-500 to-sky-600 hover:from-sky-600 hover:to-sky-700 
                               text-white px-4 py-2 rounded-full transition flex items-center gap-2 shadow-md">
                  <i class="fas fa-save"></i>
                  Guardar configuración
                </button>
              </div>
            </div>
          </div>
        </main>
         <?php include '../footerlogin.php'; ?>
      </div>
    </div>

  
    <script src="../js/usuario.js?v=<?php echo time(); ?>"></script>
    <script src="../js/config.js?v=<?php echo time(); ?>"></script>
  </body>
</html>
