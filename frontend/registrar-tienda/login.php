<?php
session_start();
// Si ya está logueado como tienda, redirigir al panel
if (isset($_SESSION['tienda_id'])) {
    header('Location: ../view/vendedor/index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <base href="../">
<?php include '../header.php'; ?>

<!-- MIGAS -->
<div class="max-w-7xl mx-auto px-4 py-6 flex items-center justify-center gap-6 text-sky-600">
  <a href="../index.php" class="flex items-center gap-2 text-sm hover:text-sky-800 transition">
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
    <i class="ph-storefront text-2xl"></i>
    <span>Panel de Tienda</span>
  </div>

  <!-- LOGIN + REGISTRO -->
  <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
    
    <!-- LOGIN DE TIENDA -->
    <section class="bg-white rounded-3xl shadow p-6 border border-sky-100">
      <h2 class="text-xl font-semibold text-sky-600 mb-6 flex items-center gap-2">
        <i class="ph-sign-in text-2xl text-sky-500"></i> Iniciar Sesión
      </h2>

      <form id="loginTiendaForm" method="POST" class="space-y-4">
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Email de la tienda *</label>
          <input 
            name="email" 
            type="email"
            class="w-full px-4 py-3 border border-sky-300 rounded-full focus:ring-sky-400 focus:border-sky-400 bg-sky-50/40"
            placeholder="tu-tienda@ejemplo.com"
            required 
          />
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Contraseña *</label>
          <input 
            name="password" 
            type="password"
            class="w-full px-4 py-3 border border-sky-300 rounded-full focus:ring-sky-400 focus:border-sky-400 bg-sky-50/40"
            required 
          />
        </div>

        <div class="flex items-center justify-between text-sm">
          <label class="flex items-center gap-2">
            <input type="checkbox" name="remember" class="rounded border-sky-300 text-sky-500 focus:ring-sky-400">
            <span class="text-gray-600">Recordarme</span>
          </label>
          <a href="recuperar-password.php" class="text-sky-500 hover:text-sky-700">¿Olvidaste tu contraseña?</a>
        </div>

        <button 
          type="submit"
          class="w-full bg-sky-500 hover:bg-sky-600 text-white py-3 rounded-full font-medium transition shadow-sm"
        >
          <i class="ph-sign-in"></i> Acceder al Panel
        </button>

        <p id="errorLogin" class="hidden text-red-600 bg-red-100 rounded-lg p-2 text-center text-sm font-medium mt-2">
          Credenciales inválidas
        </p>
        
        <p id="successLogin" class="hidden text-green-600 bg-green-100 rounded-lg p-2 text-center text-sm font-medium mt-2">
          Login exitoso. Redirigiendo...
        </p>
      </form>
    </section>

    <!-- REGISTRO DE TIENDA -->
    <section class="bg-white rounded-3xl shadow p-6 border border-sky-100">
      <h2 class="text-xl font-semibold text-sky-600 mb-6 flex items-center gap-2">
        <i class="ph-user-plus text-2xl text-sky-500"></i> Registrar Mi Tienda
      </h2>

      <form id="registroTiendaForm" method="POST" class="space-y-4">
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Nombre de la tienda *</label>
          <input 
            name="nombre_tienda" 
            type="text"
            class="w-full px-4 py-3 border border-sky-300 rounded-full focus:ring-sky-400 focus:border-sky-400 bg-sky-50/40"
            placeholder="Ej: Vida Natural"
            required 
          />
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Email *</label>
          <input 
            name="email" 
            type="email"
            class="w-full px-4 py-3 border border-sky-300 rounded-full focus:ring-sky-400 focus:border-sky-400 bg-sky-50/40"
            placeholder="contacto@tutienda.com"
            required 
          />
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Contraseña *</label>
          <input 
            name="password" 
            type="password"
            class="w-full px-4 py-3 border border-sky-300 rounded-full focus:ring-sky-400 focus:border-sky-400 bg-sky-50/40"
            minlength="6"
            required 
          />
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Teléfono</label>
          <input 
            name="telefono" 
            type="tel"
            class="w-full px-4 py-3 border border-sky-300 rounded-full focus:ring-sky-400 focus:border-sky-400 bg-sky-50/40"
            placeholder="+51 999 999 999"
          />
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Plan</label>
          <select 
            name="plan"
            class="w-full px-4 py-3 border border-sky-300 rounded-full focus:ring-sky-400 focus:border-sky-400 bg-sky-50/40"
          >
            <option value="basico">Básico - S/ 29/mes</option>
            <option value="premium">Premium - S/ 79/mes</option>
          </select>
        </div>

        <p class="text-xs text-gray-500">
          Al registrarte, aceptas nuestros 
          <a href="../terminoscondiciones.php" class="text-sky-500 underline">términos y condiciones</a> y 
          <a href="../politicasdeprivacidad.php" class="text-sky-500 underline">política de privacidad</a>.
        </p>

        <button 
          type="submit"
          class="w-full bg-sky-500 hover:bg-sky-600 text-white py-3 rounded-full font-medium transition shadow-sm"
        >
          <i class="ph-storefront"></i> Registrar Tienda
        </button>

        <p id="errorRegistro" class="hidden text-red-600 bg-red-100 rounded-lg p-2 text-center text-sm font-medium mt-2"></p>
        
        <p id="successRegistro" class="hidden text-green-600 bg-green-100 rounded-lg p-2 text-center text-sm font-medium mt-2"></p>
      </form>
    </section>
    
  </div>

  <!-- Información adicional -->
  <div class="mt-10 bg-sky-50 rounded-3xl p-6 border border-sky-100">
    <h3 class="text-lg font-semibold text-sky-700 mb-4 flex items-center gap-2">
      <i class="ph-info text-xl"></i> ¿Por qué vender en Lyrium?
    </h3>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
      <div class="flex items-start gap-3">
        <i class="ph-fill ph-chart-line text-2xl text-sky-500"></i>
        <div>
          <h4 class="font-semibold text-gray-800">Aumenta tus ventas</h4>
          <p class="text-sm text-gray-600">Llega a miles de clientes potenciales</p>
        </div>
      </div>
      <div class="flex items-start gap-3">
        <i class="ph-fill ph-gear text-2xl text-sky-500"></i>
        <div>
          <h4 class="font-semibold text-gray-800">Fácil de usar</h4>
          <p class="text-sm text-gray-600">Panel intuitivo para gestionar tu tienda</p>
        </div>
      </div>
      <div class="flex items-start gap-3">
        <i class="ph-fill ph-shield-check text-2xl text-sky-500"></i>
        <div>
          <h4 class="font-semibold text-gray-800">Seguro y confiable</h4>
          <p class="text-sm text-gray-600">Protegemos tus datos y transacciones</p>
        </div>
      </div>
    </div>
  </div>

</main>

<?php include '../footer.php'; ?>

<script>
// ============================================================================
// LOGIN DE TIENDA
// ============================================================================
document.getElementById('loginTiendaForm').addEventListener('submit', async function(e) {
  e.preventDefault();
  
  const errorDiv = document.getElementById('errorLogin');
  const successDiv = document.getElementById('successLogin');
  const btn = this.querySelector('button[type="submit"]');
  
  errorDiv.classList.add('hidden');
  successDiv.classList.add('hidden');
  
  const originalText = btn.innerHTML;
  btn.innerHTML = '<i class="ph-circle-notch animate-spin"></i> Iniciando sesión...';
  btn.disabled = true;
  
  try {
    const formData = new FormData(this);
    const response = await fetch('../backend/api/tienda.php?op=login', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({
        email: formData.get('email'),
        password: formData.get('password')
      })
    });
    
    const data = await response.json();
    
    if (data.success) {
      successDiv.textContent = data.mensaje || 'Login exitoso. Redirigiendo...';
      successDiv.classList.remove('hidden');
      
      setTimeout(() => {
        window.location.href = '/lyrium/frontend/view/vendedor/index.php';
      }, 1000);
    } else {
      errorDiv.textContent = data.error || 'Credenciales inválidas';
      errorDiv.classList.remove('hidden');
      btn.innerHTML = originalText;
      btn.disabled = false;
    }
  } catch (error) {
    errorDiv.textContent = 'Error de conexión. Intenta nuevamente.';
    errorDiv.classList.remove('hidden');
    btn.innerHTML = originalText;
    btn.disabled = false;
  }
});

// ============================================================================
// REGISTRO DE TIENDA
// ============================================================================
document.getElementById('registroTiendaForm').addEventListener('submit', async function(e) {
  e.preventDefault();
  
  const errorDiv = document.getElementById('errorRegistro');
  const successDiv = document.getElementById('successRegistro');
  const btn = this.querySelector('button[type="submit"]');
  
  errorDiv.classList.add('hidden');
  successDiv.classList.add('hidden');
  
  const originalText = btn.innerHTML;
  btn.innerHTML = '<i class="ph-circle-notch animate-spin"></i> Registrando...';
  btn.disabled = true;
  
  try {
    const formData = new FormData(this);
    const response = await fetch('../backend/api/tienda.php?op=registrar', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({
        nombre_tienda: formData.get('nombre_tienda'),
        email: formData.get('email'),
        password: formData.get('password'),
        telefono: formData.get('telefono'),
        plan: formData.get('plan')
      })
    });
    
    const data = await response.json();
    
    if (data.success) {
      successDiv.textContent = data.mensaje || 'Tienda registrada exitosamente. Recibirás un email cuando sea aprobada.';
      successDiv.classList.remove('hidden');
      this.reset();
      btn.innerHTML = originalText;
      btn.disabled = false;
    } else {
      errorDiv.textContent = data.error || 'Error al registrar tienda';
      errorDiv.classList.remove('hidden');
      btn.innerHTML = originalText;
      btn.disabled = false;
    }
  } catch (error) {
    errorDiv.textContent = 'Error de conexión. Intenta nuevamente.';
    errorDiv.classList.remove('hidden');
    btn.innerHTML = originalText;
    btn.disabled = false;
  }
});
</script>
