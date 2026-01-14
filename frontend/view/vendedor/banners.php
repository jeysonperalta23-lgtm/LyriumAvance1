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
  <title>Banners y Publicidad - <?php echo htmlspecialchars($tienda_nombre); ?></title>
  
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
      <div class="flex items-center justify-between mb-6">
        <div>
          <h1 class="text-3xl font-bold text-gray-800">Banners y Publicidad</h1>
          <p class="text-gray-500">Gestiona los banners promocionales de tu tienda</p>
        </div>
        
        <?php if ($tienda_plan === 'premium'): ?>
        <button onclick="abrirModalBanner()" class="px-6 py-3 bg-sky-500 text-white rounded-lg hover:bg-sky-600 font-medium shadow-sm">
          <i class="ph-fill ph-plus-circle"></i> Nuevo Banner
        </button>
        <?php endif; ?>
      </div>

      <?php if ($tienda_plan === 'premium'): ?>

      <!-- Secciones de Banners -->
      
      <!-- Banner Superior (Promoción Principal) -->
      <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
        <div class="flex items-center justify-between mb-4">
          <h3 class="text-lg font-bold text-gray-800">
            <i class="ph ph-image"></i> Banner Superior de Promoción
          </h3>
          <button onclick="editarBanner('superior')" class="px-4 py-2 text-sky-600 hover:bg-sky-50 rounded-lg">
            <i class="ph ph-pencil"></i> Editar
          </button>
        </div>
        
        <!-- Preview del Banner -->
        <div class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center bg-gray-50">
          <div class="bg-gradient-to-r from-pink-100 to-pink-50 rounded-lg p-6">
            <h4 class="text-2xl font-bold text-pink-600 mb-2">VENTA DE INVIERNO</h4>
            <p class="text-gray-700 mb-3">Ofertas de hasta 50% de descuento junto con campañas y ofertas limitadas.</p>
            <button class="px-6 py-2 bg-white text-pink-600 rounded-lg font-medium">Descubre más →</button>
          </div>
        </div>
      </div>

      <!-- Banner de Información (3 columnas) -->
      <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
        <div class="flex items-center justify-between mb-4">
          <h3 class="text-lg font-bold text-gray-800">
            <i class="ph ph-info"></i> Banner de Información (3 Columnas)
          </h3>
          <button onclick="editarBanner('info')" class="px-4 py-2 text-sky-600 hover:bg-sky-50 rounded-lg">
            <i class="ph ph-pencil"></i> Editar
          </button>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
          <!-- Columna 1 -->
          <div class="border border-gray-200 rounded-lg p-4 text-center">
            <i class="ph-fill ph-truck text-3xl text-sky-500 mb-2"></i>
            <h4 class="font-bold text-gray-800 mb-1">Entregas a nivel nacional</h4>
            <p class="text-sm text-gray-600">Envíos a todo el país</p>
          </div>
          
          <!-- Columna 2 -->
          <div class="border border-gray-200 rounded-lg p-4 text-center">
            <i class="ph-fill ph-headset text-3xl text-sky-500 mb-2"></i>
            <h4 class="font-bold text-gray-800 mb-1">Servicio al cliente 24/7</h4>
            <p class="text-sm text-gray-600">Atención personalizada</p>
          </div>
          
          <!-- Columna 3 -->
          <div class="border border-gray-200 rounded-lg p-4 text-center">
            <i class="ph-fill ph-clock text-3xl text-sky-500 mb-2"></i>
            <h4 class="font-bold text-gray-800 mb-1">Horarios</h4>
            <p class="text-sm text-gray-600">Lun - Vie: 10:00 - 14:00</p>
          </div>
        </div>
      </div>

      <!-- Banner Lateral (Sidebar) -->
      <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
        <div class="flex items-center justify-between mb-4">
          <h3 class="text-lg font-bold text-gray-800">
            <i class="ph ph-sidebar"></i> Banner Lateral (Ofertas Especiales)
          </h3>
          <button onclick="editarBanner('lateral')" class="px-4 py-2 text-sky-600 hover:bg-sky-50 rounded-lg">
            <i class="ph ph-pencil"></i> Editar
          </button>
        </div>
        
        <div class="max-w-sm">
          <div class="bg-gradient-to-br from-gray-700 to-gray-900 rounded-lg p-6 text-white">
            <h4 class="text-xl font-bold mb-3">Ofertas Especiales</h4>
            <p class="text-sm mb-4">Descuentos exclusivos</p>
            <button class="px-4 py-2 bg-white text-gray-900 rounded-lg text-sm font-medium">Ver ofertas →</button>
          </div>
        </div>
      </div>

      <!-- Galería de Banners Adicionales -->
      <div class="bg-white rounded-xl shadow-sm p-6">
        <div class="flex items-center justify-between mb-4">
          <h3 class="text-lg font-bold text-gray-800">
            <i class="ph ph-images"></i> Galería de Banners
          </h3>
          <button onclick="abrirModalBanner()" class="px-4 py-2 text-sky-600 hover:bg-sky-50 rounded-lg">
            <i class="ph ph-plus"></i> Agregar
          </button>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
          <!-- Banner de ejemplo -->
          <div class="border border-gray-200 rounded-lg overflow-hidden">
            <img src="https://via.placeholder.com/400x200" alt="Banner" class="w-full h-40 object-cover">
            <div class="p-3">
              <p class="text-sm font-medium text-gray-800 mb-2">Banner Promocional</p>
              <div class="flex gap-2">
                <button class="flex-1 px-3 py-1 text-xs bg-sky-50 text-sky-600 rounded hover:bg-sky-100">Editar</button>
                <button class="flex-1 px-3 py-1 text-xs bg-red-50 text-red-600 rounded hover:bg-red-100">Eliminar</button>
              </div>
            </div>
          </div>
        </div>
      </div>

      <?php else: ?>
      
      <!-- Mensaje para Plan Básico -->
      <div class="bg-orange-50 border border-orange-200 rounded-xl p-8 text-center">
        <i class="ph-fill ph-crown text-5xl text-orange-500 mb-4"></i>
        <h3 class="text-2xl font-bold text-gray-800 mb-2">Función Premium</h3>
        <p class="text-gray-600 mb-6">Los banners y publicidad personalizada están disponibles solo en el Plan Premium</p>
        <button class="px-8 py-3 bg-orange-500 text-white rounded-lg hover:bg-orange-600 font-medium">
          <i class="ph ph-arrow-up"></i> Actualizar a Premium
        </button>
      </div>

      <?php endif; ?>

    </main>
    
  </div>

  <!-- Modal Crear/Editar Banner -->
  <div id="modalBanner" class="fixed inset-0 bg-black/50 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
      <div class="p-6">
        <div class="flex items-center justify-between mb-6">
          <h3 class="text-2xl font-bold text-gray-800">Nuevo Banner</h3>
          <button onclick="cerrarModalBanner()" class="p-2 hover:bg-gray-100 rounded-lg">
            <i class="ph ph-x text-2xl"></i>
          </button>
        </div>

        <form id="formBanner" class="space-y-4">
          
          <!-- Tipo de Banner -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Tipo de Banner *</label>
            <select name="tipo" required
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-transparent">
              <option value="superior">Banner Superior (Promoción Principal)</option>
              <option value="info">Banner de Información (3 Columnas)</option>
              <option value="lateral">Banner Lateral (Sidebar)</option>
              <option value="galeria">Galería de Banners</option>
            </select>
          </div>

          <!-- Título -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Título *</label>
            <input type="text" name="titulo" required
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-transparent">
          </div>

          <!-- Descripción -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Descripción</label>
            <textarea name="descripcion" rows="3"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-transparent"></textarea>
          </div>

          <!-- URL de Imagen -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">URL de Imagen</label>
            <input type="url" name="imagen_url"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-transparent">
          </div>

          <!-- Enlace del Banner -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Enlace (URL)</label>
            <input type="url" name="enlace"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-transparent">
          </div>

          <!-- Texto del Botón -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Texto del Botón</label>
            <input type="text" name="texto_boton" placeholder="Ej: Ver más, Descubre más"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-transparent">
          </div>

          <!-- Color de Fondo -->
          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Color de Fondo</label>
              <input type="color" name="color_fondo" value="#f0f9ff"
                class="w-full h-10 border border-gray-300 rounded-lg">
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Color de Texto</label>
              <input type="color" name="color_texto" value="#0c4a6e"
                class="w-full h-10 border border-gray-300 rounded-lg">
            </div>
          </div>

          <!-- Estado -->
          <div class="flex items-center gap-2">
            <input type="checkbox" name="activo" checked class="rounded border-gray-300 text-sky-500 focus:ring-sky-400">
            <label class="text-sm text-gray-700">Banner activo</label>
          </div>

          <!-- Botones -->
          <div class="flex justify-end gap-3 pt-4">
            <button type="button" onclick="cerrarModalBanner()" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
              Cancelar
            </button>
            <button type="submit" class="px-6 py-2 bg-sky-500 text-white rounded-lg hover:bg-sky-600">
              <i class="ph ph-floppy-disk"></i> Guardar Banner
            </button>
          </div>

        </form>
      </div>
    </div>
  </div>

  <script>
    // Abrir modal
    function abrirModalBanner() {
      document.getElementById('modalBanner').classList.remove('hidden');
    }

    // Cerrar modal
    function cerrarModalBanner() {
      document.getElementById('modalBanner').classList.add('hidden');
      document.getElementById('formBanner').reset();
    }

    // Editar banner específico
    function editarBanner(tipo) {
      abrirModalBanner();
      // TODO: Cargar datos del banner
    }

    // Manejar formulario
    document.getElementById('formBanner').addEventListener('submit', async function(e) {
      e.preventDefault();
      
      const formData = new FormData(this);
      const data = Object.fromEntries(formData);
      
      try {
        const response = await fetch('../../backend/api/banner.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify({
            ...data,
            tienda_id: <?php echo $tienda_id; ?>
          })
        });
        
        const result = await response.json();
        
        if (result.success) {
          alert('Banner guardado correctamente');
          cerrarModalBanner();
          location.reload();
        } else {
          alert('Error al guardar banner: ' + result.error);
        }
      } catch (error) {
        alert('Error de conexión');
      }
    });

    // Cerrar modal al hacer clic fuera
    document.getElementById('modalBanner').addEventListener('click', function(e) {
      if (e.target === this) {
        cerrarModalBanner();
      }
    });
  </script>

</body>
</html>
