<?php
session_start();

// TODO: Descomentar cuando tengas el login funcionando
// Verificar autenticación
// if (!isset($_SESSION['tienda_id'])) {
//     header('Location: ../../registrar-tienda/login.php');
//     exit;
// }

// Datos de ejemplo para preview
$tienda_nombre = $_SESSION['tienda_nombre'] ?? 'Vida Natural';
$tienda_plan = $_SESSION['tienda_plan'] ?? 'premium';
$tienda_slug = $_SESSION['tienda_slug'] ?? 'vida-natural';
$tienda_id = $_SESSION['tienda_id'] ?? 1;
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Contenido de Tabs - <?php echo htmlspecialchars($tienda_nombre); ?></title>
  
  <!-- TailwindCSS -->
  <script src="https://cdn.tailwindcss.com"></script>
  
  <!-- Iconos Phosphor -->
  <script src="https://unpkg.com/phosphor-icons"></script>
</head>
<body class="bg-gray-50">

  <!-- Sidebar -->
  <?php include 'componentes/sidebar.php'; ?>

  <!-- Contenido Principal -->
  <div class="ml-0 lg:ml-64 min-h-screen">
    
    <!-- Header -->
    <?php include 'componentes/header.php'; ?>
    
    <!-- Contenido -->
    <main class="p-6">
      
      <!-- Título -->
      <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Contenido de Tabs</h1>
        <p class="text-gray-500">Gestiona el contenido de las pestañas de tu tienda</p>
      </div>

      <!-- Tabs de Configuración -->
      <div class="bg-white rounded-xl shadow-sm mb-6">
        <div class="border-b border-gray-200">
          <nav class="flex -mb-px overflow-x-auto">
            <button onclick="cambiarTab('sucursales')" id="tab-sucursales" class="tab-btn active px-6 py-3 border-b-2 border-sky-500 text-sky-600 font-medium whitespace-nowrap">
              <i class="ph ph-map-pin"></i> Sucursales
            </button>
            <button onclick="cambiarTab('contacto')" id="tab-contacto" class="tab-btn px-6 py-3 border-b-2 border-transparent text-gray-500 hover:text-gray-700 font-medium whitespace-nowrap">
              <i class="ph ph-envelope"></i> Contacto
            </button>
            <button onclick="cambiarTab('acerca')" id="tab-acerca" class="tab-btn px-6 py-3 border-b-2 border-transparent text-gray-500 hover:text-gray-700 font-medium whitespace-nowrap">
              <i class="ph ph-info"></i> Acerca de
            </button>
            <button onclick="cambiarTab('galeria')" id="tab-galeria" class="tab-btn px-6 py-3 border-b-2 border-transparent text-gray-500 hover:text-gray-700 font-medium whitespace-nowrap">
              <i class="ph ph-images"></i> Galería
            </button>
          </nav>
        </div>
      </div>

      <!-- TAB: Sucursales -->
      <div id="content-sucursales" class="tab-content">
        <div class="bg-white rounded-xl shadow-sm p-6">
          <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-bold text-gray-800">Sucursales</h3>
            <button onclick="abrirModalSucursal()" class="px-4 py-2 bg-sky-500 text-white rounded-lg hover:bg-sky-600">
              <i class="ph ph-plus"></i> Nueva Sucursal
            </button>
          </div>

          <!-- Lista de Sucursales -->
          <div class="space-y-4">
            <!-- Ejemplo de sucursal -->
            <div class="border border-gray-200 rounded-lg p-4">
              <div class="flex items-start justify-between">
                <div class="flex-1">
                  <h4 class="font-bold text-gray-800 mb-2">Sucursal Principal</h4>
                  <p class="text-sm text-gray-600 mb-1">
                    <i class="ph ph-map-pin text-sky-500"></i> Urb. Los Educadores Mz M Lt 04, Piura, Perú
                  </p>
                  <p class="text-sm text-gray-600 mb-1">
                    <i class="ph ph-phone text-sky-500"></i> +51 912 345 678
                  </p>
                  <p class="text-sm text-gray-600">
                    <i class="ph ph-clock text-sky-500"></i> Lun - Vie: 9:00 - 18:00
                  </p>
                </div>
                <div class="flex gap-2">
                  <button class="p-2 text-sky-600 hover:bg-sky-50 rounded-lg">
                    <i class="ph ph-pencil"></i>
                  </button>
                  <button class="p-2 text-red-600 hover:bg-red-50 rounded-lg">
                    <i class="ph ph-trash"></i>
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- TAB: Contacto -->
      <div id="content-contacto" class="tab-content hidden">
        <form class="bg-white rounded-xl shadow-sm p-6">
          <h3 class="text-lg font-bold text-gray-800 mb-6">Configuración de Formulario de Contacto</h3>
          
          <div class="space-y-4">
            
            <!-- Email de Recepción -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Email de Recepción *</label>
              <input type="email" name="email_contacto" value="contacto@vidanatural.com" required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-transparent">
              <p class="text-xs text-gray-500 mt-1">Los mensajes se enviarán a este email</p>
            </div>

            <!-- Mensaje de Confirmación -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Mensaje de Confirmación</label>
              <textarea name="mensaje_confirmacion" rows="3"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-transparent">Gracias por contactarnos. Te responderemos pronto.</textarea>
            </div>

            <!-- Campos Adicionales (Premium) -->
            <?php if ($tienda_plan === 'premium'): ?>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-3">Campos Adicionales</label>
              <div class="space-y-2">
                <label class="flex items-center gap-2">
                  <input type="checkbox" name="campo_telefono" checked class="rounded border-gray-300 text-sky-500">
                  <span class="text-sm">Solicitar teléfono</span>
                </label>
                <label class="flex items-center gap-2">
                  <input type="checkbox" name="campo_asunto" checked class="rounded border-gray-300 text-sky-500">
                  <span class="text-sm">Solicitar asunto</span>
                </label>
                <label class="flex items-center gap-2">
                  <input type="checkbox" name="campo_empresa" class="rounded border-gray-300 text-sky-500">
                  <span class="text-sm">Solicitar empresa</span>
                </label>
              </div>
            </div>
            <?php endif; ?>

          </div>

          <div class="mt-6 flex justify-end gap-3">
            <button type="button" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
              Cancelar
            </button>
            <button type="submit" class="px-6 py-2 bg-sky-500 text-white rounded-lg hover:bg-sky-600">
              <i class="ph ph-floppy-disk"></i> Guardar
            </button>
          </div>
        </form>
      </div>

      <!-- TAB: Acerca de -->
      <div id="content-acerca" class="tab-content hidden">
        <form class="bg-white rounded-xl shadow-sm p-6">
          <h3 class="text-lg font-bold text-gray-800 mb-6">Información "Acerca de"</h3>
          
          <div class="space-y-4">
            
            <!-- Descripción Completa -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Descripción Completa *</label>
              <textarea name="descripcion_completa" rows="6" required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-transparent">Somos una empresa dedicada a la comercialización de productos naturales y orgánicos. Con más de 10 años de experiencia en el mercado, ofrecemos la mejor calidad en suplementos, vitaminas y productos de bienestar.</textarea>
            </div>

            <!-- Misión -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Misión</label>
              <textarea name="mision" rows="3"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-transparent"></textarea>
            </div>

            <!-- Visión -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Visión</label>
              <textarea name="vision" rows="3"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-transparent"></textarea>
            </div>

            <!-- Valores -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Valores (separados por coma)</label>
              <input type="text" name="valores" placeholder="Calidad, Confianza, Compromiso"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-transparent">
            </div>

            <!-- Políticas y Términos -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Políticas de Devolución</label>
              <textarea name="politicas_devolucion" rows="4"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-transparent"></textarea>
            </div>

          </div>

          <div class="mt-6 flex justify-end gap-3">
            <button type="button" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
              Cancelar
            </button>
            <button type="submit" class="px-6 py-2 bg-sky-500 text-white rounded-lg hover:bg-sky-600">
              <i class="ph ph-floppy-disk"></i> Guardar
            </button>
          </div>
        </form>
      </div>

      <!-- TAB: Galería -->
      <div id="content-galeria" class="tab-content hidden">
        <div class="bg-white rounded-xl shadow-sm p-6">
          <div class="flex items-center justify-between mb-6">
            <div>
              <h3 class="text-lg font-bold text-gray-800">Galería de Fotos</h3>
              <p class="text-sm text-gray-500">
                <?php if ($tienda_plan === 'premium'): ?>
                Máximo 30 fotos (Plan Premium)
                <?php else: ?>
                Máximo 8 fotos (Plan Básico) - <a href="#" class="text-sky-600">Actualizar a Premium para 30 fotos</a>
                <?php endif; ?>
              </p>
            </div>
            <button onclick="abrirModalFoto()" class="px-4 py-2 bg-sky-500 text-white rounded-lg hover:bg-sky-600">
              <i class="ph ph-plus"></i> Agregar Foto
            </button>
          </div>

          <!-- Grid de Fotos -->
          <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <!-- Ejemplo de foto -->
            <div class="relative group">
              <img src="https://via.placeholder.com/300" alt="Foto" class="w-full h-40 object-cover rounded-lg">
              <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity rounded-lg flex items-center justify-center gap-2">
                <button class="p-2 bg-white rounded-lg text-gray-800 hover:bg-gray-100">
                  <i class="ph ph-pencil"></i>
                </button>
                <button class="p-2 bg-white rounded-lg text-red-600 hover:bg-red-50">
                  <i class="ph ph-trash"></i>
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>

    </main>
    
  </div>

  <!-- Modal Nueva Sucursal -->
  <div id="modalSucursal" class="fixed inset-0 bg-black/50 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-xl max-w-md w-full">
      <div class="p-6">
        <div class="flex items-center justify-between mb-6">
          <h3 class="text-2xl font-bold text-gray-800">Nueva Sucursal</h3>
          <button onclick="cerrarModalSucursal()" class="p-2 hover:bg-gray-100 rounded-lg">
            <i class="ph ph-x text-2xl"></i>
          </button>
        </div>

        <form id="formSucursal" class="space-y-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Nombre *</label>
            <input type="text" name="nombre" required
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-transparent">
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Dirección *</label>
            <input type="text" name="direccion" required
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-transparent">
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Teléfono</label>
            <input type="tel" name="telefono"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-transparent">
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Horario</label>
            <input type="text" name="horario" placeholder="Lun - Vie: 9:00 - 18:00"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-transparent">
          </div>

          <div class="flex justify-end gap-3 pt-4">
            <button type="button" onclick="cerrarModalSucursal()" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
              Cancelar
            </button>
            <button type="submit" class="px-6 py-2 bg-sky-500 text-white rounded-lg hover:bg-sky-600">
              <i class="ph ph-floppy-disk"></i> Guardar
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <script>
    // Cambiar entre tabs
    function cambiarTab(tab) {
      document.querySelectorAll('.tab-content').forEach(content => {
        content.classList.add('hidden');
      });
      
      document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.classList.remove('active', 'border-sky-500', 'text-sky-600');
        btn.classList.add('border-transparent', 'text-gray-500');
      });
      
      document.getElementById('content-' + tab).classList.remove('hidden');
      
      const activeBtn = document.getElementById('tab-' + tab);
      activeBtn.classList.add('active', 'border-sky-500', 'text-sky-600');
      activeBtn.classList.remove('border-transparent', 'text-gray-500');
    }

    // Modales
    function abrirModalSucursal() {
      document.getElementById('modalSucursal').classList.remove('hidden');
    }

    function cerrarModalSucursal() {
      document.getElementById('modalSucursal').classList.add('hidden');
      document.getElementById('formSucursal').reset();
    }

    function abrirModalFoto() {
      alert('Funcionalidad de subir fotos - TODO: Implementar');
    }

    // Cerrar modal al hacer clic fuera
    document.getElementById('modalSucursal').addEventListener('click', function(e) {
      if (e.target === this) {
        cerrarModalSucursal();
      }
    });
  </script>

</body>
</html>
