<?php
session_start();
?>

<?php include 'header.php'; ?>

<!-- Extra Assets for BioBlog -->
<link rel="stylesheet" href="utils/css/video-gallery.css?v=<?php echo time(); ?>">
<link rel="stylesheet" href="utils/css/ui-components.css?v=<?php echo time(); ?>">
<link rel="stylesheet" href="utils/css/comments.css?v=<?php echo time(); ?>">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.css" />
<link rel="stylesheet" href="utils/css/slider.css?v=<?php echo time(); ?>">


<link rel="stylesheet" href="utils/css/bioblog-clean.css?v=<?php echo time(); ?>">
<script type="application/ld+json"
	class="yoast-schema-graph">{"@context":"https://schema.org","@graph":[{"@type":"WebPage","@id":"https://lyriumbiomarketplace.com/blog/","url":"https://lyriumbiomarketplace.com/blog/","name":"Blog - Lyrium BioMarketplace","isPartOf":{"@id":"https://lyriumbiomarketplace.com/#website"},"primaryImageOfPage":{"@id":"https://lyriumbiomarketplace.com/blog/#primaryimage"},"image":{"@id":"https://lyriumbiomarketplace.com/blog/#primaryimage"},"thumbnailUrl":"img/img-blog/lyriumbiomarketplace.com/wp-content/uploads/2024/07/ICON.png","datePublished":"2025-04-23T13:24:30+00:00","dateModified":"2025-10-09T02:32:10+00:00","breadcrumb":{"@id":"https://lyriumbiomarketplace.com/blog/#breadcrumb"},"inLanguage":"es","potentialAction":[{"@type":"ReadAction","target":["https://lyriumbiomarketplace.com/blog/"]}]},{"@type":"ImageObject","inLanguage":"es","@id":"https://lyriumbiomarketplace.com/blog/#primaryimage","url":"img/img-blog/lyriumbiomarketplace.com/wp-content/uploads/2024/07/ICON.png","contentUrl":"img/img-blog/lyriumbiomarketplace.com/wp-content/uploads/2024/07/ICON.png","width":512,"height":512},{"@type":"BreadcrumbList","@id":"https://lyriumbiomarketplace.com/blog/#breadcrumb","itemListElement":[{"@type":"ListItem","position":1,"name":"Portada","item":"https://lyriumbiomarketplace.com/"},{"@type":"ListItem","position":2,"name":"Blog"}]},{"@type":"WebSite","@id":"https://lyriumbiomarketplace.com/#website","url":"https://lyriumbiomarketplace.com/","name":"Lyrium Biomarketplace","description":"Descubre nuestro marketplace confiable para productos saludables y de calidad. Compra con tranquilidad y apoya un estilo de vida equilibrado. ¡Tu bienestar, nuestra prioridad!","publisher":{"@id":"https://lyriumbiomarketplace.com/#organization"},"alternateName":"Lyrium Biomarketplace","potentialAction":[{"@type":"SearchAction","target":{"@type":"EntryPoint","urlTemplate":"https://lyriumbiomarketplace.com/?s={search_term_string}"},"query-input":{"@type":"PropertyValueSpecification","valueRequired":true,"valueName":"search_term_string"}}],"inLanguage":"es"},{"@type":"Organization","@id":"https://lyriumbiomarketplace.com/#organization","name":"Lyrium Biomarketplace","alternateName":"Lyrium Biomarketplace","url":"https://lyriumbiomarketplace.com/"}]}</script>




