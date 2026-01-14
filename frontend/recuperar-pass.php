<?php
session_start();
session_destroy(); // Si hay sesión, la cierro
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Recuperar Contraseña</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="flex items-center justify-center h-screen bg-gray-100">
  <div class="bg-white p-8 rounded shadow-md w-full sm:w-10/12 md:w-96 lg:w-96 xl:w-96">
    <!-- Logo -->
    <div class="flex justify-center mb-4">
      <img src="img/logo.png" alt="Logo de la Empresa" class="w-24 h-auto">
    </div>

    <h2 class="text-xl font-bold mb-4 text-center">Recuperar Contraseña</h2>

    <form id="resetEmailForm" method="POST">
      <input name="email" type="email"
             placeholder="Correo electrónico"
             class="w-full p-2 mb-3 border rounded" required />
      <button type="submit"
              class="bg-blue-500 text-white px-4 py-2 rounded w-full">
        Enviar enlace
      </button>
    </form>
    
    <p id="error"
       class="text-red-600 font-semibold mt-2 text-center hidden text-sm bg-red-100 rounded-lg p-2 shadow-sm">
    </p>

    <div class="mt-4 text-center">
      <a href="index.php" class="text-blue-500 text-sm hover:underline">Volver al inicio</a>
    </div>
  </div>

  <script src="js/recuperar-pass.js?v=<?php echo time();?>"></script>
</body>
</html>
