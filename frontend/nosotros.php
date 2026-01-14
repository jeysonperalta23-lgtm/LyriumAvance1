<?php
session_start();
session_destroy();
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Lyrium Biomarketplace</title>

  <!-- Tailwind (porque usas clases tailwind en el HTML) -->
  <script src="https://cdn.tailwindcss.com"></script>


</head>

<?php include 'header.php'; ?>
<link rel="stylesheet" href="utils/css/nosotros.css?v=<?php echo time(); ?>" />

<body>
  <main class="max-w-[80rem] mx-auto px-4 py-10 flex-1 space-y-16">

    <!-- HERO 1 (FULL WIDTH) -->
    <section
      id="heroLyrium"
      class="full-bleed hero-parallax relative h-[420px] md:h-[520px] flex items-center justify-center text-center text-white"
      style="background-image: url('img/nosotros/1.png');">
      <div class="absolute inset-0 bg-black/30"></div>
      <div class="relative z-10 px-4">
        <p class="text-[11px] md:text-xs tracking-[0.35em] uppercase mb-3">
          VIDA · SALUD · BIENESTAR
        </p>
        <h1 class="text-2xl md:text-4xl lg:text-5xl font-semibold leading-tight text-white">
          ¿QUÉ ES LYRIUM<br class="hidden md:block"> BIOMARKETPLACE?
        </h1>
      </div>
    </section>

    <!-- TEXTO + DOCTORA -->
    <section>
      <div class="max-w-[80rem] mx-auto hero-doctora">
        <div class="texto-hero">
          <span class="tag-hero">Vida · Salud · Bienestar</span>
          <h2>Tu salud es nuestra <br><span style="color:#3094b4;">prioridad absoluta</span></h2>
          <p>En <strong>LYRIUM BIOMARKETPLACE somos VIDA y SALUD.</strong> En un mundo cada vez más saturado por los productos dañinos y enfermedades, surge esta oportunidad para mejorar tu estilo de vida y el de tu familia.</p>
          <p>Nos dedicamos a ofrecerte productos 100% orgánicos y naturales, cuidadosamente seleccionados para promover tu bienestar físico y emocional. Nuestro compromiso es brindarte opciones saludables que respeten tanto tu cuerpo como el medio ambiente.</p>
          <p>Únete a nuestra comunidad y descubre cómo LYRIUM BIOMARKETPLACE puede transformar tu vida hacia un camino más saludable y equilibrado.</p>
        </div>
        <div class="doctora-wrapper">
          <img src="img/nosotros/2.png" alt="Doctora Lyrium" class="doctora-img">
        </div>
      </div>
    </section>

    <!-- NUESTROS VALORES -->
    <section class="relative h-[600px] rounded-3xl overflow-hidden shadow-2xl">
      <img
        src="https://lyriumbiomarketplace.com/wp-content/uploads/2025/05/miembros-familia-tiro-medio-posando-juntos-1-1024x683.jpg"
        alt="Familia multigeneracional feliz"
        class="w-full h-full object-cover zoom-img"
        style="width:80%;" />
      <div class="absolute top-1/2 right-8 -translate-y-1/2">
        <div class="overlay-card">
          <h2 class="text-4xl font-bold mb-7 text-white">NUESTROS VALORES</h2>
          <ul class="space-y-3 text-lg">
            <li>________________________</li>
            <li>• Integridad moral</li>
            <li>• Competitividad empresarial</li>
            <li>• Orientación al cliente</li>
          </ul>
        </div>
      </div>
    </section>

    <!-- NUESTRA RELACIÓN CONTIGO -->
    <section class="relative h-[600px] rounded-3xl overflow-hidden shadow-2xl">
      <img
        src="https://lyriumbiomarketplace.com/wp-content/uploads/2025/05/familia-de-tiro-completo-con-lindo-perro-al-aire-libre-1024x683.jpg"
        alt="Familia multigeneracional feliz"
        class="absolute top-0 right-0 h-full object-cover zoom-img"
        style="width:80%;" />
      <div class="absolute top-1/2 left-8 -translate-y-1/2">
        <div class="overlay-card">
          <h2 class="text-4xl font-bold mb-7 text-white">NUESTRA RELACIÓN CONTIGO</h2>
          <ul class="space-y-3 text-lg">
            <li>________________________</li>
            <li>Nuestra bio comunidad LYRIUMl</li>
            <li>BIOMARKETPLACE fomenta que sus</li>
            <li>tiendas vendedoras:</li>
          </ul>
        </div>
      </div>
    </section>

    <!-- HERO 2: ASI CUIDAMOS... (FULL WIDTH) -->

    <section class="timeline-section">
      <div class="max-w-[80rem] mx-auto px-6">
        <h1 class="section-title">Así Cuidamos de Ti y del Mundo</h1>
        <br><br>

        <div class="timeline">

          <div class="timeline-row">
            <div class="timeline-left timeline-content">
              <div class="timeline-card">
                <h3>¿Por Qué Elegirnos?</h3>
                <p>Lleven tus productos a tu hogar con calidad y excelencia en el servicio brindándote una diferencia hacia tu persona.</p>
              </div>
            </div>
            <div class="timeline-mid">
              <div class="timeline-icon">
                <svg viewBox="0 0 576 512">
                  <path d="M259.3 17.8L194 150.2 47.9 171.5c-26.2 3.8-36.7 36.1-17.7 54.6l105.7 103-25 145.5c-4.5 26.3 23.2 46 46.4 33.7L288 439.6l130.7 68.7c23.2 12.2 50.9-7.4 46.4-33.7l-25-145.5 105.7-103c19-18.5 8.5-50.8-17.7-54.6L382 150.2 316.7 17.8c-11.7-23.6-45.6-23.9-57.4 0z" />
                </svg>
              </div>
            </div>
            <div class="timeline-right"></div>
          </div>

          <div class="timeline-row">
            <div class="timeline-left"></div>
            <div class="timeline-mid">
              <div class="timeline-icon">
                <svg viewBox="0 0 512 512">
                  <path d="M192 208c0-17.67-14.33-32-32-32h-16c-35.35 0-64 28.65-64 64v48c0 35.35 28.65 64 64 64h16c17.67 0 32-14.33 32-32V208zm176 144c35.35 0 64-28.65 64-64v-48c0-35.35-28.65-64-64-64h-16c-17.67 0-32 14.33-32 32v112c0 17.67 14.33 32 32 32h16zM256 0C113.18 0 4.58 118.83 0 256v16c0 8.84 7.16 16 16 16h16c8.84 0 16-7.16 16-16v-16c0-114.69 93.31-208 208-208s208 93.31 208 208h-.12c.08 2.43.12 165.72.12 165.72 0 23.35-18.93 42.28-42.28 42.28H320c0-26.51-21.49-48-48-48h-32c-26.51 0-48 21.49-48 48s21.49 48 48 48h181.72c49.86 0 90.28-40.42 90.28-90.28V256C507.42 118.83 398.82 0 256 0z" />
                </svg>
              </div>
            </div>
            <div class="timeline-right timeline-content">
              <div class="timeline-card">
                <h3>Tu Tranquilidad es Importante</h3>
                <p>Y siempre reciban tus consultas con mucha alegría, solucionando todas tus dudas.</p>
              </div>
            </div>
          </div>

          <div class="timeline-row">
            <div class="timeline-left timeline-content">
              <div class="timeline-card">
                <h3>Reclamos y Devoluciones</h3>
                <p>Respeten siempre a tu persona en todo momento brindándote opción al reclamo y/o devolución.</p>
              </div>
            </div>
            <div class="timeline-mid">
              <div class="timeline-icon">
                <svg viewBox="0 0 640 512">
                  <path d="M256 336h-.02c0-16.18 1.34-8.73-85.05-181.51-17.65-35.29-68.19-35.36-85.87 0C-2.06 328.75.02 320.33.02 336H0c0 44.18 57.31 80 128 80s128-35.82 128-80zM128 176l72 144H56l72-144zm511.98 160c0-16.18 1.34-8.73-85.05-181.51-17.65-35.29-68.19-35.36-85.87 0-87.12 174.26-85.04 165.84-85.04 181.51H384c0 44.18 57.31 80 128 80s128-35.82 128-80h-.02zM440 320l72-144 72 144H440zm88 128H352V153.25c23.51-10.29 41.16-31.48 46.39-57.25H528c8.84 0 16-7.16 16-16V48c0-8.84-7.16-16-16-16H383.64C369.04 12.68 346.09 0 320 0s-49.04 12.68-63.64 32H112c-8.84 0-16 7.16-16 16v32c0 8.84 7.16 16 16 16h129.61c5.23 25.76 22.87 46.96 46.39 57.25V448H112c-8.84 0-16 7.16-16 16v32c0 8.84 7.16 16 16 16h416c8.84 0 16-7.16 16-16v-32c0-8.84-7.16-16-16-16z" />
                </svg>
              </div>
            </div>
            <div class="timeline-right"></div>
          </div>

          <div class="timeline-row">
            <div class="timeline-left"></div>
            <div class="timeline-mid">
              <div class="timeline-icon">
                <svg viewBox="0 0 640 512">
                  <path d="M192 160h32V32h-32c-35.35 0-64 28.65-64 64s28.65 64 64 64zM0 416c0 35.35 28.65 64 64 64h32V352H64c-35.35 0-64 28.65-64 64zm337.46-128c-34.91 0-76.16 13.12-104.73 32-24.79 16.38-44.52 32-104.73 32v128l57.53 15.97c26.21 7.28 53.01 13.12 80.31 15.05 32.69 2.31 65.6.67 97.58-6.2C472.9 481.3 512 429.22 512 384c0-64-84.18-96-174.54-96zM491.42 7.19C459.44.32 426.53-1.33 393.84.99c-27.3 1.93-54.1 7.77-80.31 15.04L256 32v128c60.2 0 79.94 15.62 104.73 32 28.57 18.88 69.82 32 104.73 32C555.82 224 640 192 640 128c0-45.22-39.1-97.3-148.58-120.81z" />
                </svg>
              </div>
            </div>
            <div class="timeline-right timeline-content">
              <div class="timeline-card">
                <h3>Comprendemos tus Necesidades</h3>
                <p>Intenten siempre ponerse en tus zapatos como comprador.</p>
              </div>
            </div>
          </div>

          <div class="timeline-row">
            <div class="timeline-left timeline-content">
              <div class="timeline-card">
                <h3>Transparencia y Confianza</h3>
                <p>Utilicen siempre la honradez en tu atención y servicio.</p>
              </div>
            </div>
            <div class="timeline-mid">
              <div class="timeline-icon">
                <svg viewBox="0 0 640 512">
                  <path d="M434.7 64h-85.9c-8 0-15.7 3-21.6 8.4l-98.3 90c-.1.1-.2.3-.3.4-16.6 15.6-16.3 40.5-2.1 56 12.7 13.9 39.4 17.6 56.1 2.7.1-.1.3-.1.4-.2l79.9-73.2c6.5-5.9 16.7-5.5 22.6 1 6 6.5 5.5 16.6-1 22.6l-26.1 23.9L504 313.8c2.9 2.4 5.5 5 7.9 7.7V128l-54.6-54.6c-5.9-6-14.1-9.4-22.6-9.4zM544 128.2v223.9c0 17.7 14.3 32 32 32h64V128.2h-96zm48 223.9c-8.8 0-16-7.2-16-16s7.2-16 16-16 16 7.2 16 16-7.2 16-16 16zM0 384h64c17.7 0 32-14.3 32-32V128.2H0V384zm48-63.9c8.8 0 16 7.2 16 16s-7.2 16-16 16-16-7.2-16-16c0-8.9 7.2-16 16-16zm435.9 18.6L334.6 217.5l-30 27.5c-29.7 27.1-75.2 24.5-101.7-4.4-26.9-29.4-24.8-74.9 4.4-101.7L289.1 64h-83.8c-8.5 0-16.6 3.4-22.6 9.4L128 128v223.9h18.3l90.5 81.9c27.4 22.3 67.7 18.1 90-9.3l.2-.2 17.9 15.5c15.9 13 39.4 10.5 52.3-5.4l31.4-38.6 5.4 4.4c13.7 11.1 33.9 9.1 45-4.7l9.5-11.7c11.2-13.8 9.1-33.9-4.6-45.1z" />
                </svg>
              </div>
            </div>
            <div class="timeline-right"></div>
          </div>

          <div class="timeline-row">
            <div class="timeline-left"></div>
            <div class="timeline-mid">
              <div class="timeline-icon">
                <svg viewBox="0 0 640 512">
                  <path d="M624 352h-16V243.9c0-12.7-5.1-24.9-14.1-33.9L494 110.1c-9-9-21.2-14.1-33.9-14.1H416V48c0-26.5-21.5-48-48-48H48C21.5 0 0 21.5 0 48v320c0 26.5 21.5 48 48 48h16c0 53 43 96 96 96s96-43 96-96h128c0 53 43 96 96 96s96-43 96-96h48c8.8 0 16-7.2 16-16v-32c0-8.8-7.2-16-16-16zM160 464c-26.5 0-48-21.5-48-48s21.5-48 48-48 48 21.5 48 48-21.5 48-48 48zm320 0c-26.5 0-48-21.5-48-48s21.5-48 48-48 48 21.5 48 48-21.5 48-48 48zm80-208H416V144h44.1l99.9 99.9V256z" />
                </svg>
              </div>
            </div>
            <div class="timeline-right timeline-content">
              <div class="timeline-card">
                <h3>Tu Tiempo, Nuestra Prioridad</h3>
                <p>Manden los productos a tu hogar en el tiempo prometido logrando que quedes totalmente satisfecho.</p>
              </div>
            </div>
          </div>

        </div>
      </div>
    </section>



    <!-- HERO 3 (FULL WIDTH) -->
    <section
      class="full-bleed hero-parallax relative h-[420px] md:h-[520px] flex items-center justify-center text-center text-white"
      style="background-image: url(img/nosotros/Mucho.jpg);"
      <div class="absolute inset-0 bg-black/30"></div>
      <div class="relative z-10 px-4">
        <p class="text-2xl md:text-4xl lg:text-5xl font-semibold leading-tight text-white">
          MUCHO MÁS QUE UN
        </p>
        <h1 class="text-2xl md:text-4xl lg:text-5xl font-semibold leading-tight text-white">
          MARKETPLACE: UN ESTILO DE VIDA
        </h1>
      </div>
    </section>

    <!-- ICONOS -->
    <section class="py-20 bg-white">
      <div class="max-w-[80rem] mx-auto px-4">
        <div class="flex flex-col md:flex-row items-center justify-center gap-12 lg:gap-20">

          <div class="relative w-48 h-48 icono-hover">
            <img src="img/nosotros/organic-1024x1024.avif" alt="100% Orgánico"
              class="w-full h-full object-contain drop-shadow-2xl" />
            <div class="texto-hover">
              <h3>Orgánico</h3>
              <p>Libre de químicos y pesticidas, cultivado respetando la naturaleza y tu salud.</p>
            </div>
          </div>

          <div class="relative w-40 h-40 icono-hover">
            <img src="img/nosotros/natural-1024x1024.avif" alt="Natural"
              class="w-full h-full object-contain drop-shadow-2xl" />
            <div class="texto-hover">
              <h3>Natural</h3>
              <p>Ingredientes seleccionados que conservan su pureza y propiedades originales.</p>
            </div>
          </div>

          <div class="relative w-40 h-40 icono-hover">
            <img src="img/nosotros/Bienestar.avif" alt="Bienestar"
              class="w-full h-full object-contain drop-shadow-2xl" />
            <div class="texto-hover">
              <h3>Bienestar</h3>
              <p>Apoya tu salud física y emocional de manera equilibrada.</p>
            </div>
          </div>

          <div class="relative w-40 h-40 icono-hover">
            <img src="img/nosotros/Saludable.avif" alt="Saludable"
              class="w-full h-full object-contain drop-shadow-2xl" />
            <div class="texto-hover">
              <h3>Saludable</h3>
              <p>Promueve hábitos sanos y un estilo de vida activo.</p>
            </div>
          </div>

        </div>
      </div>
    </section>

  </main>

  <?php include 'footer.php'; ?>
</body>

</html>