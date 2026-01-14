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

  <style>
    /* ===== FULL WIDTH SOLO PARA HERO SECTIONS ===== */
    html, body { width: 100%; overflow-x: hidden; }

    .full-bleed{
      width: 100vw;
      max-width: 100vw;
      margin-left: calc(50% - 50vw);
      margin-right: calc(50% - 50vw);
      position: relative;
    }

    /* Parallax en desktop (en móviles se desactiva para evitar bugs) */
    .hero-parallax{
      background-size: cover;
      background-position: center center;
      background-repeat: no-repeat;
    }
    @media (min-width: 1024px){
      .hero-parallax{ background-attachment: fixed; }
    }
    @media (max-width: 1023px){
      .hero-parallax{ background-attachment: scroll; }
    }

    /* ===== EFECTO DOCTORA ===== */
    .hero-doctora{
      display: grid;
      grid-template-columns: 1.15fr 0.85fr;
      gap: 2.5rem;
      align-items: center;
    }
    @media (max-width: 768px){
      .hero-doctora{ grid-template-columns: 1fr; }
    }
    .tag-hero{
      display: inline-block;
      font-size: 12px;
      letter-spacing: .35em;
      text-transform: uppercase;
      color: #0ea5e9;
      margin-bottom: .75rem;
    }
    .texto-hero p{
      color: #334155;
      line-height: 1.7;
      font-size: 15px;
      max-width: 720px;
    }
    .doctora-wrapper{
      position: relative;
      display: inline-block;
      border-radius: 24px;
      overflow: hidden;
      transform: translateZ(0);
      box-shadow: 0 18px 45px rgba(15, 23, 42, .18);
    }
    .doctora-wrapper::after{
      content:"";
      position:absolute;
      inset:0;
      background: radial-gradient(circle at 30% 20%, rgba(255,255,255,.35), rgba(0,0,0,0) 55%);
      opacity: .55;
      pointer-events: none;
      transition: opacity .5s ease;
    }
    .doctora-img{
      width: 100%;
      height: auto;
      display: block;
      transition: transform .75s ease, filter .75s ease;
      will-change: transform;
      transform-origin: center;
    }
    .doctora-wrapper:hover .doctora-img{
      transform: scale(1.06);
      filter: saturate(1.05) contrast(1.05);
    }
    .doctora-wrapper:hover::after{ opacity: .75; }

    /* ===== ESTILOS EXISTENTES ===== */
    .overlay-card {
      background: rgba(105, 149, 221, 0.85);
      color: white;
      padding: 4rem;
      border-radius: 1.5rem;
      max-width: 450px;
      box-shadow: 0 10px 30px rgba(0,0,0,0.2);
    }

    .zoom-img { transition: transform 0.6s ease; }
    .zoom-img:hover { transform: scale(1.1); }

    .icono-hover { 
      position: relative;
      width: 120px;          /* unifica el tamaño para que queden más equilibrados */
      height: 120px;
      border-radius: 50%;    /* hace el contenedor circular */
      overflow: hidden;      /* recorta la imagen al círculo */
      background: #f0fdfa;   /* fondo suave opcional, ej. verde muy claro */
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.12);
      transition: all 0.4s ease;
     }
    .icono-hover .texto-hover {
      transform: translateY(30px);
      position: absolute;
      inset: 0;
      background: rgba(0, 0, 0, 0.65);
      color: #fff;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      text-align: center;
      padding: 1rem;
      opacity: 0;
      transition: all 1.45s ease;
    }
    .icono-hover:hover .texto-hover { 
      opacity: 1; 
      transform: translateY(0);
    }
    .texto-hover h3 { 
      font-size: 1.1rem; 
      font-weight: bold; 
      margin-bottom: 0.4rem; }
    .texto-hover p { 
      font-size: 0.9rem; 
      line-height: 1.3; }
/* ============================
   TIMELINE (igual a tu imagen)
   - 2 líneas centrales
   - 6 iconos (2 por fila)
   - cards izquierda / derecha
============================ */

.timeline-section{
  padding: 4.5rem 0;
  background: #fff;
}