<div id="bioblog-wrapper"
	class="w-full max-w-7xl mx-auto px-4 py-6 md:py-10 flex-1 space-y-10 md:space-y-16 overflow-x-hidden">

	<div>

		<div class="lr-hero">
			<div class="lr-pill animate-in !w-full !flex justify-center">
				<i class="ph-newspaper text-[17px]"></i>
				<span class="text-[18px] md:text-[28px]">BioBlog</span>
			</div>
		</div>

		<div
			class="relative w-full flex flex-col md:flex-row min-h-[500px] md:min-h-[600px] overflow-hidden rounded-3xl shadow-2xl bg-gradient-to-br from-[#f8fafc] to-[#f1f5f9] mt-6 md:mt-[50px]">
			<!-- Contenido Izquierdo (Texto y Branding) -->
			<div
				class="relative z-10 flex-1 flex flex-col justify-center p-6 md:p-16 lg:p-24 space-y-8 bg-gradient-to-br from-[#CEEDFA] to-transparent">
				<!-- Logo/Icono -->
				<div class="w-20 h-20 md:w-32 md:h-32 transition-transform duration-500 hover:scale-110">
					<img fetchpriority="high" decoding="async" width="512" height="512"
						src="img/img-blog/lyriumbiomarketplace.com/wp-content/uploads/2024/07/ICON.png"
						class="w-full h-full object-contain drop-shadow-xl" alt="Lyrium Logo"
						srcset="img/img-blog/lyriumbiomarketplace.com/wp-content/uploads/2024/07/ICON.png 512w, img/img-blog/lyriumbiomarketplace.com/wp-content/uploads/2024/07/ICON-300x300.png 300w, img/img-blog/lyriumbiomarketplace.com/wp-content/uploads/2024/07/ICON-100x100.png 100w"
						sizes="(max-width: 512px) 100vw, 512px" />
				</div>

				<!-- Título Dual -->
				<div class="space-y-4">
					<h2 class="text-2xl md:text-5xl lg:text-6xl font-black tracking-tight text-slate-800 leading-tight">
						<span class="block text-slate-500 font-medium text-lg md:text-3xl mb-1">Bienvenidos</span>
						<span class="bg-clip-text text-transparent bg-gradient-to-r from-lime-600 to-emerald-600">Lyrium
							BioBlog</span>
					</h2>
				</div>

				<!-- Descripción -->
				<div class="max-w-xl">
					<p class="text-base md:text-xl text-slate-600 leading-relaxed font-light text-justify">
						En este espacio encontrarás información confiable, práctica y
						actualizada para mejorar tu salud, bienestar y calidad de vida.
						Somos un equipo comprometido con el cuidado integral, donde el
						conocimiento es la herramienta clave.
					</p>
				</div>


			</div>

			<!-- Imagen Derecha (Decorativa) -->
			<div class="relative flex-1 hidden md:block">
				<div class="absolute inset-0 bg-gradient-to-l from-transparent to-[#f8fafc]/50 z-10">
				</div>
				<div class="w-full h-full bg-cover bg-center transition-transform duration-700 hover:scale-105"
					style="background-image: url('img/img-blog/lyriumbiomarketplace.com/wp-content/uploads/2025/10/Fondos_BioBlog-6.webp');">
				</div>
			</div>
		</div>
		<!-- Sección de Búsqueda y Filtros -->
		<div class="w-full max-w-5xl mx-auto px-2 md:px-4 -mt-8 md:-mt-12 relative z-20">
			<div class="bg-white/90 backdrop-blur-xl p-4 md:p-8 rounded-[2rem] shadow-2xl border border-white/20">
				<!-- Barra de Búsqueda -->
				<form action="https://lyriumbiomarketplace.com/" method="get" class="relative group">
					<div class="relative flex items-center">
						<input id="search-29aabd30" type="search" name="s"
							placeholder="¿Qué deseas buscar para mejorar tu salud?"
							class="w-full pl-12 pr-24 md:pl-16 md:pr-32 py-4 md:py-5 bg-slate-100/50 border-none rounded-2xl text-slate-700 placeholder:text-slate-400 focus:ring-2 focus:ring-sky-500 transition-all duration-300 text-sm md:text-lg shadow-inner">

						<button type="submit"
							class="absolute right-1.5 px-3 py-2 md:right-3 md:px-6 md:py-3 bg-sky-500 hover:bg-sky-600 text-white text-[10px] md:text-base font-semibold rounded-xl transition-all duration-300 transform active:scale-95 shadow-md">
							Buscar
						</button>
					</div>
					<input type="hidden" name="e_search_props" value="29aabd30-12317">
				</form>

				<div class="mt-6 md:mt-8">
					<div class="flex items-center space-x-2 md:space-x-4 overflow-x-auto pb-4 scrollbar-hide no-scrollbar"
						id="category-filters-container">
						<button
							class="flex-shrink-0 px-4 py-2 md:px-6 md:py-2.5 bg-sky-500 text-white rounded-full text-xs md:text-sm font-semibold shadow-md active:scale-95 transition-all">Todos</button>
						<a href="https://lyriumbiomarketplace.com/salud-alimentaria/"
							class="flex-none px-6 py-2.5 bg-white text-slate-600 border border-slate-200 rounded-full font-medium hover:border-sky-500 hover:text-sky-500 transition-all">Salud
							Alimentaria</a>
						<a href="https://lyriumbiomarketplace.com/salud-ambiental/"
							class="flex-none px-6 py-2.5 bg-white text-slate-600 border border-slate-200 rounded-full font-medium hover:border-sky-500 hover:text-sky-500 transition-all">Salud
							Ambiental</a>
						<a href="https://lyriumbiomarketplace.com/salud-emocional/"
							class="flex-none px-6 py-2.5 bg-white text-slate-600 border border-slate-200 rounded-full font-medium hover:border-sky-500 hover:text-sky-500 transition-all">Salud
							Emocional</a>
						<a href="https://lyriumbiomarketplace.com/salud-espiritual/"
							class="flex-none px-6 py-2.5 bg-white text-slate-600 border border-slate-200 rounded-full font-medium hover:border-sky-500 hover:text-sky-500 transition-all">Salud
							Espiritual</a>
						<a href="https://lyriumbiomarketplace.com/salud-familiar/"
							class="flex-none px-6 py-2.5 bg-white text-slate-600 border border-slate-200 rounded-full font-medium hover:border-sky-500 hover:text-sky-500 transition-all">Salud
							Familiar</a>
					</div>
				</div>
			</div>
		</div>

		<!-- Sección de Publicaciones Header -->
		<div class="pt-16 pb-8 text-center max-w-4xl mx-auto px-4">
			<div class="flex items-center justify-center space-x-3 mb-4">
				<span class="h-px w-12 bg-lime-500"></span>
				<span class="text-lime-600 font-bold tracking-widest text-sm uppercase">Novedades</span>
				<span class="h-px w-12 bg-lime-500"></span>
			</div>
			<h3 class="text-3xl md:text-5xl font-black text-slate-800 mb-6 drop-shadow-sm uppercase">
				PUBLICACIONES</h3>
			<p class="text-slate-600 text-lg leading-relaxed font-light text-justify">
				Explora nuestro blog y mantente al día con artículos sobre vida saludable,
				sostenibilidad, alimentación ecológica y consejos para aprovechar al máximo
				los productos bio disponibles en nuestro marketplace.
			</p>
		</div>
		<!-- Slider Destacado (Hero Carousel - Infinite Loop) -->
		<div class="w-full pb-16 px-4 max-w-[1920px] mx-auto overflow-hidden">
			<div class="relative px-0 md:px-12" id="upk-hero-carousel">

				<!-- Custom Navigation (Big Arrows) -->
				<div id="hero-prev-btn"
					class="absolute left-2 md:left-4 top-1/2 -translate-y-1/2 z-40 p-3 rounded-full bg-white/10 backdrop-blur-md hover:bg-sky-500 text-white cursor-pointer transition-all duration-300 hidden md:flex items-center justify-center group">
					<svg class="w-8 h-8 transform group-hover:-translate-x-1 transition-transform" fill="none"
						stroke="currentColor" viewBox="0 0 24 24">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 19l-7-7 7-7">
						</path>
					</svg>
				</div>
				<div id="hero-next-btn"
					class="absolute right-2 md:right-4 top-1/2 -translate-y-1/2 z-40 p-3 rounded-full bg-white/10 backdrop-blur-md hover:bg-sky-500 text-white cursor-pointer transition-all duration-300 hidden md:flex items-center justify-center group">
					<svg class="w-8 h-8 transform group-hover:translate-x-1 transition-transform" fill="none"
						stroke="currentColor" viewBox="0 0 24 24">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5l7 7-7 7"></path>
					</svg>
				</div>

				<div class="swiper-carousel swiper w-full h-[550px] md:h-[650px] overflow-visible">
					<div class="swiper-wrapper">
						<?php
						$snog_items = [
							[
								'img' => "img/img-blog/lyriumbiomarketplace.com/wp-content/uploads/2025/05/laptop.jpg",
								'alt' => "Guía práctica para llevar tu negocio al mundo digital sin complicaciones",
								'cat' => "SALUD ALIMENTARIA",
								'title' => "Guía práctica para llevar tu negocio al mundo digital sin complicaciones",
								'url' => "https://lyriumbiomarketplace.com/guia-practica-para-llevar-tu-negocio-al-mundo-digital-sin-complicaciones/",
								'date' => "MAYO 20, 2025",
								'excerpt' => "Descubre las estrategias clave para digitalizar tu negocio de alimentación saludable..."
							],
							[
								'img' => "img/img-blog/lyriumbiomarketplace.com/wp-content/uploads/2025/05/joven-sentado.jpg",
								'alt' => "¿Tienes una tienda física? Esto es lo que necesitas para vender por internet",
								'cat' => "SALUD AMBIENTAL",
								'title' => "¿Tienes una tienda física? Vende por internet fácilmente",
								'url' => "https://lyriumbiomarketplace.com/tienes-una-tienda-fisica-esto-es-lo-que-necesitas-para-vender-por-internet/",
								'date' => "MAYO 20, 2025",
								'excerpt' => "Transición ecológica y digital: todo lo que necesitas saber para expandir tu alcance..."
							],
							[
								'img' => "img/img-blog/lyriumbiomarketplace.com/wp-content/uploads/2025/05/chica-sentada.jpg",
								'alt' => "El poder de la meditación",
								'cat' => "SALUD EMOCIONAL",
								'title' => "El poder de la meditación para una vida plena",
								'url' => "https://lyriumbiomarketplace.com/el-poder-de-la-meditacion/",
								'date' => "MAYO 4, 2025",
								'excerpt' => "Encuentra el equilibrio interior y mejora tu bienestar emocional con estas técnicas..."
							],
							[
								'img' => "img/img-blog/lyriumbiomarketplace.com/wp-content/uploads/2025/05/blog-teclas.jpg",
								'alt' => "Cómo elegir el mejor método de pago para tu eCommerce",
								'cat' => "SALUD AMBIENTAL",
								'title' => "Métodos de pago sostenibles para tu eCommerce",
								'url' => "https://lyriumbiomarketplace.com/entrada-5/",
								'date' => "ABRIL 24, 2025",
								'excerpt' => "Optimiza tus transacciones y reduce la huella de carbono digital..."
							]
						];

						foreach ($snog_items as $item): ?>
							<div
								class="upk-item swiper-slide w-full md:w-[85%] lg:w-[70%] h-full transition-transform duration-500 scale-95 opacity-90 swiper-slide-active:scale-100 swiper-slide-active:opacity-100">
								<div
									class="relative w-full h-full rounded-[3rem] overflow-hidden shadow-2xl group cursor-pointer">
									<!-- Background Image with Zoom Effect -->
									<img src="<?= $item['img'] ?>"
										class="absolute inset-0 w-full h-full object-cover transition-transform duration-1000 group-hover:scale-110"
										alt="<?= $item['alt'] ?>">

									<!-- Gradient Overlay -->
									<div
										class="absolute inset-0 bg-gradient-to-r from-slate-900/90 via-slate-900/40 to-transparent">
									</div>

									<!-- Content Container -->
									<div
										class="absolute inset-0 p-8 md:p-16 flex flex-col justify-end md:justify-center items-start max-w-2xl">
										<!-- Category Pill -->
										<div class="mb-6 overflow-hidden">
											<span
												class="inline-block px-4 py-1.5 bg-sky-500/20 backdrop-blur-md border border-sky-400/30 text-sky-300 rounded-full text-xs font-black tracking-widest uppercase transform transition-transform duration-500 translate-y-0 group-hover:-translate-y-1">
												<?= $item['cat'] ?>
											</span>
										</div>

										<!-- Title -->
										<h2
											class="text-3xl md:text-5xl lg:text-6xl font-black text-white leading-[1.1] mb-6 drop-shadow-lg opacity-0 translate-y-8 animate-[fadeInUp_0.8s_ease-out_forwards]">
											<a href="<?= $item['url'] ?>"
												class="hover:text-sky-400 transition-colors duration-300">
												<?= $item['title'] ?>
											</a>
										</h2>

										<!-- Date & Excerpt -->
										<div
											class="flex flex-col gap-4 opacity-0 translate-y-8 animate-[fadeInUp_0.8s_ease-out_0.2s_forwards]">
											<div
												class="flex items-center gap-3 text-slate-300 text-xs font-bold tracking-widest uppercase">
												<span class="text-sky-500">BY LYRIUM</span>
												<span class="w-1 h-1 rounded-full bg-slate-500"></span>
												<span><?= $item['date'] ?></span>
											</div>
											<p class="text-slate-300 text-lg line-clamp-2 max-w-lg hidden md:block">
												<?= $item['excerpt'] ?>
											</p>
										</div>

										<!-- Call to Action -->
										<div
											class="mt-8 opacity-0 translate-y-8 animate-[fadeInUp_0.8s_ease-out_0.4s_forwards]">
											<a href="<?= $item['url'] ?>"
												class="inline-flex items-center gap-2 text-white font-bold tracking-widest text-sm hover:gap-4 transition-all duration-300 group/btn">
												LEER ARTÍCULO <i
													class="fas fa-arrow-right text-sky-500 group-hover/btn:text-white transition-colors"></i>
											</a>
										</div>
									</div>
								</div>
							</div>
						<?php endforeach; ?>
					</div>
					<div class="swiper-pagination !bottom-8 !w-auto !left-8 md:!left-16 !right-auto"></div>
				</div>
			</div>
		</div>


		<!-- Carrusel Alter (Post Grid - Novedades) -->
		<!-- Diseño basado en Imagen 1: Imagen arriba, texto limpio abajo, sin bordes de tarjeta visibles, tipografía específica -->
		<div class="w-full py-16 px-4 max-w-[1600px] mx-auto">
			<div class="relative md:px-12" id="upk-alter-carousel-f868512">
				<!-- Custom Navigation Arrows -->
				<div id="alter-prev-btn"
					class="hidden md:block absolute md:left-0 top-1/2 -translate-y-1/2 text-slate-400 hover:text-sky-500 transition-colors cursor-pointer z-50 p-2">
					<svg class="w-8 h-8 md:w-10 md:h-10 transform rotate-180" fill="none" stroke="currentColor"
						viewBox="0 0 24 24">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M14 5l7 7-7 7">
						</path>
					</svg>
				</div>
				<div id="alter-next-btn"
					class="hidden md:block absolute md:right-0 top-1/2 -translate-y-1/2 text-slate-400 hover:text-sky-500 transition-colors cursor-pointer z-50 p-2">
					<svg class="w-8 h-8 md:w-10 md:h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M14 5l7 7-7 7">
						</path>
					</svg>
				</div>

				<div class="swiper-carousel swiper overflow-visible">
					<div class="swiper-wrapper">
						<?php
						$alter_items = [
							[
								'img' => "img/img-blog/lyriumbiomarketplace.com/wp-content/uploads/2025/05/blog-teclas.jpg",
								'cat' => "SALUD EMOCIONAL",
								'title' => "El poder de la meditación",
								'url' => "https://lyriumbiomarketplace.com/el-poder-de-la-meditacion/",
								'date' => "MAYO 4, 2025",
								'excerpt' => "El poder de la meditación La meditación es una práctica milenaria que ha demostrado tener un impacto profundo en el"
							],
							[
								'img' => "img/img-blog/lyriumbiomarketplace.com/wp-content/uploads/2025/05/joven-sentado.jpg",
								'cat' => "SALUD AMBIENTAL",
								'title' => "Cómo elegir el mejor método de pago para tu eCommerce",
								'url' => "https://lyriumbiomarketplace.com/entrada-5/",
								'date' => "ABRIL 24, 2025",
								'excerpt' => "Explora las principales pasarelas de pago disponibles y aprende a seleccionar la más adecuada según las necesidades de tu tienda"
							],
							[
								'img' => "img/img-blog/lyriumbiomarketplace.com/wp-content/uploads/2025/10/Fondos_BioBlog-6.webp",
								'cat' => "SALUD ESPIRITUAL",
								'title' => "Guía rápida para nuevos vendedores en un marketplace",
								'url' => "https://lyriumbiomarketplace.com/entrada-4/",
								'date' => "ABRIL 24, 2025",
								'excerpt' => "¿Eres nuevo vendiendo en un marketplace como Dokan? Esta guía te ayudará a configurar tu tienda, subir productos y comenzar"
							],
							[
								'img' => "img/img-blog/lyriumbiomarketplace.com/wp-content/uploads/2025/05/laptop.jpg",
								'cat' => "SALUD FAMILIAR",
								'title' => "Cómo convertir tu tienda física en una tienda online",
								'url' => "https://lyriumbiomarketplace.com/entrada-3/",
								'date' => "ABRIL 21, 2025",
								'excerpt' => "En transformando tu negocio, descubre los pasos esenciales para migrar tu tienda física al entorno digital con éxito."
							],
						];

						foreach ($alter_items as $item): ?>
							<div class="upk-item swiper-slide h-auto">
								<div
									class="flex flex-col h-full group bg-white border border-slate-100 rounded-[2rem] p-5 shadow-sm hover:shadow-2xl hover:scale-[1.03] transition-all duration-500">
									<!-- Imagen: Rectangular, esquinas LIGERAMENTE redondeadas (no tanto como antes) -->
									<div class="relative w-full aspect-[16/10] overflow-hidden rounded-xl mb-5">
										<img src="<?= $item['img'] ?>"
											class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-700"
											alt="<?= $item['title'] ?>">
									</div>

									<!-- Contenido: Alineado a la izquierda, tipografía precisa -->
									<div class="flex flex-col flex-grow">
										<!-- Categoría: Azul Cian Fuerte + TODOS en gris -->
										<div class="flex items-center gap-2 mb-2">
											<span
												class="text-[10px] md:text-xs font-black text-sky-500 uppercase tracking-widest leading-none">
												<?= $item['cat'] ?>
											</span>
											<span
												class="text-[10px] md:text-xs font-bold text-slate-300 uppercase tracking-widest leading-none">
												TODOS
											</span>
										</div>

										<!-- Título: Oscuro, Grande, Bold -->
										<h3
											class="text-xl md:text-2xl font-bold text-slate-800 leading-tight mb-3 group-hover:text-sky-600 transition-colors">
											<a href="<?= $item['url'] ?>"><?= $item['title'] ?></a>
										</h3>

										<!-- Extracto: Gris, fuente normal -->
										<p
											class="text-slate-500 text-sm leading-relaxed mb-4 line-clamp-3 font-medium text-justify">
											<?= $item['excerpt'] ?>
										</p>

										<!-- Footer: LYRIUM | FECHA -->
										<div
											class="mt-auto text-[10px] font-bold text-slate-400 uppercase tracking-widest flex items-center gap-1">
											LYRIUM <span class="text-slate-300">|</span> <?= $item['date'] ?>
										</div>
									</div>
								</div>
							</div>
						<?php endforeach; ?>
					</div>
					<!-- Paginación relativa abajo -->
					<div class="swiper-pagination !relative !mt-8"></div>
				</div>
			</div>
		</div>

		<!-- Carrusel Ramble (Destacados - Image Background Style) -->
		<!-- Diseño basado en Imagen 0: Tarjeta oscura con imagen de fondo, círculo verde -->
		<div class="w-full py-16 px-4 max-w-[1600px] mx-auto bg-slate-50">
			<div class="relative md:px-12" id="upk-ramble-carousel-b564613">
				<!-- Navigation for Ramble -->
				<div class="hidden md:block absolute md:left-0 top-1/2 -translate-y-1/2 text-slate-400 hover:text-sky-500 transition-colors cursor-pointer z-50 p-2"
					id="ramble-prev-btn">
					<svg class="w-8 h-8 md:w-10 md:h-10 transform rotate-180" fill="none" stroke="currentColor"
						viewBox="0 0 24 24">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M14 5l7 7-7 7">
						</path>
					</svg>
				</div>
				<div class="hidden md:block absolute md:right-0 top-1/2 -translate-y-1/2 text-slate-400 hover:text-sky-500 transition-colors cursor-pointer z-50 p-2"
					id="ramble-next-btn">
					<svg class="w-8 h-8 md:w-10 md:h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M14 5l7 7-7 7">
						</path>
					</svg>
				</div>

				<div class="swiper-carousel swiper overflow-visible">
					<div class="swiper-wrapper">
						<?php foreach ($alter_items as $index => $item): ?>
							<div class="upk-item swiper-slide h-auto">
								<div
									class="relative w-full h-[450px] rounded-[2rem] overflow-hidden group cursor-pointer shadow-xl bg-slate-900 border border-white/10 transition-all duration-500 hover:scale-[1.03] hover:shadow-2xl">
									<!-- Background Image -->
									<img src="<?= $item['img'] ?>"
										class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-105 opacity-60"
										alt="<?= $item['title'] ?>">
									<!-- Dark Overlay (Subtle gradient) -->
									<div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-black/30">
									</div>

									<!-- Top Left: Green Circle & Metadata -->
									<div class="absolute top-8 left-8 flex flex-col items-start gap-2">
										<!-- Green Circle -->
										<div class="w-10 h-10 rounded-full bg-green-500 shadow-lg mb-1"></div>
										<!-- Meta -->
										<div class="flex flex-col">
											<span
												class="text-[10px] font-bold text-white uppercase tracking-widest leading-tight">LYRIUM</span>
											<span
												class="text-[9px] font-bold text-white/70 uppercase tracking-widest leading-tight"><?= $item['date'] ?></span>
										</div>
									</div>

									<!-- Bottom Content -->
									<div class="absolute inset-x-0 bottom-0 p-8 flex flex-col justify-end">
										<h3 class="text-3xl font-bold text-white leading-tight mb-3 drop-shadow-md">
											<?= $item['title'] ?>
										</h3>

										<p
											class="text-white/80 text-xs md:text-sm leading-relaxed mb-8 line-clamp-2 max-w-2xl text-justify">
											<?= $item['excerpt'] ?>
										</p>

										<!-- Footer Bar -->
										<div class="w-full flex justify-between items-center py-4 border-t border-white/20">
											<span
												class="text-xs font-bold text-white hover:text-sky-300 transition-colors">Leer
												Más</span>
											<span class="text-xs text-white/60 font-medium">0 Comments</span>
										</div>
									</div>
									<a href="<?= $item['url'] ?>" class="absolute inset-0 z-20"></a>
								</div>
							</div>
						<?php endforeach; ?>
					</div>
					<div class="swiper-pagination !relative !mt-8"></div>
				</div>
			</div>
		</div>
		<!-- Sección Podcast - Audios -->
		<div class="w-full py-16 bg-slate-50">
			<div class="max-w-7xl mx-auto px-4">
				<!-- Header Estandarizado -->
				<div class="pt-8 pb-12 text-center max-w-7xl mx-auto px-4">
					<div class="flex items-center justify-center space-x-3 mb-4">
						<span class="h-px w-12 bg-lime-500"></span>
						<span class="text-lime-600 font-bold tracking-widest text-sm uppercase">Lyrium</span>
						<span class="h-px w-12 bg-lime-500"></span>
					</div>
					<h3 class="text-3xl md:text-5xl font-black text-slate-800 mb-6 drop-shadow-sm uppercase">
						PODCAST - AUDIOS</h3>
					<p class="text-slate-600 text-base md:text-lg leading-relaxed font-light text-center max-w-5xl mx-auto">
						Escucha nuestros podcasts sobre vida ecológica, bienestar natural y
						sostenibilidad. Entrevistas con expertos, consejos prácticos y
						experiencias reales para inspirarte a llevar un estilo de vida más
						consciente y saludable.
					</p>
				</div>

				<div class="relative overflow-hidden rounded-3xl md:rounded-[3rem] p-6 md:p-20 shadow-2xl group">
					<!-- Imagen de Fondo -->
					<img src="img\img-blog\lyriumbiomarketplace.com\wp-content\uploads\2025\10\entrevista_doctora-scaled.webp"
						class="absolute inset-0 w-full h-full object-cover transition-transform duration-1000 group-hover:scale-110"
						alt="Podcast Background">

					<!-- Overlay de legibilidad -->
					<div
						class="absolute inset-0 bg-slate-900/60 group-hover:bg-slate-900/70 transition-colors duration-1000">
					</div>

					<div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-12">
						<div class="w-full flex-1 space-y-6">
							<h1 class="text-3xl md:text-6xl font-black !text-white leading-tight">
								Conecta <span class="!text-sky-400">Natural</span>
							</h1>

							<div class="w-full h-24 md:h-14 overflow-hidden relative">
								<div class="wpr-anim-text-inner w-full transition-all duration-1000">
									<div class="flex items-center space-x-3 text-sm md:text-2xl !text-white/90 w-full">
										<span class="text-3xl flex-shrink-0">🎙️</span>
										<span class="flex-1">Cada semana con
											un nuevo invitado.</span>
									</div>
									<div class="flex items-center space-x-3 text-sm md:text-2xl !text-white/90 w-full">
										<span class="text-3xl flex-shrink-0">🎧</span>
										<span class="flex-1">Escucha el último
											episodio</span>
									</div>
								</div>
							</div>

							<div class="w-24 h-px bg-white/30"></div>

							<p class="!text-white/80 text-lg max-w-md text-justify">
								Historias, consejos y conversaciones sobre consumo
								consciente, sostenibilidad y productos bio.
							</p>
						</div>

					</div>
				</div>
			</div>
		</div>

		<!-- Sección Podcast - Videos -->
		<div class="w-full py-16 bg-white">
			<div class="max-w-7xl mx-auto px-4">
				<!-- Header Estandarizado -->
				<div class="pt-8 pb-12 text-center max-w-7xl mx-auto px-4">
					<div class="flex items-center justify-center space-x-3 mb-4">
						<span class="h-px w-12 bg-lime-500"></span>
						<span class="text-lime-600 font-bold tracking-widest text-sm uppercase">Lyrium</span>
						<span class="h-px w-12 bg-lime-500"></span>
					</div>
					<h3 class="text-3xl md:text-5xl font-black text-slate-800 mb-6 drop-shadow-sm uppercase">
						PODCAST - VIDEOS</h3>
					<p class="text-slate-600 text-base md:text-lg leading-relaxed font-light text-center max-w-5xl mx-auto">
						Disfruta de nuestros videos sobre alimentación saludable, productos
						ecológicos y estilo de vida sostenible. Tutoriales, entrevistas y
						tips visuales para inspirarte a vivir de forma más natural y
						consciente.
					</p>
				</div>

				<!-- Panel Animado "Historias" -->
				<div class="relative overflow-hidden rounded-3xl md:rounded-[3rem] p-6 md:p-20 shadow-2xl group">
					<!-- Imagen de Fondo -->
					<img src="img\img-blog\lyriumbiomarketplace.com\wp-content\uploads\2025\10\Familia-en-picnic-scaled.webp"
						class="absolute inset-0 w-full h-full object-cover transition-transform duration-1000 group-hover:scale-110"
						alt="Historias Background">

					<!-- Overlay de legibilidad -->
					<div
						class="absolute inset-0 bg-slate-900/40 group-hover:bg-slate-900/50 transition-colors duration-1000">
					</div>

					<div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-12">
						<div class="w-full flex-1 space-y-6">
							<h1 class="text-3xl md:text-6xl font-black !text-white leading-tight">
								Historias que se ven y se sienten</span>
							</h1>
							<!-- Texto Animado Loop (Historias Text Animation) -->
							<div class="w-full h-24 md:h-14 overflow-hidden relative">
								<div class="wpr-anim-text-inner w-full transition-all duration-1000">
									<div class="flex items-center space-x-3 text-sm md:text-2xl !text-white/90 w-full">
										<span class="text-3xl flex-shrink-0">🎥</span>
										<span class="flex-1">Nuevos episodios
											cada semana.</span>
									</div>
									<div class="flex items-center space-x-3 text-sm md:text-2xl !text-white/90 w-full">
										<span class="text-3xl flex-shrink-0">📽️</span>
										<span class="flex-1">Desde el lente hacia
											el corazón.</span>
									</div>
									<div class="flex items-center space-x-3 text-sm md:text-2xl !text-white/90 w-full">
										<span class="text-3xl flex-shrink-0">🎧</span>
										<span class="flex-1">Conecta con el cambio visualmente</span>
									</div>
									<div class="flex items-center space-x-3 text-sm md:text-2xl !text-white/90 w-full">
										<span class="text-3xl flex-shrink-0">🌍</span>
										<span class="flex-1">Conecta, entiende y aprende en minutos.</span>
									</div>
									<div class="flex items-center space-x-3 text-sm md:text-2xl !text-white/90 w-full">
										<span class="text-3xl flex-shrink-0">💬</span>
										<span class="flex-1">Historias de vida
											que inspiran.</span>
									</div>
								</div>
							</div>

							<div class="w-24 h-px bg-white/30"></div>

							<p class="!text-white/80 text-lg max-w-md">
								Un espacio donde cada video cuenta una historia real,
								visibiliza un propósito y nos inspira a vivir de forma más
								consciente.
							</p>
						</div>
					</div>

				</div>
			</div>
		</div>
	</div>

	<!-- Sección Video Principal -->
	<div class="w-full pb-6 bg-white overflow-hidden">
		<div class="max-w-7xl mx-auto px-4">
			<div id="videobox-container"
				class="relative rounded-[2.5rem] overflow-hidden shadow-2xl bg-slate-900 aspect-video group">
				<!-- Embed Directo de YouTube -->
				<iframe class="absolute inset-0 w-full h-full border-0 transition-opacity duration-300"
					src="https://www.youtube.com/embed/wiJzsSP_5Ao?rel=0&modestbranding=1&autoplay=0"
					title="Video BioBlog"
					allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
					allowfullscreen>
				</iframe>
			</div>
		</div>
	</div>

	<!-- Galería de Videos Filtrable -->
	<div class="w-full py-1 bg-slate-50">
		<div class="max-w-7xl mx-auto px-4">
			<div id="premium-img-gallery-b959645" class="premium-img-gallery">

				<!-- Filtros de Categoría -->
				<div class="flex flex-wrap justify-center gap-3 mb-12">
					<?php
					$video_cats = [
						['id' => '*', 'name' => 'Todos los videos'],
						['id' => '.salud-alimentaria', 'name' => 'SALUD ALIMENTARIA'],
						['id' => '.salud-ambiental', 'name' => 'SALUD AMBIENTAL'],
						['id' => '.salud-emocional', 'name' => 'SALUD EMOCIONAL'],
						['id' => '.salud-espiritual', 'name' => 'SALUD ESPIRITUAL'],
						['id' => '.salud-familiar', 'name' => 'SALUD FAMILIAR'],
						['id' => '.salud-fisica', 'name' => 'SALUD FISICA'],
						['id' => '.salud-mental', 'name' => 'SALUD MENTAL'],
						['id' => '.salud-sexual', 'name' => 'SALUD SEXUAL'],
						['id' => '.salud-social', 'name' => 'SALUD SOCIAL'],
					];
					foreach ($video_cats as $index => $cat): ?>
						<button
							class="category px-5 py-2 rounded-full text-xs font-bold uppercase tracking-wider transition-all
							duration-300
							<?= $index === 0 ? 'bg-sky-500 text-white shadow-lg' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200' ?>"
							data-filter="<?= $cat['id'] ?>">
							<?= $cat['name'] ?>
						</button>
					<?php endforeach; ?>
				</div>

				<!-- Contenedor de la Galería -->
				<div class="premium-gallery-container grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8"
					id="video-gallery">
					<?php
					$video_items = [
						['title' => 'Mejorando mi calidad de vida', 'cat' => 'salud-alimentaria', 'cat_label' => 'SALUD ALIMENTARIA', 'video_id' => 'ACPkTAPJLnM'],
						['title' => 'Cambiar mis hábitos para bien', 'cat' => 'salud-alimentaria', 'cat_label' => 'SALUD ALIMENTARIA', 'video_id' => 'E0bria5w1lc'],
						['title' => 'Salud, Belleza y Bienestar', 'cat' => 'salud-alimentaria', 'cat_label' => 'SALUD ALIMENTARIA', 'video_id' => 'wiJzsSP_5Ao'],
						['title' => 'Impacto de las emociones en la salud', 'cat' => 'salud-espiritual', 'cat_label' => 'SALUD ESPIRITUAL', 'video_id' => '9reNgtVBcJ4'],
						['title' => 'La salud mental también es importante', 'cat' => 'salud-mental', 'cat_label' => 'SALUD MENTAL', 'video_id' => 'G2vjOVda6og'],
						['title' => 'COMIDA SALUDABLE', 'cat' => 'salud-alimentaria', 'cat_label' => 'SALUD ALIMENTARIA', 'video_id' => 'wiJzsSP_5Ao'],
						['title' => 'Relaciones y Salud Social', 'cat' => 'salud-social', 'cat_label' => 'SALUD SOCIAL', 'video_id' => 'wiJzsSP_5Ao'],
						['title' => 'Bienestar Emocional Diario', 'cat' => 'salud-emocional', 'cat_label' => 'SALUD EMOCIONAL', 'video_id' => 'ACPkTAPJLnM'],
						['title' => 'Nuevas Historias Inspiradoras', 'cat' => 'salud-familiar', 'cat_label' => 'SALUD FAMILIAR', 'video_id' => 'E0bria5w1lc'],
					];

					foreach ($video_items as $index => $video):
						$isHidden = $index >= 6 ? 'hidden-video-item' : ''; ?>
						<div class="premium-gallery-item <?= $video['cat'] ?> <?= $isHidden ?> group">
							<div
								class="relative rounded-[2rem] overflow-hidden shadow-md hover:shadow-2xl transition-all duration-500 border border-slate-100 bg-white">
								<!-- Miniatura -->
								<div class="aspect-video relative overflow-hidden">
									<img src="https://i.ytimg.com/vi/<?= $video['video_id'] ?>/maxresdefault.jpg"
										class="w-full h-full object-cover transform transition-transform duration-700 group-hover:scale-110"
										alt="<?= $video['title'] ?>">
									<div class="absolute inset-0 bg-black/20 group-hover:bg-black/40 transition-colors">
									</div>

									<!-- Botón Play -->
									<a class="absolute inset-0 flex items-center justify-center pa-gallery-lightbox-wrap
									pa-gallery-video-icon" href="https://www.youtube.com/embed/<?= $video['video_id'] ?>?feature=oembed&autoplay=1"
										data-rel="prettyPhoto[premium-grid-b959645]">
										<div class="w-16 h-16 bg-sky-500 text-white rounded-full flex items-center justify-center transform
											transition-all duration-500 scale-90 group-hover:scale-100 shadow-xl
											group-hover:shadow-sky-500/50">
											<svg class="w-8 h-8 ml-1" fill="currentColor" viewBox="0 0 20 20">
												<path d="M4.5 3L15.5 10L4.5 17V3Z"></path>
											</svg>
										</div>
									</a>

									<div class="absolute top-4 left-4">
										<span class="px-3 py-1 bg-white/90 backdrop-blur-md text-slate-800 text-[10px] font-bold rounded-full
											uppercase tracking-wider shadow-sm">
											<?= $video['cat_label'] ?>
										</span>
									</div>
								</div>

								<!-- Contenido -->
								<div class="p-6">
									<h3 class="text-base md:text-lg font-bold text-slate-800 leading-tight group-hover:text-sky-600
										transition-colors line-clamp-2">
										<?= $video['title'] ?>
									</h3>
								</div>
							</div>
						</div>
					<?php endforeach; ?>
				</div>

				<!-- Botón Cargar Más -->
				<div class="flex justify-center mt-12">
					<button
						class="px-8 py-3 bg-white text-slate-800 font-bold rounded-2xl shadow-md hover:shadow-xl transition-all border border-slate-100 transform active:scale-95 premium-gallery-load-more-btn">
						Cargar más
					</button>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="w-full pb-20 px-4 max-w-7xl mx-auto">
	<!-- Cabecera de Comentarios Premium -->
	<div class="flex flex-col items-center text-center">
		<div
			class="inline-flex items-center gap-2 px-4 py-2 bg-sky-100 text-sky-700 rounded-full text-xs font-bold uppercase tracking-widest mb-4 animate-bounce-subtle">
			<i class="ph-chat-teardrop-dots-bold text-lg"></i>
			Comunidad Lyrium
		</div>
		<h2 class="text-4xl md:text-5xl font-black text-slate-800 tracking-tight">
			Comparte tu <span
				class="text-transparent bg-clip-text bg-gradient-to-r from-sky-500 to-sky-600">Opinión</span>
		</h2>
		<div class="w-24 h-1.5 bg-gradient-to-r from-sky-400 to-sky-500 rounded-full mt-6"></div>
	</div>

	<!-- Sección de Comentarios Rediseñada (Tailwind CSS) -->
	<div id="comments-section"
		class="w-full bg-white/60 backdrop-blur-md border border-white/50 rounded-[2.5rem] p-6 md:p-12 shadow-xl mt-12 mb-20 relative overflow-hidden">
		<!-- Decoración de Fondo -->
		<div
			class="absolute top-0 right-0 w-64 h-64 bg-gradient-to-br from-sky-100/50 to-transparent rounded-bl-[100%] z-0 pointer-events-none">
		</div>

		<!-- Header: Título y Acciones -->
		<div
			class="relative z-10 flex flex-col md:flex-row justify-between items-end mb-10 gap-6 border-b border-slate-200/60 pb-6">
			<div>
				<h3 class="text-3xl font-black text-slate-800 tracking-tight flex items-center gap-3">
					<span class="text-sky-500">
						<svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
								d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z">
							</path>
						</svg>
					</span>
					Comentarios
				</h3>
				<p class="text-slate-500 text-sm font-medium mt-2 ml-1">Tu opinión es importante para nuestra comunidad.
				</p>
			</div>

			<div class="flex flex-wrap gap-3">
				<!-- Botón Suscribirse (Placeholder visual) -->
				<button type="button"
					class="group flex items-center gap-2 px-6 py-2.5 bg-gradient-to-r from-sky-500 to-sky-400 text-white text-[11px] font-black uppercase tracking-widest rounded-full transition-all duration-300 shadow-lg hover:shadow-sky-500/30 hover:scale-105 active:scale-95">
					<i
						class="far fa-envelope text-white/90 group-hover:scale-110 transition-transform duration-300 text-sm"></i>
					<span>Suscríbete</span>
				</button>
				<!-- Login Link -->
				<a href="https://lyriumbiomarketplace.com/mi-cuenta/"
					class="group flex items-center gap-2 px-6 py-2.5 bg-gradient-to-r from-sky-500 to-sky-400 text-white text-[11px] font-black uppercase tracking-widest rounded-full transition-all duration-300 shadow-lg hover:shadow-sky-500/30 hover:scale-105 active:scale-95">
					<i class="fas fa-sign-in-alt transition-transform duration-300 group-hover:translate-x-1"></i>
					<span>Iniciar Sesión</span>
				</a>
			</div>
		</div>

		<!-- Formulario Principal -->
		<form method="post" enctype="multipart/form-data" class="relative z-10 space-y-8">

			<!-- Editor de Comentario -->
			<div class="flex gap-4 md:gap-6 items-start">
				<!-- Avatar Artificial -->
				<div class="flex-shrink-0 hidden md:block">
					<div
						class="w-14 h-14 rounded-2xl bg-gradient-to-br from-slate-100 to-slate-200 border-2 border-white shadow-md flex items-center justify-center text-slate-400">
						<i class="fas fa-user text-xl"></i>
					</div>
				</div>

				<!-- Área de Texto con Diseño Premium -->
				<div class="flex-1 w-full relative group">
					<!-- Glow Effect focus -->
					<div
						class="absolute -inset-0.5 bg-gradient-to-r from-sky-400 to-emerald-400 rounded-2xl opacity-0 group-focus-within:opacity-20 transition duration-500 blur-sm">
					</div>

					<div
						class="relative bg-white rounded-2xl shadow-sm border border-slate-200 hover:border-sky-300 transition-colors duration-300 overflow-hidden group-focus-within:shadow-md group-focus-within:border-sky-400">
						<textarea name="wc_comment" id="wc_comment"
							class="w-full h-40 p-5 border-none focus:ring-0 text-slate-700 placeholder:text-slate-400/80 text-base leading-relaxed bg-transparent resize-y min-h-[160px]"
							placeholder="Comparte tu experiencia, dudas o sugerencias..."></textarea>

						<!-- Simulated Toolbar -->
						<div
							class="bg-slate-50/50 px-4 py-3 border-t border-slate-100 flex items-center gap-4 text-slate-400 text-sm">
							<div class="flex gap-2">
								<button type="button"
									class="p-1.5 hover:bg-white hover:text-sky-600 rounded transition-colors"
									title="Negrita"><i class="fas fa-bold"></i></button>
								<button type="button"
									class="p-1.5 hover:bg-white hover:text-sky-600 rounded transition-colors"
									title="Cursiva"><i class="fas fa-italic"></i></button>
								<button type="button"
									class="p-1.5 hover:bg-white hover:text-sky-600 rounded transition-colors"
									title="Subrayado"><i class="fas fa-underline"></i></button>
							</div>
							<div class="w-px h-4 bg-slate-200"></div>
							<div>
								<button type="button"
									class="flex items-center gap-2 px-3 py-1.5 bg-white border border-slate-200 hover:border-sky-300 text-xs font-bold text-slate-500 hover:text-sky-600 rounded-lg transition-all shadow-sm">
									<i class="far fa-image"></i>
									<span class="hidden sm:inline">Adjuntar Foto</span>
								</button>
							</div>
						</div>
					</div>
				</div>
			</div>

			<!-- Grid de Campos Personales -->
			<div class="grid grid-cols-1 md:grid-cols-3 gap-5">
				<!-- Nombre -->
				<div class="relative group">
					<div
						class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-sky-500 transition-colors z-10">
						<i class="fas fa-user text-sm"></i>
					</div>
					<input type="text" name="wc_name" placeholder="Tu Nombre *" required
						class="w-full pl-11 pr-4 py-3.5 bg-slate-50 hover:bg-white border border-slate-200 rounded-xl text-slate-700 text-sm font-medium focus:bg-white focus:outline-none focus:border-sky-500 focus:ring-4 focus:ring-sky-500/10 shadow-sm transition-all duration-300 placeholder:text-slate-400 transition-shadow">
				</div>

				<!-- Email -->
				<div class="relative group">
					<div
						class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-sky-500 transition-colors z-10">
						<i class="fas fa-at text-sm"></i>
					</div>
					<input type="email" name="wc_email" placeholder="Tu Email *" required
						class="w-full pl-11 pr-4 py-3.5 bg-slate-50 hover:bg-white border border-slate-200 rounded-xl text-slate-700 text-sm font-medium focus:bg-white focus:outline-none focus:border-sky-500 focus:ring-4 focus:ring-sky-500/10 shadow-sm transition-all duration-300 placeholder:text-slate-400 transition-shadow">
				</div>

				<!-- Website -->
				<div class="relative group">
					<div
						class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-sky-500 transition-colors z-10">
						<i class="fas fa-link text-sm"></i>
					</div>
					<input type="url" name="wc_website" placeholder="Sitio Web (Opcional)"
						class="w-full pl-11 pr-4 py-3.5 bg-slate-50 hover:bg-white border border-slate-200 rounded-xl text-slate-700 text-sm font-medium focus:bg-white focus:outline-none focus:border-sky-500 focus:ring-4 focus:ring-sky-500/10 shadow-sm transition-all duration-300 placeholder:text-slate-400 transition-shadow">
				</div>
			</div>

			<!-- Footer del Formulario -->
			<div
				class="flex flex-col-reverse md:flex-row justify-between items-center gap-6 pt-4 border-t border-slate-100">

				<!-- Checkbox Notificaciones -->
				<label
					class="group flex items-center space-x-3 cursor-pointer select-none px-2 py-1 rounded-lg hover:bg-slate-50 transition-colors">
					<div class="relative flex items-center">
						<input type="checkbox" name="wpdiscuz_notification_type" value="comment"
							class="peer w-5 h-5 cursor-pointer appearance-none rounded-md border-2 border-slate-300 checked:border-sky-500 checked:bg-sky-500 transition-all duration-200 bg-white">
						<i
							class="fas fa-check text-white text-[10px] absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 opacity-0 peer-checked:opacity-100 transition-opacity"></i>
					</div>
					<span class="text-sm font-semibold text-slate-500 group-hover:text-slate-700 transition-colors">
						Notificarme nuevas respuestas
					</span>
				</label>

				<!-- Botón Enviar -->
				<button type="submit" name="submit"
					class="relative overflow-hidden w-full md:w-auto px-10 py-4 bg-gradient-to-r from-sky-500 to-cyan-500 text-white font-black uppercase tracking-wider text-sm rounded-xl shadow-lg shadow-sky-500/30 hover:shadow-sky-500/50 hover:scale-105 active:scale-95 transition-all duration-300 group">
					<span class="relative z-10 flex items-center justify-center gap-2">
						Publicar Comentario
						<i
							class="fas fa-paper-plane group-hover:translate-x-1 group-hover:-translate-y-1 transition-transform"></i>
					</span>
					<!-- Shine Effect -->
					<div
						class="absolute inset-0 -translate-x-full group-hover:translate-x-full transition-transform duration-700 bg-gradient-to-r from-transparent via-white/20 to-transparent">
					</div>
				</button>
			</div>

			<!-- Campos ocultos necesarios para wpDiscuz (Intentando mantener compatibilidad mínima) -->
			<input type="hidden" name="wpdiscuz_unique_id" value="0_0">
		</form>
	</div>


	<!-- Clean Scripts -->
	<script src="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.js"></script>
	<script src="js/bioblog.js"></script>
	<script src="js/video-gallery.js"></script>

	<!-- Snog Slider Synchronization Script -->
	<script>
		document.addEventListener('DOMContentLoaded', function () {

			// 1. Initialize Hero Carousel (Replaces Snog Slider)
			var heroEl = document.querySelector('#upk-hero-carousel .swiper-carousel');
			if (heroEl) {
				var upkHeroCarousel = new Swiper(heroEl, {
					slidesPerView: 1, // Mobile: Full width
					spaceBetween: 20,
					centeredSlides: true, // Center the active slide
					loop: true,
					speed: 1000,
					grabCursor: true, // Enable grab cursor for interaction
					effect: 'coverflow', // Nice effect for hero
					coverflowEffect: {
						rotate: 0,
						stretch: 0,
						depth: 100,
						modifier: 1,
						slideShadows: false,
					},
					autoplay: {
						delay: 6000,
						disableOnInteraction: false
					},
					breakpoints: {
						768: {
							slidesPerView: 'auto', // Desktop: Auto width (allows side previews)
							spaceBetween: 30,
						}
					},
					pagination: {
						el: '#upk-hero-carousel .swiper-pagination',
						clickable: true,
						renderBullet: function (index, className) {
							return '<span class="' + className + ' w-3 h-3 bg-white/50 hover:bg-white transition-all duration-300"></span>';
						},
					},
					navigation: { // Connect custom internal navigation if swiper supports it directly, or custom below
						nextEl: '#hero-next-btn',
						prevEl: '#hero-prev-btn',
					}
				});

				// Manual Click Triggers for Hero (Redundant if navigation prop works, but good for safety)
				var heroPrevBtn = document.getElementById('hero-prev-btn');
				var heroNextBtn = document.getElementById('hero-next-btn');
				if (heroPrevBtn) {
					heroPrevBtn.addEventListener('click', function (e) {
						e.preventDefault(); e.stopPropagation(); upkHeroCarousel.slidePrev();
					});
				}
				if (heroNextBtn) {
					heroNextBtn.addEventListener('click', function (e) {
						e.preventDefault(); e.stopPropagation(); upkHeroCarousel.slideNext();
					});
				}
			}

			// Delay initialization to ensure DOM is ready and any other scripts have finished
			setTimeout(function () {
				// 4. Initialize Alter Carousel
				var alterEl = document.querySelector('#upk-alter-carousel-f868512 .swiper-carousel');
				if (alterEl) {
					if (alterEl.swiper) alterEl.swiper.destroy(true, true);
					var upkAlterCarousel = new Swiper(alterEl, {
						slidesPerView: 1,
						slidesPerGroup: 1,
						spaceBetween: 20,
						loop: true,
						speed: 600,
						grabCursor: true, // Ensure drag interaction
						observer: true,
						observeParents: true,
						watchSlidesProgress: true,
						autoplay: {
							delay: 5000,
							disableOnInteraction: false
						},
						breakpoints: {
							640: { slidesPerView: 2, spaceBetween: 20 },
							1024: { slidesPerView: 3, spaceBetween: 30 },
							1280: { slidesPerView: 3, spaceBetween: 40 }
						},
						pagination: {
							el: '#upk-alter-carousel-f868512 .swiper-pagination',
							clickable: true,
						},
					});

					// Manual Click Triggers for Alter Carousel
					var prevBtn = document.getElementById('alter-prev-btn');
					var nextBtn = document.getElementById('alter-next-btn');
					if (prevBtn) {
						prevBtn.addEventListener('click', function (e) {
							e.preventDefault(); e.stopPropagation(); upkAlterCarousel.slidePrev();
						});
					}
					if (nextBtn) {
						nextBtn.addEventListener('click', function (e) {
							e.preventDefault(); e.stopPropagation(); upkAlterCarousel.slideNext();
						});
					}
				}

				// 5. Initialize Ramble Carousel
				var rambleEl = document.querySelector('#upk-ramble-carousel-b564613 .swiper-carousel');
				if (rambleEl) {
					if (rambleEl.swiper) rambleEl.swiper.destroy(true, true);
					var upkRambleCarousel = new Swiper(rambleEl, {
						slidesPerView: 1,
						slidesPerGroup: 1,
						spaceBetween: 20,
						loop: true,
						speed: 600,
						grabCursor: true,
						observer: true,
						observeParents: true,
						watchSlidesProgress: true,
						autoplay: {
							delay: 5000,
							disableOnInteraction: false
						},
						breakpoints: {
							640: { slidesPerView: 2, spaceBetween: 20 },
							1024: { slidesPerView: 3, spaceBetween: 30 },
							1280: { slidesPerView: 3, spaceBetween: 40 }
						},
						pagination: {
							el: '#upk-ramble-carousel-b564613 .swiper-pagination',
							clickable: true,
						},
					});

					// Manual Click Triggers for Ramble Carousel
					var ramblePrevBtn = document.getElementById('ramble-prev-btn');
					var rambleNextBtn = document.getElementById('ramble-next-btn');
					if (ramblePrevBtn) {
						ramblePrevBtn.addEventListener('click', function (e) {
							e.preventDefault(); e.stopPropagation(); upkRambleCarousel.slidePrev();
						});
					}
					if (rambleNextBtn) {
						rambleNextBtn.addEventListener('click', function (e) {
							e.preventDefault(); e.stopPropagation(); upkRambleCarousel.slideNext();
						});
					}
				}
			}, 300);

			// Fix for wpDiscuz Subscribe Toggle
			var subscribeToggle = document.querySelector('.wpd-sbs-toggle');
			var subscribeBar = document.querySelector('.wpdiscuz-subscribe-bar');
			if (subscribeToggle && subscribeBar) {
				subscribeToggle.addEventListener('click', function () {
					subscribeBar.classList.toggle('wpdiscuz-hidden');
					// Toggle icons if needed
					var iconDown = subscribeToggle.querySelector('.fa-caret-down');
					var iconUp = subscribeToggle.querySelector('.fa-caret-up');
					if (iconDown) {
						if (subscribeBar.classList.contains('wpdiscuz-hidden')) {
							iconDown.style.transform = 'rotate(0deg)';
						} else {
							iconDown.style.transform = 'rotate(180deg)';
						}
					}
				});
			}
		});
	</script>

</div>

<?php include 'footer.php'; ?>