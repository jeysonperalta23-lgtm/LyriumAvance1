<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Registro de Usuario</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen flex items-center justify-center bg-gray-100 px-4">
  <div class="bg-white p-6 rounded-lg shadow-md w-full max-w-md">
    <h2 class="text-xl font-bold mb-6 text-center text-gray-800">Registro de Usuario</h2>
    
    <form id="registroForm" class="grid grid-cols-1 gap-4 md:grid-cols-2 text-sm">
      <div class="md:col-span-2">
        <label for="nombre" class="block font-medium text-gray-700">Nombre / Razón social *</label>
        <input id="nombre" name="nombre" type="text" placeholder="Ej. Juan Pérez"
               class="w-full p-2 border rounded mt-1" required />
      </div>

      <div>
        <label for="documento_identidad" class="block font-medium text-gray-700">DNI o RUC *</label>
        <!-- nombre de campo alineado a la BD: documento_identidad -->
        <input id="documento_identidad" name="documento_identidad" type="text"
               placeholder="12345678"
               class="w-full p-2 border rounded mt-1" required />
      </div>

      <div>
        <label for="fecha_nacimiento" class="block font-medium text-gray-700">Fecha de nacimiento</label>
        <input id="fecha_nacimiento" name="fecha_nacimiento" type="date"
               class="w-full p-2 border rounded mt-1" />
      </div>

      <div>
        <label for="sexo" class="block font-medium text-gray-700">Sexo *</label>
        <select id="sexo" name="sexo" class="w-full p-2 border rounded mt-1" required>
          <option value="">Seleccione</option>
          <option value="M">Masculino</option>
          <option value="F">Femenino</option>
        </select>
      </div>

      <div>
        <label for="username" class="block font-medium text-gray-700">Usuario *</label>
        <input id="username" name="username" type="text" placeholder="juan123"
               class="w-full p-2 border rounded mt-1" required />
      </div>

      <div>
        <label for="password" class="block font-medium text-gray-700">Contraseña *</label>
        <input id="password" name="password" type="password" placeholder="********"
               class="w-full p-2 border rounded mt-1" required />
      </div>

      <div class="md:col-span-2">
        <label for="correo" class="block font-medium text-gray-700">Correo electrónico</label>
        <input id="correo" name="correo" type="email" placeholder="correo@ejemplo.com"
               class="w-full p-2 border rounded mt-1" />
      </div>

      <!-- Tipo de persona (por ahora fijo a Persona natural = 1) -->
      <input type="hidden" name="tipo_persona_id" value="1">

      <!-- Rol del usuario, alineado al enum de la tabla usuarios -->
      <div class="md:col-span-2">
        <label for="rol" class="block font-medium text-gray-700">Rol *</label>
        <select id="rol" name="rol" class="w-full p-2 border rounded mt-1" required>
          <option value="">Seleccione un rol</option>
          <option value="Administrador">Administrador</option>
          <option value="Operador" selected>Operador</option>
          <option value="Visor">Visor</option>
        </select>
      </div>

      <div class="md:col-span-2">
        <button type="submit"
                class="bg-green-500 hover:bg-green-600 text-white py-2 rounded w-full">
          Registrar
        </button>
      </div>
    </form>

    <div class="mt-4 text-center text-sm">
      <a href="index.php" class="text-blue-500 hover:underline">
        ¿Ya tienes cuenta? Inicia sesión
      </a>
    </div>

    <p id="error"
       class="text-red-600 font-semibold mt-2 text-center hidden text-sm bg-red-100 rounded-lg p-2 shadow-sm">
    </p>
  </div>

  <script src="js/registro.js?v=<?php echo time();?>"></script>
</body>
</html>
