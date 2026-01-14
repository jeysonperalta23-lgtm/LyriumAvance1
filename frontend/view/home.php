<!DOCTYPE html>
<html lang="es">
   <?php include '../headlogin.php'; ?>
   <body class="bg-gray-100 text-gray-800">
      <!-- Contenedor principal -->
      <div class="flex flex-col md:flex-row min-h-screen">
         <?php include '../asider.php'; ?>
         <!-- Contenedor de contenido -->
         <div id="main-content" class="flex flex-col flex-1 min-h-screen transition-all duration-300 ease-in-out">
    
            <!-- Contenido -->
            <main class="flex-grow p-4 bg-gray-100 overflow-auto">
               <div class="bg-white shadow rounded-lg p-4 sm:p-6">
                  <!-- Controles -->
                  <div class="flex flex-col lg:flex-row lg:justify-between lg:items-center gap-4 mb-4">
                     <h2 class="text-lg md:text-xl font-semibold">Bienvenidos</h2>
                  </div>
               </div>
            </main>
         <?php include '../footerlogin.php'; ?>
         </div>
      </div>
      <script src="../js/home.js?v=<?php echo time();?>"></script>
      <script src="../js/config.js?v=<?php echo time();?>"></script>
   </body>
</html>