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

  <!-- Estilos externos -->
  <link rel="stylesheet" href="utils/css/libroreclamaciones.css">

  <!-- Phosphor Icons -->
  <script src="https://unpkg.com/phosphor-icons"></script>

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

  <!-- Script externo -->
  <script src="utils/js/libroreclamaciones.js"></script>
</main>

<?php include 'footer.php'; ?>
</html>
