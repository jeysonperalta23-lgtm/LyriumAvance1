<?php
session_start();
session_destroy();

/* ===== DEMO DATA (cámbialo por tu data real / BD) ===== */
$tiendas = [
  [
    "nombre" => "Vida Natural05",
    "ciudad" => "Piura, Perú",
    "telefono" => "912345678",
    "direccion" => "Urb. Los Educadores Mz m. Lt - 04, Piura, Perú",
    "cover" => "https://images.unsplash.com/photo-1543362906-acfc16c67564?q=80&w=1400&auto=format&fit=crop",
    "logo"  => "https://upload.wikimedia.org/wikipedia/commons/thumb/4/4f/Iconic_image_of_a_leaf.svg/256px-Iconic_image_of_a_leaf.svg.png",
    "estado" => "",
  ],
  [
    "nombre" => "sanjuandedios",
    "ciudad" => "Piura, Perú",
    "telefono" => "989787676",
    "direccion" => "Piura, Perú",
    "cover" => "https://images.unsplash.com/photo-1584515933487-779824d29309?q=80&w=1400&auto=format&fit=crop",
    "logo"  => "https://upload.wikimedia.org/wikipedia/commons/thumb/3/3b/Red_Cross.svg/240px-Red_Cross.svg.png",
    "estado" => "",
  ],
  [
    "nombre" => "fisiocenter",
    "ciudad" => "Piura, Perú",
    "telefono" => "987654321",
    "direccion" => "Piura, Perú",
    "cover" => "https://lyriumbiomarketplace.com/wp-content/uploads/2024/08/cropped-FISIOCENTER-1.png",
    "logo"  => "https://lyriumbiomarketplace.com/wp-content/uploads/2024/08/cropped-FISIOCENTER-1.png",
    "estado" => "",
  ],
  [
    "nombre" => "plazamedic",
    "ciudad" => "Piura, Perú",
    "telefono" => "987676536",
    "direccion" => "Piura, Perú",
    "cover" => "https://lyriumbiomarketplace.com/wp-content/uploads/2024/08/cropped-PLAZA-MEDIC-3.png",
    "logo"  => "https://lyriumbiomarketplace.com/wp-content/uploads/2024/08/cropped-PLAZA-MEDIC-3.png",
    "estado" => "",
  ],
  [
    "nombre" => "sotomayor",
    "ciudad" => "Piura, Perú",
    "telefono" => "987878766",
    "direccion" => "Piura, Perú",
    "cover" => "https://lyriumbiomarketplace.com/wp-content/uploads/2024/08/cropped-cropped-SOTOMAYOR.png",
    "logo"  => "https://lyriumbiomarketplace.com/wp-content/uploads/2024/08/cropped-cropped-SOTOMAYOR.png",
    "estado" => "",
  ],
  [
    "nombre" => "fitbody",
    "ciudad" => "Piura, Perú",
    "telefono" => "987878722",
    "direccion" => "Piura, Perú",
    "cover" => "https://images.unsplash.com/photo-1517836357463-d25dfeac3438?q=80&w=1400&auto=format&fit=crop",
    "logo"  => "https://upload.wikimedia.org/wikipedia/commons/thumb/4/4c/Dumbbell_icon.svg/256px-Dumbbell_icon.svg.png",
    "estado" => "",
  ],
  [
    "nombre" => "amó spa",
    "ciudad" => "Piura, Perú",
    "telefono" => "987876763",
    "direccion" => "Piura, Perú",
    "cover" => "https://images.unsplash.com/photo-1556228578-0d85b1a4d571?q=80&w=1400&auto=format&fit=crop",
    "logo"  => "https://upload.wikimedia.org/wikipedia/commons/thumb/3/3a/Flower_icon.svg/256px-Flower_icon.svg.png",
    "estado" => "",
  ],
  [
    "nombre" => "vinasol",
    "ciudad" => "Piura, Perú",
    "telefono" => "988978676",
    "direccion" => "Piura, Perú",
    "cover" => "https://images.unsplash.com/photo-1505250469679-203ad9ced0cb?q=80&w=1400&auto=format&fit=crop",
    "logo"  => "https://upload.wikimedia.org/wikipedia/commons/thumb/8/8a/Fruit_icon.svg/256px-Fruit_icon.svg.png",
    "estado" => "",
  ],
  [
    "nombre" => "vidanatural",
    "ciudad" => "Piura, Perú",
    "telefono" => "987878766",
    "direccion" => "Piura, Perú",
    "cover" => "https://images.unsplash.com/photo-1542838132-92c53300491e?q=80&w=1400&auto=format&fit=crop",
    "logo"  => "https://upload.wikimedia.org/wikipedia/commons/thumb/4/43/Leaf_icon.svg/256px-Leaf_icon.svg.png",
    "estado" => "Abierto",
  ],
];

