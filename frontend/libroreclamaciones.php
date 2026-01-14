<?php
session_start();
session_destroy();
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Lyrium Biomarketplace | Libro de reclamaciones</title>

  <!-- Phosphor Icons -->
  <script src="https://unpkg.com/phosphor-icons"></script>

  <style>
    /* =============================================================================
       ANIMACIONES PREMIUM
    ============================================================================= */
    @keyframes fadeInUp {
      from {
        opacity: 0;
        transform: translateY(30px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    @keyframes shimmer {
      0% { background-position: -1000px 0; }
      100% { background-position: 1000px 0; }
    }

    @keyframes particleMove {
      0% { transform: translate(0, 0); }
      100% { transform: translate(50px, 50px); }
    }

    @keyframes shimmerLine {
      0%, 100% { background-position: 200% 0; opacity: 0.3; }
      50% { background-position: -200% 0; opacity: 1; }
    }

    .animate-in {
      animation: fadeInUp 0.6s ease-out forwards;
    }

    .animate-delay-1 { animation-delay: 0.1s; opacity: 0; }
    .animate-delay-2 { animation-delay: 0.2s; opacity: 0; }
    .animate-delay-3 { animation-delay: 0.3s; opacity: 0; }
    .animate-delay-4 { animation-delay: 0.4s; opacity: 0; }

    html {
      scroll-behavior: smooth;
    }

    /* =============================================================================
       ESTILOS DEL FORMULARIO - ESTILO PREMIUM
    ============================================================================= */
    .lr-page {
      background: radial-gradient(1200px 240px at 50% 0%, #e0f2fe 0%, rgba(224,242,254,0) 60%), #f7fbff;
      padding: 42px 16px 60px;
      min-height: 100vh;
      font-family: 'Outfit', sans-serif;
    }

    .lr-wrap {
      max-width: 1000px;
      margin: 0 auto;
    }

    /* Header animado con estilo de políticas */
    .ly-pagehead-row {
      display: flex;
      justify-content: center;
      margin: 10px 0 30px;
    }

    .ly-pagehead {
      display: inline-flex;
      align-items: center;
      gap: 16px;
      padding: 16px 32px;
      background: linear-gradient(135deg, #0ea5e9, #0284c7);
      border-radius: 999px;
      box-shadow: 0 20px 40px rgba(14, 165, 233, 0.2);
      transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
      text-decoration: none;
    }

    .ly-pagehead:hover {
      transform: translateY(-5px) scale(1.02);
      box-shadow: 0 30px 60px rgba(14, 165, 233, 0.3);
    }

    .ly-pagehead__icon {
      width: 44px;
      height: 44px;
      background: rgba(255, 255, 255, 0.2);
      backdrop-filter: blur(4px);
      border-radius: 12px;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 8px;
      transition: all 0.4s ease;
    }

    .ly-pagehead:hover .ly-pagehead__icon {
      transform: rotate(10deg) scale(1.1);
      background: rgba(255, 255, 255, 0.3);
    }

    .ly-pagehead__icon img {
      width: 100%;
      height: 100%;
      object-fit: contain;
    }

    .ly-pagehead__title {
      color: #ffffff;
      font-size: clamp(20px, 4vw, 32px);
      font-weight: 800;
      letter-spacing: -0.02em;
    }

    .lr-pill {
      display: inline-flex;
      align-items: center;
      gap: 14px;
      padding: 16px 28px;
      border-radius: 999px;
      background: linear-gradient(135deg, #0ea5e9, #38bdf8);
      color: #fff;
      box-shadow: 0 18px 35px rgba(14,165,233,.18);
      font-weight: 900;
      letter-spacing: -0.02em;
      font-size: clamp(20px, 2.6vw, 34px);
      transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
      cursor: pointer;
      position: relative;
      overflow: hidden;
    }

    /* Efecto de partículas decorativas */
    .lr-pill::before {
      content: '';
      position: absolute;
      top: -50%;
      left: -50%;
      width: 200%;
      height: 200%;
      background: radial-gradient(circle, rgba(255,255,255,0.1) 1px, transparent 1px);
      background-size: 30px 30px;
      animation: particleMove 15s linear infinite;
      pointer-events: none;
    }

    /* Línea de brillo superior animada */
    .lr-pill::after {
      content: '';
      position: absolute;
      top: 0;
      left: 50%;
      transform: translateX(-50%);
      width: 70%;
      height: 2px;
      background: linear-gradient(90deg, transparent, rgba(255,255,255,0.8), transparent);
      background-size: 200% 100%;
      animation: shimmerLine 3s ease-in-out infinite;
      border-radius: 0 0 10px 10px;
      box-shadow: 0 0 15px rgba(255,255,255,0.6);
    }

    .lr-pill:hover {
      transform: translateY(-5px) scale(1.05);
      box-shadow: 0 30px 60px rgba(14,165,233,.4);
    }

    .lr-pill i {
      font-size: 26px;
      background: rgba(255,255,255,.18);
      border-radius: 999px;
      padding: 10px;
      transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
      position: relative;
      z-index: 1;
    }

    .lr-pill:hover i {
      transform: rotate(360deg) scale(1.2);
      background: rgba(255,255,255,.3);
    }

    .lr-intro {
      background: rgba(255,255,255,0.7);
      backdrop-filter: blur(10px);
      border-radius: 20px;
      padding: 20px 24px;
      margin-bottom: 24px;
      color: #475569;
      line-height: 1.8;
      font-size: 15.5px;
      border: 1px solid rgba(14,165,233,0.1);
      box-shadow: 0 8px 24px rgba(15,23,42,.08);
      transition: all 0.3s ease;
    }

    .lr-intro:hover {
      background: rgba(255,255,255,0.9);
      box-shadow: 0 12px 32px rgba(15,23,42,.12);
      transform: translateY(-2px);
    }

    .lr-intro b {
      color: #0ea5e9;
      font-weight: 700;
    }

    .lr-card {
      background: rgba(255,255,255,.92);
      backdrop-filter: blur(10px);
      border: 1px solid rgba(226,232,240,.9);
      border-radius: 26px;
      box-shadow: 0 22px 60px rgba(15,23,42,.08);
      overflow: hidden;
      padding: 32px;
      transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
      position: relative;
    }

    .lr-card::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 4px;
      background: linear-gradient(90deg, #0ea5e9, #38bdf8, #0ea5e9);
      background-size: 200% 100%;
      animation: shimmer 3s linear infinite;
    }

    .lr-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 35px 80px rgba(15,23,42,.15);
      border-color: rgba(14,165,233,.2);
    }

    .lr-grid {
      display: grid;
      grid-template-columns: 1fr;
      gap: 20px;
    }

    @media (min-width: 768px) {
      .lr-grid {
        grid-template-columns: repeat(3, 1fr);
      }
    }

    .lr-field {
      grid-column: span 1;
      transition: all 0.3s ease;
    }

    .lr-field:hover {
      transform: translateY(-2px);
    }

    .lr-col-2 {
      grid-column: span 1;
    }

    .lr-col-3 {
      grid-column: span 1;
    }

    @media (min-width: 768px) {
      .lr-col-2 {
        grid-column: span 2;
      }
      .lr-col-3 {
        grid-column: span 3;
      }
    }

    .lr-label {
      display: block;
      font-weight: 700;
      color: #0f172a;
      margin-bottom: 8px;
      font-size: 14px;
      transition: all 0.3s ease;
    }
    
    .lr-field:hover .lr-label {
      color: #0ea5e9;
      transform: translateX(2px);
    }

    .lr-control {
      width: 100%;
      padding: 12px 16px;
      border: 2px solid #e2e8f0;
      background: #ffffff;
      border-radius: 12px;
      font-size: 15.5px;
      color: #1e293b;
      transition: all 0.25s ease;
      font-family: inherit;
    }

    .lr-control::placeholder {
      color: #94a3b8;
    }

    .lr-control:hover {
      border-color: #38bdf8;
      background: #f8fafc;
      box-shadow: 0 4px 12px rgba(14,165,233,0.1);
    }

    .lr-control:focus {
      outline: none;
      border-color: #0ea5e9;
      background: #ffffff;
      box-shadow: 0 0 0 4px rgba(14,165,233,.15);
      transform: translateY(-2px);
    }

    textarea.lr-control {
      min-height: 120px;
      resize: vertical;
    }

    .lr-section-title {
      grid-column: span 1;
      font-size: 20px;
      font-weight: 900;
      background: linear-gradient(135deg, #0ea5e9, #38bdf8);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
      margin: 12px 0 -8px;
      letter-spacing: -0.02em;
      transition: all 0.3s ease;
    }

    @media (min-width: 768px) {
      .lr-section-title {
        grid-column: span 3;
      }
    }

    .lr-section-title:hover {
      transform: scale(1.02);
      filter: drop-shadow(0 4px 8px rgba(14,165,233,0.3));
    }

    .lr-help {
      grid-column: span 1;
      padding: 16px 20px;
      background: linear-gradient(135deg, rgba(240, 249, 255, 0.8), rgba(224, 242, 254, 0.5));
      border-left: 4px solid #0ea5e9;
      border-radius: 12px;
      color: #475569;
      font-size: 14px;
      line-height: 1.7;
      transition: all 0.3s ease;
    }

    @media (min-width: 768px) {
      .lr-help {
        grid-column: span 3;
      }
    }

    .lr-help:hover {
      background: linear-gradient(135deg, rgba(240, 249, 255, 1), rgba(224, 242, 254, 0.8));
      box-shadow: 0 4px 16px rgba(14,165,233,.15);
      transform: translateX(4px);
    }

    .lr-help b {
      color: #0ea5e9;
      font-weight: 700;
    }

    .lr-file input[type="file"] {
      width: 100%;
      padding: 12px;
      border: 2px dashed #cbd5e1;
      border-radius: 12px;
      background: #f8fafc;
      cursor: pointer;
      transition: all 0.3s ease;
    }

    .lr-file input[type="file"]:hover {
      border-color: #0ea5e9;
      background: rgba(14,165,233,.05);
      transform: scale(1.01);
    }

    .lr-checks {
      grid-column: span 1;
      display: flex;
      flex-direction: column;
      gap: 12px;
      margin-top: 8px;
    }

    @media (min-width: 768px) {
      .lr-checks {
        grid-column: span 3;
      }
    }

    .lr-check {
      display: flex;
      gap: 10px;
      align-items: flex-start;
      padding: 14px;
      background: rgba(255,255,255,0.5);
      border-radius: 12px;
      cursor: pointer;
      transition: all 0.3s ease;
      border: 2px solid transparent;
    }

    .lr-check:hover {
      background: rgba(14,165,233,.05);
      border-color: rgba(14,165,233,.2);
      transform: translateX(4px);
    }

    .lr-check input[type="checkbox"] {
      margin-top: 4px;
      width: 18px;
      height: 18px;
      cursor: pointer;
      accent-color: #0ea5e9;
    }

    .lr-check span {
      flex: 1;
      color: #475569;
      font-size: 14px;
      line-height: 1.6;
    }

    .lr-check a {
      color: #0ea5e9;
      text-decoration: none;
      font-weight: 700;
      transition: all 0.2s ease;
    }

    .lr-check a:hover {
      color: #38bdf8;
      text-decoration: underline;
    }

    .lr-actions {
      grid-column: span 1;
      display: flex;
      justify-content: center;
      margin-top: 12px;
    }

    @media (min-width: 768px) {
      .lr-actions {
        grid-column: span 3;
      }
    }

    .lr-submit {
      padding: 16px 48px;
      font-size: 16px;
      font-weight: 900;
      color: #fff;
      background: linear-gradient(135deg, #0ea5e9 0%, #38bdf8 50%, #0ea5e9 100%);
      background-size: 200% 100%;
      border: 2px solid transparent;
      border-radius: 999px;
      cursor: pointer;
      box-shadow: 0 18px 40px rgba(14,165,233,.25), inset 0 1px 2px rgba(255,255,255,.3);
      transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
      position: relative;
      overflow: hidden;
    }

    .lr-submit::before {
      content: '';
      position: absolute;
      inset: -2px;
      background: linear-gradient(135deg, #38bdf8, #7dd3fc, #38bdf8, #0ea5e9);
      background-size: 300% 100%;
      border-radius: 999px;
      opacity: 0;
      z-index: -1;
      animation: shimmer 3s linear infinite;
      transition: opacity 0.4s ease;
    }

    .lr-submit:hover::before {
      opacity: 1;
    }

    .lr-submit:hover {
      transform: translateY(-6px) scale(1.05);
      box-shadow: 0 28px 60px rgba(14,165,233,.4), 0 0 40px rgba(56,189,248,.3);
      background-position: -100% 0;
    }

    .lr-submit:active {
      transform: translateY(-4px) scale(1.03);
    }
  </style>
</head>

<?php include 'header.php'; ?>

<main class="lr-page flex-1">
  <div class="lr-wrap">

   <div class="ly-pagehead-row">
        <div class="ly-pagehead">
          <span class="ly-pagehead__icon">
            <img src="https://img.icons8.com/ios-filled/96/ffffff/complaint.png" alt="Reclamos">
          </span>
          <span class="ly-pagehead__title">Libro de reclamaciones</span>
        </div>
      </div>
    <div class="lr-intro animate-in animate-delay-1">
      Conforme a lo establecido en el Código de Protección y Defensa del Consumidor este establecimiento cuenta con un Libro de Reclamaciones Virtual a tu disposición.<br>
      <b>Queja:</b> Disconformidad frente a una mala atención del proveedor, pero que no guarda relación directa con el producto o servicio adquirido.<br>
      <b>Reclamo:</b> Se produce cuando no estás conforme con el producto adquirido o servicio brindado.
    </div>

    <div class="lr-card animate-in animate-delay-2">
      <form id="libroReclamosForm" class="lr-grid" method="POST" action="procesar_reclamo.php" enctype="multipart/form-data" autocomplete="on">

        <!-- Fila 1 -->
        <div class="lr-field animate-in animate-delay-3">
          <label class="lr-label" for="tipo_persona">Tipo de persona</label>
          <select class="lr-control" id="tipo_persona" name="tipo_persona" required>
            <option value="Persona natural" selected>Persona natural</option>
            <option value="Persona jurídica">Persona jurídica</option>
          </select>
        </div>

        <div class="lr-field animate-in animate-delay-3">
          <label class="lr-label" for="nombre_razon">Nombre y Apellido/Razón Social*</label>
          <input class="lr-control" id="nombre_razon" name="nombre_razon" type="text" placeholder="Nombre y Apellido/Razón Social*" required />
        </div>

        <div class="lr-field animate-in animate-delay-3">
          <label class="lr-label" for="correo">Correo electrónico</label>
          <input class="lr-control" id="correo" name="correo" type="email" placeholder="Correo electrónico" />
        </div>

        <!-- Fila 2 -->
        <div class="lr-field animate-in animate-delay-4">
          <label class="lr-label" for="tipo_documento">Tipo de documento</label>
          <select class="lr-control" id="tipo_documento" name="tipo_documento" required>
            <option value="DNI" selected>DNI</option>
            <option value="CE">CE</option>
            <option value="Pasaporte">Pasaporte</option>
            <option value="RUC">RUC</option>
          </select>
        </div>

        <div class="lr-field animate-in animate-delay-4">
          <label class="lr-label" for="numero_documento">Número de documento</label>
          <input class="lr-control" id="numero_documento" name="numero_documento" type="text" placeholder="Número de documento" required />
        </div>

        <div class="lr-field animate-in animate-delay-4">
          <label class="lr-label" for="telefono">Número telefónico</label>
          <input class="lr-control" id="telefono" name="telefono" type="tel" placeholder="Número telefónico" />
        </div>

        <!-- Dirección -->
        <div class="lr-field lr-col-3">
          <label class="lr-label" for="direccion">Dirección</label>
          <input class="lr-control" id="direccion" name="direccion" type="text" placeholder="Escribe tu dirección completa. Avenida/calle, nombre de edificio, piso/número de casa" />
        </div>

        <!-- Ubigeo -->
        <div class="lr-field">
          <label class="lr-label" for="distrito">Distrito</label>
          <input class="lr-control" id="distrito" name="distrito" type="text" placeholder="Distrito" />
        </div>

        <div class="lr-field">
          <label class="lr-label" for="provincia">Provincia</label>
          <input class="lr-control" id="provincia" name="provincia" type="text" placeholder="Provincia" />
        </div>

        <div class="lr-field">
          <label class="lr-label" for="departamento">Departamento</label>
          <input class="lr-control" id="departamento" name="departamento" type="text" placeholder="Departamento" />
        </div>

        <div class="lr-section-title">Detalles del reclamo</div>

        <!-- Detalles -->
        <div class="lr-field">
          <label class="lr-label" for="tipo_reclamo">Tipo de reclamo</label>
          <select class="lr-control" id="tipo_reclamo" name="tipo_reclamo" required>
            <option value="Reclamo" selected>Reclamo</option>
            <option value="Queja">Queja</option>
          </select>
        </div>

        <div class="lr-field">
          <label class="lr-label" for="bien_contratado">Bien contratado</label>
          <select class="lr-control" id="bien_contratado" name="bien_contratado" required>
            <option value="Producto" selected>Producto</option>
            <option value="Servicio">Servicio</option>
          </select>
        </div>

        <div class="lr-field">
          <label class="lr-label" for="comprobante_pago">Comprobante de pago</label>
          <select class="lr-control" id="comprobante_pago" name="comprobante_pago" required>
            <option value="Boleta" selected>Boleta</option>
            <option value="Factura">Factura</option>
            <option value="Sin comprobante">Sin comprobante</option>
          </select>
        </div>

        <div class="lr-field">
          <label class="lr-label" for="numero_comprobante">Número de comprobante</label>
          <input class="lr-control" id="numero_comprobante" name="numero_comprobante" type="text" placeholder="Ingresar el número de comprobante" />
        </div>

        <div class="lr-field">
          <label class="lr-label" for="fecha_incidente">Fecha del incidente</label>
          <input class="lr-control" id="fecha_incidente" name="fecha_incidente" type="date" />
        </div>

        <div class="lr-field">
          <label class="lr-label" for="detalle_producto">Detalle del producto o servicio</label>
          <input class="lr-control" id="detalle_producto" name="detalle_producto" type="text" placeholder="Escribe aquí el detalle del producto o servicio contratado." />
        </div>

        <div class="lr-field lr-col-2">
          <label class="lr-label" for="detalle_reclamo">Detalle del reclamo o queja</label>
          <textarea class="lr-control" id="detalle_reclamo" name="detalle_reclamo" placeholder="Escribe aquí el detalle del reclamo o queja." required></textarea>
        </div>

        <div class="lr-field">
          <label class="lr-label" for="archivo">Subir un archivo</label>
          <div class="lr-file">
            <input id="archivo" name="archivo" type="file" />
          </div>
        </div>

        <div class="lr-field">
          <label class="lr-label" for="tienda_responsable">Tienda responsable</label>
          <select class="lr-control" id="tienda_responsable" name="tienda_responsable" required>
            <option value="VIDA NATURAL" selected>VIDA NATURAL</option>
            <option value="LYRIUM">LYRIUM</option>
          </select>
        </div>

        <div id="lrHelp" class="lr-help">
          <b>Reclamo:</b> Disconformidad relacionada a un producto/servicio adquirido. &nbsp;|&nbsp;
          <b>Queja:</b> Disconformidad por la atención recibida.
        </div>

        <div class="lr-checks">
          <label class="lr-check">
            <input type="checkbox" name="chk_titular" required>
            <span>Declaro ser el titular del contenido del presente formulario y que la información suministrada es correcta.</span>
          </label>

          <label class="lr-check">
            <input type="checkbox" name="chk_politica" required>
            <span>He leído y acepto la <a href="politicasdeprivacidad.php">Política de Privacidad</a>.</span>
          </label>
        </div>

        <div class="lr-actions">
          <button class="lr-submit" type="submit">Enviar reporte</button>
        </div>

      </form>
    </div>

  </div>

  <script>
    (function(){
      const tipo = document.getElementById('tipo_reclamo');
      const help = document.getElementById('lrHelp');
      const detalle = document.getElementById('detalle_reclamo');

      function renderHelp(){
        const v = (tipo.value || '').toLowerCase();
        if(v === 'queja'){
          help.innerHTML = '<b>Queja:</b> Disconformidad frente a una mala atención del proveedor (sin relación directa con el producto/servicio).';
          detalle.placeholder = 'Describe la atención recibida, fecha, canal, persona que te atendió y lo ocurrido.';
        }else{
          help.innerHTML = '<b>Reclamo:</b> Disconformidad relacionada a un producto/servicio adquirido (calidad, entrega, garantía, etc.).';
          detalle.placeholder = 'Describe el problema con el producto/servicio, cuándo ocurrió y qué solución solicitas.';
        }
      }

      tipo.addEventListener('change', renderHelp);
      renderHelp();
    })();
  </script>
</main>

<?php include 'footer.php'; ?>
</html>
