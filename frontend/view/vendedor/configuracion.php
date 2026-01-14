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
  <title>Configuración - <?php echo htmlspecialchars($tienda_nombre); ?></title>
  
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
        <h1 class="text-3xl font-bold text-gray-800">Configuración de Tienda</h1>
        <p class="text-gray-500">Personaliza la información y apariencia de tu tienda</p>
      </div>

      <!-- Tabs de Configuración -->
      <div class="bg-white rounded-xl shadow-sm mb-6">
        <div class="border-b border-gray-200">
          <nav class="flex -mb-px">
            <button onclick="cambiarTab('info')" id="tab-info" class="tab-btn active px-6 py-3 border-b-2 border-sky-500 text-sky-600 font-medium">
              <i class="ph ph-info"></i> Información Básica
            </button>
            <button onclick="cambiarTab('diseno')" id="tab-diseno" class="tab-btn px-6 py-3 border-b-2 border-transparent text-gray-500 hover:text-gray-700 font-medium">
              <i class="ph ph-palette"></i> Diseño y Apariencia
            </button>
            <button onclick="cambiarTab('contacto')" id="tab-contacto" class="tab-btn px-6 py-3 border-b-2 border-transparent text-gray-500 hover:text-gray-700 font-medium">
              <i class="ph ph-phone"></i> Contacto y Redes
            </button>
          </nav>
        </div>
      </div>

      <!-- Contenido de Tabs -->
      
      <!-- TAB: Información Básica -->
      <div id="content-info" class="tab-content">
        <form id="formInfoBasica" class="bg-white rounded-xl shadow-sm p-6">
          <h3 class="text-lg font-bold text-gray-800 mb-4">Información Básica</h3>
          
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            
            <!-- Nombre de Tienda -->
            <div class="md:col-span-2">
              <label class="block text-sm font-medium text-gray-700 mb-2">Nombre de la Tienda *</label>
              <input type="text" name="nombre" value="Vida Natural" required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-transparent">
            </div>

            <!-- Descripción -->
            <div class="md:col-span-2">
              <label class="block text-sm font-medium text-gray-700 mb-2">Descripción *</label>
              <textarea name="descripcion" rows="4" required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-transparent">Somos una empresa dedicada a la comercialización de productos naturales y orgánicos.</textarea>
            </div>

            <!-- Logo URL -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">URL del Logo</label>
              <input type="url" name="logo" value="https://upload.wikimedia.org/wikipedia/commons/thumb/4/4f/Iconic_image_of_a_leaf.svg/256px-Iconic_image_of_a_leaf.svg.png"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-transparent">
              <p class="text-xs text-gray-500 mt-1">Tamaño recomendado: 256x256px</p>
            </div>

            <!-- Cover URL -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">URL del Banner</label>
              <input type="url" name="cover" value="https://images.unsplash.com/photo-1543362906-acfc16c67564?q=80&w=1400&auto=format&fit=crop"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-transparent">
              <p class="text-xs text-gray-500 mt-1">Tamaño recomendado: 1400x400px</p>
            </div>

            <!-- Categoría -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Categoría *</label>
              <select name="categoria" required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-transparent">
                <option value="Salud y Bienestar">Salud y Bienestar</option>
                <option value="Belleza y Cuidado Personal">Belleza y Cuidado Personal</option>
                <option value="Alimentos Orgánicos">Alimentos Orgánicos</option>
                <option value="Suplementos">Suplementos</option>
                <option value="Productos para Mascotas">Productos para Mascotas</option>
              </select>
            </div>

            <!-- Actividad -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Actividad Comercial</label>
              <input type="text" name="actividad" value="Comercio al por menor de productos naturales"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-transparent">
            </div>

          </div>

          <div class="mt-6 flex justify-end gap-3">
            <button type="button" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
              Cancelar
            </button>
            <button type="submit" class="px-6 py-2 bg-sky-500 text-white rounded-lg hover:bg-sky-600">
              <i class="ph ph-floppy-disk"></i> Guardar Cambios
            </button>
          </div>
        </form>
      </div>

      <!-- TAB: Diseño y Apariencia -->
      <div id="content-diseno" class="tab-content hidden">
        <form id="formDiseno" class="bg-white rounded-xl shadow-sm p-6">
          <h3 class="text-lg font-bold text-gray-800 mb-4">Diseño y Apariencia</h3>
          
          <?php if ($tienda_plan === 'premium'): ?>
          
          <!-- Layout -->
          <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-3">Modelo de Layout</label>
            <p class="text-sm text-gray-500 mb-4">Selecciona cómo se organizará el contenido de tu tienda</p>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
              
              <!-- Layout 1: Sidebar Derecha (Por Defecto) -->
              <label class="relative cursor-pointer group">
                <input type="radio" name="layout_modelo" value="1" checked class="peer sr-only">
                <div class="border-2 border-gray-300 rounded-lg p-4 peer-checked:border-sky-500 peer-checked:bg-sky-50 hover:border-sky-300 transition-all">
                  <!-- Preview detallado -->
                  <div class="aspect-[3/4] bg-gray-100 rounded mb-3 p-2 flex flex-col gap-1">
                    <!-- Banner rojo -->
                    <div class="h-8 bg-red-400 rounded"></div>
                    <!-- Contenido con sidebar derecha -->
                    <div class="flex-1 flex gap-1">
                      <!-- Contenido principal -->
                      <div class="flex-1 flex flex-col gap-1">
                        <!-- Secciones beige -->
                        <div class="h-4 bg-amber-200 rounded"></div>
                        <div class="h-4 bg-sky-300 rounded"></div>
                        <!-- Productos verdes -->
                        <div class="flex-1 grid grid-cols-3 gap-1">
                          <div class="bg-green-400 rounded"></div>
                          <div class="bg-green-400 rounded"></div>
                          <div class="bg-green-400 rounded"></div>
                        </div>
                        <!-- Sección beige -->
                        <div class="h-4 bg-amber-200 rounded"></div>
                        <!-- Más productos -->
                        <div class="flex-1 grid grid-cols-4 gap-1">
                          <div class="bg-green-400 rounded"></div>
                          <div class="bg-green-400 rounded"></div>
                          <div class="bg-green-400 rounded"></div>
                          <div class="bg-green-400 rounded"></div>
                        </div>
                      </div>
                      <!-- Sidebar amarillo derecha -->
                      <div class="w-8 bg-amber-300 rounded"></div>
                    </div>
                  </div>
                  <div class="text-center">
                    <p class="text-sm font-bold text-gray-800">Layout A - Sidebar Derecha</p>
                    <p class="text-xs text-gray-500 mt-1">Por defecto</p>
                  </div>
                </div>
                <!-- Indicador de selección -->
                <div class="absolute top-2 right-2 w-6 h-6 bg-sky-500 rounded-full items-center justify-center text-white hidden peer-checked:flex">
                  <i class="ph-fill ph-check text-sm"></i>
                </div>
              </label>

              <!-- Layout 2: Sidebar Izquierda -->
              <label class="relative cursor-pointer group">
                <input type="radio" name="layout_modelo" value="2" class="peer sr-only">
                <div class="border-2 border-gray-300 rounded-lg p-4 peer-checked:border-sky-500 peer-checked:bg-sky-50 hover:border-sky-300 transition-all">
                  <!-- Preview detallado -->
                  <div class="aspect-[3/4] bg-gray-100 rounded mb-3 p-2 flex flex-col gap-1">
                    <!-- Banner rojo -->
                    <div class="h-8 bg-red-400 rounded"></div>
                    <!-- Contenido con sidebar izquierda -->
                    <div class="flex-1 flex gap-1">
                      <!-- Sidebar amarillo izquierda -->
                      <div class="w-8 bg-amber-300 rounded"></div>
                      <!-- Contenido principal -->
                      <div class="flex-1 flex flex-col gap-1">
                        <!-- Secciones beige -->
                        <div class="h-4 bg-amber-200 rounded"></div>
                        <div class="h-4 bg-sky-300 rounded"></div>
                        <!-- Productos verdes -->
                        <div class="flex-1 grid grid-cols-3 gap-1">
                          <div class="bg-green-400 rounded"></div>
                          <div class="bg-green-400 rounded"></div>
                          <div class="bg-green-400 rounded"></div>
                        </div>
                        <!-- Sección beige -->
                        <div class="h-4 bg-amber-200 rounded"></div>
                        <!-- Más productos -->
                        <div class="flex-1 grid grid-cols-4 gap-1">
                          <div class="bg-green-400 rounded"></div>
                          <div class="bg-green-400 rounded"></div>
                          <div class="bg-green-400 rounded"></div>
                          <div class="bg-green-400 rounded"></div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="text-center">
                    <p class="text-sm font-bold text-gray-800">Layout B - Sidebar Izquierda</p>
                    <p class="text-xs text-gray-500 mt-1">Premium</p>
                  </div>
                </div>
                <!-- Indicador de selección -->
                <div class="absolute top-2 right-2 w-6 h-6 bg-sky-500 rounded-full items-center justify-center text-white hidden peer-checked:flex">
                  <i class="ph-fill ph-check text-sm"></i>
                </div>
              </label>

              <!-- Layout 3: Full Width (Sin Sidebar) -->
              <label class="relative cursor-pointer group">
                <input type="radio" name="layout_modelo" value="3" class="peer sr-only">
                <div class="border-2 border-gray-300 rounded-lg p-4 peer-checked:border-sky-500 peer-checked:bg-sky-50 hover:border-sky-300 transition-all">
                  <!-- Preview detallado -->
                  <div class="aspect-[3/4] bg-gray-100 rounded mb-3 p-2 flex flex-col gap-1">
                    <!-- Banner rojo -->
                    <div class="h-8 bg-red-400 rounded"></div>
                    <!-- Contenido sin sidebar -->
                    <div class="flex-1 flex flex-col gap-1">
                      <!-- Secciones beige más anchas -->
                      <div class="h-4 bg-amber-200 rounded"></div>
                      <div class="h-5 bg-sky-300 rounded"></div>
                      <div class="h-4 bg-amber-200 rounded"></div>
                      <!-- Productos verdes (4 columnas) -->
                      <div class="flex-1 grid grid-cols-4 gap-1">
                        <div class="bg-green-400 rounded"></div>
                        <div class="bg-green-400 rounded"></div>
                        <div class="bg-green-400 rounded"></div>
                        <div class="bg-green-400 rounded"></div>
                      </div>
                      <!-- Banner adicional -->
                      <div class="h-5 bg-amber-200 rounded"></div>
                      <!-- Más productos (4 columnas) -->
                      <div class="flex-1 grid grid-cols-4 gap-1">
                        <div class="bg-green-400 rounded"></div>
                        <div class="bg-green-400 rounded"></div>
                        <div class="bg-green-400 rounded"></div>
                        <div class="bg-green-400 rounded"></div>
                      </div>
                    </div>
                  </div>
                  <div class="text-center">
                    <p class="text-sm font-bold text-gray-800">Layout C - Ancho Completo</p>
                    <p class="text-xs text-gray-500 mt-1">Sin sidebar, más banners</p>
                  </div>
                </div>
                <!-- Indicador de selección -->
                <div class="absolute top-2 right-2 w-6 h-6 bg-sky-500 rounded-full items-center justify-center text-white hidden peer-checked:flex">
                  <i class="ph-fill ph-check text-sm"></i>
                </div>
              </label>

            </div>
          </div>

          <!-- Tema de Color -->
          <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-3">Tema de Color</label>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
              
              <label class="relative cursor-pointer">
                <input type="radio" name="tema" value="" checked class="peer sr-only">
                <div class="border-2 border-gray-300 rounded-lg p-4 peer-checked:border-sky-500 peer-checked:bg-sky-50">
                  <div class="h-16 bg-gradient-to-r from-sky-400 to-blue-500 rounded mb-2"></div>
                  <p class="text-sm font-medium text-center">Sky Blue (Default)</p>
                </div>
              </label>

              <label class="relative cursor-pointer">
                <input type="radio" name="tema" value="tema-ocean" class="peer sr-only">
                <div class="border-2 border-gray-300 rounded-lg p-4 peer-checked:border-sky-500 peer-checked:bg-sky-50">
                  <div class="h-16 bg-gradient-to-r from-cyan-500 to-teal-600 rounded mb-2"></div>
                  <p class="text-sm font-medium text-center">Ocean</p>
                </div>
              </label>

              <label class="relative cursor-pointer">
                <input type="radio" name="tema" value="tema-dark" class="peer sr-only">
                <div class="border-2 border-gray-300 rounded-lg p-4 peer-checked:border-sky-500 peer-checked:bg-sky-50">
                  <div class="h-16 bg-gradient-to-r from-gray-700 to-gray-900 rounded mb-2"></div>
                  <p class="text-sm font-medium text-center">Dark</p>
                </div>
              </label>

              <label class="relative cursor-pointer">
                <input type="radio" name="tema" value="tema-minimal" class="peer sr-only">
                <div class="border-2 border-gray-300 rounded-lg p-4 peer-checked:border-sky-500 peer-checked:bg-sky-50">
                  <div class="h-16 bg-gradient-to-r from-gray-100 to-gray-300 rounded mb-2"></div>
                  <p class="text-sm font-medium text-center">Minimal</p>
                </div>
              </label>

            </div>
          </div>

          <?php else: ?>
          
          <div class="bg-orange-50 border border-orange-200 rounded-lg p-6 text-center">
            <i class="ph-fill ph-crown text-4xl text-orange-500 mb-3"></i>
            <h4 class="text-lg font-bold text-gray-800 mb-2">Función Premium</h4>
            <p class="text-gray-600 mb-4">La personalización de diseño y temas está disponible solo en el Plan Premium</p>
            <button type="button" class="px-6 py-2 bg-orange-500 text-white rounded-lg hover:bg-orange-600">
              <i class="ph ph-arrow-up"></i> Actualizar a Premium
            </button>
          </div>

          <?php endif; ?>

          <div class="mt-6 flex justify-end gap-3">
            <button type="button" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
              Cancelar
            </button>
            <button type="submit" class="px-6 py-2 bg-sky-500 text-white rounded-lg hover:bg-sky-600">
              <i class="ph ph-floppy-disk"></i> Guardar Cambios
            </button>
          </div>
        </form>
      </div>

      <!-- TAB: Contacto y Redes -->
      <div id="content-contacto" class="tab-content hidden">
        <form id="formContacto" class="bg-white rounded-xl shadow-sm p-6">
          <h3 class="text-lg font-bold text-gray-800 mb-4">Información de Contacto</h3>
          
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            
            <!-- Teléfono -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Teléfono *</label>
              <input type="tel" name="telefono" value="+51 912 345 678" required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-transparent">
            </div>

            <!-- Email -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Email *</label>
              <input type="email" name="correo" value="contacto@vidanatural.com" required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-transparent">
            </div>

            <!-- Dirección -->
            <div class="md:col-span-2">
              <label class="block text-sm font-medium text-gray-700 mb-2">Dirección</label>
              <input type="text" name="direccion" value="Urb. Los Educadores Mz M Lt 04, Piura, Perú"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-transparent">
            </div>

            <!-- Redes Sociales -->
            <div class="md:col-span-2">
              <h4 class="text-md font-semibold text-gray-800 mb-3">Redes Sociales</h4>
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">
                <i class="ph-fill ph-facebook-logo text-blue-600"></i> Facebook
              </label>
              <input type="url" name="facebook" placeholder="https://facebook.com/tutienda"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-transparent">
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">
                <i class="ph-fill ph-instagram-logo text-pink-600"></i> Instagram
              </label>
              <input type="url" name="instagram" placeholder="https://instagram.com/tutienda"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-transparent">
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">
                <i class="ph-fill ph-whatsapp-logo text-green-600"></i> WhatsApp
              </label>
              <input type="tel" name="whatsapp" placeholder="+51 999 999 999"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-transparent">
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">
                <i class="ph-fill ph-tiktok-logo text-black"></i> TikTok
              </label>
              <input type="url" name="tiktok" placeholder="https://tiktok.com/@tutienda"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-transparent">
            </div>

          </div>

          <div class="mt-6 flex justify-end gap-3">
            <button type="button" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
              Cancelar
            </button>
            <button type="submit" class="px-6 py-2 bg-sky-500 text-white rounded-lg hover:bg-sky-600">
              <i class="ph ph-floppy-disk"></i> Guardar Cambios
            </button>
          </div>
        </form>
      </div>

    </main>
    
  </div>

  <script>
    // Cambiar entre tabs
    function cambiarTab(tab) {
      // Ocultar todos los contenidos
      document.querySelectorAll('.tab-content').forEach(content => {
        content.classList.add('hidden');
      });
      
      // Desactivar todos los botones
      document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.classList.remove('active', 'border-sky-500', 'text-sky-600');
        btn.classList.add('border-transparent', 'text-gray-500');
      });
      
      // Mostrar contenido seleccionado
      document.getElementById('content-' + tab).classList.remove('hidden');
      
      // Activar botón seleccionado
      const activeBtn = document.getElementById('tab-' + tab);
      activeBtn.classList.add('active', 'border-sky-500', 'text-sky-600');
      activeBtn.classList.remove('border-transparent', 'text-gray-500');
    }

    // Manejar formulario de información básica
    document.getElementById('formInfoBasica').addEventListener('submit', async function(e) {
      e.preventDefault();
      
      const formData = new FormData(this);
      const data = Object.fromEntries(formData);
      
      try {
        const response = await fetch('../../backend/api/tienda.php', {
          method: 'PUT',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify({
            id: <?php echo $tienda_id; ?>,
            ...data
          })
        });
        
        const result = await response.json();
        
        if (result.success) {
          alert('Información actualizada correctamente');
        } else {
          alert('Error al actualizar: ' + result.error);
        }
      } catch (error) {
        alert('Error de conexión');
      }
    });

    // Manejar formulario de diseño
    document.getElementById('formDiseno').addEventListener('submit', async function(e) {
      e.preventDefault();
      
      const formData = new FormData(this);
      const data = Object.fromEntries(formData);
      
      try {
        const response = await fetch('../../backend/api/tienda.php?op=actualizar_configuracion', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify(data)
        });
        
        const result = await response.json();
        
        if (result.success) {
          alert('Diseño actualizado correctamente');
        } else {
          alert('Error al actualizar: ' + result.error);
        }
      } catch (error) {
        alert('Error de conexión');
      }
    });

    // Manejar formulario de contacto
    document.getElementById('formContacto').addEventListener('submit', async function(e) {
      e.preventDefault();
      
      const formData = new FormData(this);
      const data = Object.fromEntries(formData);
      
      try {
        const response = await fetch('../../backend/api/tienda.php', {
          method: 'PUT',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify({
            id: <?php echo $tienda_id; ?>,
            ...data
          })
        });
        
        const result = await response.json();
        
        if (result.success) {
          alert('Contacto actualizado correctamente');
        } else {
          alert('Error al actualizar: ' + result.error);
        }
      } catch (error) {
        alert('Error de conexión');
      }
    });
  </script>

</body>
</html>
