<!-- Contact Modal -->
<div id="contactModal">
  <div class="modal-backdrop"></div>
  
  <div class="modal-container">
    <button class="modal-close" aria-label="Cerrar">
      <i class="ph-x"></i>
    </button>

    <div class="modal-content">
      <!-- Visual Section -->
      <div class="modal-visual">
        <div class="modal-visual-content">
          <div class="modal-icon">
            <i class="ph-chat-circle-dots"></i>
          </div>
          
          <h2>¡Contáctanos!</h2>
          <p>Estamos aquí para ayudarte. Envíanos tu consulta y te responderemos lo antes posible.</p>

          <div class="modal-contact-info">
            <div class="contact-info-item">
              <i class="ph-map-pin"></i>
              <span>Perú</span>
            </div>
            <div class="contact-info-item">
              <i class="ph-envelope-simple"></i>
              <span>ventas@lyriumbiomarketplace.com</span>
            </div>
            <div class="contact-info-item">
              <i class="ph-phone-call"></i>
              <span>+51 937 093 420</span>
            </div>
          </div>
        </div>
      </div>

      <!-- Form Section -->
      <div class="modal-form">
        <h3>Envíanos un mensaje</h3>
        <p class="modal-form-subtitle">Completa el formulario y nos pondremos en contacto contigo</p>

        <div id="modalSuccessMessage" class="success-message">
          <i class="ph-check-circle"></i>
          <div class="success-message-content">
            <h4>¡Mensaje enviado con éxito!</h4>
            <p>Nos pondremos en contacto contigo pronto.</p>
          </div>
        </div>

        <form id="contactModalForm">
          <div class="form-group">
            <label for="modalName">Nombre y Apellidos <span class="required">*</span></label>
            <input type="text" id="modalName" name="name" class="form-input" placeholder="Escribe tu nombre completo" required>
            <div class="error-message"></div>
          </div>

          <div class="form-group">
            <label for="modalEmail">Correo Electrónico <span class="required">*</span></label>
            <input type="email" id="modalEmail" name="email" class="form-input" placeholder="tu@email.com" required>
            <div class="error-message"></div>
          </div>

          <div class="form-group">
            <label for="modalSubject">Asunto <span class="required">*</span></label>
            <input type="text" id="modalSubject" name="subject" class="form-input" placeholder="¿En qué podemos ayudarte?" required>
            <div class="error-message"></div>
          </div>

          <div class="form-group">
            <label for="modalMessage">Mensaje <span class="required">*</span></label>
            <textarea id="modalMessage" name="message" class="form-textarea" placeholder="Escribe tu mensaje aquí..." maxlength="500" required></textarea>
            <div id="modalCharCounter" class="char-counter">0 / 500</div>
            <div class="error-message"></div>
          </div>

          <div class="form-checkbox-group">
            <input type="checkbox" id="modalPrivacy" name="privacy" class="form-checkbox" required>
            <label for="modalPrivacy" class="form-checkbox-label">
              Acepto la <a href="politicasdeprivacidad.php" target="_blank">política de privacidad</a>
            </label>
            <div class="error-message"></div>
          </div>

          <button type="submit" class="form-submit">ENVIAR MENSAJE</button>
        </form>
      </div>
    </div>
  </div>
</div>

<link href="utils/css/contact-modal.css" rel="stylesheet">
