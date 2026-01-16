<?php
session_start();
session_destroy();
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Términos y Condiciones - Lyrium Biomarketplace</title>
  <link rel="stylesheet" href="utils/css/terminoscondiciones.css">
  <link rel="stylesheet" href="utils/css/ly-pagehead.css">
  <link rel="stylesheet" href="utils/css/mobile-popup.css">
  <link rel="stylesheet" href="utils/css/share-modal.css">

  <?php include 'header.php'; ?>

  <main class="max-w-7xl mx-auto px-4 py-10 flex-1 space-y-10">

    <?php
    $PDF_CLIENTE = 'pdf/cliente.pdf';
    $PDF_VENDEDOR = 'pdf/vendedor.pdf';

    $TERMINOS = [
      'cliente' => [
        [
          'id' => 'primero-privacidad',
          'title' => '1. Privacidad',
          'html' => '
            <p class="pp-text">El uso del Marketplace lyriumbiomarketplace.com estará sujeto a la aceptación de los Términos y Condiciones detallados a continuación:</p>
            <div class="pp-title">1.1 Información que recopilamos</div>
            <p class="pp-text">Los datos personales requeridos por el Marketplace, tales como nombres, apellidos, RUC (opcional), tipo de documento de identidad, número de documento de identidad, domicilio, departamento, provincia, número de contacto y correo electrónico serán de uso exclusivo del sitio para concretar las operaciones de compra y venta que el usuario desee realizar.</p>
            <p class="pp-text">La información que indirectamente nos proporcione, tales como cookies (preferencias del usuario cuando visite nuestra página web o redes sociales), direcciones IP, conexiones y sistemas de información; podrán ser usadas con el único fin de brindar una mejor experiencia al usuario y gestionar mejoras de productos/servicios.</p>
            <div class="pp-title">1.2 Guardamos su información de manera segura</div>
            <p class="pp-text">El sitio guarda toda su información personal de forma segura y confidencial. Sólo personal autorizado podrá acceder a su información personal en cumplimiento de los términos y condiciones.</p>
            <div class="pp-title">1.3 Finalidades del tratamiento</div>
            <p class="pp-text">Los datos personales que usted libremente proporciona serán tratados para: atender consultas, quejas, reclamos y hacer seguimiento; enviar información o publicidad sobre productos/servicios; invitar a participar en concursos/actividades; encuestas de satisfacción; administración interna; estudios de mercado; comercio electrónico; entrega de pedidos, entre otros.</p>
            <div class="pp-title">1.4 Acceso y Transferencia</div>
            <p class="pp-text">El sitio podrá compartir con terceros sus datos personales bajo circunstancias limitadas (socios, proveedores de internet, administradores web, managers de redes sociales, call centers, mensajería, transportes, etc.) para cumplir finalidades antes descritas. También podrá facilitar información a organismos públicos o autoridades competentes si es requerido por ley.</p>
          '
        ],
        [
          'id' => 'segundo-modificacion',
          'title' => '2. Modificación de los T&C',
          'html' => '
            <p class="pp-text">El sitio se reserva el derecho de actualizar y/o modificar los Términos y Condiciones del uso del Marketplace, poniéndolos a disposición de los usuarios para su conocimiento con el objetivo final de mejorar el servicio de nuestra comunidad.</p>
          '
        ],
        [
          'id' => 'tercero-creacion-cuenta',
          'title' => '3. Creación de Cuenta',
          'html' => '
            <p class="pp-text">Para comprar productos y/o servicios en el sitio no es necesario estar registrado; sin embargo, aquel usuario que esté registrado o cree una cuenta podrá recibir beneficios adicionales.</p>
            <p class="pp-text">Un usuario podrá crear una cuenta completando el formulario que aparece en el sitio web. Al registrarse acepta que:</p>
            <ul class="pp-list">
              <li>Proveerá información básica y real sobre su persona/empresa.</li>
              <li>Actualizará esta información si cambia.</li>
              <li>Será responsable por la veracidad de la información y por mantener la confidencialidad de sus credenciales.</li>
            </ul>
          '
        ],
        [
          'id' => 'quinto-metodo-pago',
          'title' => '5. Método de Pago',
          'html' => '
            <p class="pp-text">Los productos y servicios ofrecidos sólo podrán ser pagados bajo la modalidad de pago en línea. Los pagos en línea son procesados por una pasarela de pagos; el uso de tarjetas se sujetará a lo establecido por su banco emisor y al marco contractual correspondiente.</p>
            <p class="pp-text">Los reembolsos por devoluciones se realizarán a través del mismo medio de pago utilizado, sujeto a las políticas del banco emisor.</p>
          '
        ],
        [
          'id' => 'sexto-consentimiento',
          'title' => '6. Formación del Consentimiento',
          'html' => '
            <p class="pp-text">Las empresas vendedoras realizan ofertas de bienes y servicios que pueden concretarse mediante la aceptación del usuario (en línea) y usando los mecanismos del sitio. Toda aceptación quedará sujeta a la condición suspensiva de validación de la transacción por parte del sitio.</p>
          '
        ],
        [
          'id' => 'septimo-envios',
          'title' => '7. Envíos',
          'html' => '
            <p class="pp-text">El sitio permite despachos a nivel nacional de productos. Estos estarán sujetos a las políticas de despacho de cada empresa vendedora. La accesibilidad de destinos será identificada por el usuario al momento de comprar.</p>
          '
        ],
        [
          'id' => 'octavo-devoluciones',
          'title' => '8. Devoluciones',
          'html' => '
            <p class="pp-text">Los procedimientos de devoluciones de cada artículo o producto están sujetos a las decisiones de cada empresa vendedora del Marketplace LYRIUM.</p>
          '
        ],
        [
          'id' => 'noveno-reembolsos',
          'title' => '9. Reembolsos',
          'html' => '
            <p class="pp-text">Los procedimientos de reembolso de cada producto y/o servicio están sujetos a las decisiones de cada empresa vendedora del Marketplace LYRIUM.</p>
          '
        ],
        [
          'id' => 'decimo-exoneracion',
          'title' => '10. Exoneración de Responsabilidad',
          'html' => '
            <p class="pp-text">LYRIUM se constituye como un Marketplace que reúne a vendedores y compradores; por tanto, no será responsable de las características del producto adquirido, su envío y/o despacho, los cuales corresponden a la empresa vendedora.</p>
          '
        ],
      ],
      'vendedor' => [
        [
          'id' => 'vend-primero',
          'title' => '1. Obligaciones del Vendedor',
          'html' => '
            <p class="pp-text">El vendedor se compromete a publicar información veraz y completa sobre sus productos/servicios, incluyendo precios, stock, condiciones, imágenes y restricciones aplicables.</p>
            <ul class="pp-list">
              <li>Cumplir con los plazos de despacho y la atención posventa ofrecida.</li>
              <li>Gestionar garantías, devoluciones y reclamos según su política y la normativa aplicable.</li>
              <li>Responder por la calidad, idoneidad y legalidad de los bienes/servicios ofrecidos.</li>
            </ul>
          '
        ],
      ],
    ];
    ?>

    <div class="pp-wrap"
      data-terms='<?php echo htmlspecialchars(json_encode($TERMINOS, JSON_UNESCAPED_UNICODE), ENT_QUOTES, "UTF-8"); ?>'
      data-config='<?php echo htmlspecialchars(json_encode([
        "cliente" => [
          "subtitle" => "TÉRMINOS Y CONDICIONES GENERALES APLICABLES A LOS CLIENTES",
          "pdfLabel" => "Descargar PDF Cliente",
          "pdfHref" => $PDF_CLIENTE,
          "pdfName" => "cliente.pdf"
        ],
        "vendedor" => [
          "subtitle" => "TÉRMINOS Y CONDICIONES GENERALES APLICABLES A LOS VENDEDORES",
          "pdfLabel" => "Descargar PDF Vendedor",
          "pdfHref" => $PDF_VENDEDOR,
          "pdfName" => "vendedor.pdf"
        ]
      ]), ENT_QUOTES, "UTF-8"); ?>'>

      <!-- HEADER "PILL" -->

      <div class="text-center mb-10">
        <div class="ly-pagehead">
          <span class="ly-pagehead__icon">
            <img src="https://img.icons8.com/ios-filled/96/ffffff/terms-and-conditions.png" alt="Términos">
          </span>
          <span class="ly-pagehead__title">Términos y condiciones</span>
        </div>
      </div>


      <p class="pp-sub">
        Revisa los términos aplicables al uso de <strong>LYRIUM BIO MARKETPLACE</strong>.
        Usa las pestañas para cambiar entre Cliente y Vendedor.
      </p>

      <!-- Toolbar con tabs (Estilo unificado) -->
      <div class="flex justify-center gap-4 mt-6 mb-2 flex-wrap">
        <button class="pp-badge tc-tab-btn active" data-mode="cliente" type="button">
          <i class="ph ph-user-circle"></i><span>Del Cliente</span>
        </button>
        <button class="pp-badge tc-tab-btn" data-mode="vendedor" type="button">
          <i class="ph ph-storefront"></i><span>Del Vendedor</span>
        </button>
        <a id="tcPdfBtn" class="pp-badge pp-btn-premium" href="<?php echo htmlspecialchars($PDF_CLIENTE); ?>"
          download="cliente.pdf">
          <i class="ph ph-download-simple"></i><span id="tcPdfLabel">Descargar PDF Cliente</span>
        </a>
        <button id="btnShareTerms" class="pp-badge" style="background: white; color: #0ea5e9; border: 1px solid #e0f2fe;">
          <i class="ph-bold ph-arrow-bend-up-right"></i><span>Compartir</span>
        </button>
      </div>

      <div class="pp-grid mt-6">

        <!-- NAV / ÍNDICE (Dinámico) -->
        <aside class="pp-nav hidden lg:block animate-in animate-delay-1">
          <h4>Contenido</h4>
          <div id="tcTOC"></div>
        </aside>

        <!-- CARD PRINCIPAL -->
        <section class="pp-card animate-in animate-delay-2">
          <div class="pp-card-inner">
            <div id="tcSubtitle" class="text-center text-xl font-black text-gray-800 mb-2 uppercase">
              TÉRMINOS Y CONDICIONES GENERALES APLICABLES A LOS CLIENTES
            </div>
            <div class="text-center text-sm text-gray-500 mb-8">-LYRIUM BIOMARKETPLACE-</div>

            <div id="tcContent"></div>

            <div class="pp-foot">
              Última actualización: <strong>2025</strong>. Al usar LYRIUM BIO MARKETPLACE, aceptas los términos y
              condiciones.
            </div>
          </div>
        </section>

      </div>
    </div>





  </main>

  <!-- FLOATING DARK MODE TOGGLE -->
  <button id="darkModeToggle" class="dark-mode-toggle" aria-label="Alternar modo oscuro" title="Cambiar tema">
    <div class="icon-wrapper">
      <i class="ph-fill ph-moon moon-icon"></i>
      <i class="ph-fill ph-sun sun-icon"></i>
    </div>
  </button>

  <!-- MENÚ MÓVIL POP-UP (Movido al final) -->
  <button id="btnMobilePopup" class="mobile-popup-btn top-right lg:hidden" aria-label="Abrir índice">
    <i class="ph-list-bullets"></i> <span>Índice</span>
  </button>

  <div id="overlayMobilePopup" class="mobile-popup-overlay lg:hidden">
    <div class="mobile-popup-modal">
        <div class="mobile-popup-header">
            <span class="mobile-popup-title">Contenido</span>
            <button id="btnCloseMobilePopup" class="mobile-popup-close" aria-label="Cerrar">
                <i class="ph-x"></i>
            </button>
        </div>
        <div id="mobilePopupContent" class="mobile-popup-body">
            <?php if (isset($TERMINOS['cliente']) && is_array($TERMINOS['cliente'])): ?>
                <?php foreach ($TERMINOS['cliente'] as $t): ?>
                    <a href="#<?php echo $t['id']; ?>" class="mobile-popup-link">
                        <span><?php echo $t['title']; ?></span><i class="ph-caret-right"></i>
                    </a>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
  </div>

  <?php include 'footer.php'; ?>

  <!-- Scripts al final para asegurar que el DOM (incluido el popup) esté listo -->
  <script src="utils/js/terminoscondiciones.js?v=1.1"></script>
  <script src="utils/js/mobile-popup.js?v=1.1"></script>
  <script src="utils/js/share-modal.js?v=1.1"></script>
  <script src="utils/js/dark-mode-toggle.js?v=1.0"></script>

  <!-- MODAL DE COMPARTIR -->
  <div id="modalShareTerms" class="share-modal-overlay">
    <div class="share-modal">
      <div class="share-modal-header">
        <h3>Compartir contenido</h3>
        <button id="closeShareModal" class="share-modal-close"><i class="ph-x"></i></button>
      </div>
      <div class="share-modal-body">
        <div class="share-grid">
          <a href="#" id="shareWhatsapp" class="share-item">
            <div class="share-icon-wrap whatsapp"><i class="ph ph-whatsapp-logo"></i></div>
            <span>WhatsApp</span>
          </a>
          <a href="#" id="shareFacebook" class="share-item">
            <div class="share-icon-wrap facebook"><i class="ph ph-facebook-logo"></i></div>
            <span>Facebook</span>
          </a>
          <div class="share-item">
            <div class="share-icon-wrap copy"><i class="ph ph-link-simple"></i></div>
            <span>Enlace</span>
          </div>
        </div>
        
        <div class="share-copy-area">
          <span id="shareUrlInput" class="share-url-text">https://lyrium.com...</span>
          <button id="copyLinkBtn" class="share-copy-btn">
            <i class="ph ph-copy-simple"></i><span>Copiar</span>
          </button>
        </div>
      </div>
    </div>
  </div>