.timeline{
  --midW: 120px;      /* ancho del carril central */
  --lineGap: 16px;    /* separación entre las 2 líneas */
  position: relative;
  max-width: 1250px;
  margin: 0 auto;
}

/* 2 líneas del centro */
.timeline::before,
.timeline::after{
  content:"";
  position:absolute;
  top: 0;
  bottom: 0;
  width: 4px;
  border-radius: 999px;
  z-index: 1;
}

.timeline::before{
  left: calc(50% - var(--lineGap));
  transform: translateX(-50%);
  background: #38bdf8; /* celeste */
  opacity: 1;
}

.timeline::after{
  left: calc(50% + var(--lineGap));
  transform: translateX(-50%);
  background: rgba(167,139,250,.45); /* lila */
  opacity: .75;
}

/* filas */
.timeline-row{
  display: grid;
  grid-template-columns: 1fr var(--midW) 1fr;
  align-items: start;
  margin: 0 0 72px 0;
}
.timeline-row:last-child{ margin-bottom: 0; }

.timeline-left{
  display: flex;
  justify-content: flex-end;
  padding-right: 28px;
}

.timeline-right{
  display: flex;
  justify-content: flex-start;
  padding-left: 28px;
}

/* carril central */
.timeline-mid{
  position: relative;
  min-height: 50px;
}

/* iconos (uno por cada línea) */
.timeline-icon{
  position: absolute;
  top: 28px;
  width: 28px;
  height: 28px;
  border-radius: 8px;
  display: grid;
  place-items: center;
  font-size: 16px;
  font-weight: 800;
  color: #fff;
  box-shadow: 0 10px 24px rgba(15,23,42,.22);
  z-index: 3;
}

/* pegados a cada línea */
.ticon-left{
  left: calc(50% - var(--lineGap));
  transform: translateX(-50%);
}
.ticon-right{
  left: calc(50% + var(--lineGap));
  transform: translateX(-50%);
}

