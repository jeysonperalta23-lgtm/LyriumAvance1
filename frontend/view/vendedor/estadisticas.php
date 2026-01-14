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
  <title>Estadísticas - <?php echo htmlspecialchars($tienda_nombre); ?></title>
  
  <!-- TailwindCSS -->
  <script src="https://cdn.tailwindcss.com"></script>
  
  <!-- Iconos Phosphor -->
  <script src="https://unpkg.com/phosphor-icons"></script>
  
  <!-- Chart.js -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
      
      <!-- Título y Filtro de Período -->
      <div class="flex items-center justify-between mb-6">
        <div>
          <h1 class="text-3xl font-bold text-gray-800">Estadísticas</h1>
          <p class="text-gray-500">Análisis detallado del rendimiento de tu tienda</p>
        </div>
        <select class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-transparent">
          <option value="7">Últimos 7 días</option>
          <option value="30" selected>Últimos 30 días</option>
          <option value="90">Últimos 3 meses</option>
          <option value="365">Último año</option>
        </select>
      </div>

      <!-- Tarjetas de Métricas Principales -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        
        <div class="bg-white rounded-xl shadow-sm p-6">
          <div class="flex items-center justify-between mb-3">
            <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
              <i class="ph-fill ph-currency-circle-dollar text-2xl text-green-600"></i>
            </div>
            <span class="text-xs text-green-600 bg-green-50 px-2 py-1 rounded-full">+18%</span>
          </div>
          <h3 class="text-2xl font-bold text-gray-800 mb-1">S/ 12,450</h3>
          <p class="text-sm text-gray-500">Ingresos Totales</p>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6">
          <div class="flex items-center justify-between mb-3">
            <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
              <i class="ph-fill ph-shopping-cart text-2xl text-blue-600"></i>
            </div>
            <span class="text-xs text-blue-600 bg-blue-50 px-2 py-1 rounded-full">+12%</span>
          </div>
          <h3 class="text-2xl font-bold text-gray-800 mb-1">87</h3>
          <p class="text-sm text-gray-500">Pedidos Realizados</p>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6">
          <div class="flex items-center justify-between mb-3">
            <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
              <i class="ph-fill ph-users text-2xl text-purple-600"></i>
            </div>
            <span class="text-xs text-purple-600 bg-purple-50 px-2 py-1 rounded-full">+24%</span>
          </div>
          <h3 class="text-2xl font-bold text-gray-800 mb-1">1,234</h3>
          <p class="text-sm text-gray-500">Visitantes</p>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6">
          <div class="flex items-center justify-between mb-3">
            <div class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center">
              <i class="ph-fill ph-percent text-2xl text-orange-600"></i>
            </div>
            <span class="text-xs text-orange-600 bg-orange-50 px-2 py-1 rounded-full">7.05%</span>
          </div>
          <h3 class="text-2xl font-bold text-gray-800 mb-1">7.05%</h3>
          <p class="text-sm text-gray-500">Tasa de Conversión</p>
        </div>

      </div>

      <!-- Gráficos -->
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        
        <!-- Gráfico de Ventas -->
        <div class="bg-white rounded-xl shadow-sm p-6">
          <h3 class="text-lg font-bold text-gray-800 mb-4">
            <i class="ph ph-chart-line"></i> Ventas por Día
          </h3>
          <canvas id="chartVentas" height="250"></canvas>
        </div>

        <!-- Gráfico de Productos -->
        <div class="bg-white rounded-xl shadow-sm p-6">
          <h3 class="text-lg font-bold text-gray-800 mb-4">
            <i class="ph ph-chart-pie"></i> Productos Más Vendidos
          </h3>
          <canvas id="chartProductos" height="250"></canvas>
        </div>

      </div>

      <!-- Tabla de Productos Más Vendidos -->
      <div class="bg-white rounded-xl shadow-sm p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4">
          <i class="ph ph-trophy"></i> Top 10 Productos Más Vendidos
        </h3>
        <div class="overflow-x-auto">
          <table class="w-full">
            <thead class="bg-gray-50 border-b border-gray-200">
              <tr>
                <th class="text-left py-3 px-4 text-sm font-semibold text-gray-600">#</th>
                <th class="text-left py-3 px-4 text-sm font-semibold text-gray-600">Producto</th>
                <th class="text-left py-3 px-4 text-sm font-semibold text-gray-600">Categoría</th>
                <th class="text-left py-3 px-4 text-sm font-semibold text-gray-600">Ventas</th>
                <th class="text-left py-3 px-4 text-sm font-semibold text-gray-600">Ingresos</th>
              </tr>
            </thead>
            <tbody>
              <tr class="border-b border-gray-100">
                <td class="py-3 px-4 text-sm text-gray-600">1</td>
                <td class="py-3 px-4">
                  <div class="flex items-center gap-3">
                    <img src="https://via.placeholder.com/40" alt="Producto" class="w-10 h-10 rounded-lg">
                    <span class="font-medium text-gray-800">Omega 3 Premium</span>
                  </div>
                </td>
                <td class="py-3 px-4 text-sm text-gray-600">Suplementos</td>
                <td class="py-3 px-4 text-sm font-medium text-gray-800">45 unidades</td>
                <td class="py-3 px-4 text-sm font-medium text-green-600">S/ 4,045.50</td>
              </tr>
              <!-- Más productos... -->
            </tbody>
          </table>
        </div>
      </div>

    </main>
    
  </div>

  <script>
    // Gráfico de Ventas
    const ctxVentas = document.getElementById('chartVentas').getContext('2d');
    new Chart(ctxVentas, {
      type: 'line',
      data: {
        labels: ['Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb', 'Dom'],
        datasets: [{
          label: 'Ventas (S/)',
          data: [450, 680, 520, 890, 720, 1100, 850],
          borderColor: '#0ea5e9',
          backgroundColor: 'rgba(14, 165, 233, 0.1)',
          tension: 0.4,
          fill: true
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            display: false
          }
        },
        scales: {
          y: {
            beginAtZero: true,
            ticks: {
              callback: function(value) {
                return 'S/ ' + value;
              }
            }
          }
        }
      }
    });

    // Gráfico de Productos
    const ctxProductos = document.getElementById('chartProductos').getContext('2d');
    new Chart(ctxProductos, {
      type: 'doughnut',
      data: {
        labels: ['Omega 3', 'Vitamina C', 'Colágeno', 'Magnesio', 'Otros'],
        datasets: [{
          data: [30, 25, 20, 15, 10],
          backgroundColor: [
            '#0ea5e9',
            '#84cc16',
            '#f59e0b',
            '#8b5cf6',
            '#6b7280'
          ]
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            position: 'bottom'
          }
        }
      }
    });
  </script>

</body>
</html>
