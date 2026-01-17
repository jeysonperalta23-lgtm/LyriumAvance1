<?php include 'header.php'; ?>

<div class="contact-page-wrapper">
    <div class="contact-container">
        <div class="contact-content">
            <!-- Visual Section -->
            <div class="contact-visual">
                <div class="contact-visual-content">
                    <div class="contact-icon">
                        <i class="ph-chat-circle-dots"></i>
                    </div>
                    
                    <h2>¡Contáctanos!</h2>
                    <p>Estamos aquí para ayudarte. Envíanos tu consulta y te responderemos lo antes posible.</p>

                    <div class="contact-info">
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
                <div class="visual-lotus-icon">
                    <i class="ph ph-flower-lotus"></i>
                </div>
            </div>

            <!-- Form Section -->
            <div class="contact-form">
                <h3>Envíanos un mensaje</h3>
                <p class="contact-form-subtitle">Completa el formulario y nos pondremos en contacto contigo</p>

                <div id="successMessage" class="success-message">
                    <i class="ph-check-circle"></i>
                    <div class="success-message-content">
                        <h4>¡Mensaje enviado con éxito!</h4>
                        <p>Nos pondremos en contacto contigo pronto.</p>
                    </div>
                </div>

                <form id="contactForm">
                    <div class="form-group">
                        <label for="name">Nombre y Apellidos <span class="required">*</span></label>
                        <input type="text" id="name" name="name" class="form-input" placeholder="Escribe tu nombre completo" required>
                        <div class="error-message"></div>
                    </div>

                    <div class="form-group">
                        <label for="email">Correo Electrónico <span class="required">*</span></label>
                        <input type="email" id="email" name="email" class="form-input" placeholder="tu@email.com" required>
                        <div class="error-message"></div>
                    </div>

                    <div class="form-group">
                        <label for="subject">Asunto <span class="required">*</span></label>
                        <input type="text" id="subject" name="subject" class="form-input" placeholder="¿En qué podemos ayudarte?" required>
                        <div class="error-message"></div>
                    </div>

                    <div class="form-group">
                        <label for="message">Mensaje <span class="required">*</span></label>
                        <textarea id="message" name="message" class="form-textarea" placeholder="Escribe tu mensaje aquí..." maxlength="500" required></textarea>
                        <div id="charCounter" class="char-counter">0 / 500</div>
                        <div class="error-message"></div>
                    </div>

                    <div class="form-checkbox-group">
                        <input type="checkbox" id="privacy" name="privacy" class="form-checkbox" required>
                        <label for="privacy" class="form-checkbox-label">
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


<?php include 'footer.php'; ?>
