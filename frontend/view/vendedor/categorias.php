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
  <title>Categorías - <?php echo htmlspecialchars($tienda_nombre); ?></title>
  
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
      
      <!-- Título y Acciones -->
      <div class="flex items-center justify-between mb-6">
        <div>
          <h1 class="text-3xl font-bold text-gray-800">Categorías</h1>
          <p class="text-gray-500">Organiza tus productos por categorías</p>
        </div>
        <button onclick="abrirModalCategoria()" class="px-6 py-3 bg-sky-500 text-white rounded-lg hover:bg-sky-600 font-medium shadow-sm">
          <i class="ph-fill ph-plus-circle"></i> Nueva Categoría
        </button>
      </div>

      <!-- Grid de Categorías -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        
        <!-- Ejemplo de Categoría -->
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200 hover:border-sky-300 transition">
          <div class="flex items-start justify-between mb-4">
            <div class="w-12 h-12 bg-sky-100 rounded-lg flex items-center justify-center">
              <i class="ph-fill ph-pill text-2xl text-sky-600"></i>
            </div>
            <div class="flex gap-2">
              <button class="p-2 text-sky-600 hover:bg-sky-50 rounded-lg" title="Editar">
                <i class="ph ph-pencil"></i>
              </button>
              <button class="p-2 text-red-600 hover:bg-red-50 rounded-lg" title="Eliminar">
                <i class="ph ph-trash"></i>
              </button>
            </div>
          </div>
          <h3 class="text-lg font-bold text-gray-800 mb-2">Suplementos</h3>
          <p class="text-sm text-gray-600 mb-4">Vitaminas, minerales y suplementos alimenticios</p>
          <div class="flex items-center justify-between text-sm">
            <span class="text-gray-500">12 productos</span>
            <span class="px-2 py-1 bg-green-100 text-green-700 text-xs rounded-full">
              <i class="ph-fill ph-check-circle"></i> Activa
            </span>
          </div>
        </div>

        <!-- Más categorías se cargarán dinámicamente -->

      </div>

    </main>
    
  </div>

  <!-- Modal Nueva/Editar Categoría -->
  <div id="modalCategoria" class="fixed inset-0 bg-black/50 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-xl max-w-md w-full">
      <div class="p-6">
        <div class="flex items-center justify-between mb-6">
          <h3 class="text-2xl font-bold text-gray-800">Nueva Categoría</h3>
          <button onclick="cerrarModalCategoria()" class="p-2 hover:bg-gray-100 rounded-lg">
            <i class="ph ph-x text-2xl"></i>
          </button>
        </div>

        <form id="formCategoria" class="space-y-4">
          
          <!-- Nombre -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Nombre de la Categoría *</label>
            <input type="text" name="nombre" required
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-transparent">
          </div>

          <!-- Descripción -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Descripción</label>
            <textarea name="descripcion" rows="3"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-transparent"></textarea>
          </div>

          <!-- Icono -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Icono (Phosphor)</label>
            <input type="text" name="icono" placeholder="ph-pill"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-transparent">
            <p class="text-xs text-gray-500 mt-1">Ej: ph-pill, ph-heart, ph-leaf</p>
          </div>

          <!-- Estado -->
          <div class="flex items-center gap-2">
            <input type="checkbox" name="activa" checked class="rounded border-gray-300 text-sky-500 focus:ring-sky-400">
            <label class="text-sm text-gray-700">Categoría activa</label>
          </div>

          <!-- Botones -->
          <div class="flex justify-end gap-3 pt-4">
            <button type="button" onclick="cerrarModalCategoria()" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
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
    // Abrir modal
    function abrirModalCategoria() {
      document.getElementById('modalCategoria').classList.remove('hidden');
    }

    // Cerrar modal
    function cerrarModalCategoria() {
      document.getElementById('modalCategoria').classList.add('hidden');
      document.getElementById('formCategoria').reset();
    }

    // Manejar formulario
    document.getElementById('formCategoria').addEventListener('submit', async function(e) {
      e.preventDefault();
      
      const formData = new FormData(this);
      const data = Object.fromEntries(formData);
      
      try {
        const response = await fetch('../../backend/api/categoria.php', {
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
          alert('Categoría creada correctamente');
          cerrarModalCategoria();
          location.reload();
        } else {
          alert('Error al crear categoría: ' + result.error);
        }
      } catch (error) {
        alert('Error de conexión');
      }
    });

    // Cerrar modal al hacer clic fuera
    document.getElementById('modalCategoria').addEventListener('click', function(e) {
      if (e.target === this) {
        cerrarModalCategoria();
      }
    });
  </script>

</body>
</html>
