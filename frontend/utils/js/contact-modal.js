// ===================== CONTACT MODAL FUNCTIONALITY =====================

(function () {
    'use strict';

    // Initialize immediately if DOM is ready, otherwise wait
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initContactModal);
    } else {
        initContactModal();
    }

    function initContactModal() {
        const modal = document.getElementById('contactModal');
        const openBtn = document.getElementById('openContactModal');
        const openBtnsMobile = document.querySelectorAll('.openContactModal');
        const closeBtn = document.querySelector('.modal-close');
        const form = document.getElementById('contactModalForm');

        if (!modal) return;

        // Open modal - Desktop
        if (openBtn) {
            openBtn.addEventListener('click', function (e) {
                e.preventDefault();
                openModal();
            });
        }

        // Open modal - Mobile
        openBtnsMobile.forEach(btn => {
            btn.addEventListener('click', function (e) {
                e.preventDefault();
                openModal();
                closeMobileMenu();
            });
        });

        // Close modal
        if (closeBtn) {
            closeBtn.addEventListener('click', closeModal);
        }

        // Close when clicking outside modal
        modal.addEventListener('click', function (e) {
            if (e.target === modal) {
                closeModal();
            }
        });

        // Close on ESC key
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && modal.classList.contains('active')) {
                closeModal();
            }
        });

        // Form submission
        if (form) {
            form.addEventListener('submit', handleFormSubmit);

            // Character counter
            const messageField = document.getElementById('modalMessage');
            const charCounter = document.getElementById('modalCharCounter');
            if (messageField && charCounter) {
                messageField.addEventListener('input', function () {
                    const length = this.value.length;
                    charCounter.textContent = `${length} / 500`;
                    charCounter.style.color = length > 450 ? '#ef4444' : length > 350 ? '#f59e0b' : '#94a3b8';
                });
            }
        }

        function openModal() {
            modal.classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        function closeModal() {
            modal.classList.remove('active');
            document.body.style.overflow = '';

            // Optional: reset form after transition
            setTimeout(() => {
                if (!modal.classList.contains('active')) {
                    if (form) {
                        form.reset();
                        const charCounter = document.getElementById('modalCharCounter');
                        if (charCounter) {
                            charCounter.textContent = '0 / 500';
                            charCounter.style.color = '#94a3b8';
                        }
                    }
                }
            }, 300);
        }

        function closeMobileMenu() {
            const mobileMenu = document.getElementById('mobileMenu');
            const overlay = document.getElementById('mobileMenuOverlay');

            if (mobileMenu && mobileMenu.classList.contains('translate-x-0')) {
                mobileMenu.classList.add('-translate-x-full');
                mobileMenu.classList.remove('translate-x-0');
                if (overlay) {
                    setTimeout(() => overlay.classList.add('hidden'), 300);
                }
            }
        }

        function handleFormSubmit(e) {
            e.preventDefault();

            const fields = {
                name: document.getElementById('modalName'),
                email: document.getElementById('modalEmail'),
                subject: document.getElementById('modalSubject'),
                message: document.getElementById('modalMessage'),
                privacy: document.getElementById('modalPrivacy')
            };

            const isValid = validateField(fields.name, 'name', 3) &&
                validateField(fields.email, 'email') &&
                validateField(fields.subject, 'subject', 5) &&
                validateField(fields.message, 'message', 10) &&
                validatePrivacy(fields.privacy);

            if (isValid) {
                submitForm();
            }
        }

        function validateField(field, type, minLength = 0) {
            if (!field) return false;

            const value = field.value.trim();
            let errorMsg = '';

            if (type === 'email') {
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!value) errorMsg = 'Por favor ingrese su correo electrónico';
                else if (!emailRegex.test(value)) errorMsg = 'Correo electrónico inválido';
            } else {
                if (!value) errorMsg = `Por favor ingrese ${type === 'name' ? 'su nombre' : type === 'subject' ? 'el asunto' : 'su mensaje'}`;
                else if (value.length < minLength) errorMsg = `Mínimo ${minLength} caracteres`;
            }

            if (errorMsg) {
                showError(field, errorMsg);
                return false;
            }

            clearError(field);
            return true;
        }

        function validatePrivacy(checkbox) {
            if (!checkbox || !checkbox.checked) {
                showError(checkbox, 'Debe aceptar la política de privacidad');
                return false;
            }
            clearError(checkbox);
            return true;
        }

        function showError(field, message) {
            if (!field) return;
            field.classList.add('error');

            let errorDiv = field.parentElement.querySelector('.error-message');
            if (!errorDiv) {
                errorDiv = document.createElement('div');
                errorDiv.className = 'error-message';
                field.parentElement.appendChild(errorDiv);
            }

            errorDiv.textContent = message;
            errorDiv.classList.add('active');
            field.style.animation = 'shake 0.3s ease';
            setTimeout(() => field.style.animation = '', 300);
        }

        function clearError(field) {
            if (!field) return;
            field.classList.remove('error');
            const errorDiv = field.parentElement.querySelector('.error-message');
            if (errorDiv) errorDiv.classList.remove('active');
        }

        function submitForm() {
            const submitBtn = form.querySelector('.form-submit');
            const originalText = submitBtn.innerHTML;

            submitBtn.classList.add('loading');
            submitBtn.innerHTML = '<span class="spinner"></span>';

            setTimeout(() => {
                submitBtn.classList.remove('loading');
                submitBtn.innerHTML = originalText;

                const successMsg = document.getElementById('modalSuccessMessage');
                if (successMsg) {
                    successMsg.classList.add('active');
                    setTimeout(() => successMsg.classList.remove('active'), 5000);
                }

                form.reset();
                const charCounter = document.getElementById('modalCharCounter');
                if (charCounter) {
                    charCounter.textContent = '0 / 500';
                    charCounter.style.color = '#94a3b8';
                }
            }, 1500);
        }
    }
})();
