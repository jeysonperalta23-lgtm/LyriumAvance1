<?php
session_start();
session_destroy();
?>
  <?php include 'header.php'; ?>

  <!-- MIGAS -->
  <div class="max-w-7xl mx-auto px-4 py-6 flex items-center justify-center gap-6 text-sky-600">
    <a href="index.php" class="flex items-center gap-2 text-sm hover:text-sky-800 transition">
      <i class="ph-house text-lg"></i>
      <span class="font-semibold">Página Principal</span>
    </a>

    <div class="w-px h-6 bg-sky-300"></div>

    <a href="javascript:history.back();" class="flex items-center gap-2 text-sm hover:text-sky-800 transition">
      <i class="ph-arrow-left text-lg"></i>
      <span class="font-semibold">Página Anterior</span>
    </a>
  </div>

  <!-- CONTENIDO -->
  <main class="max-w-7xl mx-auto px-4 py-10 flex-1">

    <!-- Título -->
    <div class="bg-sky-500 text-white rounded-full py-4 text-2xl font-semibold text-center shadow-lg mb-10 flex items-center justify-center gap-3">
      <i class="ph-user-circle text-2xl"></i>
      <span>Mi cuenta</span>
    </div>

    <!-- LOGIN + REGISTRO -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
      <!-- LOGIN -->
      <section class="bg-white rounded-3xl shadow p-6 border border-sky-100">
        <h2 class="text-xl font-semibold text-sky-600 mb-6 flex items-center gap-2">
          <i class="ph-sign-in text-2xl text-sky-500"></i> Iniciar Sesión
        </h2>

        <form id="loginForm" method="POST" class="space-y-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Correo o usuario *</label>
            <input name="username" type="text"
              class="w-full px-4 py-3 border border-sky-300 rounded-full focus:ring-sky-400 focus:border-sky-400 bg-sky-50/40"
              required />
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Contraseña *</label>
            <input name="password" type="password"
              class="w-full px-4 py-3 border border-sky-300 rounded-full focus:ring-sky-400 focus:border-sky-400 bg-sky-50/40"
              required />
          </div>

          <button type="submit"
            class="w-full bg-sky-500 hover:bg-sky-600 text-white py-3 rounded-full font-medium transition shadow-sm">
            Acceso
          </button>

          <p id="error"
            class="hidden text-red-600 bg-red-100 rounded-lg p-2 text-center text-sm font-medium mt-2">
            Credenciales inválidas
          </p>
        </form>
      </section>

      <!-- REGISTRO -->
      <section class="bg-white rounded-3xl shadow p-6 border border-sky-100">
        <h2 class="text-xl font-semibold text-sky-600 mb-6 flex items-center gap-2">
          <i class="ph-user-plus text-2xl text-sky-500"></i> Registrarse
        </h2>

        <form action="registro.php" method="POST" class="space-y-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Correo electrónico *</label>
            <input name="email" type="email"
              class="w-full px-4 py-3 border border-sky-300 rounded-full focus:ring-sky-400 focus:border-sky-400 bg-sky-50/40"
              required />
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Contraseña *</label>
            <input name="password_reg" type="password"
              class="w-full px-4 py-3 border border-sky-300 rounded-full focus:ring-sky-400 focus:border-sky-400 bg-sky-50/40"
              required />
          </div>

          <p class="text-xs text-gray-500">
            Tus datos serán utilizados para administrar tu cuenta y mejorar tu experiencia.
            Revisa nuestra <a href="politicas.php" class="text-sky-500 underline">política de privacidad</a>.
          </p>

          <button type="submit"
            class="w-full bg-sky-500 hover:bg-sky-600 text-white py-3 rounded-full font-medium transition shadow-sm">
            Registrarse
          </button>
        </form>
      </section>
    </div>
  </main>
  <?php include 'footer.php'; ?>
  <script src="js/login.js?v=<?php echo time();?>"></script>