<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - Panel de Tienda | Lyrium</title>
  
  <!-- TailwindCSS -->
  <script src="https://cdn.tailwindcss.com"></script>
  
  <!-- Iconos Phosphor -->
  <script src="https://unpkg.com/phosphor-icons"></script>
  
  <style>
    body {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
  </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4">

  <!-- Contenedor Principal -->
  <div class="w-full max-w-md">
    
    <!-- Logo y Título -->
    <div class="text-center mb-8">
      <div class="inline-block bg-white rounded-2xl p-4 shadow-lg mb-4">
        <i class="ph-storefront text-5xl text-purple-600"></i>
      </div>
      <h1 class="text-3xl font-bold text-white mb-2">Panel de Tienda</h1>
      <p class="text-purple-100">Ingresa con tus credenciales</p>
    </div>
    
    <!-- Card de Login -->
    <div class="bg-white rounded-2xl shadow-2xl p-8">
      
      <!-- Formulario -->
      <form id="formLogin" class="space-y-6">
        
        <!-- Email -->
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">
            <i class="ph ph-envelope"></i> Email
          </label>
          <input 
            type="email" 
            id="email" 
            required
            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all"
            placeholder="tu-tienda@ejemplo.com"
          >
        </div>
        
        <!-- Password -->
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">
            <i class="ph ph-lock"></i> Contraseña
          </label>
          <div class="relative">
            <input 
              type="password" 
              id="password" 
              required
              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all pr-12"
              placeholder="••••••••"
            >
            <button 
              type="button" 
              id="togglePassword"
              class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600"
            >
              <i class="ph ph-eye text-xl"></i>
            </button>
          </div>
        </div>
        
        <!-- Recordarme -->
        <div class="flex items-center justify-between">
          <label class="flex items-center">
            <input type="checkbox" id="remember" class="w-4 h-4 text-purple-600 border-gray-300 rounded focus:ring-purple-500">
            <span class="ml-2 text-sm text-gray-600">Recordarme</span>
          </label>
          <a href="recuperar-password.php" class="text-sm text-purple-600 hover:text-purple-700 font-medium">
            ¿Olvidaste tu contraseña?
          </a>
        </div>
        
        <!-- Botón Login -->
        <button 
          type="submit" 
          id="btnLogin"
          class="w-full bg-gradient-to-r from-purple-600 to-indigo-600 text-white font-bold py-3 px-6 rounded-lg hover:from-purple-700 hover:to-indigo-700 transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-0.5"
        >
          <i class="ph ph-sign-in"></i> Iniciar Sesión
        </button>
        
      </form>
      
      <!-- Mensaje de error -->
      <div id="errorMessage" class="hidden mt-4 p-3 bg-red-50 border border-red-200 rounded-lg text-red-700 text-sm">
        <i class="ph ph-warning-circle"></i>
        <span id="errorText"></span>
      </div>
      
      <!-- Mensaje de éxito -->
      <div id="successMessage" class="hidden mt-4 p-3 bg-green-50 border border-green-200 rounded-lg text-green-700 text-sm">
        <i class="ph ph-check-circle"></i>
        <span id="successText"></span>
      </div>
      
      <!-- Separador -->
      <div class="relative my-6">
        <div class="absolute inset-0 flex items-center">
          <div class="w-full border-t border-gray-300"></div>
        </div>
        <div class="relative flex justify-center text-sm">
          <span class="px-4 bg-white text-gray-500">¿No tienes cuenta?</span>
        </div>
      </div>
      
      <!-- Link a Registro -->
      <a 
        href="../registrar-tienda/index.php" 
        class="block w-full text-center py-3 px-6 border-2 border-purple-600 text-purple-600 font-bold rounded-lg hover:bg-purple-50 transition-all"
      >
        <i class="ph ph-user-plus"></i> Registrar Mi Tienda
      </a>
      
    </div>
    
    <!-- Footer -->
    <div class="text-center mt-6">
      <a href="../../index.php" class="text-white hover:text-purple-100 text-sm">
        <i class="ph ph-arrow-left"></i> Volver al inicio
      </a>
    </div>
    
  </div>

  <script>
    // Toggle password visibility
    document.getElementById('togglePassword').addEventListener('click', function() {
      const passwordInput = document.getElementById('password');
      const icon = this.querySelector('i');
      
      if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        icon.className = 'ph ph-eye-slash text-xl';
      } else {
        passwordInput.type = 'password';
        icon.className = 'ph ph-eye text-xl';
      }
    });
    
    // Manejar submit del formulario
    document.getElementById('formLogin').addEventListener('submit', async function(e) {
      e.preventDefault();
      
      const btn = document.getElementById('btnLogin');
      const errorDiv = document.getElementById('errorMessage');
      const successDiv = document.getElementById('successMessage');
      const errorText = document.getElementById('errorText');
      const successText = document.getElementById('successText');
      
      // Ocultar mensajes previos
      errorDiv.classList.add('hidden');
      successDiv.classList.add('hidden');
      
      // Deshabilitar botón y mostrar loading
      const originalText = btn.innerHTML;
      btn.innerHTML = '<i class="ph ph-circle-notch animate-spin"></i> Iniciando sesión...';
      btn.disabled = true;
      
      try {
        const response = await fetch('../../backend/api/tienda.php?op=login', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify({
            email: document.getElementById('email').value,
            password: document.getElementById('password').value
          })
        });
        
        const data = await response.json();
        
        if (data.success) {
          // Éxito
          successText.textContent = data.mensaje || 'Login exitoso. Redirigiendo...';
          successDiv.classList.remove('hidden');
          
          // Redirigir al dashboard
          setTimeout(() => {
            window.location.href = 'index.php';
          }, 1000);
          
        } else {
          // Error
          errorText.textContent = data.error || 'Error al iniciar sesión';
          errorDiv.classList.remove('hidden');
          
          btn.innerHTML = originalText;
          btn.disabled = false;
        }
        
      } catch (error) {
        errorText.textContent = 'Error de conexión. Intenta nuevamente.';
        errorDiv.classList.remove('hidden');
        
        btn.innerHTML = originalText;
        btn.disabled = false;
      }
    });
  </script>

</body>
</html>
