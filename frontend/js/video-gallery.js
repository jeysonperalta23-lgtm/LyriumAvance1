document.addEventListener('DOMContentLoaded', function () {
    console.log("Video Gallery Script Loaded");

    // --- Lightbox Functionality ---
    const videoLinks = document.querySelectorAll('#video-gallery .pa-gallery-lightbox-wrap');

    if (videoLinks.length > 0) {
        // Create modal HTML
        const modal = document.createElement('div');
        modal.id = 'video-lightbox-modal';
        modal.style.cssText = 'position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.9);z-index:999999;display:none;justify-content:center;align-items:center;flex-direction:column;';

        modal.innerHTML = `
            <div style="width:100%; max-width:900px; position:relative; padding: 20px;">
                <div style="position:relative; width:100%; padding-bottom:56.25%; height:0; background:#000;">
                    <iframe id="lightbox-iframe" 
                        style="position:absolute; top:0; left:0; width:100%; height:100%;" 
                        src="" frameborder="0" 
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                        allowfullscreen>
                    </iframe>
                </div>
                <button id="close-lightbox" style="position:absolute; top:-30px; right:10px; background:none; border:none; color:#fff; font-size:30px; cursor:pointer; outline:none;">&times;</button>
            </div>
        `;

        document.body.appendChild(modal);

        const iframe = modal.querySelector('#lightbox-iframe');
        const closeBtn = modal.querySelector('#close-lightbox');

        const openModal = (url) => {
            if (url.indexOf('autoplay=1') === -1) {
                url += (url.indexOf('?') === -1) ? '?autoplay=1' : '&autoplay=1';
            }
            iframe.src = url;
            modal.style.display = 'flex';
            document.body.style.overflow = 'hidden';
        };

        const closeModal = () => {
            modal.style.display = 'none';
            iframe.src = '';
            document.body.style.overflow = '';
        };

        closeBtn.addEventListener('click', closeModal);
        modal.addEventListener('click', (e) => { if (e.target === modal) closeModal(); });
        document.addEventListener('keydown', (e) => { if (e.key === 'Escape' && modal.style.display === 'flex') closeModal(); });

        videoLinks.forEach(link => {
            link.addEventListener('click', function (e) {
                e.preventDefault();
                e.stopPropagation();
                let url = this.getAttribute('href');
                if (url) openModal(url);
            });
        });
    }

    // --- Filtering and Load More/Less Functionality ---
    const loadMoreBtn = document.querySelector('.premium-gallery-load-more-btn');
    const filterButtons = document.querySelectorAll('#premium-img-gallery-b959645 .category');
    const galleryItems = document.querySelectorAll('.premium-gallery-item');
    let isExpanded = false;
    let activeFilter = '*';

    function updateGalleryVisibility() {
        let visibleCount = 0;
        const maxInitial = 6;

        galleryItems.forEach(item => {
            const matchesFilter = activeFilter === '*' || item.classList.contains(activeFilter.substring(1));

            if (matchesFilter) {
                visibleCount++;
                if (activeFilter === '*') {
                    // In "All" mode, respect the expand/collapse state
                    if (visibleCount <= maxInitial || isExpanded) {
                        item.classList.remove('hidden-video-item');
                        item.style.display = 'block';
                    } else {
                        item.classList.add('hidden-video-item');
                        item.style.display = 'none';
                    }
                } else {
                    // In specific category mode, show all matches and hide "Load More"
                    item.classList.remove('hidden-video-item');
                    item.style.display = 'block';
                }
            } else {
                item.style.display = 'none';
                item.classList.add('hidden-video-item');
            }
        });

        // Update Button Visibility and Text
        if (loadMoreBtn) {
            if (activeFilter !== '*') {
                loadMoreBtn.parentElement.style.display = 'none';
            } else {
                loadMoreBtn.parentElement.style.display = 'flex';
                loadMoreBtn.textContent = isExpanded ? 'Cargar menos' : 'Cargar más';
                loadMoreBtn.style.opacity = '1';
                loadMoreBtn.style.pointerEvents = 'auto';
                loadMoreBtn.style.display = 'inline-block';
            }
        }
    }

    // Filter Button Clicks
    filterButtons.forEach(btn => {
        btn.addEventListener('click', function () {
            filterButtons.forEach(b => {
                b.classList.remove('bg-sky-500', 'text-white', 'shadow-lg');
                b.classList.add('bg-white', 'text-slate-600', 'border', 'border-slate-200');
            });
            this.classList.remove('bg-white', 'text-slate-600', 'border', 'border-slate-200');
            this.classList.add('bg-sky-500', 'text-white', 'shadow-lg');

            activeFilter = this.getAttribute('data-filter');
            isExpanded = false; // Reset expand state when changing filters
            updateGalleryVisibility();
        });
    });

    // Load More/Less Click
    if (loadMoreBtn) {
        loadMoreBtn.addEventListener('click', function (e) {
            e.preventDefault();
            isExpanded = !isExpanded;

            if (isExpanded) {
                // Animate items showing up
                const hiddenNow = document.querySelectorAll('.hidden-video-item');
                hiddenNow.forEach((item, index) => {
                    item.classList.remove('hidden-video-item');
                    item.style.display = 'block';
                    item.style.opacity = '0';
                    item.style.transform = 'translateY(20px)';

                    setTimeout(() => {
                        item.style.transition = 'all 0.6s cubic-bezier(0.23, 1, 0.32, 1)';
                        item.style.opacity = '1';
                        item.style.transform = 'translateY(0)';
                    }, index * 50);
                });
            } else {
                // Collapse and scroll back up to the gallery
                updateGalleryVisibility();
                const galleryTop = document.getElementById('premium-img-gallery-b959645').offsetTop - 100;
                window.scrollTo({ top: galleryTop, behavior: 'smooth' });
            }

            this.textContent = isExpanded ? 'Cargar menos' : 'Cargar más';
        });
    }

    // Initial run to set state
    updateGalleryVisibility();
});
