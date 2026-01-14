<?php
// bioforo.php
session_start();
/* ⚠️ NO destruir sesión aquí */
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Lyrium Biomarketplace | BioForo</title>

  <style>
    html, body{ width:100%; overflow-x:hidden; }
    *{ box-sizing:border-box; }

    :root{
      --ink:#0f172a;
      --muted:#6b7280;
      --muted2:#94a3b8;
      --line:#e5e7eb;
      --bg:#ffffff;
      --soft:#f8fafc;

      --blue:#2ea8ff;
      --blue-700:#1e88e5;

      --radius:16px;
      --shadow: 0 14px 40px rgba(15,23,42,.08);

      --font: ui-sans-serif, system-ui, -apple-system, Segoe UI, Roboto, "Helvetica Neue", Arial, "Noto Sans", sans-serif;
    }

    body{
      margin:0;
      font-family: var(--font);
      color:var(--ink);
      background: var(--bg);
    }
    header{ position: relative; z-index: 60; }
    main, footer{ position: relative; z-index: 1; }

    /* ✅ Pagehead (igual “premium” y sin negrita fuerte) */
    .ly-pagehead{
      display:inline-flex;
      align-items:center;
      justify-content:center;
      gap: 12px;
      padding: 12px 16px;
      border-radius: 999px;
      background: linear-gradient(135deg, rgba(34,174,247,.16), rgba(14,165,233,.08));
      border: 1px solid rgba(14,165,233,.16);
      box-shadow: 0 16px 40px rgba(2,132,199,.10);
      backdrop-filter: blur(6px);
    }
    .ly-pagehead__title{
      font-weight: 650;
      letter-spacing: .12em;
      color: #ffffff;
      font-size: 13px;
      text-transform: uppercase;
    }
    .ly-pagehead__icon{
      width: 34px;
      height: 34px;
      border-radius: 999px;
      overflow:hidden;
      display:grid;
      place-items:center;
      background: linear-gradient(135deg, #22aef7, #0ea5e9);
      box-shadow: 0 10px 22px rgba(14,165,233,.22);
    }
    .ly-pagehead__icon img{
      width: 18px;
      height: 18px;
      filter: brightness(2.2);
    }

    .container{
      max-width: 1120px;
      margin: 0 auto;
      padding: 0 18px;
    }

    .hero{
      margin-top: 22px;
      border-radius: 18px;
      overflow:hidden;
      box-shadow: var(--shadow);
      position: relative;
      min-height: 300px;
      background: #0b1220;
    }
    .hero__bg{
      position:absolute; inset:0;
      background-size: cover;
      background-position: center;
      transform: scale(1.03);
      filter: saturate(1.05) contrast(1.03);
    }
    .hero__overlay{
      position:absolute; inset:0;
      background: linear-gradient(90deg, rgba(2,6,23,.70), rgba(2,6,23,.18));
    }
    .hero__content{
      position:relative;
      padding: 44px 42px;
      max-width: 880px;
      color:#fff;
    }
    .hero__title{
      margin:0 0 10px;
      font-size: clamp(32px, 4vw, 54px);
      line-height: 1.02;
      font-weight: 650; /* ✅ menos pesado */
      letter-spacing: .08em;
      text-transform: uppercase;
      text-shadow: 0 12px 26px rgba(2,6,23,.35);
    }
    .hero__subtitle{
      margin:0;
      font-size: 14px;
      font-weight: 550; /* ✅ menos negrita */
      opacity:.92;
      text-transform: uppercase;
      letter-spacing: .12em;
    }
    .hero__line{
      width: 170px;
      height: 3px;
      border-radius: 999px;
      background: var(--blue);
      margin-top: 18px;
      box-shadow: 0 12px 20px rgba(46,168,255,.28);
    }

    /* ===== Intro 2 columnas ===== */
    .intro{
      display:grid;
      grid-template-columns: 1fr 1fr;
      gap: 26px;
      align-items:center;
      margin: 26px 0 8px;
    }
    .intro__img{
      border-radius: 12px;
      overflow:hidden;
      background:#eee;
      box-shadow: 0 10px 24px rgba(15,23,42,.08);
      aspect-ratio: 4 / 5;
    }
    .intro__img img{
      width:100%;
      height:100%;
      display:block;
      object-fit: cover;
      object-position: center;
      transform: scale(1.01);
    }

    .intro__text{
      display:flex;
      gap: 14px;
      align-items:flex-start;
      justify-content:flex-start;
      padding-top: 6px;
    }
    .intro__logo{
      width: 58px;
      height: 58px;
      border-radius: 999px;
      display:grid;
      place-items:center;
      background: #fff;
      border: 1px solid var(--line);
      box-shadow: 0 10px 22px rgba(15,23,42,.07);
      flex: 0 0 auto;
    }
    .intro__logo img{
      width: 40px;
      height: 40px;
      object-fit: contain;
      display:block;
    }
    .intro__copy{
      color: var(--muted);
      font-size: 15px;
      line-height: 1.85;
      font-weight: 450;
      max-width: 520px;
    }
    .intro__copy b, .intro__copy strong{ font-weight: 650; color: var(--ink); }

    /* ===== Tabs bar ===== */
    .tabsbar{
      margin-top: 14px;
      border-top: 1px solid var(--line);
      border-bottom: 1px solid var(--line);
      display:flex;
      align-items:center;
      justify-content:space-between;
      gap: 12px;
    }

    .tabs{
      display:flex;
      align-items:center;
      gap: 24px;
      padding-left: 6px;
      overflow:auto;
      -webkit-overflow-scrolling: touch;
    }
    .tab{
      border:0;
      background: transparent;
      padding: 14px 2px;
      font-weight: 550;
      font-size: 13px;
      color: #374151;
      cursor:pointer;
      position:relative;
      white-space: nowrap;
    }
    .tab:hover{ color: var(--ink); }
    .tab.active{ color: var(--ink); }
    .tab.active::after{
      content:"";
      position:absolute;
      left:0; right:0; bottom:-1px;
      height: 3px;
      border-radius: 999px;
      background: var(--blue);
    }

    .searchWrap{
      position:relative;
      padding-right: 6px;
      display:flex;
      align-items:center;
      gap:10px;
    }
    .iconBtn{
      width: 36px;
      height: 36px;
      border-radius: 999px;
      border: 1px solid var(--line);
      background:#fff;
      display:grid;
      place-items:center;
      cursor:pointer;
      transition: transform .15s ease, box-shadow .15s ease;
    }
    .iconBtn:hover{
      transform: translateY(-1px);
      box-shadow: 0 10px 18px rgba(15,23,42,.08);
    }

    .searchPopover{
      position:absolute;
      top: calc(100% + 10px);
      right: 0;
      width: min(420px, calc(100vw - 36px));
      background:#fff;
      border: 1px solid var(--line);
      border-radius: 14px;
      box-shadow: 0 18px 50px rgba(15,23,42,.12);
      padding: 10px;
      display:none;
      z-index: 50;
    }
    .searchPopover.open{ display:block; }
    .searchInput{
      width:100%;
      border-radius: 12px;
      border: 1px solid var(--line);
      padding: 12px 12px;
      font-size: 13.5px;
      font-weight: 500;
      color: var(--ink);
      outline:none;
    }
    .searchInput:focus{
      border-color: rgba(46,168,255,.65);
      box-shadow: 0 0 0 4px rgba(46,168,255,.18);
    }

    /* ===== Topbar ===== */
    .topbar{
      margin-top: 18px;
      padding: 12px 12px;
      border-radius: 14px;
      border: 1px solid var(--line);
      background:#fff;
      display:flex;
      align-items:center;
      justify-content:space-between;
      gap: 12px;
    }

    .dd{
      display:flex;
      align-items:center;
      gap: 10px;
      color: var(--ink);
      font-weight: 550;
      font-size: 13px;
    }
    .dd .ddIco{
      width: 34px;
      height: 34px;
      border-radius: 12px;
      display:grid;
      place-items:center;
      border: 1px solid var(--line);
      background: #fff;
      color: #111827;
      flex: 0 0 auto;
    }
    .dd select{
      border:0;
      outline:0;
      background: transparent;
      font-weight: 550;
      font-size: 13px;
      color: var(--ink);
      cursor:pointer;
      padding: 6px 6px;
    }

    /* ===== Botones ===== */
    .btn{
      appearance:none;
      -webkit-appearance:none;
      border-radius: 999px;
      height: 38px;
      padding: 0 16px;
      font-family: var(--font);
      font-weight: 550;
      font-size: 13px;
      letter-spacing: .02em;
      cursor:pointer;
      display:inline-flex;
      align-items:center;
      justify-content:center;
      gap: 10px;
      user-select:none;
      transition: transform .15s ease, box-shadow .15s ease, background .15s ease, border-color .15s ease, color .15s ease;
      white-space: nowrap;
    }

    .btn-outline{
      background:#fff;
      border: 1.5px solid rgba(46,168,255,.60);
      color: var(--ink);
      box-shadow: 0 10px 22px rgba(15,23,42,.06);
    }
    .btn-outline:hover{
      transform: translateY(-1px);
      border-color: rgba(46,168,255,.85);
      box-shadow: 0 14px 28px rgba(15,23,42,.08);
    }

    .btn-primary{
      background: var(--blue);
      border: 1px solid var(--blue);
      color:#fff;
      box-shadow: 0 14px 28px rgba(46,168,255,.20);
    }
    .btn-primary:hover{
      transform: translateY(-1px);
      background: var(--blue-700);
      border-color: var(--blue-700);
      box-shadow: 0 18px 34px rgba(46,168,255,.22);
    }

    /* ===== Panel ===== */
    .panel{
      margin-top: 18px;
      border-radius: 14px;
      border: 1px solid var(--line);
      overflow:hidden;
      background:#fff;
    }
    .listHead, .row{
      display:grid;
      grid-template-columns: 110px 1fr 160px 110px 110px 170px;
      gap: 12px;
      align-items:center;
      padding: 12px 14px;
    }
    .listHead{
      color:#6b7280;
      font-weight: 550;
      font-size: 12px;
      border-bottom: 1px solid var(--line);
      background:#fff;
    }
    .row{
      border-bottom: 1px solid var(--line);
      font-size: 13.5px;
      font-weight: 500;
      color: var(--ink);
      background:#fff;
    }
    .row:last-child{ border-bottom:0; }

    .pill{
      display:inline-flex;
      align-items:center;
      gap:8px;
      padding: 6px 10px;
      border-radius: 999px;
      font-weight: 550;
      font-size: 12px;
      border: 1px solid var(--line);
      background: #fff;
      color: var(--ink);
      white-space: nowrap;
    }
    .pill.ok{ border-color: rgba(16,185,129,.35); background: rgba(16,185,129,.08); }
    .pill.new{ border-color: rgba(46,168,255,.35); background: rgba(46,168,255,.10); }
    .pill.hot{ border-color: rgba(245,158,11,.35); background: rgba(245,158,11,.10); }

    .topic{
      display:flex;
      flex-direction:column;
      gap:6px;
      min-width:0;
    }
    .topic a{
      color: var(--ink);
      text-decoration:none;
      font-weight: 600;
      overflow:hidden;
      text-overflow: ellipsis;
      white-space: nowrap;
    }
    .topic a:hover{ text-decoration: underline; }
    .topic small{
      color: var(--muted2);
      font-size: 12.5px;
      font-weight: 500;
      overflow:hidden;
      text-overflow: ellipsis;
      white-space: nowrap;
    }

    .center{ text-align:center; color:#374151; font-weight:550; }
    .right{ text-align:right; color:#374151; font-weight:550; }

    /* ===== Stats ===== */
    .stats{
      margin-top: 18px;
      border-radius: 14px;
      border: 1px solid var(--line);
      background:#fff;
      padding: 14px;
      color:#374151;
      font-size: 13px;
    }
    .statsRow{
      display:flex;
      align-items:center;
      flex-wrap:wrap;
      gap: 18px;
      color:#374151;
      font-weight: 550;
    }
    .statsItem{
      display:flex;
      align-items:center;
      gap: 8px;
      color:#374151;
    }
    .statsItem span{ color:#6b7280; font-weight: 550; }
    .statsItem b{ font-weight: 650; }

    /* ===== Vistas ===== */
    .view{ margin-top: 18px; }
    .box{
      border: 1px solid var(--line);
      border-radius: 14px;
      background:#fff;
      padding: 14px;
    }
    .boxTitle{
      display:flex;
      align-items:center;
      justify-content:space-between;
      gap: 12px;
      margin-bottom: 10px;
    }
    .boxTitle b{ font-size: 15px; font-weight: 650; }
    .muted{ color: var(--muted2); font-weight: 550; font-size: 12.5px; }

    .grid3{
      display:grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 10px;
      margin-top: 10px;
    }
    .cardMini{
      border: 1px solid var(--line);
      border-radius: 12px;
      padding: 12px;
      background:#fff;
    }
    .cardMini b{ display:block; font-weight: 650; }
    .cardMini small{ color: var(--muted2); font-weight: 550; }

    /* ===== Modal ===== */
    .modal{
      position: fixed;
      inset: 0;
      display:none;
      align-items:center;
      justify-content:center;
      padding: 18px;
      z-index: 9999;
    }
    .modal.open{ display:flex; }
    .backdrop{
      position:absolute; inset:0;
      background: rgba(2,6,23,.55);
      backdrop-filter: blur(6px);
      -webkit-backdrop-filter: blur(6px);
    }
    .dialog{
      position: relative;
      width: min(720px, 100%);
      border-radius: 14px;
      overflow:hidden;
      background:#fff;
      border: 1px solid var(--line);
      box-shadow: 0 30px 90px rgba(2,6,23,.35);
      transform: translateY(8px);
      opacity: 0;
      transition: transform .18s ease, opacity .18s ease;
    }
    .modal.open .dialog{ transform: translateY(0); opacity: 1; }
    .dialogHead{
      padding: 14px 14px;
      display:flex;
      align-items:center;
      justify-content:space-between;
      gap:10px;
      border-bottom: 1px solid var(--line);
      background:#fff;
    }
    .dialogTitle{
      display:flex;
      align-items:center;
      gap:10px;
      color: var(--ink);
      font-weight: 650;
      font-size: 14px;
    }
    .x{
      border: 1px solid var(--line);
      background:#fff;
      color: var(--ink);
      width: 36px; height: 36px;
      border-radius: 12px;
      cursor:pointer;
      display:grid; place-items:center;
      transition: transform .15s ease;
    }
    .x:hover{ transform: translateY(-1px); }
    .dialogBody{ padding: 14px; }
    .form{
      display:grid;
      grid-template-columns: 1fr 1fr;
      gap: 12px;
    }
    .field{ display:flex; flex-direction:column; gap: 6px; }
    .field.full{ grid-column: 1 / -1; }
    .field label{
      font-size: 12px;
      color: var(--muted);
      font-weight: 550;
    }
    .input, .textarea, .select{
      width:100%;
      border-radius: 12px;
      border: 1px solid var(--line);
      background:#fff;
      padding: 11px 12px;
      font-size: 13.5px;
      font-weight: 500;
      color: var(--ink);
      outline: none;
    }
    .textarea{ min-height: 96px; resize: vertical; line-height:1.6; }
    .input:focus, .textarea:focus, .select:focus{
      border-color: rgba(46,168,255,.65);
      box-shadow: 0 0 0 4px rgba(46,168,255,.18);
    }
    .help{
      margin-top: 6px;
      font-size: 12.5px;
      color: var(--muted2);
      font-weight: 500;
      line-height:1.5;
    }
    .dialogFoot{
      display:flex;
      justify-content:space-between;
      align-items:center;
      gap:10px;
      padding: 14px;
      border-top: 1px solid var(--line);
      background:#fff;
    }

    .toast{
      position: fixed;
      bottom: 18px;
      left: 50%;
      transform: translateX(-50%);
      z-index: 10000;
      background: rgba(15,23,42,.92);
      color:#fff;
      padding: 12px 14px;
      border-radius: 12px;
      box-shadow: 0 24px 70px rgba(2,6,23,.35);
      display:none;
      font-weight: 500;
      font-size: 13px;
      max-width: min(560px, calc(100% - 30px));
    }
    .toast.show{ display:block; }

    /* ===== Responsive ===== */
    @media (max-width: 980px){
      .intro{ grid-template-columns: 1fr; }
      .hero__content{ padding: 34px 26px; }
      .listHead{ display:none; }
      .row{
        grid-template-columns: 1fr;
        gap: 10px;
        padding: 14px;
      }
      .row > div{
        display:flex;
        justify-content:space-between;
        gap: 12px;
      }
      .row > div::before{
        content: attr(data-label);
        color:#9ca3af;
        font-weight: 650;
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: .12em;
        min-width: 120px;
      }
      .center, .right{ text-align:left; }
      .form{ grid-template-columns: 1fr; }
      .grid3{ grid-template-columns: 1fr; }
      .intro__img{ aspect-ratio: 3 / 4; }
 
    body { font-family: system-ui, sans-serif; margin:0; padding:0; background:#f9fafb; color:#1f2937; line-height:1.6; }
    .container { max-width:1100px; margin:0 auto; padding:20px; }
    h1, h2 { color:#1d4ed8; }
    .foro-header { background:#eff6ff; padding:24px; border-radius:12px; margin-bottom:24px; border:1px solid #bfdbfe; }
    .tema { background:white; border:1px solid #e5e7eb; border-radius:10px; margin-bottom:16px; padding:16px; }
    .tema.principal { border-left:5px solid #3b82f6; background:#f0f9ff; }
    .tema .titulo { font-size:1.25rem; font-weight:600; margin:0 0 8px; }
    .tema .meta { font-size:0.875rem; color:#6b7280; margin-bottom:12px; }
    .rol-badge { font-size:0.75rem; padding:2px 8px; border-radius:999px; font-weight:600; }
    .rol-admin  { background:#1e40af; color:white; }
    .rol-esp    { background:#065f46; color:white; }
    .rol-usuario{ background:#047857; color:white; }
    .rol-anon   { background:#9ca3af; color:white; }
    .respuestas { margin-left:32px; margin-top:16px; border-left:2px solid #e5e7eb; padding-left:16px; }
    .form-simple { margin:16px 0; }
    .form-simple textarea { width:100%; min-height:90px; padding:10px; border:1px solid #d1d5db; border-radius:6px; }
    .btn { background:#1d4ed8; color:white; border:none; padding:8px 16px; border-radius:6px; cursor:pointer; font-weight:500; }
    .btn-admin { background:#b91c1c; }
    .likes { color:#f59e0b; font-weight:600; cursor:pointer; }
    }

    /* ===== Responsive PRO (móvil primero) ===== */

/* Tablets y abajo */
@media (max-width: 980px){
  .container{ padding: 0 14px; }

  .hero{ min-height: 240px; }
  .hero__content{ padding: 28px 18px; }
  .hero__overlay{
    background: linear-gradient(180deg, rgba(2,6,23,.72), rgba(2,6,23,.22));
  }
  .hero__title{
    letter-spacing: .06em;
    font-size: clamp(26px, 7vw, 44px);
  }
  .hero__subtitle{ font-size: 12px; letter-spacing: .10em; }

  .intro{ grid-template-columns: 1fr; gap: 16px; }
  .intro__img{ aspect-ratio: 16 / 10; } /* ✅ mejor en móvil */
  .intro__text{ gap: 12px; }
  .intro__logo{ width: 52px; height: 52px; }
  .intro__copy{ font-size: 14px; line-height: 1.8; max-width: 100%; }

  /* Tabsbar: apilar */
  .tabsbar{
    flex-direction: column;
    align-items: stretch;
    gap: 10px;
    padding: 10px 0;
  }
  .tabs{
    padding-left: 0;
    gap: 14px;
  }
  .tab{ padding: 12px 2px; }

  .searchWrap{
    justify-content: flex-end;
    padding-right: 0;
  }
  .searchPopover{
    right: 0;
    left: 0;                 /* ✅ ocupa ancho */
    width: auto;             /* ✅ */
    max-width: 100%;
  }

  /* Topbar: columna + botones a full */
  .topbar{
    flex-direction: column;
    align-items: stretch;
    gap: 10px;
  }
  .dd{
    width: 100%;
    justify-content: space-between;
  }
  .dd select{
    width: 100%;
    max-width: 220px;
    text-align: right;
  }

  /* Contenedor de botones (tu div inline) */
  .topbar > div:last-child{
    width: 100%;
    justify-content: stretch !important;
  }

  /* Botones: más cómodos */
  .btn{
    height: 42px;
    padding: 0 16px;
    width: 100%;
    justify-content: center;
  }

  /* Panel lista */
  .listHead{ display:none; }
  .row{
    grid-template-columns: 1fr;
    gap: 10px;
    padding: 14px 12px;
  }
/* ✅ Mobile: label arriba + contenido abajo (no aplasta) */
.row > div{
  display:flex;
  flex-direction:column;
  align-items:flex-start;
  justify-content:flex-start;
  gap: 6px;
}

/* label */
.row > div::before{
  content: attr(data-label);
  color:#9ca3af;
  font-weight: 650;
  font-size: 10.5px;
  text-transform: uppercase;
  letter-spacing: .10em;
}

/* ✅ permitir que el título/desc hagan wrap en móvil */
.topic a{
  white-space: normal;
  overflow: visible;
  text-overflow: unset;
  line-height: 1.35;
}
.topic small{
  white-space: normal;
  overflow: visible;
  text-overflow: unset;
  line-height: 1.45;
}

/* alineaciones */
.center, .right{ text-align:left; }


  /* Modal */
  .dialog{ width: 100%; }
  .form{ grid-template-columns: 1fr; }
  .dialogFoot{
    flex-direction: column;
    align-items: stretch;
    gap: 10px;
  }
  .dialogFoot > div:last-child{
    justify-content: stretch !important;
  }
}

/* Celulares chicos */
@media (max-width: 420px){
  .ly-pagehead{ padding: 10px 12px; gap: 10px; }
  .ly-pagehead__title{ font-size: 12px; letter-spacing: .10em; }
  .ly-pagehead__icon{ width: 32px; height: 32px; }

  .hero{ border-radius: 16px; }
  .hero__line{ width: 130px; }

  .row > div::before{
    min-width: 84px;
    font-size: 10px;
  }

  .pill{ font-size: 11px; padding: 6px 9px; }
  .topic a{ font-size: 13.5px; }
  .topic small{ font-size: 12px; }
}

  </style>
</head>

<body>
  <?php include 'header.php'; ?>

  <main class="max-w-7xl mx-auto px-4 py-10 flex-1 space-y-16">

    <div class="text-center mb-10">
      <div class="ly-pagehead">
        <span class="ly-pagehead__icon">
          <!-- ✅ ICONO MEJOR (simple y consistente) -->
          <i class="ph-chats-circle text-lg"></i> 
        </span>
        <span class="ly-pagehead__title">BioForo</span>
      </div>
    </div>

    <div class="container">
      <!-- HERO -->
      <section class="hero" aria-label="BioForo Hero">
        <div class="hero__bg" style="background-image:url('https://lyriumbiomarketplace.com/wp-content/uploads/2025/06/bioforo_banner-scaled.jpg');"></div>
        <div class="hero__overlay"></div>

        <div class="hero__content">
          <h1 class="hero__title">Conecta BioForo</h1>
          <p class="hero__subtitle">Explora foros destacados, nuevas ideas y comunidad</p>
          <div class="hero__line"></div>
        </div>
      </section>

      <!-- INTRO -->
      <section class="intro" aria-label="BioForo Introducción">
        <div class="intro__img">
          <img src="https://lyriumbiomarketplace.com/wp-content/uploads/2025/10/Fondos_BioBlog-4.png" alt="BioForo">
        </div>

        <div class="intro__text">
          <div class="intro__logo" aria-hidden="true">
            <img src="https://lyriumbiomarketplace.com/wp-content/uploads/2025/10/Fondos_BioBlog-4.png" alt="">
          </div>
          <div class="intro__copy">
            <strong>BioForo</strong> es el espacio donde expertos, emprendedores y entusiastas del biocomercio,
            biotecnología, salud natural y sostenibilidad se conectan, comparten conocimientos y resuelven dudas.
          </div>
        </div>
      </section>


<?php

// Determinar rol y nombre del usuario
$usuario = null;
$rol = 'anonimo';
$nombre = 'Anónimo-' . rand(1000, 9999);

if (isset($_SESSION['user_id'])) {
    $usuario = $_SESSION['user_id'];
    $rol     = $_SESSION['rol'] ?? 'usuario';
    $nombre  = $_SESSION['nombre'] ?? 'Usuario';
}

// Conexión a la base de datos
try {
    $pdo = new PDO(
        "mysql:host=localhost;dbname=lyrium;charset=utf8mb4",
        "root",
        ""
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}

// Crear nuevo foro (solo admin)
if (isset($_POST['action']) && $_POST['action'] === 'crear_foro' && $rol === 'admin') {
    $nombre_foro = trim($_POST['nombre'] ?? '');
    if ($nombre_foro && strlen($nombre_foro) >= 3) {
        $slug = strtolower(preg_replace('/[^a-z0-9]+/i', '-', $nombre_foro));
        $stmt = $pdo->prepare("INSERT INTO bioforo_categorias (nombre, slug) VALUES (?, ?)");
        $stmt->execute([$nombre_foro, $slug]);
    }
}

// Crear tema nuevo
if (isset($_POST['action']) && $_POST['action'] === 'nuevo_tema') {
    $titulo    = trim($_POST['titulo'] ?? '');
    $contenido = trim($_POST['contenido'] ?? '');
    $cat_id    = (int)($_POST['categoria'] ?? 0);

    // Límites de caracteres
    if (mb_strlen($titulo) > 120)    $titulo = mb_substr($titulo, 0, 120);
    if (mb_strlen($contenido) > 400) $contenido = mb_substr($contenido, 0, 400);

    // Filtro de lenguaje ofensivo - BLOQUEAR
    $badwords = [
        'mierda', 'mierd', 'mrd', 'joder', 'jod', 'puta', 'puto', 'put4', 'cabrón', 'cabron',
        'pendejo', 'pendej', 'carajo', 'hijo de puta', 'hdp', 'hijueputa', 'hp', 'coño',
        'culiao', 'culero', 'marica', 'maric', 'verga', 'vrg', 'concha', 'huevon', 'gil'
    ];

    $contenido_lower = mb_strtolower($contenido);
    $is_bad = false;
    foreach ($badwords as $word) {
        if (strpos($contenido_lower, $word) !== false) {
            $is_bad = true;
            break;
        }
    }

    if ($is_bad) {
        echo '<div style="color:red; padding:10px; background:#fee2e2; border:1px solid #fecaca; border-radius:6px; margin:10px 0;">
                No se permiten palabras ofensivas o lenguaje inapropiado. Por favor, modifica tu mensaje.
              </div>';
    } elseif ($titulo && $contenido && $cat_id > 0) {
        $stmt = $pdo->prepare("
            INSERT INTO bioforo_temas 
            (categoria_id, usuario_id, anonimo_nombre, rol, titulo, contenido)
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([$cat_id, $usuario, $usuario ? null : $nombre, $rol, $titulo, $contenido]);
    }
}

// Crear respuesta
if (isset($_POST['action']) && $_POST['action'] === 'responder' && isset($_POST['tema_id'])) {
    $tema_id   = (int)$_POST['tema_id'];
    $contenido = trim($_POST['contenido'] ?? '');

    if (mb_strlen($contenido) > 1500) $contenido = mb_substr($contenido, 0, 1500);

    // Filtro de lenguaje ofensivo - BLOQUEAR
    $badwords = [
        'mierda', 'mierd', 'mrd', 'joder', 'jod', 'puta', 'puto', 'put4', 'cabrón', 'cabron',
        'pendejo', 'pendej', 'carajo', 'hijo de puta', 'hdp', 'hijueputa', 'hp', 'coño',
        'culiao', 'culero', 'marica', 'maric', 'verga', 'vrg', 'concha', 'huevon', 'gil'
    ];

    $contenido_lower = mb_strtolower($contenido);
    $is_bad = false;
    foreach ($badwords as $word) {
        if (strpos($contenido_lower, $word) !== false) {
            $is_bad = true;
            break;
        }
    }

    if ($is_bad) {
        echo '<div style="color:red; padding:10px; background:#fee2e2; border:1px solid #fecaca; border-radius:6px; margin:10px 0;">
                No se permiten palabras ofensivas o lenguaje inapropiado. Por favor, modifica tu mensaje.
              </div>';
    } elseif ($contenido && $tema_id > 0) {
        $stmt = $pdo->prepare("
            INSERT INTO bioforo_respuestas 
            (tema_id, usuario_id, anonimo_nombre, rol, contenido)
            VALUES (?, ?, ?, ?, ?)
        ");
        $stmt->execute([$tema_id, $usuario, $usuario ? null : $nombre, $rol, $contenido]);
    }
}

// Listado de categorías
$categorias = $pdo->query("SELECT * FROM bioforo_categorias ORDER BY nombre")->fetchAll(PDO::FETCH_ASSOC);

// Listado de temas (más votados primero)
$temas = $pdo->query("
    SELECT t.*, c.nombre AS cat_nombre,
           COALESCE(u.nombre, t.anonimo_nombre) AS autor
    FROM bioforo_temas t
    LEFT JOIN bioforo_categorias c ON c.id = t.categoria_id
    LEFT JOIN usuarios u ON u.id = t.usuario_id
    ORDER BY t.likes DESC, t.creado_en DESC
    LIMIT 30
")->fetchAll(PDO::FETCH_ASSOC);
?>






  <?php if ($rol === 'admin'): ?>
  <div style="margin:20px 0;">
    <button class="btn btn-admin" onclick="document.getElementById('formCrearForo').style.display='block'">+ Crear nuevo foro</button>
    <form id="formCrearForo" method="post" style="display:none; margin-top:12px;">
      <input type="hidden" name="action" value="crear_foro">
      <input type="text" name="nombre" placeholder="Nombre del nuevo foro" required style="padding:8px; width:300px;">
      <button type="submit" class="btn">Crear</button>
    </form>
  </div>
  <?php endif; ?>

  <h2>Temas más votados</h2>

  <?php if (empty($temas)): ?>
    <p class="text-center text-gray-600 py-8">Aún no hay temas publicados. ¡Sé el primero en crear uno!</p>
  <?php else: ?>
    <?php foreach ($temas as $tema): ?>
      <div class="tema principal">
        <div class="titulo"><?= htmlspecialchars($tema['titulo']) ?></div>
        <div class="meta">
          <span class="rol-badge rol-<?= htmlspecialchars($tema['rol']) ?>">
            <?= $tema['rol'] === 'admin' ? 'Admin' : ($tema['rol'] === 'especialista' ? 'Esp' : ($tema['rol'] === 'usuario' ? 'Usr' : 'Anónimo')) ?>
          </span>
           ·  <?= htmlspecialchars($tema['autor']) ?> 
          · <?= date('d M Y H:i', strtotime($tema['creado_en'])) ?>
          · <span class="likes">👍 <?= $tema['likes'] ?></span>
        </div>
        <div><?= nl2br(htmlspecialchars($tema['contenido'])) ?></div>

        <!-- Respuestas -->
        <?php
        $res = $pdo->prepare("SELECT * FROM bioforo_respuestas WHERE tema_id = ? ORDER BY creado_en ASC");
        $res->execute([$tema['id']]);
        $respuestas = $res->fetchAll(PDO::FETCH_ASSOC);
        if ($respuestas): ?>
          <div class="respuestas">
            <?php foreach ($respuestas as $r): ?>
              <div class="tema" style="margin:12px 0; background:#f9fafb;">
                <div class="meta">
                  <span class="rol-badge rol-<?= htmlspecialchars($r['rol']) ?>">
                    <?= $r['rol'] === 'admin' ? 'Admin' : ($r['rol'] === 'especialista' ? 'Esp' : ($r['rol'] === 'usuario' ? 'Usr' : 'Anónimo')) ?>
                  </span>
                   ·  <?= htmlspecialchars($r['anonimo_nombre'] ?? $r['autor'] ?? '—') ?> 
                  · <?= date('d M H:i', strtotime($r['creado_en'])) ?>
                  · <span class="likes">👍 <?= $r['likes'] ?></span>
                </div>
                <div><?= nl2br(htmlspecialchars($r['contenido'])) ?></div>
              </div>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>

        <!-- Form respuesta -->
        <form method="post" class="form-simple">
          <input type="hidden" name="action" value="responder">
          <input type="hidden" name="tema_id" value="<?= $tema['id'] ?>">
          <textarea name="contenido" placeholder="Escribe tu respuesta aquí..." maxlength="1500" required></textarea>
          <button type="submit" class="btn">Responder</button>
        </form>
      </div>
    <?php endforeach; ?>
  <?php endif; ?>

  <!-- Form nuevo tema -->
  <h2>Crear nuevo tema</h2>
  <form method="post" class="form-simple">
    <input type="hidden" name="action" value="nuevo_tema">
    <select name="categoria" required>
      <option value="">— Selecciona categoría —</option>
      <?php foreach ($categorias as $c): ?>
        <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['nombre']) ?></option>
      <?php endforeach; ?>
    </select>
    <input type="text" name="titulo" placeholder="Título (máx 120 caracteres)" maxlength="120" required>
    <textarea name="contenido" placeholder="Descripción inicial (máx 400 caracteres)" maxlength="400" required></textarea>
    <button type="submit" class="btn">Publicar tema</button>
  </form>
</div>

<?php include 'footer.php'; ?>

</body>
</html>
