// Character counter
const messageTextarea = document.getElementById('message');
const charCounter = document.getElementById('charCounter');

if (messageTextarea && charCounter) {
    messageTextarea.addEventListener('input', function () {
        const length = this.value.length;
        charCounter.textContent = `${length} / 500`;
    });
}

// Form submission
const contactForm = document.getElementById('contactForm');
const successMessage = document.getElementById('successMessage');

if (contactForm && successMessage) {
    contactForm.addEventListener('submit', function (e) {
        e.preventDefault();

        // Clear previous errors
        document.querySelectorAll('.error-message').forEach(el => {
            el.classList.remove('active');
            el.textContent = '';
        });
        document.querySelectorAll('.form-input, .form-textarea').forEach(el => {
            el.classList.remove('error');
        });

        // Validate form
        let isValid = true;
        const formData = new FormData(this);

        // Validate name
        if (!formData.get('name').trim()) {
            showError('name', 'Por favor ingresa tu nombre');
            isValid = false;
        }

        // Validate email
        const email = formData.get('email');
        if (!email.trim() || !isValidEmail(email)) {
            showError('email', 'Por favor ingresa un correo válido');
            isValid = false;
        }

        // Validate subject
        if (!formData.get('subject').trim()) {
            showError('subject', 'Por favor ingresa un asunto');
            isValid = false;
        }

        // Validate message
        if (!formData.get('message').trim()) {
            showError('message', 'Por favor ingresa un mensaje');
            isValid = false;
        }

        // Validate privacy checkbox
        const privacyCheckbox = document.getElementById('privacy');
        if (privacyCheckbox && !privacyCheckbox.checked) {
            const privacyGroup = document.querySelector('.form-checkbox-group');
            const privacyError = privacyGroup.nextElementSibling;
            if (privacyError && privacyError.classList.contains('error-message')) {
                privacyError.textContent = 'Debes aceptar la política de privacidad';
                privacyError.classList.add('active');
            }
            isValid = false;
        }

        if (isValid) {
            // Show success message
            successMessage.classList.add('active');
            contactForm.reset();
            charCounter.textContent = '0 / 500';

            // Hide success message after 5 seconds
            setTimeout(() => {
                successMessage.classList.remove('active');
            }, 5000);
        }
    });
}

function showError(fieldId, message) {
    const field = document.getElementById(fieldId);
    if (!field) return;

    const errorDiv = field.parentElement.querySelector('.error-message');

    field.classList.add('error');
    if (errorDiv) {
        errorDiv.textContent = message;
        errorDiv.classList.add('active');
    }
}

function isValidEmail(email) {
    return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
}
