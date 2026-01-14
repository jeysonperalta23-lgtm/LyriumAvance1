<?php
session_start();
session_destroy();
?>
<!DOCTYPE html>
<html lang="es">
  <head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Preguntas Frecuentes - Lyrium Biomarketplace</title>

  <link rel="stylesheet" href="utils/css/preguntasfrecuentes.css">
  <link rel="stylesheet" href="utils/css/ly-pagehead.css">

<?php include 'header.php'; ?>

  <!-- CONTENIDO: FAQ -->
  <main class="max-w-7xl mx-auto px-4 py-10 flex-1 space-y-16">

  <!-- ===================== FAQ / PREGUNTAS FRECUENTES ===================== -->
  <section class="space-y-10">

    <!-- Header pill animado -->
   <div class="text-center mb-10">
      <div class="ly-pagehead">
        <span class="ly-pagehead__icon">
          <img src="https://img.icons8.com/ios-filled/96/ffffff/help.png" alt="FAQ">
        </span>
        <span class="ly-pagehead__title">Preguntas frecuentes</span>
      </div>
    </div>

    <!-- Tabs visuales mejorados -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 max-w-6xl mx-auto">
      <!-- Tab 1: Para todos - Enlace a sección -->
      <a href="#faq-todos" class="tab-card shine-effect rounded-2xl border-2 border-gray-100 bg-white shadow-lg p-6 text-center cursor-pointer animate-in animate-delay-1 no-underline">
        <div class="tab-icon mx-auto w-16 h-16 rounded-full bg-gradient-to-br from-sky-400 to-sky-600 text-white flex items-center justify-center mb-4 shadow-lg transition-all duration-300 group-hover:scale-110 group-hover:rotate-6">
          <i class="ph-users-three text-[32px]"></i>
        </div>
        <div class="font-extrabold text-gray-800 text-xl mb-2">Para todos</div>
        <div class="text-sm text-gray-500">Información general</div>
      </a>

      <!-- Tab 2: Comprador - Enlace a sección -->
      <a href="#faq-comprador" class="tab-card shine-effect rounded-2xl border-2 border-gray-100 bg-white shadow-lg p-6 text-center cursor-pointer animate-in animate-delay-2 no-underline">
        <div class="tab-icon mx-auto w-16 h-16 rounded-full bg-gradient-to-br from-emerald-400 to-emerald-600 text-white flex items-center justify-center mb-4 shadow-lg transition-all duration-300 group-hover:scale-110 group-hover:rotate-6">
          <i class="ph-shopping-cart text-[32px]"></i>
        </div>
        <div class="font-extrabold text-gray-800 text-xl mb-2">Si soy comprador</div>
        <div class="text-sm text-gray-500">Compras, pagos y entregas</div>
      </a>

      <!-- Tab 3: Vendedor - Enlace a sección -->
      <a href="#faq-vendedor" class="tab-card shine-effect rounded-2xl border-2 border-gray-100 bg-white shadow-lg p-6 text-center cursor-pointer animate-in animate-delay-3 no-underline">
        <div class="tab-icon mx-auto w-16 h-16 rounded-full bg-gradient-to-br from-violet-400 to-violet-600 text-white flex items-center justify-center mb-4 shadow-lg transition-all duration-300 group-hover:scale-110 group-hover:rotate-6">
          <i class="ph-storefront text-[32px]"></i>
        </div>
        <div class="font-extrabold text-gray-800 text-xl mb-2">Si soy vendedor</div>
        <div class="text-sm text-gray-500">Registro y ventas</div>
      </a>
    </div>

    <!-- Acordeones modernos -->
    <div class="max-w-6xl mx-auto space-y-8">

      <!-- Grupo: Para todos -->
      <div id="faq-todos" class="faq-group rounded-3xl border-2 border-gray-100 bg-white shadow-lg overflow-hidden animate-in animate-delay-1">
        <div class="px-6 md:px-8 py-6 border-b border-gray-100 bg-gradient-to-r from-sky-50 to-cyan-50">
          <h3 class="text-xl md:text-2xl font-extrabold text-gray-800 flex items-center gap-3">
            <span class="inline-flex w-10 h-10 rounded-full bg-gradient-to-br from-sky-400 to-sky-600 text-white items-center justify-center text-xl shadow-lg">👥</span>
            Para todos
          </h3>
        </div>

        <div class="p-3 md:p-5">
          <!-- item -->
          <details class="faq-item group rounded-2xl transition-all duration-300">
            <summary class="cursor-pointer list-none flex items-start gap-4 px-4 md:px-6 py-5">
              <span class="faq-arrow mt-0.5 text-sky-600 font-bold text-xl">▸</span>
              <span class="faq-title font-bold text-gray-800 flex-1">¿Qué es LYRIUM BIOMARKETPLACE?</span>
              <span class="faq-plus text-gray-400">+</span>
            </summary>
            <div class="px-4 md:px-6 pb-6 pl-14 text-gray-700 leading-relaxed">
              Es un centro comercial online donde coexisten tiendas, productos y servicios saludables y muchos compradores.
            </div>
          </details>

          <div class="h-px bg-gray-100 my-2"></div>

          <details class="faq-item group rounded-2xl transition-all duration-300">
            <summary class="cursor-pointer list-none flex items-start gap-4 px-4 md:px-6 py-5">
              <span class="faq-arrow mt-0.5 text-gray-400 font-bold text-xl">▸</span>
              <span class="faq-title font-semibold text-gray-700 flex-1">¿Qué tipos de productos y servicios ofrece LYRIUM BIOMARKETPLACE?</span>
              <span class="faq-plus text-gray-400">+</span>
            </summary>
            <div class="px-4 md:px-6 pb-6 pl-14 text-gray-700 leading-relaxed">
              Ofrece productos y servicios saludables en distintas categorías (bienestar, belleza, digestión, servicios médicos, etc.).
            </div>
          </details>

          <div class="h-px bg-gray-100 my-2"></div>

          <details class="faq-item group rounded-2xl transition-all duration-300">
            <summary class="cursor-pointer list-none flex items-start gap-4 px-4 md:px-6 py-5">
              <span class="faq-arrow mt-0.5 text-gray-400 font-bold text-xl">▸</span>
              <span class="faq-title font-semibold text-gray-700 flex-1">¿Cómo puedo contactarme con LYRIUM BIOMARKETPLACE?</span>
              <span class="faq-plus text-gray-400">+</span>
            </summary>
            <div class="px-4 md:px-6 pb-6 pl-14 text-gray-700 leading-relaxed">
              Puedes contactarnos desde la sección "Contáctanos", redes sociales o WhatsApp (botón flotante).
            </div>
          </details>
        </div>
      </div>

      <!-- Grupo: Comprador -->
      <div id="faq-comprador" class="faq-group rounded-3xl border-2 border-gray-100 bg-white shadow-lg overflow-hidden animate-in animate-delay-2">
        <div class="px-6 md:px-8 py-6 border-b border-gray-100 bg-gradient-to-r from-emerald-50 to-teal-50">
          <h3 class="text-xl md:text-2xl font-extrabold text-gray-800 flex items-center gap-3">
            <span class="inline-flex w-10 h-10 rounded-full bg-gradient-to-br from-emerald-400 to-emerald-600 text-white items-center justify-center text-xl shadow-lg">🛒</span>
            Si soy comprador
          </h3>
        </div>

        <div class="p-3 md:p-5">
          <details class="faq-item group rounded-2xl transition-all duration-300">
            <summary class="cursor-pointer list-none flex items-start gap-4 px-4 md:px-6 py-5">
              <span class="faq-arrow mt-0.5 text-emerald-600 font-bold text-xl">▸</span>
              <span class="faq-title font-bold text-gray-800 flex-1">¿Cuál es el horario de atención de LYRIUM BIOMARKETPLACE?</span>
              <span class="faq-plus text-gray-400">+</span>
            </summary>
            <div class="px-4 md:px-6 pb-6 pl-14 text-gray-700 leading-relaxed">
              LYRIUM BIOMARKETPLACE atiende las 24 horas del día, los 7 días de la semana durante todo el año; sin embargo,
              las tiendas registradas poseen propios y distintos horarios de atención.
            </div>
          </details>

          <div class="h-px bg-gray-100 my-2"></div>

          <details class="faq-item group rounded-2xl transition-all duration-300">
            <summary class="cursor-pointer list-none flex items-start gap-4 px-4 md:px-6 py-5">
              <span class="faq-arrow mt-0.5 text-gray-400 font-bold text-xl">▸</span>
              <span class="faq-title font-semibold text-gray-700 flex-1">¿Cómo puedo comprar en LYRIUM BIOMARKETPLACE?</span>
              <span class="faq-plus text-gray-400">+</span>
            </summary>
            <div class="px-4 md:px-6 pb-6 pl-14 text-gray-700 leading-relaxed">
              Selecciona el producto/servicio, agrégalo al carrito y completa el proceso de compra con tus datos.
            </div>
          </details>

          <div class="h-px bg-gray-100 my-2"></div>

          <details class="faq-item group rounded-2xl transition-all duration-300">
            <summary class="cursor-pointer list-none flex items-start gap-4 px-4 md:px-6 py-5">
              <span class="faq-arrow mt-0.5 text-gray-400 font-bold text-xl">▸</span>
              <span class="faq-title font-semibold text-gray-700 flex-1">¿Cuáles son los métodos de pago de LYRIUM BIOMARKETPLACE?</span>
              <span class="faq-plus text-gray-400">+</span>
            </summary>
            <div class="px-4 md:px-6 pb-6 pl-14 text-gray-700 leading-relaxed">
              Los métodos dependen de la tienda. Usualmente se aceptan tarjetas y otros medios habilitados por la plataforma.
            </div>
          </details>

          <div class="h-px bg-gray-100 my-2"></div>

          <details class="faq-item group rounded-2xl transition-all duration-300">
            <summary class="cursor-pointer list-none flex items-start gap-4 px-4 md:px-6 py-5">
              <span class="faq-arrow mt-0.5 text-gray-400 font-bold text-xl">▸</span>
              <span class="faq-title font-semibold text-gray-700 flex-1">¿Cómo logro recibir mi(s) producto(s) luego de comprarlo(s) en LYRIUM BIOMARKETPLACE?</span>
              <span class="faq-plus text-gray-400">+</span>
            </summary>
            <div class="px-4 md:px-6 pb-6 pl-14 text-gray-700 leading-relaxed">
              Coordinas envío/entrega según la tienda y la zona; se mostrará la información disponible al finalizar la compra.
            </div>
          </details>

          <div class="h-px bg-gray-100 my-2"></div>

          <details class="faq-item group rounded-2xl transition-all duration-300">
            <summary class="cursor-pointer list-none flex items-start gap-4 px-4 md:px-6 py-5">
              <span class="faq-arrow mt-0.5 text-gray-400 font-bold text-xl">▸</span>
              <span class="faq-title font-semibold text-gray-700 flex-1">¿Cómo logro recibir mi(s) servicio(s) luego de comprarlo(s) en LYRIUM BIOMARKETPLACE?</span>
              <span class="faq-plus text-gray-400">+</span>
            </summary>
            <div class="px-4 md:px-6 pb-6 pl-14 text-gray-700 leading-relaxed">
              La tienda/proveedor coordina contigo el agendamiento o la atención del servicio.
            </div>
          </details>

          <div class="h-px bg-gray-100 my-2"></div>

          <details class="faq-item group rounded-2xl transition-all duration-300">
            <summary class="cursor-pointer list-none flex items-start gap-4 px-4 md:px-6 py-5">
              <span class="faq-arrow mt-0.5 text-gray-400 font-bold text-xl">▸</span>
              <span class="faq-title font-semibold text-gray-700 flex-1">¿Cómo realizo una devolución en LYRIUM BIOMARKETPLACE?</span>
              <span class="faq-plus text-gray-400">+</span>
            </summary>
            <div class="px-4 md:px-6 pb-6 pl-14 text-gray-700 leading-relaxed">
              Revisa la política de la tienda y solicita la devolución dentro del plazo indicado en términos y condiciones.
            </div>
          </details>

          <div class="h-px bg-gray-100 my-2"></div>

          <details class="faq-item group rounded-2xl transition-all duration-300">
            <summary class="cursor-pointer list-none flex items-start gap-4 px-4 md:px-6 py-5">
              <span class="faq-arrow mt-0.5 text-gray-400 font-bold text-xl">▸</span>
              <span class="faq-title font-semibold text-gray-700 flex-1">¿Cómo se realizó un reembolso en LYRIUM BIOMARKETPLACE?</span>
              <span class="faq-plus text-gray-400">+</span>
            </summary>
            <div class="px-4 md:px-6 pb-6 pl-14 text-gray-700 leading-relaxed">
              Los reembolsos se gestionan según el medio de pago y la aprobación de la tienda, respetando tiempos bancarios.
            </div>
          </details>
        </div>
      </div>

      <!-- Grupo: Vendedor -->
      <div id="faq-vendedor" class="faq-group rounded-3xl border-2 border-gray-100 bg-white shadow-lg overflow-hidden animate-in animate-delay-3">
        <div class="px-6 md:px-8 py-6 border-b border-gray-100 bg-gradient-to-r from-violet-50 to-purple-50">
          <h3 class="text-xl md:text-2xl font-extrabold text-gray-800 flex items-center gap-3">
            <span class="inline-flex w-10 h-10 rounded-full bg-gradient-to-br from-violet-400 to-violet-600 text-white items-center justify-center text-xl shadow-lg">🏪</span>
            Si soy vendedor
          </h3>
        </div>

        <div class="p-3 md:p-5">
          <details class="faq-item group rounded-2xl transition-all duration-300">
            <summary class="cursor-pointer list-none flex items-start gap-4 px-4 md:px-6 py-5">
              <span class="faq-arrow mt-0.5 text-violet-600 font-bold text-xl">▸</span>
              <span class="faq-title font-bold text-gray-800 flex-1">¿Qué tipos de tiendas pueden registrarse en LYRIUM BIOMARKETPLACE?</span>
              <span class="faq-plus text-gray-400">+</span>
            </summary>
            <div class="px-4 md:px-6 pb-6 pl-14 text-gray-700 leading-relaxed">
              Pueden registrarse vendedores con personería natural y jurídica cuyo rubro esté incluido en cualquiera de las
              categorías de productos y servicios que figuran en el menú de la página principal del sitio web.
            </div>
          </details>

          <div class="h-px bg-gray-100 my-2"></div>

          <details class="faq-item group rounded-2xl transition-all duration-300">
            <summary class="cursor-pointer list-none flex items-start gap-4 px-4 md:px-6 py-5">
              <span class="faq-arrow mt-0.5 text-gray-400 font-bold text-xl">▸</span>
              <span class="faq-title font-semibold text-gray-700 flex-1">¿Cómo puedo vender en LYRIUM BIOMARKETPLACE?</span>
              <span class="faq-plus text-gray-400">+</span>
            </summary>
            <div class="px-4 md:px-6 pb-6 pl-14 text-gray-700 leading-relaxed">
              Registra tu tienda, completa tus datos, publica productos/servicios y gestiona tus ventas desde tu panel.
            </div>
          </details>
        </div>
      </div>

    </div>
  </section>

  <!-- ===================== BENEFICIOS MODERNOS ===================== -->
  <section class="pt-4">
    <div class="max-w-6xl mx-auto">

      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8 px-4">
        <!-- TODO SALUD -->
        <div class="benefit-card group shine-effect rounded-[40px] border-2 border-gray-100 bg-white shadow-xl hover:shadow-2xl p-8 text-center cursor-pointer transition-all duration-500 hover:-translate-y-4 animate-in animate-delay-1">
          <div class="benefit-icon-wrap mx-auto w-20 h-20 rounded-[28px] bg-sky-100 flex items-center justify-center mb-8 shadow-inner group-hover:bg-sky-200 group-hover:rotate-6 transition-all duration-500">
            <i class="ph-heart-fill text-sky-600 text-4xl group-hover:scale-125 transition-transform duration-500"></i>
          </div>
          <h4 class="benefit-title font-black tracking-tight text-gray-900 text-lg uppercase">Todo salud</h4>
          <p class="mt-3 text-sky-500 font-bold text-sm leading-relaxed px-2">
            Tiendas saludables y ecoamigables para tu bienestar
          </p>
        </div>

        <!-- TIENDAS SELECTAS -->
        <div class="benefit-card group shine-effect rounded-[40px] border-2 border-gray-100 bg-white shadow-xl hover:shadow-2xl p-8 text-center cursor-pointer transition-all duration-500 hover:-translate-y-4 animate-in animate-delay-2">
          <div class="benefit-icon-wrap mx-auto w-20 h-20 rounded-[28px] bg-emerald-100 flex items-center justify-center mb-8 shadow-inner group-hover:bg-emerald-200 group-hover:rotate-6 transition-all duration-500">
            <i class="ph-storefront-fill text-emerald-600 text-4xl group-hover:scale-125 transition-transform duration-500"></i>
          </div>
          <h4 class="benefit-title font-black tracking-tight text-gray-900 text-lg uppercase">Tiendas selectas</h4>
          <p class="mt-3 text-sky-500 font-bold text-sm leading-relaxed px-2">
            Tiendas de calidad cuidadosamente seleccionadas para ti
          </p>
        </div>

        <!-- MEJORES PRECIOS -->
        <div class="benefit-card group shine-effect rounded-[40px] border-2 border-gray-100 bg-white shadow-xl hover:shadow-2xl p-8 text-center cursor-pointer transition-all duration-500 hover:-translate-y-4 animate-in animate-delay-3">
          <div class="benefit-icon-wrap mx-auto w-20 h-20 rounded-[28px] bg-amber-100 flex items-center justify-center mb-8 shadow-inner group-hover:bg-amber-200 group-hover:rotate-6 transition-all duration-500">
            <i class="ph-tag-fill text-amber-600 text-4xl group-hover:scale-125 transition-transform duration-500"></i>
          </div>
          <h4 class="benefit-title font-black tracking-tight text-gray-900 text-lg uppercase">Mejores precios</h4>
          <p class="mt-3 text-sky-500 font-bold text-sm leading-relaxed px-2">
            Mejores ofertas, promociones y descuentos
          </p>
        </div>

        <!-- SEGURIDAD -->
        <div class="benefit-card group shine-effect rounded-[40px] border-2 border-gray-100 bg-white shadow-xl hover:shadow-2xl p-8 text-center cursor-pointer transition-all duration-500 hover:-translate-y-4 animate-in animate-delay-4">
          <div class="benefit-icon-wrap mx-auto w-20 h-20 rounded-[28px] bg-violet-100 flex items-center justify-center mb-8 shadow-inner group-hover:bg-violet-200 group-hover:rotate-6 transition-all duration-500">
            <i class="ph-shield-check-fill text-violet-600 text-4xl group-hover:scale-125 transition-transform duration-500"></i>
          </div>
          <h4 class="benefit-title font-black tracking-tight text-gray-900 text-lg uppercase">Seguridad</h4>
          <p class="mt-3 text-sky-500 font-bold text-sm leading-relaxed px-2">
            Biomarketplace 100% seguro
          </p>
        </div>
      </div>

      <!-- Segunda fila centrada (3) -->
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8 mt-10 px-4 max-w-5xl mx-auto">
        <!-- RAPIDEZ -->
        <div class="benefit-card group shine-effect rounded-[40px] border-2 border-gray-100 bg-white shadow-xl hover:shadow-2xl p-8 text-center cursor-pointer transition-all duration-500 hover:-translate-y-4 animate-in animate-delay-1">
          <div class="benefit-icon-wrap mx-auto w-20 h-20 rounded-[28px] bg-lime-100 flex items-center justify-center mb-8 shadow-inner group-hover:bg-lime-200 group-hover:rotate-6 transition-all duration-500">
            <i class="ph-lightning-fill text-lime-600 text-4xl group-hover:scale-125 transition-transform duration-500"></i>
          </div>
          <h4 class="benefit-title font-black tracking-tight text-gray-900 text-lg uppercase">Rapidez</h4>
          <p class="mt-3 text-sky-500 font-bold text-sm leading-relaxed px-2">
            Mayor rapidez en tus compras
          </p>
        </div>

        <!-- MÁS TIEMPO -->
        <div class="benefit-card group shine-effect rounded-[40px] border-2 border-gray-100 bg-white shadow-xl hover:shadow-2xl p-8 text-center cursor-pointer transition-all duration-500 hover:-translate-y-4 animate-in animate-delay-2">
          <div class="benefit-icon-wrap mx-auto w-20 h-20 rounded-[28px] bg-slate-100 flex items-center justify-center mb-8 shadow-inner group-hover:bg-slate-200 group-hover:rotate-6 transition-all duration-500">
            <i class="ph-clock-fill text-slate-600 text-4xl group-hover:scale-125 transition-transform duration-500"></i>
          </div>
          <h4 class="benefit-title font-black tracking-tight text-gray-900 text-lg uppercase">Más tiempo</h4>
          <p class="mt-3 text-sky-500 font-bold text-sm leading-relaxed px-2">
            Ahorra tiempo en transportarte y en colas presenciales
          </p>
        </div>

        <!-- DONDE QUIERAS -->
        <div class="benefit-card group shine-effect rounded-[40px] border-2 border-gray-100 bg-white shadow-xl hover:shadow-2xl p-8 text-center cursor-pointer transition-all duration-500 hover:-translate-y-4 animate-in animate-delay-3">
          <div class="benefit-icon-wrap mx-auto w-20 h-20 rounded-[28px] bg-rose-100 flex items-center justify-center mb-8 shadow-inner group-hover:bg-rose-200 group-hover:rotate-6 transition-all duration-500">
            <i class="ph-globe-hemisphere-west-fill text-rose-600 text-4xl group-hover:scale-125 transition-transform duration-500"></i>
          </div>
          <h4 class="benefit-title font-black tracking-tight text-gray-900 text-lg uppercase">Donde quieras</h4>
          <p class="mt-3 text-sky-500 font-bold text-sm leading-relaxed px-2">
            Envíos a todo el Perú
          </p>
        </div>
      </div>

    </div>
  </section>

</main>

  <?php include 'footer.php'; ?>
  <script src="utils/js/preguntasfrecuentes.js"></script>