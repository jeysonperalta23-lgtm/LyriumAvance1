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
  <title>Productos - <?php echo htmlspecialchars($tienda_nombre); ?></title>
  
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
          <h1 class="text-3xl font-bold text-gray-800">Productos</h1>
          <p class="text-gray-500">Gestiona el catálogo de productos de tu tienda</p>
        </div>
        <button onclick="abrirModalProducto()" class="px-6 py-3 bg-sky-500 text-white rounded-lg hover:bg-sky-600 font-medium shadow-sm">
          <i class="ph-fill ph-plus-circle"></i> Nuevo Producto
        </button>
      </div>

      <!-- Filtros y Búsqueda -->
      <div class="bg-white rounded-xl shadow-sm p-4 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
          
          <!-- Búsqueda -->
          <div class="md:col-span-2">
            <div class="relative">
              <i class="ph ph-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
              <input type="text" id="buscarProducto" placeholder="Buscar productos..."
                class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-transparent">
            </div>
          </div>

          <!-- Filtro por Categoría -->
          <div>
            <select id="filtroCategoria" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-transparent">
              <option value="">Todas las categorías</option>
              <option value="1">Suplementos</option>
              <option value="2">Vitaminas</option>
              <option value="3">Productos Naturales</option>
            </select>
          </div>

          <!-- Filtro por Estado -->
          <div>
            <select id="filtroEstado" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-transparent">
              <option value="">Todos los estados</option>
              <option value="1">Activos</option>
              <option value="0">Inactivos</option>
            </select>
          </div>

        </div>
      </div>

      <!-- Tabla de Productos -->
      <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
          <table class="w-full">
            <thead class="bg-gray-50 border-b border-gray-200">
              <tr>
                <th class="text-left py-3 px-4 text-sm font-semibold text-gray-600">Producto</th>
                <th class="text-left py-3 px-4 text-sm font-semibold text-gray-600">Categoría</th>
                <th class="text-left py-3 px-4 text-sm font-semibold text-gray-600">Precio</th>
                <th class="text-left py-3 px-4 text-sm font-semibold text-gray-600">Stock</th>
                <th class="text-left py-3 px-4 text-sm font-semibold text-gray-600">Estado</th>
                <th class="text-left py-3 px-4 text-sm font-semibold text-gray-600">Acciones</th>
              </tr>
            </thead>
            <tbody id="tablaProductos">
              <!-- Ejemplo de producto -->
              <tr class="border-b border-gray-100 hover:bg-gray-50">
                <td class="py-3 px-4">
                  <div class="flex items-center gap-3">
                    <img src="https://via.placeholder.com/50" alt="Producto" class="w-12 h-12 rounded-lg object-cover">
                    <div>
                      <p class="font-medium text-gray-800">Omega 3 Premium</p>
                      <p class="text-xs text-gray-500">SKU: OMG-001</p>
                    </div>
                  </div>
                </td>
                <td class="py-3 px-4 text-sm text-gray-600">Suplementos</td>
                <td class="py-3 px-4">
                  <div>
                    <p class="font-medium text-gray-800">S/ 89.90</p>
                    <p class="text-xs text-gray-500 line-through">S/ 120.00</p>
                  </div>
                </td>
                <td class="py-3 px-4">
                  <span class="px-2 py-1 bg-green-100 text-green-700 text-xs rounded-full">45 unidades</span>
                </td>
                <td class="py-3 px-4">
                  <span class="px-2 py-1 bg-green-100 text-green-700 text-xs rounded-full">
                    <i class="ph-fill ph-check-circle"></i> Activo
                  </span>
                </td>
                <td class="py-3 px-4">
                  <div class="flex items-center gap-2">
                    <button class="p-2 text-sky-600 hover:bg-sky-50 rounded-lg" title="Editar">
                      <i class="ph ph-pencil text-lg"></i>
                    </button>
                    <button class="p-2 text-red-600 hover:bg-red-50 rounded-lg" title="Eliminar">
                      <i class="ph ph-trash text-lg"></i>
                    </button>
                  </div>
                </td>
              </tr>

              <!-- Más productos se cargarán dinámicamente -->
            </tbody>
          </table>
        </div>

        <!-- Paginación -->
        <div class="border-t border-gray-200 px-4 py-3 flex items-center justify-between">
          <div class="text-sm text-gray-600">
            Mostrando <span class="font-medium">1-10</span> de <span class="font-medium">45</span> productos
          </div>
          <div class="flex gap-2">
            <button class="px-3 py-1 border border-gray-300 rounded-lg text-sm hover:bg-gray-50">Anterior</button>
            <button class="px-3 py-1 bg-sky-500 text-white rounded-lg text-sm">1</button>
            <button class="px-3 py-1 border border-gray-300 rounded-lg text-sm hover:bg-gray-50">2</button>
            <button class="px-3 py-1 border border-gray-300 rounded-lg text-sm hover:bg-gray-50">3</button>
            <button class="px-3 py-1 border border-gray-300 rounded-lg text-sm hover:bg-gray-50">Siguiente</button>
          </div>
        </div>
      </div>

    </main>
    
  </div>

  <!-- Modal Nuevo/Editar Producto -->
  <div id="modalProducto" class="fixed inset-0 bg-black/50 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
      <div class="p-6">
        <div class="flex items-center justify-between mb-6">
          <h3 class="text-2xl font-bold text-gray-800">Nuevo Producto</h3>
          <button onclick="cerrarModalProducto()" class="p-2 hover:bg-gray-100 rounded-lg">
            <i class="ph ph-x text-2xl"></i>
          </button>
        </div>

        <form id="formProducto" class="space-y-4">
          
          <!-- Nombre -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Nombre del Producto *</label>
            <input type="text" name="nombre" required
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-transparent">
          </div>

          <!-- Descripción -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Descripción Corta</label>
            <textarea name="descripcion_corta" rows="3"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-transparent"></textarea>
          </div>

          <!-- Categoría y SKU -->
          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Categoría *</label>
              <select name="categoria_id" required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-transparent">
                <option value="">Seleccionar...</option>
                <option value="1">Suplementos</option>
                <option value="2">Vitaminas</option>
                <option value="3">Productos Naturales</option>
              </select>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">SKU</label>
              <input type="text" name="sku"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-transparent">
            </div>
          </div>

          <!-- Precio y Precio Oferta -->
          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Precio *</label>
              <input type="number" name="precio" step="0.01" required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-transparent">
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Precio Oferta</label>
              <input type="number" name="precio_oferta" step="0.01"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-transparent">
            </div>
          </div>

          <!-- Stock -->
          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Stock *</label>
              <input type="number" name="stock" required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-transparent">
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Stock Mínimo</label>
              <input type="number" name="stock_minimo"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-transparent">
            </div>
          </div>

          <!-- Estado -->
          <div class="flex items-center gap-6">
            <label class="flex items-center gap-2">
              <input type="checkbox" name="publicado" checked class="rounded border-gray-300 text-sky-500 focus:ring-sky-400">
              <span class="text-sm text-gray-700">Publicado</span>
            </label>
            <label class="flex items-center gap-2">
              <input type="checkbox" name="destacado" class="rounded border-gray-300 text-sky-500 focus:ring-sky-400">
              <span class="text-sm text-gray-700">Destacado</span>
            </label>
          </div>

          <!-- Botones -->
          <div class="flex justify-end gap-3 pt-4">
            <button type="button" onclick="cerrarModalProducto()" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
              Cancelar
            </button>
            <button type="submit" class="px-6 py-2 bg-sky-500 text-white rounded-lg hover:bg-sky-600">
              <i class="ph ph-floppy-disk"></i> Guardar Producto
            </button>
          </div>

        </form>
      </div>
    </div>
  </div>

  <script>
    // Abrir modal
    function abrirModalProducto() {
      document.getElementById('modalProducto').classList.remove('hidden');
    }

    // Cerrar modal
    function cerrarModalProducto() {
      document.getElementById('modalProducto').classList.add('hidden');
      document.getElementById('formProducto').reset();
    }

    // Manejar formulario
    document.getElementById('formProducto').addEventListener('submit', async function(e) {
      e.preventDefault();
      
      const formData = new FormData(this);
      const data = Object.fromEntries(formData);
      
      try {
        const response = await fetch('../../backend/api/producto.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify({
            ...data,
            vendedor_usuario_id: <?php echo $tienda_id; ?>
          })
        });
        
        const result = await response.json();
        
        if (result.success) {
          alert('Producto creado correctamente');
          cerrarModalProducto();
          // Recargar tabla
          location.reload();
        } else {
          alert('Error al crear producto: ' + result.error);
        }
      } catch (error) {
        alert('Error de conexión');
      }
    });

    // Cerrar modal al hacer clic fuera
    document.getElementById('modalProducto').addEventListener('click', function(e) {
      if (e.target === this) {
        cerrarModalProducto();
      }
    });
  </script>

</body>
</html>
