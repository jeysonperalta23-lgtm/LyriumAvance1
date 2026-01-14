<?php
session_start();


// Datos de ejemplo para preview
$tienda_nombre = $_SESSION['tienda_nombre'] ?? 'Vida Natural';
$tienda_plan = $_SESSION['tienda_plan'] ?? 'premium';
$tienda_slug = $_SESSION['tienda_slug'] ?? 'vida-natural';
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard - <?php echo htmlspecialchars($tienda_nombre); ?></title>
  
  <!-- TailwindCSS -->
  <script src="https://cdn.tailwindcss.com"></script>
  
  <!-- Iconos Phosphor -->
  <script src="https://unpkg.com/phosphor-icons"></script>
  
  <!-- Chart.js para gráficos -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-gray-50">

  <!-- Sidebar -->
  <?php include 'componentes/sidebar.php'; ?>

  <!-- Contenido Principal -->
  <div class="ml-0 lg:ml-56 min-h-screen">
    
    <!-- Header -->
    <?php include 'componentes/header.php'; ?>
    
    <!-- Contenido -->
    <main class="p-6">
      
      <!-- Título -->
      <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Dashboard</h1>
        <p class="text-gray-500">Resumen de tu tienda</p>
      </div>
      
      <!-- Tarjetas de Estadísticas -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        
        <!-- Ventas del Mes -->
        <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-green-500">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm text-gray-500 mb-1">Ventas del Mes</p>
              <h3 class="text-2xl font-bold text-gray-800" id="ventasMes">S/ 0.00</h3>
              <p class="text-xs text-green-600 mt-1">
                <i class="ph-fill ph-arrow-up"></i> +12% vs mes anterior
              </p>
            </div>
            <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
              <i class="ph-fill ph-currency-circle-dollar text-2xl text-green-600"></i>
            </div>
          </div>
        </div>
        
        <!-- Pedidos Pendientes -->
        <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-orange-500">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm text-gray-500 mb-1">Pedidos Pendientes</p>
              <h3 class="text-2xl font-bold text-gray-800" id="pedidosPendientes">0</h3>
              <p class="text-xs text-orange-600 mt-1">
                <i class="ph-fill ph-clock"></i> Requieren atención
              </p>
            </div>
            <div class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center">
              <i class="ph-fill ph-package text-2xl text-orange-600"></i>
            </div>
          </div>
        </div>
        
        <!-- Productos Activos -->
        <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-sky-500">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm text-gray-500 mb-1">Productos Activos</p>
              <h3 class="text-2xl font-bold text-gray-800" id="productosActivos">0</h3>
              <p class="text-xs text-sky-600 mt-1">
                <i class="ph-fill ph-check-circle"></i> Publicados
              </p>
            </div>
            <div class="w-12 h-12 bg-sky-100 rounded-full flex items-center justify-center">
              <i class="ph-fill ph-shopping-bag text-2xl text-sky-600"></i>
            </div>
          </div>
        </div>
        
        <!-- Visitas -->
        <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-lime-500">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm text-gray-500 mb-1">Visitas del Mes</p>
              <h3 class="text-2xl font-bold text-gray-800" id="visitasMes">0</h3>
              <p class="text-xs text-lime-600 mt-1">
                <i class="ph-fill ph-eye"></i> Visitantes únicos
              </p>
            </div>
            <div class="w-12 h-12 bg-lime-100 rounded-full flex items-center justify-center">
              <i class="ph-fill ph-users text-2xl text-lime-600"></i>
            </div>
          </div>
        </div>
        
      </div>
      
      <!-- Gráficos y Tablas -->
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        
        <!-- Gráfico de Ventas -->
        <div class="bg-white rounded-xl shadow-sm p-6">
          <h3 class="text-lg font-bold text-gray-800 mb-4">
            <i class="ph ph-chart-line"></i> Ventas de los últimos 7 días
          </h3>
          <canvas id="chartVentas" height="200"></canvas>
        </div>
        
        <!-- Productos Más Vendidos -->
        <div class="bg-white rounded-xl shadow-sm p-6">
          <h3 class="text-lg font-bold text-gray-800 mb-4">
            <i class="ph ph-trophy"></i> Productos Más Vendidos
          </h3>
          <div id="productosTop" class="space-y-3">
            <!-- Se llenará dinámicamente -->
            <div class="text-center text-gray-400 py-8">
              <i class="ph ph-package text-4xl mb-2"></i>
              <p>No hay datos disponibles</p>
            </div>
          </div>
        </div>
        
      </div>
      
      <!-- Últimos Pedidos -->
      <div class="bg-white rounded-xl shadow-sm p-6">
        <div class="flex items-center justify-between mb-4">
          <h3 class="text-lg font-bold text-gray-800">
            <i class="ph ph-list"></i> Últimos Pedidos
          </h3>
          <a href="pedidos.php" class="text-sm text-sky-600 hover:text-sky-700 font-medium">
            Ver todos <i class="ph ph-arrow-right"></i>
          </a>
        </div>
        
        <div class="overflow-x-auto">
          <table class="w-full">
            <thead>
              <tr class="border-b border-gray-200">
                <th class="text-left py-3 px-4 text-sm font-semibold text-gray-600">Pedido</th>
                <th class="text-left py-3 px-4 text-sm font-semibold text-gray-600">Cliente</th>
                <th class="text-left py-3 px-4 text-sm font-semibold text-gray-600">Fecha</th>
                <th class="text-left py-3 px-4 text-sm font-semibold text-gray-600">Total</th>
                <th class="text-left py-3 px-4 text-sm font-semibold text-gray-600">Estado</th>
              </tr>
            </thead>
            <tbody id="tablaPedidos">
              <tr>
                <td colspan="5" class="text-center py-8 text-gray-400">
                  <i class="ph ph-shopping-cart text-4xl mb-2"></i>
                  <p>No hay pedidos recientes</p>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
      
    </main>
    
  </div>

  <script>
    // Cargar datos del dashboard
    async function cargarDashboard() {
      try {
        // TODO: Implementar llamada a API para obtener estadísticas
        // const response = await fetch('../../backend/api/tienda.php?op=estadisticas');
        // const data = await response.json();
        
        // Por ahora, datos de ejemplo
        document.getElementById('ventasMes').textContent = 'S/ 2,450.00';
        document.getElementById('pedidosPendientes').textContent = '3';
        document.getElementById('productosActivos').textContent = '12';
        document.getElementById('visitasMes').textContent = '156';
        
        // Crear gráfico de ventas
        crearGraficoVentas();
        
      } catch (error) {
        console.error('Error al cargar dashboard:', error);
      }
    }
    
    // Crear gráfico de ventas
    function crearGraficoVentas() {
      const ctx = document.getElementById('chartVentas').getContext('2d');
      new Chart(ctx, {
        type: 'line',
        data: {
          labels: ['Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb', 'Dom'],
          datasets: [{
            label: 'Ventas (S/)',
            data: [120, 190, 150, 280, 220, 310, 250],
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
    }
    
    // Cargar al iniciar
    document.addEventListener('DOMContentLoaded', cargarDashboard);
  </script>

</body>
</html>
