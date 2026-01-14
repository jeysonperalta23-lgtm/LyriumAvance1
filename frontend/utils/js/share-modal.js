/**
 * Lyrium Share Modal Logic
 */

function initShareModal() {
    const btnShare = document.getElementById('btnShareTerms');
    const overlay = document.getElementById('modalShareTerms');
    const closeBtn = document.getElementById('closeShareModal');
    const copyBtn = document.getElementById('copyLinkBtn');
    const shareUrlInput = document.getElementById('shareUrlInput');

    if (!btnShare || !overlay) return;

    // Redes Sociales
    const btnWhatsapp = document.getElementById('shareWhatsapp');
    const btnFacebook = document.getElementById('shareFacebook');

    const getShareUrl = () => {
        // Obtenemos la URL base y añadimos el modo actual (hash)
        const baseUrl = window.location.origin + window.location.pathname;
        const activeTab = document.querySelector('.tc-tab-btn.active');
        const mode = activeTab ? activeTab.dataset.mode : 'cliente';
        return `${baseUrl}#${mode}`;
    };

    const openModal = () => {
        const url = getShareUrl();
        if (shareUrlInput) shareUrlInput.textContent = url;
        overlay.classList.add('active');
        document.body.style.overflow = 'hidden';
    };

    const closeModal = () => {
        overlay.classList.remove('active');
        document.body.style.overflow = '';

        // Reset copy button
        if (copyBtn) {
            copyBtn.innerHTML = '<i class="ph ph-copy-simple"></i><span>Copiar</span>';
            copyBtn.classList.remove('copied');
        }
    };

    btnShare.addEventListener('click', (e) => {
        e.preventDefault();
        openModal();
    });

    if (closeBtn) closeBtn.addEventListener('click', closeModal);

    overlay.addEventListener('click', (e) => {
        if (e.target === overlay) closeModal();
    });

    // Lógica de Copia
    if (copyBtn) {
        copyBtn.addEventListener('click', async () => {
            const url = getShareUrl();
            try {
                await navigator.clipboard.writeText(url);
                copyBtn.innerHTML = '<i class="ph ph-check-circle"></i><span>¡Copiado!</span>';
                copyBtn.classList.add('copied');

                setTimeout(() => {
                    copyBtn.innerHTML = '<i class="ph ph-copy-simple"></i><span>Copiar</span>';
                    copyBtn.classList.remove('copied');
                }, 2000);
            } catch (err) {
                console.error('Error al copiar:', err);
            }
        });
    }

    // WhatsApp
    if (btnWhatsapp) {
        btnWhatsapp.addEventListener('click', (e) => {
            e.preventDefault();
            const text = encodeURIComponent("Revisa los Términos y Condiciones de Lyrium Biomarketplace: " + getShareUrl());
            window.open(`https://api.whatsapp.com/send?text=${text}`, '_blank');
        });
    }

    // Facebook
    if (btnFacebook) {
        btnFacebook.addEventListener('click', (e) => {
            e.preventDefault();
            const url = encodeURIComponent(getShareUrl());
            window.open(`https://www.facebook.com/sharer/sharer.php?u=${url}`, '_blank');
        });
    }
}

// Inicializar cuando el DOM esté listo
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initShareModal);
} else {
    initShareModal();
}
