<?php
/**
 * COMPONENTE: Sistema de Pestañas de Tienda
 * 
 * Variables esperadas:
 * - $tienda: datos de la tienda
 * - $productos: array de productos
 * - $sucursales: array de sucursales
 * - $fotos: array de fotos de galería
 * - $opiniones: array de opiniones/reseñas
 * - $terminos: array con políticas y términos
 * - $plan: 'basico' o 'premium'
 */

$maxFotos = ($plan === 'premium') ? 30 : 8;
$fotosVisibles = array_slice($fotos ?? [], 0, $maxFotos);
$formularioAvanzado = ($plan === 'premium');
?>

<div class="tienda-tabs">
  <!-- Header de Tabs - Estilo Arrow Nav -->
  <div class="tienda-tabs-header arrow-style shadow-sm">
    <div class="tienda-tabs-nav">
      <button class="tienda-tab-btn arrow-nav-btn active" data-tab="productos">
        <span>Productos</span>
      </button>
      <button class="tienda-tab-btn arrow-nav-btn" data-tab="sucursales">
        <span>Sucursales</span>
      </button>

      <button class="tienda-tab-btn arrow-nav-btn" data-tab="contacto">
        <span>Contacto</span>
      </button>
      <button class="tienda-tab-btn arrow-nav-btn" data-tab="opiniones">
        <span>Reseñas (<?php echo count($opiniones ?? []); ?>★)</span>
      </button>
      <button class="tienda-tab-btn arrow-nav-btn" data-tab="terminos">
        <span>Acerca de</span>
      </button>
    </div>
  </div>

  <!-- ===================== -->
  <!-- TAB: PRODUCTOS (NUEVO - PRIMERO) -->
  <!-- ===================== -->
  <div id="tab-productos" class="tienda-tab-content active">
    
    <!-- SCROLL DE PRODUCTOS DESTACADOS (S) - Ahora disponible en ambos para cumplir "1 scroll" -->
    <div class="tienda-productos-grid-section">
      <!-- Header del grid - Solo título, sin ordenar ni iconos -->
      <div class="tienda-grid-header">
        <h3 class="tienda-grid-titulo">
          Productos destacados
        </h3>
      </div>
      <div class="tienda-scroll-wrapper">
        <div class="tienda-scroll-container">
          <?php foreach (array_slice($productos, 0, 8) as $producto): ?>
          <div class="producto-scroll-card-overlay">
            <!-- Imagen del producto con overlay de botones -->
            <div class="producto-scroll-imagen">
              <a href="producto.php?id=<?php echo $producto['id']; ?>">
                <img 
                  src="<?php echo htmlspecialchars($producto['imagen']); ?>" 
                  alt="<?php echo htmlspecialchars($producto['nombre']); ?>"
                  loading="lazy"
                >
              </a>
              
              <!-- Sticker de estado -->
              <?php if (!empty($producto['sticker'])): ?>
              <span class="producto-scroll-sticker <?php echo htmlspecialchars($producto['sticker']); ?>">
                <?php 
                  $stickerTextos = [
                    'oferta' => 'Oferta',
                    'nuevo' => 'Nuevo',
                    'promo' => 'Promo',
                    'limitado' => 'Limitado'
                  ];
                  echo $stickerTextos[$producto['sticker']] ?? ucfirst($producto['sticker']);
                ?>
              </span>
              <?php endif; ?>
              
              <!-- Icono favorito (esquina superior derecha) -->
              <button class="producto-scroll-fav" title="Añadir a favoritos">
                <i class="ph ph-heart"></i>
              </button>
              
              <!-- Icono carrito (esquina inferior derecha) -->
              <button class="producto-scroll-cart" title="Añadir al carrito">
                <i class="ph ph-shopping-cart-simple"></i>
              </button>
              
              <!-- Overlay con botones (aparece al hover) -->
              <div class="producto-scroll-overlay">
                <button class="producto-overlay-btn" onclick="vistaRapidaProducto(<?php echo $producto['id']; ?>)">
                  <i class="ph ph-eye"></i>
                  Previsualizar
                </button>
                <button class="producto-overlay-btn producto-overlay-btn-outline">
                  <i class="ph ph-squares-four"></i>
                  Artículos similares
                </button>
              </div>
            </div>
            
            <!-- Info del producto -->
            <div class="producto-scroll-info">
              <h4 class="producto-scroll-nombre">
                <a href="producto.php?id=<?php echo $producto['id']; ?>">
                  <?php echo htmlspecialchars($producto['nombre']); ?>
                </a>
              </h4>
              
              <div class="producto-scroll-precios">
                <span class="producto-scroll-precio">
                  <?php echo number_format($producto['precio'], 2); ?>
                </span>
                <?php if (!empty($producto['precio_anterior'])): ?>
                <span class="producto-scroll-precio-old">
                  S/ <?php echo number_format($producto['precio_anterior'], 2); ?>
                </span>
                <?php endif; ?>
              </div>
              
              <div class="producto-scroll-meta">
                <div class="producto-scroll-stars">
                  <?php for ($i = 1; $i <= 5; $i++): ?>
                  <i class="ph-fill ph-star"></i>
                  <?php endfor; ?>
                  <span><?php echo $producto['ventas'] ?? rand(100, 5000); ?></span>
                </div>
              </div>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
        <button class="tienda-scroll-nav prev"><i class="ph-caret-left"></i></button>
        <button class="tienda-scroll-nav next"><i class="ph-caret-right"></i></button>
      </div>
    </div>

    <!-- Si es plan básico, incluimos el grid completo aquí para que no se vea vacío abajo -->
    <?php if ($plan === 'basico'): ?>
      <div class="mt-8">
        <?php include 'componentes/tienda/tienda-productos-grid.php'; ?>
      </div>
    <?php endif; ?>

  </div>

  <!-- ===================== -->
  <!-- TAB: INFORMACIÓN -->
  <!-- ===================== -->
  <div id="tab-info" class="tienda-tab-content">
    <div class="tienda-info-section">
      <h4 class="tienda-info-title">
        <i class="ph-building-office"></i>
        Sobre Nosotros
      </h4>
      <p class="text-slate-600 leading-relaxed">
        <?php echo nl2br(htmlspecialchars($tienda['descripcion'] ?? 'Sin descripción disponible.')); ?>
      </p>
    </div>
    
    <?php if (!empty($tienda['actividad'])): ?>
    <div class="tienda-info-section">
      <h4 class="tienda-info-title">
        <i class="ph-briefcase"></i>
        Actividad Empresarial
      </h4>
      <p class="text-slate-600">
        <?php echo htmlspecialchars($tienda['actividad']); ?>
      </p>
    </div>
    <?php endif; ?>
    
    <?php if (!empty($tienda['rubros'])): ?>
    <div class="tienda-info-section">
      <h4 class="tienda-info-title">
        <i class="ph-tag"></i>
        Rubros
      </h4>
      <div class="flex flex-wrap gap-2">
        <?php foreach ($tienda['rubros'] as $rubro): ?>
        <span class="inline-flex items-center gap-1 px-3 py-1 bg-sky-50 text-sky-700 rounded-full text-sm font-medium">
          <?php echo htmlspecialchars($rubro); ?>
        </span>
        <?php endforeach; ?>
      </div>
    </div>
    <?php endif; ?>
  </div>

  <!-- ===================== -->
  <!-- TAB: SUCURSALES -->
  <!-- ===================== -->
  <div id="tab-sucursales" class="tienda-tab-content">
    <?php if (!empty($sucursales)): ?>
    <div class="tienda-sucursales-grid">
      <?php foreach ($sucursales as $index => $sucursal): ?>
      <div class="tienda-sucursal-card">
        <h4 class="tienda-sucursal-nombre">
          <i class="ph-storefront"></i>
          <?php echo htmlspecialchars($sucursal['nombre']); ?>
          <?php if (!empty($sucursal['es_principal'])): ?>
          <span class="tienda-sucursal-principal">Principal</span>
          <?php endif; ?>
        </h4>
        
        <div class="tienda-sucursal-info">
          <?php if (!empty($sucursal['direccion'])): ?>
          <div class="tienda-sucursal-info-item">
            <i class="ph-map-pin"></i>
            <span><?php echo htmlspecialchars($sucursal['direccion']); ?></span>
          </div>
          <?php endif; ?>
          
          <?php if (!empty($sucursal['ciudad'])): ?>
          <div class="tienda-sucursal-info-item">
            <i class="ph-buildings"></i>
            <span><?php echo htmlspecialchars($sucursal['ciudad']); ?></span>
          </div>
          <?php endif; ?>
          
          <?php if (!empty($sucursal['telefono'])): ?>
          <div class="tienda-sucursal-info-item">
            <i class="ph-phone"></i>
            <a href="tel:<?php echo htmlspecialchars($sucursal['telefono']); ?>" class="hover:text-sky-500">
              <?php echo htmlspecialchars($sucursal['telefono']); ?>
            </a>
          </div>
          <?php endif; ?>
          
          <?php if (!empty($sucursal['horario_apertura']) && !empty($sucursal['horario_cierre'])): ?>
          <div class="tienda-sucursal-info-item">
            <i class="ph-clock"></i>
            <span><?php echo htmlspecialchars($sucursal['horario_apertura']); ?> - <?php echo htmlspecialchars($sucursal['horario_cierre']); ?></span>
          </div>
          <?php endif; ?>
        </div>
        
        <?php if (!empty($sucursal['google_maps_url'])): ?>
        <a href="<?php echo htmlspecialchars($sucursal['google_maps_url']); ?>" target="_blank" class="tienda-sucursal-mapa">
          <i class="ph-map-trifold"></i>
          Ver en Google Maps
        </a>
        <?php endif; ?>
      </div>
      <?php endforeach; ?>
    </div>
    <?php else: ?>
    <div class="text-center py-8 text-slate-400">
      <i class="ph-storefront text-4xl mb-2"></i>
      <p>No hay sucursales registradas</p>
    </div>
    <?php endif; ?>
    
    <p class="text-xs text-slate-400 mt-4">
      <i class="ph-info mr-1"></i>
      Máximo 8 sucursales por tienda
    </p>
  </div>

  <!-- ===================== -->
  <!-- TAB: FOTOS -->
  <!-- ===================== -->
  <div id="tab-fotos" class="tienda-tab-content">
    <?php if (!empty($fotosVisibles)): ?>
    <div class="tienda-fotos-grid">
      <?php foreach ($fotosVisibles as $index => $foto): ?>
      <div class="tienda-foto-item" onclick="TiendaModule.openFotoLightbox(<?php echo $index; ?>)">
        <img 
          src="<?php echo htmlspecialchars($foto['url']); ?>" 
          alt="<?php echo htmlspecialchars($foto['titulo'] ?? 'Foto ' . ($index + 1)); ?>"
          loading="lazy"
        >
      </div>
      <?php endforeach; ?>
    </div>
    
    <?php if ($plan === 'basico' && count($fotos ?? []) > $maxFotos): ?>
    <div class="tienda-fotos-limite mt-4">
      <i class="ph-crown mr-1"></i>
      Tu plan permite <?php echo $maxFotos; ?> fotos. 
      <a href="planes.php" class="underline font-bold">Actualiza a Premium</a> para mostrar hasta 30.
    </div>
    <?php endif; ?>
    
    <?php else: ?>
    <div class="text-center py-8 text-slate-400">
      <i class="ph-images text-4xl mb-2"></i>
      <p>No hay fotos en la galería</p>
    </div>
    <?php endif; ?>
    
    <p class="text-xs text-slate-400 mt-4">
      <i class="ph-info mr-1"></i>
      Formatos permitidos: JPG, PNG, WEBP | Máximo 5MB por imagen
    </p>
  </div>

  <div id="tab-contacto" class="tienda-tab-content">
    <?php if ($plan === 'premium'): ?>
    <!-- ========================================= -->
    <!-- CONTACTO PREMIUM: Message Center Style -->
    <!-- ========================================= -->
    <div class="tienda-mensaje-layout shadow-sm">
      <!-- Sidebar de mensajes -->
      <aside class="mensaje-sidebar">
        <div class="mensaje-sidebar-header">
          <i class="ph ph-chat-circle-dots text-xl text-slate-400"></i>
          <h3>Mensajes</h3>
        </div>
        
        <div class="mensaje-list">
          <!-- Item tienda (Activo) -->
          <div class="mensaje-item active">
            <div class="mensaje-item-avatar">
              <img src="<?php echo htmlspecialchars($tienda['logo']); ?>" alt="Store Logo">
            </div>
            <div class="mensaje-item-info">
              <div class="mensaje-item-header">
                <span class="mensaje-item-nombre"><?php echo htmlspecialchars($tienda['nombre']); ?></span>
                <span class="mensaje-item-hora"><?php echo date('H:i'); ?></span>
              </div>
              <p class="mensaje-item-preview">[Nuevo Mensaje]</p>
            </div>
          </div>
        </div>
      </aside>

      <!-- Área de mensaje principal -->
      <div class="mensaje-main">
        <div class="mensaje-main-header">
          <h3><?php echo htmlspecialchars($tienda['nombre']); ?> Official Store</h3>
          <div class="header-actions">
            <button class="text-slate-400 hover:text-slate-600"><i class="ph ph-gear-six text-xl"></i></button>
          </div>
        </div>

        <div class="mensaje-form-container" id="container-form-premium">
          <form id="formContactoTiendaPremium" class="tienda-contacto-premium">
            
            <!-- Datos Personales (Grid) -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
              <div class="mensaje-field-group mb-0">
                <label class="mensaje-field-label">Nombre completo *</label>
                <input type="text" name="nombre" class="mensaje-input-text" placeholder="Tu nombre" required>
              </div>
              <div class="mensaje-field-group mb-0">
                <label class="mensaje-field-label">Correo electrónico *</label>
                <input type="email" name="correo" class="mensaje-input-text" placeholder="tucorreo@ejemplo.com" required>
              </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
              <div class="mensaje-field-group mb-0">
                <label class="mensaje-field-label">Teléfono de contacto</label>
                <input type="tel" name="telefono" class="mensaje-input-text" placeholder="+51 999 999 999">
              </div>
              <div class="mensaje-field-group mb-0">
                <label class="mensaje-field-label">Asunto del mensaje *</label>
                <select name="asunto" id="asunto-premium" class="mensaje-input-text cursor-pointer" required>
                  <option value="">Selecciona un asunto</option>
                  <option value="consulta">Consulta general</option>
                  <option value="cotizacion">Solicitar cotización</option>
                  <option value="reclamo">Reclamo</option>
                  <option value="sugerencia">Sugerencia</option>
                  <option value="otro">Otro</option>
                </select>
              </div>
            </div>

            <!-- Thumbnail Image / Attachment -->
            <div class="mensaje-field-group mb-4">
              <label class="mensaje-field-label">Adjuntar archivo o enlace de Drive (Máx. 2MB)</label>
              <div class="mensaje-file-upload">
                <label for="contact_file" class="mensaje-file-btn">Seleccionar archivo</label>
                <input type="file" id="contact_file" name="archivo" class="hidden" onchange="validarArchivo(this)">
                <span id="file-name-display" class="mensaje-file-name">Ningún archivo seleccionado</span>
              </div>
              <p id="file-limit-warning" class="text-[10px] text-slate-400 mt-1">
                Límite: **2 MB**. Si su archivo es más grande, por favor comprímalo o suba un enlace de Drive.
              </p>
            </div>

            <!-- Project Description (CKEditor) -->
            <div class="mensaje-field-group">
              <label class="mensaje-field-label">Descripción del requerimiento o mensaje *</label>
              
              <!-- Cargamos CKEditor solo si es Premium -->
              <script src="https://cdn.ckeditor.com/ckeditor5/36.0.1/classic/ckeditor.js"></script>
              
              <div id="editor-premium"></div>
              <input type="hidden" name="mensaje" id="mensaje-hidden">
            </div>

            <div class="flex justify-start">
              <button type="submit" class="mensaje-submit-btn" id="btn-resolver-premium">
                <i class="ph ph-paper-plane-tilt"></i>
                Enviar
              </button>
            </div>
          </form>
        </div>

        <!-- VISTA DE CHAT (Inicialmente oculta) -->
        <div id="container-chat-premium" class="hidden flex-1 flex flex-col h-full">
          <div class="mensaje-chat-window" id="chat-messages-container">
             <!-- Los mensajes se inyectarán aquí -->
          </div>
          
          <!-- Input falso de chat inferior -->
          <div class="p-4 border-t border-slate-100 bg-white">
            <div class="flex items-center gap-3 bg-slate-50 p-2 rounded-full border border-slate-200">
              <input type="text" placeholder="Escribe un mensaje de seguimiento..." class="flex-1 bg-transparent border-none outline-none px-4 text-sm" disabled>
              <button class="w-10 h-10 bg-slate-200 text-white rounded-full"><i class="ph ph-paper-plane-right"></i></button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <script>
      let premiumEditor;

      function validarArchivo(input) {
        const file = input.files[0];
        const display = document.getElementById('file-name-display');
        const warning = document.getElementById('file-limit-warning');
        
        if (file) {
          display.innerText = file.name;
          // Validar 2MB (2 * 1024 * 1024 bytes)
          if (file.size > 2 * 1024 * 1024) {
            warning.classList.remove('text-slate-400');
            warning.classList.add('text-rose-500', 'font-bold');
            warning.innerHTML = '<i class="ph ph-warning-circle"></i> ¡ARCHIVO MUY GRANDE! Por favor comprímalo a menos de 2MB o use Drive.';
            input.value = ""; // Limpiar input
          } else {
            warning.classList.add('text-slate-400');
            warning.classList.remove('text-rose-500', 'font-bold');
            warning.innerText = 'Límite: 2 MB. Archivo aceptado correctamente.';
          }
        }
      }

      document.addEventListener('DOMContentLoaded', () => {
        if (document.querySelector('#editor-premium')) {
          ClassicEditor
            .create(document.querySelector('#editor-premium'), {
              placeholder: 'Escribe aquí los detalles de tu consulta...',
              toolbar: [ 'heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'undo', 'redo' ]
            })
            .then(editor => {
              premiumEditor = editor;
              editor.model.document.on('change:data', () => {
                document.querySelector('#mensaje-hidden').value = editor.getData();
              });
            })
            .catch(error => { console.error(error); });
        }

        // Lógica de Envío Simulado
        const form = document.querySelector('#formContactoTiendaPremium');
        if (form) {
          form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const btn = document.querySelector('#btn-resolver-premium');
            const originalContent = btn.innerHTML;
            
            // 1. Simular carga
            btn.innerHTML = '<i class="ph ph-circle-notch animate-spin"></i> Enviando...';
            btn.disabled = true;

            setTimeout(() => {
              // 2. Transición a vista de chat
              document.querySelector('#container-form-premium').classList.add('hidden');
              document.querySelector('#container-chat-premium').classList.remove('hidden');
              
              // 3. Inyectar el mensaje del usuario
              const mensajeUser = premiumEditor.getData();
              const asunto = document.querySelector('#asunto-premium').value;
              const chatContainer = document.querySelector('#chat-messages-container');
              const hora = new Date().toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});

              chatContainer.innerHTML = `
                <div class="chat-bubble mine">
                  <strong>Asunto: ${asunto}</strong><br>
                  ${mensajeUser}
                  <span class="chat-time">${hora}</span>
                </div>
                <div id="typing-status" class="theirs opacity-70 italic text-xs mb-2">
                  <i class="ph ph-dots-three-outline animate-pulse text-lg"></i> ${document.querySelector('.mensaje-item-nombre').innerText} está escribiendo...
                </div>
              `;

              // Actualizar sidebar
              document.querySelector('.mensaje-item-preview').innerHTML = '<span class="text-sky-500 font-bold">Enviado ✔</span>';

              // 4. Respuesta automática simulada tras 3 segundos
              setTimeout(() => {
                const status = document.querySelector('#typing-status');
                if (status) status.remove();

                chatContainer.innerHTML += `
                  <div class="chat-bubble theirs shadow-sm">
                    ¡Gracias por contactarnos! Hemos recibido tu solicitud sobre <strong>"${asunto}"</strong> exitosamente.
                    <br><br>
                    Nuestro equipo revisará los detalles y te responderemos por este mismo medio o a tu correo en un plazo máximo de 24 horas.
                    <span class="chat-time">${new Date().toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})}</span>
                  </div>
                `;
                
                // Actualizar sidebar a "Leído"
                document.querySelector('.mensaje-item-preview').innerHTML = 'Respuesta recibida';
              }, 3000);

            }, 1500);
          });
        }
      });
    </script>

    <?php else: ?>
    <!-- ========================================= -->
    <!-- CONTACTO BÁSICO: Formulario Estándar -->
    <!-- ========================================= -->
    <form class="tienda-contacto-form" id="formContactoTienda">
      <!-- Campos básicos (todos los planes) -->
      <div class="tienda-form-group">
        <label class="tienda-form-label">Nombre *</label>
        <input type="text" name="nombre" class="tienda-form-input" required placeholder="Tu nombre completo">
      </div>
      
      <div class="tienda-form-group">
        <label class="tienda-form-label">Correo electrónico *</label>
        <input type="email" name="correo" class="tienda-form-input" required placeholder="tucorreo@ejemplo.com">
      </div>
      
      <div class="tienda-form-group">
        <label class="tienda-form-label">Mensaje *</label>
        <textarea name="mensaje" class="tienda-form-textarea" required placeholder="Escribe tu mensaje aquí..."></textarea>
      </div>
      
      <button type="submit" class="tienda-form-btn">
        <i class="ph-paper-plane-tilt"></i>
        Enviar mensaje
      </button>
    </form>
    <?php endif; ?>
  </div>

  <!-- ===================== -->
  <!-- TAB: OPINIONES -->
  <!-- ===================== -->
  <div id="tab-opiniones" class="tienda-tab-content">
    <?php if (!empty($opiniones)): ?>
    <div class="tienda-opiniones-list">
      <?php foreach ($opiniones as $opinion): ?>
      <div class="tienda-opinion-card">
        <div class="tienda-opinion-header">
          <div class="tienda-opinion-avatar">
            <?php echo strtoupper(substr($opinion['autor'], 0, 1)); ?>
          </div>
          <div class="tienda-opinion-autor">
            <span class="tienda-opinion-nombre"><?php echo htmlspecialchars($opinion['autor']); ?></span>
            <span class="tienda-opinion-fecha"><?php echo htmlspecialchars($opinion['fecha']); ?></span>
          </div>
          <div class="tienda-opinion-rating">
            <?php for ($i = 1; $i <= 5; $i++): ?>
            <i class="ph-star<?php echo $i <= $opinion['rating'] ? '-fill' : ''; ?>"></i>
            <?php endfor; ?>
          </div>
        </div>
        
        <p class="tienda-opinion-texto">
          <?php echo htmlspecialchars($opinion['comentario']); ?>
        </p>
        
        <div class="tienda-opinion-votos">
          <button 
            class="tienda-opinion-voto-btn" 
            data-opinion="<?php echo $opinion['id']; ?>" 
            data-voto="util"
            onclick="TiendaModule.votarOpinion(<?php echo $opinion['id']; ?>, 'util')"
          >
            <i class="ph-thumbs-up"></i>
            <span><?php echo $opinion['votos_util'] ?? 0; ?></span> Útil
          </button>
          <button 
            class="tienda-opinion-voto-btn"
            data-opinion="<?php echo $opinion['id']; ?>" 
            data-voto="no_util"
            onclick="TiendaModule.votarOpinion(<?php echo $opinion['id']; ?>, 'no_util')"
          >
            <i class="ph-thumbs-down"></i>
            <span><?php echo $opinion['votos_no_util'] ?? 0; ?></span>
          </button>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
    <?php else: ?>
    <div class="text-center py-8 text-slate-400">
      <i class="ph-chat-circle-text text-4xl mb-2"></i>
      <p>Aún no hay opiniones</p>
      <p class="text-sm mt-1">¡Sé el primero en dejar una reseña!</p>
    </div>
    <?php endif; ?>
    
    <!-- Formulario para nueva opinión -->
    <div class="mt-6 pt-6 border-t border-slate-200">
      <h4 class="font-bold text-slate-700 mb-4">
        <i class="ph-pencil-simple-line mr-1 text-sky-500"></i>
        Deja tu opinión
      </h4>
      <form id="formNuevaOpinion" class="space-y-4">
        <div class="flex items-center gap-2">
          <span class="text-sm text-slate-600">Tu calificación:</span>
          <div class="flex gap-1 rating-stars">
            <?php for ($i = 1; $i <= 5; $i++): ?>
            <button type="button" class="text-2xl text-slate-300 hover:text-amber-400 transition-colors" data-rating="<?php echo $i; ?>">
              <i class="ph-star"></i>
            </button>
            <?php endfor; ?>
          </div>
          <input type="hidden" name="rating" value="0">
        </div>
        <textarea name="comentario" class="tienda-form-textarea" placeholder="Comparte tu experiencia con esta tienda..." required></textarea>
        <button type="submit" class="tienda-form-btn">
          <i class="ph-paper-plane-tilt"></i>
          Publicar opinión
        </button>
      </form>
    </div>
  </div>

  <!-- ===================== -->
  <!-- TAB: ACERCA DE -->
  <!-- ===================== -->
  <div id="tab-terminos" class="tienda-tab-content">
    
    <!-- Descripción de la Empresa -->
    <div class="bg-white rounded-xl border border-slate-200 p-6 mb-6">
      <div class="flex items-start gap-4">
        <div class="w-14 h-14 bg-sky-50 rounded-xl flex items-center justify-center flex-shrink-0">
          <i class="ph-buildings text-sky-600 text-2xl"></i>
        </div>
        <div class="flex-1">
          <h3 class="text-xl font-bold text-slate-800 mb-3">
            <?php echo htmlspecialchars($tienda['nombre']); ?>
          </h3>
          <p class="text-slate-600 leading-relaxed mb-4">
            <?php echo htmlspecialchars($tienda['descripcion']); ?>
          </p>
          
          <!-- Categoría y Actividad en línea -->
          <div class="flex flex-wrap gap-3 text-sm">
            <div class="flex items-center gap-2 text-slate-600">
              <i class="ph-tag text-purple-500"></i>
              <span class="font-medium">Categoría:</span>
              <span class="font-bold text-slate-700"><?php echo htmlspecialchars($tienda['categoria'] ?? 'General'); ?></span>
            </div>
            <div class="flex items-center gap-2 text-slate-600">
              <i class="ph-briefcase text-emerald-500"></i>
              <span class="font-medium">Actividad:</span>
              <span class="font-bold text-slate-700"><?php echo htmlspecialchars($tienda['actividad'] ?? 'Comercio'); ?></span>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Información de Contacto -->
    <div class="bg-white rounded-xl border border-slate-200 p-6 mb-6">
      <h4 class="text-lg font-bold text-slate-800 mb-4 flex items-center gap-2">
        <i class="ph-address-book text-sky-600"></i>
        Información de Contacto
      </h4>
      
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <!-- Teléfono -->
        <?php if (!empty($tienda['telefono'])): ?>
        <div class="flex items-center gap-3 p-3 bg-slate-50 rounded-lg">
          <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
            <i class="ph-phone text-blue-600"></i>
          </div>
          <div>
            <p class="text-xs text-slate-500 font-medium">Teléfono</p>
            <a href="tel:<?php echo htmlspecialchars($tienda['telefono']); ?>" class="text-sm font-bold text-slate-700 hover:text-sky-600">
              <?php echo htmlspecialchars($tienda['telefono']); ?>
            </a>
          </div>
        </div>
        <?php endif; ?>
        
        <!-- Email -->
        <?php if (!empty($tienda['correo'])): ?>
        <div class="flex items-center gap-3 p-3 bg-slate-50 rounded-lg">
          <div class="w-10 h-10 bg-rose-100 rounded-lg flex items-center justify-center">
            <i class="ph-envelope text-rose-600"></i>
          </div>
          <div class="flex-1 min-w-0">
            <p class="text-xs text-slate-500 font-medium">Correo Electrónico</p>
            <a href="mailto:<?php echo htmlspecialchars($tienda['correo']); ?>" class="text-sm font-bold text-slate-700 hover:text-sky-600 truncate block">
              <?php echo htmlspecialchars($tienda['correo']); ?>
            </a>
          </div>
        </div>
        <?php endif; ?>
        
        <!-- Dirección -->
        <?php if (!empty($tienda['direccion'])): ?>
        <div class="flex items-start gap-3 p-3 bg-slate-50 rounded-lg md:col-span-2">
          <div class="w-10 h-10 bg-amber-100 rounded-lg flex items-center justify-center flex-shrink-0">
            <i class="ph-map-pin text-amber-600"></i>
          </div>
          <div>
            <p class="text-xs text-slate-500 font-medium">Dirección</p>
            <p class="text-sm font-bold text-slate-700">
              <?php echo htmlspecialchars($tienda['direccion']); ?>
            </p>
          </div>
        </div>
        <?php endif; ?>
      </div>
    </div>

    <!-- Redes Sociales -->
    <?php if (!empty($redes)): ?>
    <div class="bg-white rounded-xl border border-slate-200 p-6 mb-6">
      <h4 class="text-lg font-bold text-slate-800 mb-4 flex items-center gap-2">
        <i class="ph-share-network text-purple-600"></i>
        Redes Sociales
      </h4>
      
      <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
        <?php 
        $redesIconos = [
          'instagram' => ['icono' => 'ph-instagram-logo', 'color' => 'bg-gradient-to-br from-pink-500 to-purple-600', 'nombre' => 'Instagram'],
          'facebook' => ['icono' => 'ph-facebook-logo', 'color' => 'bg-blue-600', 'nombre' => 'Facebook'],
          'tiktok' => ['icono' => 'ph-tiktok-logo', 'color' => 'bg-slate-900', 'nombre' => 'TikTok'],
          'whatsapp' => ['icono' => 'ph-whatsapp-logo', 'color' => 'bg-green-500', 'nombre' => 'WhatsApp'],
          'youtube' => ['icono' => 'ph-youtube-logo', 'color' => 'bg-red-600', 'nombre' => 'YouTube'],
          'twitter' => ['icono' => 'ph-twitter-logo', 'color' => 'bg-sky-500', 'nombre' => 'Twitter'],
          'linkedin' => ['icono' => 'ph-linkedin-logo', 'color' => 'bg-blue-700', 'nombre' => 'LinkedIn'],
          'pinterest' => ['icono' => 'ph-pinterest-logo', 'color' => 'bg-red-600', 'nombre' => 'Pinterest'],
          'telegram' => ['icono' => 'ph-telegram-logo', 'color' => 'bg-sky-400', 'nombre' => 'Telegram'],
          'web' => ['icono' => 'ph-globe', 'color' => 'bg-slate-600', 'nombre' => 'Sitio Web'],
        ];
        
        foreach ($redes as $plataforma => $red): 
          if (!empty($red['url'])):
            $info = $redesIconos[$plataforma] ?? ['icono' => 'ph-link', 'color' => 'bg-slate-500', 'nombre' => ucfirst($plataforma)];
        ?>
        <a href="<?php echo htmlspecialchars($red['url']); ?>" target="_blank" 
           class="flex items-center gap-2 p-3 rounded-lg border border-slate-200 hover:border-slate-300 hover:shadow-sm transition-all group">
          <div class="w-8 h-8 <?php echo $info['color']; ?> rounded-lg flex items-center justify-center">
            <i class="<?php echo $info['icono']; ?> text-white"></i>
          </div>
          <span class="text-xs font-semibold text-slate-700 group-hover:text-sky-600"><?php echo $info['nombre']; ?></span>
        </a>
        <?php 
          endif;
        endforeach; 
        ?>
      </div>
    </div>
    <?php endif; ?>

    <!-- Rubros -->
    <?php if (!empty($tienda['rubros'])): ?>
    <div class="bg-white rounded-xl border border-slate-200 p-6 mb-6">
      <h4 class="text-lg font-bold text-slate-800 mb-4 flex items-center gap-2">
        <i class="ph-stack text-indigo-600"></i>
        Rubros
      </h4>
      
      <div class="flex flex-wrap gap-2">
        <?php foreach ($tienda['rubros'] as $rubro): ?>
        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-slate-100 border border-slate-200 rounded-lg text-sm font-medium text-slate-700">
          <i class="ph-check-circle text-sky-500 text-xs"></i>
          <?php echo htmlspecialchars($rubro); ?>
        </span>
        <?php endforeach; ?>
      </div>
    </div>
    <?php endif; ?>

    <!-- Políticas y Términos -->
    <?php if (!empty($terminos['envio']) || !empty($terminos['devolucion']) || !empty($terminos['privacidad'])): ?>
    <div class="bg-white rounded-xl border border-slate-200 p-6">
      <h4 class="text-lg font-bold text-slate-800 mb-4 flex items-center gap-2">
        <i class="ph-file-text text-slate-600"></i>
        Políticas y Términos
      </h4>
      
      <div class="space-y-3">
        <?php if (!empty($terminos['envio'])): ?>
        <details class="border border-slate-200 rounded-lg overflow-hidden">
          <summary class="flex items-center justify-between p-4 cursor-pointer hover:bg-slate-50 transition-colors">
            <div class="flex items-center gap-2">
              <i class="ph-truck text-sky-600"></i>
              <span class="font-semibold text-slate-700">Política de Envío</span>
            </div>
            <i class="ph-caret-down text-slate-400"></i>
          </summary>
          <div class="p-4 pt-0 text-sm text-slate-600 leading-relaxed">
            <?php echo nl2br(htmlspecialchars($terminos['envio'])); ?>
          </div>
        </details>
        <?php endif; ?>
        
        <?php if (!empty($terminos['devolucion'])): ?>
        <details class="border border-slate-200 rounded-lg overflow-hidden">
          <summary class="flex items-center justify-between p-4 cursor-pointer hover:bg-slate-50 transition-colors">
            <div class="flex items-center gap-2">
              <i class="ph-arrow-counter-clockwise text-amber-600"></i>
              <span class="font-semibold text-slate-700">Política de Devolución</span>
            </div>
            <i class="ph-caret-down text-slate-400"></i>
          </summary>
          <div class="p-4 pt-0 text-sm text-slate-600 leading-relaxed">
            <?php echo nl2br(htmlspecialchars($terminos['devolucion'])); ?>
          </div>
        </details>
        <?php endif; ?>
        
        <?php if (!empty($terminos['privacidad'])): ?>
        <details class="border border-slate-200 rounded-lg overflow-hidden">
          <summary class="flex items-center justify-between p-4 cursor-pointer hover:bg-slate-50 transition-colors">
            <div class="flex items-center gap-2">
              <i class="ph-shield-check text-emerald-600"></i>
              <span class="font-semibold text-slate-700">Política de Privacidad</span>
            </div>
            <i class="ph-caret-down text-slate-400"></i>
          </summary>
          <div class="p-4 pt-0 text-sm text-slate-600 leading-relaxed">
            <?php echo nl2br(htmlspecialchars($terminos['privacidad'])); ?>
          </div>
        </details>
        <?php endif; ?>
      </div>
      
      <!-- Documentos Descargables -->
      <?php if (!empty($terminos['archivos'])): ?>
      <div class="mt-4 pt-4 border-t border-slate-200">
        <p class="text-sm font-semibold text-slate-700 mb-3">
          <i class="ph-download-simple mr-1"></i>
          Documentos Descargables
        </p>
        <div class="flex flex-wrap gap-2">
          <?php foreach ($terminos['archivos'] as $archivo): ?>
          <a href="<?php echo htmlspecialchars($archivo['url']); ?>" target="_blank" 
             class="inline-flex items-center gap-2 px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm font-medium text-slate-700 hover:bg-sky-50 hover:border-sky-300 hover:text-sky-600 transition-all">
            <i class="ph-file-pdf text-red-500"></i>
            <?php echo htmlspecialchars($archivo['nombre']); ?>
            <i class="ph-download-simple text-xs"></i>
          </a>
          <?php endforeach; ?>
        </div>
      </div>
      <?php endif; ?>
    </div>
    <?php endif; ?>
    
  </div>



</div>

<script>
// Inicializar sistema de estrellas para nueva opinión
document.querySelectorAll('.rating-stars button').forEach(btn => {
  btn.addEventListener('click', function() {
    const rating = parseInt(this.dataset.rating);
    document.querySelector('input[name="rating"]').value = rating;
    
    document.querySelectorAll('.rating-stars button').forEach((star, index) => {
      const icon = star.querySelector('i');
      if (index < rating) {
        icon.className = 'ph-star-fill';
        star.classList.add('text-amber-400');
        star.classList.remove('text-slate-300');
      } else {
        icon.className = 'ph-star';
        star.classList.remove('text-amber-400');
        star.classList.add('text-slate-300');
      }
    });
  });
});
</script>
