<?php
// C:\xampp\htdocs\lyrium\frontend\login.php
session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Lyrium Biomarketplace | Mi cuenta</title>

  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://unpkg.com/phosphor-icons"></script>

  <style>
    .ly-pagehead{
      display:inline-flex; align-items:center; justify-content:center;
      gap:12px; padding:12px 16px; border-radius:999px;
      background:linear-gradient(135deg, rgba(34,174,247,.16), rgba(14,165,233,.08));
      border:1px solid rgba(14,165,233,.16);
      box-shadow:0 16px 40px rgba(2,132,199,.10);
      backdrop-filter:blur(6px);
    }
    .ly-pagehead__title{ font-weight:650; letter-spacing:.12em; color:#fff; font-size:13px; text-transform:uppercase; }
    .ly-pagehead__icon{
      width:34px; height:34px; border-radius:999px; overflow:hidden;
      display:grid; place-items:center;
      background:linear-gradient(135deg, #22aef7, #0ea5e9);
      box-shadow:0 10px 22px rgba(14,165,233,.22);
      color:#fff;
    }
    .ly-pagehead__icon i{ font-size:18px; }
    .ly-card{
      border-radius:24px; border:1px solid rgba(226,232,240,1);
      background: radial-gradient(900px 420px at 12% 0%, rgba(14,165,233,.08), transparent 55%),
                  linear-gradient(to bottom, #ffffff, #f8fafc);
      box-shadow:0 18px 45px rgba(2,132,199,.10);
      overflow:hidden;
    }
    .ly-card-title{ font-weight:650; color:#0284c7; letter-spacing:.2px; }
    .ly-input{
      width:100%; padding:12px 16px; border-radius:999px;
      border:1px solid rgba(125,211,252,.75);
      background:rgba(14,165,233,.05);
      outline:none;
      transition: box-shadow .25s ease, border-color .25s ease, transform .25s ease;
    }
    .ly-input:focus{
      border-color: rgba(14,165,233,.70);
      box-shadow:0 0 0 5px rgba(14,165,233,.16);
      transform: translateY(-1px);
      background:#fff;
    }
    .ly-btn{
      width:100%; border-radius:999px; padding:12px 16px;
      color:#fff; font-weight:650; letter-spacing:.02em;
      background:linear-gradient(135deg, #0ea5e9, #38bdf8);
      box-shadow:0 18px 40px rgba(14,165,233,.25);
      transition: transform .2s ease, box-shadow .2s ease, filter .2s ease;
      position:relative; overflow:hidden;
    }
    .ly-btn:hover{ transform: translateY(-2px); box-shadow:0 26px 60px rgba(14,165,233,.35); filter:saturate(1.05); }
    .ly-btn::after{
      content:""; position:absolute; top:-50%; left:-30%;
      width:35%; height:200%; background:rgba(255,255,255,.35);
      transform: rotate(18deg); transition:left .55s ease; filter: blur(2px);
    }
    .ly-btn:hover::after{ left:115%; }
    .ly-error{
      color:#b91c1c; background:rgba(239,68,68,.12);
      border:1px solid rgba(239,68,68,.20);
      border-radius:14px; padding:10px 12px;
      text-align:center; font-weight:600; font-size:13px;
    }
    .ly-ok{
      color:#166534; background:rgba(34,197,94,.12);
      border:1px solid rgba(34,197,94,.20);
      border-radius:14px; padding:10px 12px;
      text-align:center; font-weight:600; font-size:13px;
    }
  </style>
</head>

<body class="bg-gray-50 min-h-screen flex flex-col">
  <?php include 'header.php'; ?>

  <main class="max-w-7xl mx-auto px-4 py-10 flex-1">
    <div class="text-center mb-10">
      <div class="ly-pagehead">
        <span class="ly-pagehead__title">Mi cuenta</span>
        <span class="ly-pagehead__icon"><i class="ph-user-circle"></i></span>
      </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
      <!-- LOGIN -->
      <section class="ly-card p-6 md:p-8">
        <h2 class="text-xl mb-6 flex items-center gap-2 ly-card-title">
          <i class="ph-sign-in text-2xl text-sky-500"></i> Iniciar Sesión
        </h2>

        <form id="loginForm" autocomplete="on" class="space-y-4">
          <div>
            <label class="block text-sm font-semibold text-slate-700 mb-1">Correo *</label>
            <input name="correo" type="email" class="ly-input" required autocomplete="email" placeholder="tucorreo@dominio.com" />
          </div>

          <div>
            <label class="block text-sm font-semibold text-slate-700 mb-1">Contraseña *</label>
            <input name="password" type="password" class="ly-input" required autocomplete="current-password" placeholder="••••••••" />
          </div>

          <button type="submit" class="ly-btn">Acceso</button>
          <p id="error" class="hidden ly-error mt-2"></p>
        </form>
      </section>

      <!-- REGISTRO MÍNIMO -->
      <section class="ly-card p-6 md:p-8">
        <h2 class="text-xl mb-6 flex items-center gap-2 ly-card-title">
          <i class="ph-user-plus text-2xl text-sky-500"></i> Registrarse
        </h2>

        <!-- ✅ SOLO correo + clave, sin action -->
        <form id="registroMinForm" autocomplete="on" class="space-y-4">
          <div>
            <label class="block text-sm font-semibold text-slate-700 mb-1">Correo electrónico *</label>
            <input name="correo" type="email" class="ly-input" required autocomplete="email" placeholder="tucorreo@dominio.com" />
          </div>

          <div>
            <label class="block text-sm font-semibold text-slate-700 mb-1">Contraseña *</label>
            <input name="password" type="password" class="ly-input" required autocomplete="new-password" placeholder="Crea una contraseña" />
          </div>

          <p class="text-xs text-slate-500 leading-relaxed">
            Te enviaremos un correo con un link para completar tu perfil como <b>Cliente</b> o <b>Vendedor</b>.
          </p>

          <button type="submit" class="ly-btn">Crear cuenta</button>

          <p id="registroMsg" class="hidden mt-2"></p>
        </form>
      </section>
    </div>
  </main>

  <?php include 'footer.php'; ?>

  <script src="js/login.js?v=<?= time() ?>"></script>
</body>
</html>
