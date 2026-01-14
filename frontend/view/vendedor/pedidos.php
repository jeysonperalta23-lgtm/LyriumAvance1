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
  <title>Pedidos - <?php echo htmlspecialchars($tienda_nombre); ?></title>
  
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
        <h1 class="text-3xl font-bold text-gray-800">Pedidos</h1>
        <p class="text-gray-500">Gestiona los pedidos de tu tienda</p>
      </div>

      <!-- Estadísticas Rápidas -->
      <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        
        <div class="bg-white rounded-xl shadow-sm p-4 border-l-4 border-orange-500">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm text-gray-500">Pendientes</p>
              <h3 class="text-2xl font-bold text-gray-800">3</h3>
            </div>
            <i class="ph-fill ph-clock text-3xl text-orange-500"></i>
          </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-4 border-l-4 border-blue-500">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm text-gray-500">En Proceso</p>
              <h3 class="text-2xl font-bold text-gray-800">5</h3>
            </div>
            <i class="ph-fill ph-package text-3xl text-blue-500"></i>
          </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-4 border-l-4 border-green-500">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm text-gray-500">Completados</p>
              <h3 class="text-2xl font-bold text-gray-800">127</h3>
            </div>
            <i class="ph-fill ph-check-circle text-3xl text-green-500"></i>
          </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-4 border-l-4 border-red-500">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm text-gray-500">Cancelados</p>
              <h3 class="text-2xl font-bold text-gray-800">2</h3>
            </div>
            <i class="ph-fill ph-x-circle text-3xl text-red-500"></i>
          </div>
        </div>

      </div>

      <!-- Filtros -->
      <div class="bg-white rounded-xl shadow-sm p-4 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
          
          <!-- Búsqueda -->
          <div class="md:col-span-2">
            <div class="relative">
              <i class="ph ph-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
              <input type="text" id="buscarPedido" placeholder="Buscar por ID o cliente..."
                class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-transparent">
            </div>
          </div>

          <!-- Filtro por Estado -->
          <div>
            <select id="filtroEstado" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-transparent">
              <option value="">Todos los estados</option>
              <option value="pendiente">Pendientes</option>
              <option value="proceso">En Proceso</option>
              <option value="completado">Completados</option>
              <option value="cancelado">Cancelados</option>
            </select>
          </div>

          <!-- Filtro por Fecha -->
          <div>
            <input type="date" id="filtroFecha"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-transparent">
          </div>

        </div>
      </div>

      <!-- Tabla de Pedidos -->
      <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
          <table class="w-full">
            <thead class="bg-gray-50 border-b border-gray-200">
              <tr>
                <th class="text-left py-3 px-4 text-sm font-semibold text-gray-600">Pedido</th>
                <th class="text-left py-3 px-4 text-sm font-semibold text-gray-600">Cliente</th>
                <th class="text-left py-3 px-4 text-sm font-semibold text-gray-600">Fecha</th>
                <th class="text-left py-3 px-4 text-sm font-semibold text-gray-600">Total</th>
                <th class="text-left py-3 px-4 text-sm font-semibold text-gray-600">Estado</th>
                <th class="text-left py-3 px-4 text-sm font-semibold text-gray-600">Acciones</th>
              </tr>
            </thead>
            <tbody id="tablaPedidos">
              
              <!-- Ejemplo de pedido -->
              <tr class="border-b border-gray-100 hover:bg-gray-50">
                <td class="py-3 px-4">
                  <div>
                    <p class="font-medium text-gray-800">#PED-00123</p>
                    <p class="text-xs text-gray-500">3 productos</p>
                  </div>
                </td>
                <td class="py-3 px-4">
                  <div>
                    <p class="font-medium text-gray-800">Juan Pérez</p>
                    <p class="text-xs text-gray-500">juan@example.com</p>
                  </div>
                </td>
                <td class="py-3 px-4 text-sm text-gray-600">
                  <p>09/01/2026</p>
                  <p class="text-xs text-gray-500">14:30</p>
                </td>
                <td class="py-3 px-4">
                  <p class="font-medium text-gray-800">S/ 245.00</p>
                </td>
                <td class="py-3 px-4">
                  <span class="px-2 py-1 bg-orange-100 text-orange-700 text-xs rounded-full">
                    <i class="ph-fill ph-clock"></i> Pendiente
                  </span>
                </td>
                <td class="py-3 px-4">
                  <div class="flex items-center gap-2">
                    <button onclick="verDetallePedido(123)" class="p-2 text-sky-600 hover:bg-sky-50 rounded-lg" title="Ver Detalle">
                      <i class="ph ph-eye text-lg"></i>
                    </button>
                    <button class="p-2 text-green-600 hover:bg-green-50 rounded-lg" title="Cambiar Estado">
                      <i class="ph ph-check text-lg"></i>
                    </button>
                  </div>
                </td>
              </tr>

              <!-- Más pedidos se cargarán dinámicamente -->
            </tbody>
          </table>
        </div>

        <!-- Paginación -->
        <div class="border-t border-gray-200 px-4 py-3 flex items-center justify-between">
          <div class="text-sm text-gray-600">
            Mostrando <span class="font-medium">1-10</span> de <span class="font-medium">137</span> pedidos
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

  <!-- Modal Detalle Pedido -->
  <div id="modalDetallePedido" class="fixed inset-0 bg-black/50 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-xl max-w-3xl w-full max-h-[90vh] overflow-y-auto">
      <div class="p-6">
        <div class="flex items-center justify-between mb-6">
          <h3 class="text-2xl font-bold text-gray-800">Detalle del Pedido #PED-00123</h3>
          <button onclick="cerrarModalDetalle()" class="p-2 hover:bg-gray-100 rounded-lg">
            <i class="ph ph-x text-2xl"></i>
          </button>
        </div>

        <!-- Información del Cliente -->
        <div class="bg-gray-50 rounded-lg p-4 mb-6">
          <h4 class="font-semibold text-gray-800 mb-3">Información del Cliente</h4>
          <div class="grid grid-cols-2 gap-4 text-sm">
            <div>
              <p class="text-gray-500">Nombre:</p>
              <p class="font-medium">Juan Pérez</p>
            </div>
            <div>
              <p class="text-gray-500">Email:</p>
              <p class="font-medium">juan@example.com</p>
            </div>
            <div>
              <p class="text-gray-500">Teléfono:</p>
              <p class="font-medium">+51 999 999 999</p>
            </div>
            <div>
              <p class="text-gray-500">Dirección:</p>
              <p class="font-medium">Av. Principal 123, Lima</p>
            </div>
          </div>
        </div>

        <!-- Productos del Pedido -->
        <div class="mb-6">
          <h4 class="font-semibold text-gray-800 mb-3">Productos</h4>
          <div class="space-y-3">
            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
              <div class="flex items-center gap-3">
                <img src="https://via.placeholder.com/50" alt="Producto" class="w-12 h-12 rounded-lg">
                <div>
                  <p class="font-medium text-gray-800">Omega 3 Premium</p>
                  <p class="text-sm text-gray-500">Cantidad: 2</p>
                </div>
              </div>
              <p class="font-medium text-gray-800">S/ 179.80</p>
            </div>
          </div>
        </div>

        <!-- Resumen del Pedido -->
        <div class="border-t border-gray-200 pt-4">
          <div class="space-y-2 text-sm">
            <div class="flex justify-between">
              <span class="text-gray-600">Subtotal:</span>
              <span class="font-medium">S/ 179.80</span>
            </div>
            <div class="flex justify-between">
              <span class="text-gray-600">Envío:</span>
              <span class="font-medium">S/ 15.00</span>
            </div>
            <div class="flex justify-between text-lg font-bold">
              <span>Total:</span>
              <span class="text-sky-600">S/ 194.80</span>
            </div>
          </div>
        </div>

        <!-- Cambiar Estado -->
        <div class="mt-6 pt-6 border-t border-gray-200">
          <label class="block text-sm font-medium text-gray-700 mb-2">Cambiar Estado del Pedido</label>
          <div class="flex gap-3">
            <select class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-transparent">
              <option value="pendiente">Pendiente</option>
              <option value="proceso">En Proceso</option>
              <option value="completado">Completado</option>
              <option value="cancelado">Cancelado</option>
            </select>
            <button class="px-6 py-2 bg-sky-500 text-white rounded-lg hover:bg-sky-600">
              Actualizar
            </button>
          </div>
        </div>

      </div>
    </div>
  </div>

  <script>
    // Ver detalle del pedido
    function verDetallePedido(id) {
      document.getElementById('modalDetallePedido').classList.remove('hidden');
      // TODO: Cargar datos del pedido desde la API
    }

    // Cerrar modal
    function cerrarModalDetalle() {
      document.getElementById('modalDetallePedido').classList.add('hidden');
    }

    // Cerrar modal al hacer clic fuera
    document.getElementById('modalDetallePedido').addEventListener('click', function(e) {
      if (e.target === this) {
        cerrarModalDetalle();
      }
    });
  </script>

</body>
</html>
