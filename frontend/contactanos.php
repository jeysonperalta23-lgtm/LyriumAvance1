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

  <!-- TailwindCSS -->
  <script src="https://cdn.tailwindcss.com"></script>

  <!-- Iconos Phosphor -->
  <script src="https://unpkg.com/phosphor-icons"></script>

  <!-- JS general -->
  <script src="utils/js/3.4.16.js"></script>

  <link href="utils/css/index.css" rel="stylesheet">
  <link rel="icon" type="image/png" href="img/logo.png?v=<?php echo time();?>" />

  <style>
    /* ====== EFECTOS GENERALES (NO ROMPE TUS FUNCIONALIDADES) ====== */
    .zoom-img{ transition: transform .7s ease; }
    .zoom-img:hover{ transform: scale(1.06); }

    /* ====== CONTACTO: DISEÑO MODERNO + LEGIBLE ====== */
    .contact-card{
      position: relative;
      border-radius: 24px;
      border: 1px solid rgba(14,165,233,.25);
      background: radial-gradient(1200px 400px at 20% 0%, rgba(14,165,233,.10), transparent 60%),
                  linear-gradient(to bottom, #ffffff, #f8fafc);
      box-shadow: 0 18px 50px rgba(2,132,199,.12);
      overflow: hidden;
    }
    .contact-card::before{
      content:"";
      position:absolute;
      inset:-2px;
      background: linear-gradient(90deg, rgba(132,204,22,.18), rgba(14,165,233,.18), rgba(132,204,22,.18));
      filter: blur(18px);
      opacity: .65;
      pointer-events:none;
    }
    .contact-inner{ position: relative; z-index: 1; }

    /* TÍTULO PILL */
    .contact-title{
      display:flex;
      align-items:center;
      justify-content:center;
      gap:14px;
      width:100%;
      padding: 18px 18px;
      border-radius: 999px;
      color: #fff;
      font-weight: 800;
      letter-spacing: .02em;
      background: linear-gradient(135deg, #38bdf8, #0ea5e9);
      box-shadow: 0 18px 40px rgba(14,165,233,.30);
      transform: translateY(0);
      transition: transform .35s ease, filter .35s ease;
    }
    .contact-title:hover{
      transform: translateY(-2px);
      filter: saturate(1.05) contrast(1.05);
    }

    /* ANIMACIÓN SUAVE AL CARGAR */
    .fade-up{
      opacity: 0;
      transform: translateY(14px);
      animation: fadeUp .8s ease forwards;
    }
    @keyframes fadeUp{
      to{ opacity:1; transform: translateY(0); }
    }

    /* INPUTS BONITOS */
    .field{
      border-radius: 999px;
      border: 1px solid rgba(14,165,233,.35);
      background: rgba(255,255,255,.9);
      transition: box-shadow .25s ease, transform .25s ease, border-color .25s ease;
    }
    .field:focus{
      outline: none;
      border-color: rgba(14,165,233,.70);
      box-shadow: 0 0 0 5px rgba(14,165,233,.18);
      transform: translateY(-1px);
    }
    .field-area{
      border-radius: 20px;
      border: 1px solid rgba(14,165,233,.35);
      background: rgba(255,255,255,.9);
      transition: box-shadow .25s ease, transform .25s ease, border-color .25s ease;
    }
    .field-area:focus{
      outline: none;
      border-color: rgba(14,165,233,.70);
      box-shadow: 0 0 0 5px rgba(14,165,233,.18);
      transform: translateY(-1px);
    }

    /* BOTÓN CON SHINE */
    .btn-send{
      position: relative;
      overflow: hidden;
      border-radius: 999px;
      background: linear-gradient(135deg, #0ea5e9, #38bdf8);
      box-shadow: 0 20px 45px rgba(14,165,233,.30);
      transition: transform .25s ease, box-shadow .25s ease, filter .25s ease;
    }
    .btn-send:hover{
      transform: translateY(-2px);
      filter: saturate(1.05);
      box-shadow: 0 28px 60px rgba(14,165,233,.40);
    }
    .btn-send::after{
      content:"";
      position:absolute;
      top:-50%;
      left:-30%;
      width: 35%;
      height: 200%;
      background: rgba(255,255,255,.35);
      transform: rotate(18deg);
      transition: left .55s ease;
      filter: blur(2px);
    }
    .btn-send:hover::after{ left: 115%; }

    /* COLUMNA DERECHA: IMAGEN CON OVERLAY */
    .contact-aside{
      border-radius: 22px;
      background: linear-gradient(to bottom, rgba(15,23,42,.05), rgba(15,23,42,.02));
      border: 1px solid rgba(148,163,184,.30);
      overflow: hidden;
      box-shadow: 0 16px 45px rgba(2,8,23,.10);
    }
    .aside-img-wrap{
      position: relative;
      border-radius: 18px;
      overflow: hidden;
      box-shadow: 0 18px 45px rgba(2,8,23,.15);
    }
    .aside-img-wrap::after{
      content:"";
      position:absolute;
      inset:0;
      background: linear-gradient(to top, rgba(2,8,23,.55), rgba(2,8,23,0) 55%);
      opacity: .75;
      pointer-events:none;
      transition: opacity .35s ease;
    }
    .aside-img-wrap:hover::after{ opacity: .55; }
    .aside-img{
      width: 100%;
      height: 340px;
      object-fit: cover;
      display:block;
      transition: transform .9s ease;
    }
    .aside-img-wrap:hover .aside-img{ transform: scale(1.06); }

    /* LISTA CONTACTO */
    .contact-row{
      border-radius: 16px;
      padding: 12px 14px;
      background: rgba(255,255,255,.75);
      border: 1px solid rgba(148,163,184,.25);
      transition: transform .25s ease, box-shadow .25s ease;
    }
    .contact-row:hover{
      transform: translateY(-2px);
      box-shadow: 0 14px 35px rgba(2,8,23,.10);
    }

    /* ANIMACIÓN AL HACER SCROLL (se activa con JS abajo) */
    .reveal{
      opacity: 0;
      transform: translateY(14px);
      transition: opacity .7s ease, transform .7s ease;
    }
    .reveal.is-visible{
      opacity: 1;
      transform: translateY(0);
    }
  </style>
</head>

<body class="bg-gray-50 min-h-screen flex flex-col">

 <?php include 'header.php'; ?>

  <!-- CONTENIDO -->
  <main class="max-w-7xl mx-auto px-4 py-10 flex-1 space-y-16">

    <!-- ===== CONTACTO MEJORADO ===== -->
    <section class="max-w-7xl mx-auto px-4 py-12 bg-white">
      <div class="contact-card p-6 md:p-10 fade-up">
        <div class="contact-inner">

          <div class="text-center mb-10">
            <div class="contact-title text-2xl md:text-3xl">
              Contáctanos
              <img src="https://img.icons8.com/ios-filled/96/ffffff/phone.png" alt="Teléfono" class="w-10 h-10 md:w-12 md:h-12"/>
            </div>
            <p class="mt-4 text-sm md:text-base text-slate-600 max-w-2xl mx-auto reveal">
              ¿Tienes consultas o sugerencias? Escríbenos y te responderemos lo antes posible.
            </p>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-stretch">

            <!-- FORM -->
            <div class="reveal">
              <h2 class="text-3xl md:text-4xl font-extrabold text-sky-600 mb-6 tracking-tight">
                Datos personales
              </h2>

              <form class="space-y-4">
                <div>
                  <label class="block text-sm font-semibold text-slate-700 mb-1">
                    Nombre y Apellidos <span class="text-red-600">*</span>
                  </label>
                  <input type="text" class="field w-full px-5 py-3" placeholder="Escriba sus nombres.." />
                </div>

                <div>
                  <label class="block text-sm font-semibold text-slate-700 mb-1">
                    Correo electrónico <span class="text-red-600">*</span>
                  </label>
                  <input type="email" class="field w-full px-5 py-3" placeholder="Escriba su email" />
                </div>

                <div>
                  <label class="block text-sm font-semibold text-slate-700 mb-1">
                    Asunto <span class="text-red-600">*</span>
                  </label>
                  <input type="text" class="field w-full px-5 py-3" placeholder="Asunto.." />
                </div>

                <div>
                  <label class="block text-sm font-semibold text-slate-700 mb-1">
                    Mensaje <span class="text-red-600">*</span>
                  </label>
                  <textarea class="field-area w-full px-5 py-3" rows="5" placeholder="Mensaje.."></textarea>
                </div>

                <div class="flex items-center gap-2">
                  <input type="checkbox" class="h-4 w-4 text-sky-600 focus:ring-sky-500 border-gray-300 rounded">
                  <label class="text-sm text-slate-700">Acepto la política de privacidad.</label>
                </div>

                <button type="submit" class="btn-send w-full text-white py-3 font-bold tracking-wide">
                  ENVIAR MENSAJE
                </button>
              </form>
            </div>

            <!-- ASIDE -->
            <div class="contact-aside p-4 md:p-6 reveal">
              <div class="aside-img-wrap">
                <img
                  src="https://lyriumbiomarketplace.com/wp-content/uploads/2025/05/primer-plano-de-una-empresaria-con-una-gran-sonrisa-2048x1365.jpg"
                  alt="Atención al cliente"
                  class="aside-img"
                />
              </div>

              <p class="mt-5 text-center text-slate-600 text-sm md:text-base">
                Envíanos tus consultas y/o sugerencias, estaremos encantados de poder atenderte.
              </p>

              <div class="mt-6 space-y-3 text-sm md:text-base">
                <div class="contact-row flex items-center gap-3">
                  <i class="ph-map-pin text-2xl text-sky-600"></i>
                  <span class="text-slate-700 font-semibold">Perú</span>
                </div>

                <div class="contact-row flex items-center gap-3">
                  <i class="ph-envelope-simple text-2xl text-sky-600"></i>
                  <span class="text-slate-700 font-semibold">ventas@lyriumbiomarketplace.com</span>
                </div>

                <div class="contact-row flex items-center gap-3">
                  <i class="ph-phone-call text-2xl text-sky-600"></i>
                  <span class="text-slate-700 font-semibold">+51 937 093 420</span>
                </div>
              </div>
            </div>

          </div>

        </div>
      </div>
    </section>

  </main>

  <?php include 'footer.php'; ?>

  <!-- ===== ANIMACIÓN AL HACER SCROLL (sin librerías) ===== -->
  <script>
    (function(){
      const els = document.querySelectorAll('.reveal');
      const io = new IntersectionObserver((entries)=>{
        entries.forEach(e=>{
          if(e.isIntersecting){
            e.target.classList.add('is-visible');
            io.unobserve(e.target);
          }
        });
      }, { threshold: 0.14 });
      els.forEach(el=>io.observe(el));
    })();
  </script>

</body>
</html>