/* colores de iconos */
.timeline-icon.icon-1{ background:#0ea5e9; } /* ★ */
.timeline-icon.icon-2{ background:#60a5fa; } /* 🎧 */
.timeline-icon.icon-3{ background:#22c55e; } /* ♻ */
.timeline-icon.icon-4{ background:#16a34a; } /* 🤝 */
.timeline-icon.icon-5{ background:#ef4444; } /* 📩 */
.timeline-icon.icon-6{ background:#a855f7; } /* 🚚 */

/* cards */
.timeline-card{
  width: 520px;
  max-width: 100%;
  padding: 2.2rem 2.8rem;
  border-radius: 0;
  box-shadow: 0 18px 45px rgba(15,23,42,.14);
  position: relative;
  transition: transform .35s ease, box-shadow .35s ease;
  min-height: 140px;                /* evita que se achiquen demasiado */
  display: flex;
  flex-direction: column;
  justify-content: center;
}

.timeline-card:hover{
  transform: translateY(-6px);
  box-shadow: 0 26px 70px rgba(15,23,42,.20);
}

.timeline-left .timeline-card{ text-align: right; }
.timeline-right .timeline-card{ text-align: left; }

/* badge */
.timeline-badge{
  position: absolute;
  top: -12px;
  right: 18px;
  padding: .35rem .7rem;
  border-radius: 999px;
  font-size: 12px;
  font-weight: 800;
  letter-spacing: .14em;
  color: rgba(255,255,255,.95);
  background: rgba(255,255,255,.22);
  backdrop-filter: blur(8px);
}

/* tipografía */
.timeline-card h3{
  margin: 0 0 .6rem 0;
  font-size: 1.45rem;
  font-weight: 800;
  line-height: 1.2;
  color: #fff;
}
.timeline-card p{
  margin: 0;
  font-size: .95rem;
  line-height: 1.65;
  color: rgba(255,255,255,.95);
}

/* colores cards */
.card-blue{ background:#2f93b5; color:#fff; }
.card-blue-light{ background:#7fcdf1; color:#fff; }
.card-green{ background:#46c98a; color:#fff; }
.card-green-dark{ background:#78d7b0; color:#fff; }
.card-yellow-dark{ background:#95b90a; color:#fff; }
.card-yellow{ background:#cfe96b; color:#fff; }

/* MOBILE */
@media (max-width: 768px){
  .timeline{ --midW: 52px; --lineGap: 8px; }

  .timeline::before{ left: 26px; transform:none; }
  .timeline::after{ left: 36px; transform:none; }

  .timeline-row{
    grid-template-columns: var(--midW) 1fr;
    margin-bottom: 26px;
  }

  .timeline-left, .timeline-right{
    grid-column: 2;
    justify-content: flex-start;
    padding: 0;
  }

  .timeline-mid{
    grid-column: 1;
  }

  .timeline-icon{ top: 22px; }
  .ticon-left{ left: 26px; transform:none; }
  .ticon-right{ left: 36px; transform:none; }

  .timeline-left .timeline-card,
  .timeline-right .timeline-card{
    text-align: left;
    width: 100%;
  }
}

  </style>
</head>

<?php include 'header.php'; ?>

<body>
  <main class="max-w-[80rem] mx-auto px-4 py-10 flex-1 space-y-16">

    <!-- HERO 1 (FULL WIDTH) -->
    <section
      id="heroLyrium"
      class="full-bleed hero-parallax relative h-[420px] md:h-[520px] flex items-center justify-center text-center text-white"
      style="background-image: url('img/Banner/1.png');"
    >
      <div class="absolute inset-0 bg-black/30"></div>
      <div class="relative z-10 px-4">
        <p class="text-[11px] md:text-xs tracking-[0.35em] uppercase mb-3">
          VIDA · SALUD · BIENESTAR
        </p>
        <h1 class="text-2xl md:text-4xl lg:text-5xl font-semibold leading-tight">
          ¿QUÉ ES LYRIUM<br class="hidden md:block"> BIOMARKETPLACE?
        </h1>
      </div>
    </section>

    <!-- TEXTO + DOCTORA -->
    <section class="w-full py-10 md:py-16 bg-white">
      <div class="max-w-[80rem] mx-auto px-4 hero-doctora">
        <div class="order-2 md:order-1 flex items-center">
          <div class="texto-hero">
            <span class="tag-hero">Vida · Salud · Bienestar</span>
            <p>
              En <strong>LYRIUM BIOMARKETPLACE somos VIDA y SALUD.</strong> En un mundo cada vez
              más saturado por los productos dañinos y enfermedades, surge esta
              oportunidad para mejorar tu estilo de vida y el de tu familia, en el que
              podrás encontrar todo lo relacionado a la salud en un solo lugar.
            </p>
          </div>
        </div>

        <div class="order-1 md:order-2 flex justify-end">
          <div class="doctora-wrapper">
            <img src="img/Banner/2.png" alt="Doctora Lyrium" class="doctora-img" />
          </div>
        </div>
      </div>
    </section>

    <!-- NUESTROS VALORES -->
    <section class="relative h-[600px] rounded-3xl overflow-hidden shadow-2xl">
      <img
        src="https://lyriumbiomarketplace.com/wp-content/uploads/2025/05/miembros-familia-tiro-medio-posando-juntos-1-1024x683.jpg"
        alt="Familia multigeneracional feliz"
        class="w-full h-full object-cover zoom-img"
        style="width:80%;"
      />
      <div class="absolute top-1/2 right-8 -translate-y-1/2">
        <div class="overlay-card">
          <h2 class="text-4xl font-bold mb-7">NUESTROS VALORES</h2>
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
        style="width:80%;"
      />
      <div class="absolute top-1/2 left-8 -translate-y-1/2">
        <div class="overlay-card">
          <h2 class="text-4xl font-bold mb-7">NUESTRA RELACIÓN CONTIGO</h2>
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
    <section
      class="full-bleed hero-parallax relative h-[420px] md:h-[520px] flex items-center justify-center text-center text-white"
      style="background-image: url('img/nosotros/hermoso jardin de fresas y amanecer en doi ang khang chiang mai tailandia scaled.jpg.jpeg');"
    >
      <div class="absolute inset-0 bg-black/30"></div>
      <div class="relative z-10 px-4">
        <h1 class="text-2xl md:text-4xl lg:text-5xl font-semibold leading-tight">
          ASI CUIDAMOS DE TI<br class="hidden md:block"> Y DEL MUNDO
        </h1>
      </div>
    </section>

<section class="timeline-section">
  <div class="timeline">

    <!-- FILA 1 -->
    <div class="timeline-row">
      <div class="timeline-left">
        <div class="timeline-card card-blue">
          <span class="timeline-badge">01</span>
          <h3>¿Por Qué Elegirnos?</h3>
          <p>
            Llevamos tus productos a tu hogar con calidad y excelencia en el servicio,
            brindándote una diferencia hacia tu persona en cada momento de tu compra.
          </p>
        </div>
      </div>

      <div class="timeline-mid">
        <span class="timeline-icon icon-1 ticon-left">★</span>
        <span class="timeline-icon icon-2 ticon-right">🎧</span>
      </div>

      <div class="timeline-right">
        <div class="timeline-card card-blue-light">
          <span class="timeline-badge">02</span>
          <h3>Tu Tranquilidad es Importante</h3>
          <p>
            Y siempre reciban tus consultas con mucha alegría, solucionando todas tus dudas.
          </p>
        </div>
      </div>
    </div>

    <!-- FILA 2 -->
    <div class="timeline-row">
      <div class="timeline-left">
        <div class="timeline-card card-green">
          <span class="timeline-badge">03</span>
          <h3>Reclamos y Devoluciones Justas</h3>
          <p>
            Respeten siempre a tu persona en todo momento brindándote opción al reclamo
            y/o devolución de tu producto(s) y/o servicio(s).
          </p>
        </div>
      </div>

      <div class="timeline-mid">
        <span class="timeline-icon icon-3 ticon-left">♻</span>
        <span class="timeline-icon icon-4 ticon-right">🤝</span>
      </div>

      <div class="timeline-right">
        <div class="timeline-card card-green-dark">
          <span class="timeline-badge">04</span>
          <h3>Comprendemos tus Necesidades</h3>
          <p>Intenten siempre ponerse en tus zapatos como comprador.</p>
        </div>
      </div>
    </div>

    <!-- FILA 3 -->
    <div class="timeline-row">
      <div class="timeline-left">
        <div class="timeline-card card-yellow-dark">
          <span class="timeline-badge">05</span>
          <h3>Transparencia y Confianza</h3>
          <p>Utilicen siempre la honradez en tu atención y servicio.</p>
        </div>
      </div>

      <div class="timeline-mid">
        <span class="timeline-icon icon-5 ticon-left">📩</span>
        <span class="timeline-icon icon-6 ticon-right">🚚</span>
      </div>

      <div class="timeline-right">
        <div class="timeline-card card-yellow">
          <span class="timeline-badge">06</span>
          <h3>Tu Tiempo, Nuestra Prioridad</h3>
          <p>
            Manden los productos a tu hogar en el tiempo prometido logrando que quedes
            totalmente satisfecho con la atención que recibiste.
          </p>
        </div>
      </div>
    </div>

  </div>
</section>


    <!-- HERO 3 (FULL WIDTH) -->
    <section
      class="full-bleed hero-parallax relative h-[420px] md:h-[520px] flex items-center justify-center text-center text-white"
      style="background-image: url('img/nosotros/servicios medicos scaled.jpg.jpeg');"
    >
      <div class="absolute inset-0 bg-black/30"></div>
      <div class="relative z-10 px-4">
        <p class="text-2xl md:text-4xl lg:text-5xl font-semibold leading-tight">
          MUCHO MÁS QUE UN
        </p>
        <h1 class="text-2xl md:text-4xl lg:text-5xl font-semibold leading-tight">
          MARKETPLACE: UN ESTILO DE VIDAO
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
            <img src="img/nosotros/Bienestar-600x600.avif" alt="Bienestar"
                 class="w-full h-full object-contain drop-shadow-2xl" />
            <div class="texto-hover">
              <h3>Bienestar</h3>
              <p>Apoya tu salud física y emocional de manera equilibrada.</p>
            </div>
          </div>

          <div class="relative w-40 h-40 icono-hover">
            <img src="img/nosotros/Saludable-600x600.avif" alt="Saludable"
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
