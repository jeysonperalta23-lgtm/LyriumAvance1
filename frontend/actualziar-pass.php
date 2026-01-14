<?php
session_start();
session_destroy(); // Por si llega con sesión, la limpiamos
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Restablecer Contraseña</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="flex items-center justify-center min-h-screen bg-gray-100">
  <div class="bg-white p-8 rounded shadow-md w-full sm:w-10/12 md:w-96 lg:w-96 xl:w-96">
    
    <!-- Logo -->
    <div class="flex justify-center mb-4">
      <img src="img/logo.png" alt="Logo de la Empresa" class="w-20 h-auto">
    </div>

    <h2 class="text-xl font-bold mb-4 text-center text-gray-700">Restablecer Contraseña</h2>

    <!-- Mensaje de respuesta -->
    <div id="mensaje" class="hidden p-2 mb-3 rounded text-center text-sm"></div>

    <!-- Formulario -->
    <form id="resetPasswordForm" class="text-sm space-y-3">
      <div id="passwordField">
        <input 
          type="password" 
          id="new_password" 
          placeholder="Nueva contraseña"
          class="w-full p-2 border rounded focus:outline-none focus:ring-1 focus:ring-gray-400" 
          required 
        />
      </div>
      <button 
        type="submit" 
        class="w-full bg-blue-500 text-white py-2 rounded hover:bg-blue-600 transition"
      >
        Actualizar
      </button>
    </form>

    <!-- Volver al login -->
    <div id="volverInicio" class="hidden mt-4 text-center text-sm">
      <a href="index.php" class="text-blue-500 hover:underline">Volver al inicio de sesión</a>
    </div>

  </div>

  <script src="js/actualizar-pass.js?v=<?php echo time();?>"></script>
</body>
</html>