$totalTiendas = count($tiendas);
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

  <link rel="icon" type="image/png" href="img/logo.png?v=<?php echo time();?>" />

  <style>
    html, body { width: 100%; overflow-x: hidden; }

    /* ====== TIENDAS REGISTRADAS (diseño como tus imágenes) ====== */
    .stores-wrap{
      background: radial-gradient(1200px 350px at 50% 0%, rgba(56,189,248,.18), rgba(255,255,255,0));
    }

    .stores-title{
      letter-spacing: .06em;
      text-transform: uppercase;
      color: #334155;
      font-weight: 800;
      text-align: center;
      font-size: clamp(28px, 3.2vw, 44px);
      margin-bottom: 1.75rem;
    }

    .stores-toolbar{
      background: #fff;
      border: 1px solid rgba(226,232,240,1);
      border-radius: 18px;
      box-shadow: 0 10px 30px rgba(15,23,42,.08);
      padding: 16px 18px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 12px;
    }

    .filter-pill{
      display: inline-flex;
      align-items: center;
      gap: 10px;
      background: linear-gradient(135deg, #38bdf8, #0ea5e9);
      color: #fff;
      font-weight: 700;
      padding: 10px 16px;
      border-radius: 999px;
      box-shadow: 0 12px 25px rgba(14,165,233,.25);
      transition: transform .25s ease, box-shadow .25s ease, filter .25s ease;
      user-select: none;
    }
    .filter-pill:hover{
      transform: translateY(-2px);
      box-shadow: 0 18px 35px rgba(14,165,233,.32);
      filter: saturate(1.05);
    }

    .toolbar-iconbtn{
      width: 44px;
      height: 44px;
      border-radius: 12px;
      border: 1px solid rgba(226,232,240,1);
      background: #fff;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      box-shadow: 0 10px 25px rgba(15,23,42,.06);
      transition: transform .2s ease, box-shadow .2s ease;
    }
    .toolbar-iconbtn:hover{
      transform: translateY(-2px);
      box-shadow: 0 14px 30px rgba(15,23,42,.10);
    }

    .store-card{
      background: #fff;
      border: 1px solid rgba(226,232,240,1);
      border-radius: 18px;
      box-shadow: 0 14px 35px rgba(15,23,42,.08);
      overflow: hidden;
      transition: transform .35s ease, box-shadow .35s ease;
    }
    .store-card:hover{
      transform: translateY(-6px);
      box-shadow: 0 20px 55px rgba(15,23,42,.14);
    }

    .store-cover{
      position: relative;
      height: 220px;
      overflow: hidden;
    }
    .store-cover img{
      width: 100%;
      height: 100%;
      object-fit: cover;
      transform: scale(1.02);
      transition: transform .75s ease;
    }
    .store-card:hover .store-cover img{ transform: scale(1.08); }

    .store-cover::after{
      content:"";
      position:absolute;
      inset:0;
      background: linear-gradient(90deg, rgba(0,0,0,.55), rgba(0,0,0,.12) 55%, rgba(0,0,0,0));
      pointer-events:none;
    }

    .store-meta{
      position:absolute;
      inset: 0;
      padding: 18px 18px 18px 18px;
      display:flex;
      flex-direction:column;
      justify-content: space-between;
      z-index: 2;
    }

    .store-name{
      color:#fff;
      font-weight: 800;
      font-size: 22px;
      text-shadow: 0 10px 25px rgba(0,0,0,.45);
      line-height: 1.1;
    }

    .store-info{
      color: rgba(255,255,255,.95);
      font-weight: 600;
      font-size: 14px;
      display: grid;
      gap: 6px;
    }
    .store-info .line{
      display:flex;
      align-items:center;
      gap: 8px;
      text-shadow: 0 10px 18px rgba(0,0,0,.35);
    }
    .store-info i{ font-size: 18px; }

    .store-chip{
      position:absolute;
      top: 14px;
      right: 14px;
      z-index: 3;
      font-weight: 800;
      font-size: 12px;
      color: #fff;
      padding: 7px 12px;
      border-radius: 999px;
      background: rgba(34,197,94,.92);
      box-shadow: 0 12px 25px rgba(34,197,94,.25);
      backdrop-filter: blur(6px);
    }

    .store-logo{
      position:absolute;
      right: 16px;
      bottom: -26px;
      z-index: 4;
      width: 80px;
      height: 80px;
      border-radius: 999px;
      background: #fff;
      border: 4px solid rgba(255,255,255,.85);
      box-shadow: 0 18px 40px rgba(15,23,42,.18);
      display:flex;
      align-items:center;
      justify-content:center;
      overflow:hidden;
    }
    .store-logo img{
      width: 60%;
      height: 60%;
      object-fit: contain;
      filter: drop-shadow(0 6px 12px rgba(0,0,0,.10));
    }

    .store-actions{
      padding: 16px 16px 18px 16px;
      display:flex;
      align-items:center;
      gap: 12px;
      background: linear-gradient(180deg, rgba(248,250,252,1), rgba(255,255,255,1));
    }

    .btn-go{
      width: 44px;
      height: 44px;
      border-radius: 999px;
      background: linear-gradient(135deg, #38bdf8, #0ea5e9);
      color: #fff;
      display:flex;
      align-items:center;
      justify-content:center;
      box-shadow: 0 14px 28px rgba(14,165,233,.25);
      transition: transform .25s ease, box-shadow .25s ease;
      flex: 0 0 auto;
    }
    .btn-go:hover{ transform: translateY(-2px); box-shadow: 0 18px 36px rgba(14,165,233,.32); }
    .btn-go i{ font-size: 18px; }

    .btn-follow{
      height: 44px;
      padding: 0 18px;
      border-radius: 999px;
      font-weight: 800;
      color: #0ea5e9;
      background: #fff;
      border: 2px solid rgba(56,189,248,.55);
      box-shadow: 0 10px 22px rgba(15,23,42,.06);
      transition: transform .2s ease, background .2s ease, border-color .2s ease, color .2s ease;
    }
    .btn-follow:hover{
      transform: translateY(-2px);
      background: rgba(14,165,233,.08);
      border-color: rgba(14,165,233,.70);
      color: #0284c7;
    }

    @media (max-width: 640px){
      .store-cover{ height: 200px; }
      .store-name{ font-size: 20px; }
      .store-logo{ width: 74px; height: 74px; right: 14px; }
    }
  </style>
</head>

<?php include 'header.php'; ?>

<body class="bg-gray-50 min-h-screen flex flex-col">

  <main class="max-w-7xl mx-auto px-4 py-10 flex-1 space-y-10 stores-wrap">

    <!-- TÍTULO -->
    <h1 class="stores-title">TIENDAS REGISTRADAS</h1>

    <!-- TOOLBAR (como tu captura: total + filtro a la derecha) -->
    <div class="stores-toolbar">
      <div class="text-slate-600 font-semibold text-sm sm:text-base">
        Total de Tiendas Registradas: <span class="text-slate-900 font-extrabold"><?php echo $totalTiendas; ?></span>
      </div>

      <div class="flex items-center gap-10">
        <button class="filter-pill" type="button">
          <i class="ph-funnel-simple"></i>
          <span>Filtro</span>
        </button>

        <button class="toolbar-iconbtn" type="button" aria-label="Vista en cuadrícula">
          <i class="ph-squares-four text-slate-700 text-xl"></i>
        </button>

        <button class="toolbar-iconbtn" type="button" aria-label="Vista en lista">
          <i class="ph-list-bullets text-slate-700 text-xl"></i>
        </button>
      </div>
    </div>

    <!-- GRID DE TIENDAS -->
    <section class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 pt-2">
      <?php foreach($tiendas as $t): ?>
        <article class="store-card">
          <div class="store-cover">
            <img src="<?php echo htmlspecialchars($t["cover"]); ?>" alt="<?php echo htmlspecialchars($t["nombre"]); ?>">

            <?php if (!empty($t["estado"])): ?>
              <div class="store-chip"><?php echo htmlspecialchars($t["estado"]); ?></div>
            <?php endif; ?>

            <div class="store-meta">
              <div>
                <div class="store-name"><?php echo htmlspecialchars($t["nombre"]); ?></div>
              </div>

              <div class="store-info">
                <div class="line">
                  <i class="ph-map-pin"></i>
                  <span><?php echo htmlspecialchars($t["ciudad"]); ?></span>
                </div>
                <div class="line">
                  <i class="ph-phone-call"></i>
                  <span><?php echo htmlspecialchars($t["telefono"]); ?></span>
                </div>
                <?php if (!empty($t["direccion"])): ?>
                <div class="line" style="opacity:.95">
                  <i class="ph-map-trifold"></i>
                  <span class="line-clamp-1"><?php echo htmlspecialchars($t["direccion"]); ?></span>
                </div>
                <?php endif; ?>
              </div>
            </div>

            <div class="store-logo" aria-hidden="true">
              <img src="<?php echo htmlspecialchars($t["logo"]); ?>" alt="">
            </div>
          </div>

          <div class="store-actions">
            <a href="#" class="btn-go" aria-label="Ver tienda">
              <i class="ph-caret-right-bold"></i>
            </a>
            <button class="btn-follow" type="button">Seguir</button>
          </div>
        </article>
      <?php endforeach; ?>
    </section>

  </main>

  <?php include 'footer.php'; ?>

</body>
</html>
