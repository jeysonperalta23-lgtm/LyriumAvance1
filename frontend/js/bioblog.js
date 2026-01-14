document.addEventListener("DOMContentLoaded", () => {
	document.querySelectorAll(".wpr-anim-text").forEach(wrapper => {
		const items = wrapper.querySelectorAll("b");
		if (items.length === 0) return;

		const animDurationAttr = wrapper.dataset.animDuration || "1200,2000";
		const durations = animDurationAttr.split(",").map(Number);

		const animIn = durations[0] || 1200;
		const delay = durations[1] || 2000;
		const loop = wrapper.dataset.animLoop === "yes";

		let index = 0;

		// Asegura que solo uno esté activo inicialmente
		items.forEach((el, i) => {
			el.classList.toggle("wpr-active", i === 0);
		});

		function switchText() {
			items[index].classList.remove("wpr-active");
			index = (index + 1) % items.length;
			items[index].classList.add("wpr-active");
		}

		if (loop && items.length > 1) {
			setInterval(switchText, animIn + delay);
		}
	});

	// Podcast Text Animation Loop (REFINED Zoom In/Out Effect)
	document.querySelectorAll(".wpr-anim-text-inner").forEach(inner => {
		const items = Array.from(inner.children);
		if (items.length <= 1) return;

		inner.style.position = 'relative';
		inner.style.height = '100%';

		items.forEach((item, index) => {
			item.style.position = 'absolute';
			item.style.top = '0';
			item.style.left = '0';
			item.style.width = '100%';
			item.style.height = '100%';
			item.style.display = 'flex';
			item.style.alignItems = 'center';

			// Force absolute stacking and visibility control
			item.style.opacity = index === 0 ? '1' : '0';
			item.style.transform = index === 0 ? 'scale(1)' : 'scale(0.8)';
			item.style.transition = 'opacity 1000ms cubic-bezier(0.4, 0, 0.2, 1), transform 1000ms cubic-bezier(0.34, 1.56, 0.64, 1)';
			item.style.pointerEvents = index === 0 ? 'auto' : 'none';
		});

		let currentIndex = 0;
		const cycleDelay = 4500; // Increased to let the animation breathe

		function runZoomEffect() {
			const current = items[currentIndex];
			currentIndex = (currentIndex + 1) % items.length;
			const next = items[currentIndex];

			// 1. Zoom Out (Disminuyendo) & Fade Out current item
			// Usamos 1500ms para una animación más lenta y fluida
			current.style.transition = 'opacity 1500ms ease-in-out, transform 1500ms ease-in-out';
			current.style.opacity = '0';
			current.style.transform = 'scale(0.8)'; // La oración vieja "va disminuyendo"
			current.style.pointerEvents = 'none';

			// 2. Prepare next item (Viene grande)
			next.style.transition = 'none';
			next.style.opacity = '0';
			next.style.transform = 'scale(1.5)'; // La nueva oración "viene grande"

			// Use requestAnimationFrame for smooth sequencing
			requestAnimationFrame(() => {
				requestAnimationFrame(() => {
					// 3. Zoom In & Fade In next item to original size
					// Usamos 1500ms y un bezier suave para un efecto premium
					next.style.transition = 'opacity 1500ms cubic-bezier(0.23, 1, 0.32, 1), transform 1500ms cubic-bezier(0.23, 1, 0.32, 1)';
					next.style.opacity = '1';
					next.style.transform = 'scale(1)'; // Se acomoda al tamaño original
					next.style.pointerEvents = 'auto';
				});
			});
		}

		let intervalId = setInterval(runZoomEffect, cycleDelay);

		// FIX: Page Visibility API to pause animation on tab switch
		document.addEventListener("visibilitychange", () => {
			if (document.hidden) {
				clearInterval(intervalId);
			} else {
				// Clean state: ensure only active index is visible
				items.forEach((item, idx) => {
					item.style.transition = 'none';
					item.style.opacity = idx === currentIndex ? '1' : '0';
					item.style.transform = idx === currentIndex ? 'scale(1)' : 'scale(0.8)';
					item.style.pointerEvents = idx === currentIndex ? 'auto' : 'none';
				});
				// Restart interval
				clearInterval(intervalId);
				intervalId = setInterval(runZoomEffect, cycleDelay);
			}
		});
	});
});