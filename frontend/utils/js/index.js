// ===================== MENÚ MÓVIL LATERAL =====================
const btnMenu = document.getElementById("btnMenu");
const mobileMenu = document.getElementById("mobileMenu");
const mobileMenuOverlay = document.getElementById("mobileMenuOverlay");
const closeMobileMenu = document.getElementById("closeMobileMenu");

function openMobileMenu() {
  if (!mobileMenu || !mobileMenuOverlay) return;

  // Mostrar overlay
  mobileMenuOverlay.classList.remove("hidden");

  // Deslizar menú desde la izquierda
  setTimeout(() => {
    mobileMenu.classList.remove("-translate-x-full");
    mobileMenu.classList.add("translate-x-0");
  }, 10);

  // Prevenir scroll del body
  document.body.style.overflow = "hidden";
}

function closeMobileMenuFunc() {
  if (!mobileMenu || !mobileMenuOverlay) return;

  // Ocultar menú
  mobileMenu.classList.add("-translate-x-full");
  mobileMenu.classList.remove("translate-x-0");

  // Ocultar overlay después de la animación
  setTimeout(() => {
    mobileMenuOverlay.classList.add("hidden");
  }, 300);

  // Restaurar scroll del body
  document.body.style.overflow = "";
}

// Abrir menú con botón hamburguesa
if (btnMenu) {
  btnMenu.addEventListener("click", openMobileMenu);
}

// Cerrar menú con botón X
if (closeMobileMenu) {
  closeMobileMenu.addEventListener("click", closeMobileMenuFunc);
}

// Cerrar menú al hacer clic en el overlay
if (mobileMenuOverlay) {
  mobileMenuOverlay.addEventListener("click", closeMobileMenuFunc);
}

// Cerrar menú al hacer clic en un enlace (opcional, con delay para feedback visual)
if (mobileMenu) {
  const menuLinks = mobileMenu.querySelectorAll("a");
  menuLinks.forEach(link => {
    link.addEventListener("click", () => {
      setTimeout(closeMobileMenuFunc, 200);
    });
  });
}

// Estas funciones déjalas SOLO si tu HTML móvil las usa
function openMenu(id) {
  const el = document.getElementById(id);
  if (!el) return;
  el.classList.remove("opacity-0", "pointer-events-none", "translate-y-1");
  el.classList.add("opacity-100", "translate-y-0");
}

function closeMenu(id) {
  const el = document.getElementById(id);
  if (!el) return;
  el.classList.add("opacity-0", "pointer-events-none", "translate-y-1");
  el.classList.remove("opacity-100", "translate-y-0");
}

// ===================== MEGA MENU: DATA + RENDER (PRODUCTOS) =====================
const MEGA_MENU = {
  "Bebés y recién nacidos": {
    icons: [
      { title: "De paseo y en el coche", img: "img/Servicios//Limpieza/1.png", href: "#" },
      { title: "Alimentación", img: "img/Servicios//Limpieza/2.png", href: "#" },
      { title: "Juguetes", img: "img/Servicios//Limpieza/3.png", href: "#" },
      { title: "Ropa", img: "img/Servicios//Limpieza/4.png", href: "#" },
      { title: "Calzado", img: "img/Servicios//Limpieza/5.png", href: "#" },
      { title: "Lactancia y chupetes", img: "img/Servicios//Limpieza/6.png", href: "#" },
    ],
    cols: [
      { h: "ALIMENTACIÓN", items: ["Menaje infantil", "Suplementos nutricionales", "Tronas y elevadores"] },
      { h: "CALZADO", items: ["Bebé (0–2 años)", "Infante (2–4 años)"] },
      { h: "DE PASEO Y EN EL COCHE", items: ["De paseo", "En el coche"] },
      { h: "DESCANSO", items: ["Accesorios para dormitorio", "Cunas y moisés", "Proyectores", "Relax y juego"] },
      { h: "ROPA", items: ["Bebé (0–2 años)", "Infante (2–4 años)"] },
    ],
  },

  "Belleza": {
    icons: [
      { title: "Skincare", img: "img/Servicios/Equipos/1.png", href: "#" },
      { title: "Cabello", img: "img/Servicios/Equipos/2.png", href: "#" },
      { title: "Cuerpo", img: "img/Servicios/Equipos/3.png", href: "#" },
      { title: "Maquillaje", img: "img/Servicios/Equipos/4.png", href: "#" },
      { title: "Perfumería", img: "img/Servicios/Equipos/5.png", href: "#" },
      { title: "Accesorios", img: "img/Servicios/Equipos/6.png", href: "#" },
    ],
    cols: [
      { h: "ROSTRO", items: ["Limpieza", "Hidratación", "Manchas", "Anti-edad"] },
      { h: "CABELLO", items: ["Shampoo", "Tratamientos", "Aceites", "Anticaída"] },
      { h: "CUERPO", items: ["Cremas", "Exfoliantes", "Protección solar"] },
      { h: "MAQUILLAJE", items: ["Base", "Labios", "Ojos"] },
      { h: "EXTRAS", items: ["Sets", "Travel size", "Ofertas"] },
    ],
  },

  "Bienestar emocional y medicina natural": {
    icons: [
      { title: "Aromaterapia", img: "img/Servicios/Suplementos/1.png", href: "#" },
      { title: "Relajación", img: "img/Servicios/Suplementos/2.png", href: "#" },
      { title: "Sueño", img: "img/Servicios/Suplementos/3.png", href: "#" },
      { title: "Infusiones", img: "img/Servicios/Suplementos/4.png", href: "#" },
      { title: "Suplementos", img: "img/Servicios/Suplementos/5.png", href: "#" },
      { title: "Terapias", img: "img/Servicios/Suplementos/6.png", href: "#" },
    ],
    cols: [
      { h: "RELAX", items: ["Aromas", "Velas", "Difusores"] },
      { h: "SUEÑO", items: ["Melatonina", "Tés", "Rutina nocturna"] },
      { h: "MEDICINA NATURAL", items: ["Extractos", "Plantas", "Jarabes"] },
      { h: "MENTE", items: ["Mindfulness", "Journaling"] },
      { h: "BIENESTAR", items: ["Packs", "Promos"] },
    ],
  },

  "Bienestar físico y deportes": {
    icons: [
      { title: "Proteína", img: "img/Servicios/Medicinales/1.png", href: "#" },
      { title: "Pre workout", img: "img/Servicios/Medicinales/2.png", href: "#" },
      { title: "Post workout", img: "img/Servicios/Medicinales/3.png", href: "#" },
      { title: "Accesorios", img: "img/Servicios/Medicinales/4.png", href: "#" },
      { title: "Ropa deportiva", img: "img/Servicios/Medicinales/5.png", href: "#" },
      { title: "Fitness", img: "img/Servicios/Medicinales/1.png", href: "#" },
    ],
    cols: [
      { h: "SUPLEMENTOS", items: ["Proteínas", "Creatina", "Aminoácidos"] },
      { h: "ENTRENAMIENTO", items: ["Bandas", "Guantes", "Cuerdas"] },
      { h: "RECUPERACIÓN", items: ["Masaje", "Magnesio", "Electrolitos"] },
      { h: "ROPA", items: ["Mujer", "Hombre"] },
      { h: "EXTRAS", items: ["Combos", "Ofertas"] },
    ],
  },

  "Digestión saludable": {
    icons: [
      { title: "Probióticos", img: "img/Servicios/Marcas/1.png", href: "#" },
      { title: "Prebióticos", img: "img/Servicios/Marcas/2.png", href: "#" },
      { title: "Enzimas", img: "img/Servicios/Marcas/3.png", href: "#" },
      { title: "Infusiones", img: "img/Servicios/Marcas/4.png", href: "#" },
      { title: "Fibra", img: "img/Servicios/Marcas/5.png", href: "#" },
      { title: "Detox", img: "img/Servicios/Marcas/6.png", href: "#" },
    ],
    cols: [
      { h: "PROBIÓTICOS", items: ["Cápsulas", "Bebibles", "Kids"] },
      { h: "FIBRA", items: ["Psyllium", "Inulina"] },
      { h: "ENZIMAS", items: ["Digestivas", "Lactasa"] },
      { h: "INFUSIONES", items: ["Manzanilla", "Menta", "Anís"] },
      { h: "EXTRAS", items: ["Packs", "Ofertas"] },
    ],
  },

  "Equipos y dispositivos médicos": {
    icons: [
      { title: "Monitoreo", img: "img/Servicios/Suplementos/1.png", href: "#" },
      { title: "Cuidado", img: "img/Servicios/Suplementos/2.png", href: "#" },
      { title: "Movilidad", img: "img/Servicios/Suplementos/3.png", href: "#" },
      { title: "Terapia", img: "img/Servicios/Suplementos/4.png", href: "#" },
      { title: "Diagnóstico", img: "img/Servicios/Suplementos/5.png", href: "#" },
      { title: "Accesorios", img: "img/Servicios/Suplementos/6.png", href: "#" },
    ],
    cols: [
      { h: "MONITOREO", items: ["Oxímetros", "Tensiómetros", "Termómetros"] },
      { h: "TERAPIA", items: ["Nebulizadores", "Electroestimulación"] },
      { h: "MOVILIDAD", items: ["Ortopedia", "Bastones"] },
      { h: "DIAGNÓSTICO", items: ["Kits", "Pruebas"] },
      { h: "EXTRAS", items: ["Repuestos", "Garantía"] },
    ],
  },

  "Mascotas": {
    icons: [
      { title: "Alimento", img: "img/Servicios/Marcas/1.png", href: "#" },
      { title: "Higiene", img: "img/Servicios/Marcas/2.png", href: "#" },
      { title: "Juguetes", img: "img/Servicios/Marcas/3.png", href: "#" },
      { title: "Accesorios", img: "img/Servicios/Marcas/4.png", href: "#" },
      { title: "Salud", img: "img/Servicios/Marcas/5.png", href: "#" },
      { title: "Camas", img: "img/Servicios/Marcas/6.png", href: "#" },
    ],
    cols: [
      { h: "ALIMENTO", items: ["Perros", "Gatos", "Snacks"] },
      { h: "HIGIENE", items: ["Shampoo", "Cepillos", "Arena"] },
      { h: "SALUD", items: ["Vitaminas", "Antipulgas"] },
      { h: "ACCESORIOS", items: ["Collares", "Correas", "Ropa"] },
      { h: "EXTRAS", items: ["Packs", "Ofertas"] },
    ],
  },

  "Protección limpieza y desinfección": {
    icons: [
      { title: "Hogar", img: "img/Servicios/limpieza/1.png", href: "#" },
      { title: "Manos", img: "img/Servicios/limpieza/2.png", href: "#" },
      { title: "Superficies", img: "img/Servicios/limpieza/3.png", href: "#" },
      { title: "Aire", img: "img/Servicios/limpieza/4.png", href: "#" },
      { title: "Antibacterial", img: "img/Servicios/limpieza/5.png", href: "#" },
      { title: "Packs", img: "img/Servicios/limpieza/6.png", href: "#" },
    ],
    cols: [
      { h: "HOGAR", items: ["Multiusos", "Baño", "Cocina"] },
      { h: "DESINFECCIÓN", items: ["Alcohol", "Sprays", "Toallitas"] },
      { h: "PROTECCIÓN", items: ["Mascarillas", "Guantes"] },
      { h: "AIRE", items: ["Purificadores", "Aromas"] },
      { h: "EXTRAS", items: ["Combos", "Ofertas"] },
    ],
  },

  "Suplementos vitamínicos": {
    icons: [
      { title: "Multivit", img: "img/Servicios/Medicinales/1.png", href: "#" },
      { title: "Vit C", img: "img/Servicios/Medicinales/2.png", href: "#" },
      { title: "Vit D", img: "img/Servicios/Medicinales/3.png", href: "#" },
      { title: "Zinc", img: "img/Servicios/Medicinales/4.png", href: "#" },
      { title: "Omega", img: "img/Servicios/Medicinales/5.png", href: "#" },
      { title: "Kids", img: "img/Servicios/Medicinales/1.png", href: "#" },
    ],
    cols: [
      { h: "INMUNIDAD", items: ["Vitamina C", "Zinc", "Vitamina D"] },
      { h: "ENERGÍA", items: ["B12", "Complejo B"] },
      { h: "CEREBRO", items: ["Omega 3", "Magnesio"] },
      { h: "KIDS", items: ["Gomitas", "Jarabes"] },
      { h: "EXTRAS", items: ["Packs", "Ofertas"] },
    ],
  },
};

// Render helpers (PRODUCTOS)
function renderMegaCategoryList() {
  const ul = document.getElementById("megaCats");
  if (!ul) return;

  ul.innerHTML = "";

  const keys = Object.keys(MEGA_MENU);
  keys.forEach((name, idx) => {
    const li = document.createElement("li");
    li.innerHTML = `
      <button type="button"
              data-cat="${name}"
              class="w-full flex items-center justify-between px-4 py-3 rounded-xl
                     text-gray-800 hover:bg-white hover:shadow-sm transition
                     ${idx === 0 ? "bg-white shadow-sm" : ""}">
        <span>${name}</span>
        <i class="ph-caret-right text-gray-400"></i>
      </button>
    `;
    ul.appendChild(li);
  });
}

function renderMegaRight(catName) {
  const data = MEGA_MENU[catName];
  if (!data) return;

  const titleEl = document.getElementById("catHeaderTitle");
  if (titleEl) titleEl.textContent = catName;

  const icons = document.getElementById("megaIcons");
  if (icons) {
    icons.innerHTML = data.icons
      .map(
        (x) => `
      <a href="${x.href || "#"}" class="group text-center">
        <div class="mx-auto w-20 h-20 rounded-full bg-lime-500/20 border border-lime-200
                    flex items-center justify-center shadow-sm
                    group-hover:shadow-md group-hover:scale-[1.02] transition">
          <img src="${x.img}" alt="${x.title}" class="w-12 h-12 object-contain">
        </div>
        <div class="mt-2 text-[12px] font-semibold text-gray-700 group-hover:text-sky-600 transition">
          ${x.title}
        </div>
      </a>
    `
      )
      .join("");
  }

  const cols = document.getElementById("megaCols");
  if (cols) {
    cols.innerHTML = data.cols
      .map(
        (col) => `
      <div>
        <div class="text-[13px] font-extrabold tracking-wide text-gray-900 uppercase mb-2">${col.h}</div>
        <ul class="space-y-1.5">
          ${col.items
            .map(
              (it) => `
            <li>
              <a href="#" class="text-[12px] text-gray-700 hover:text-sky-600 transition">${it}</a>
            </li>
          `
            )
            .join("")}
        </ul>
      </div>
    `
      )
      .join("");
  }

  const btns = document.querySelectorAll("#megaCats [data-cat]");
  btns.forEach((b) => {
    const isActive = b.getAttribute("data-cat") === catName;
    b.classList.toggle("bg-white", isActive);
    b.classList.toggle("shadow-sm", isActive);
  });
}

function initMegaMenuProductos() {
  renderMegaCategoryList();
  const first = Object.keys(MEGA_MENU)[0];
  if (first) renderMegaRight(first);

  const catsRoot = document.getElementById("megaCats");
  if (!catsRoot) return;

  catsRoot.addEventListener("mouseover", (e) => {
    const btn = e.target.closest("[data-cat]");
    if (!btn) return;
    renderMegaRight(btn.getAttribute("data-cat"));
  });

  catsRoot.addEventListener("click", (e) => {
    const btn = e.target.closest("[data-cat]");
    if (!btn) return;
    renderMegaRight(btn.getAttribute("data-cat"));
  });
}

// ===================== MEGA MENU: DATA + RENDER (SERVICIOS) =====================
const MEGA_SERVICIOS = {
  "Servicios médicos": {
    icons: [
      { t: "Gastroenterología", img: "img/Servicios/Marcas/1.png" },
      { t: "Geriatría", img: "img/Servicios/Marcas/2.png" },
      { t: "Laboratorio clínico", img: "img/Servicios/Marcas/3.png" },
      { t: "Medicina general", img: "img/Servicios/Marcas/4.png" },
      { t: "Nutrición", img: "img/Servicios/Marcas/5.png" },
      { t: "Pediatría", img: "img/Servicios/Marcas/6.png" },
    ],
    cols: [
      ["CARDIOLOGÍA", "RADIOLOGÍA", "DERMATOLOGÍA", "MEDICINA GENERAL", "ENDOCRINOLOGÍA", "ENFERMERÍA"],
      ["GERIATRÍA", "GINECOLOGÍA", "LABORATORIO CLÍNICO", "MEDICINA FÍSICA", "NEUMOLOGÍA", "NEUROLOGÍA"],
      ["ODONTOLOGÍA", "OFTALMOLOGÍA", "ONCOLOGÍA", "PEDIATRÍA", "PSICOLOGÍA", "PSIQUIATRÍA"],
    ],
  },

  "Belleza servicios": {
    icons: [
      { t: "Estética facial", img: "img/Servicios/Equipos/1.png" },
      { t: "Cosmetología", img: "img/Servicios/Equipos/2.png" },
      { t: "Tratamientos", img: "img/Servicios/Equipos/3.png" },
    ],
    cols: [
      ["LIMPIEZA FACIAL", "ANTI-EDAD", "ACNÉ"],
      ["DEPILACIÓN", "MASAJES", "SPA"],
    ],
  },

  "Deportes servicios": {
    icons: [
      { t: "Entrenamiento", img: "img/Servicios/Equipos/4.png" },
      { t: "Fisioterapia", img: "img/Servicios/Equipos/5.png" },
    ],
    cols: [["FISIOTERAPIA", "REHABILITACIÓN"], ["ENTRENAMIENTO FUNCIONAL"]],
  },

  "Servicios para animales": {
    icons: [
      { t: "Veterinaria", img: "img//Servicios/Equipos/6.png" },
      { t: "Vacunas", img: "img/Servicios/Equipos/1.png" },
    ],
    cols: [["CONSULTAS", "VACUNACIÓN", "DESPARASITACIÓN"]],
  },
};

function initMegaMenuServicios() {
  const cats = document.getElementById("servCats");
  const icons = document.getElementById("servIcons");
  const cols = document.getElementById("servCols");
  if (!cats || !icons || !cols) return;

  const keys = Object.keys(MEGA_SERVICIOS);

  cats.innerHTML = keys
    .map(
      (k, i) => `
      <button data-cat="${k}"
        class="w-full text-left px-4 py-3 rounded-xl text-gray-800 hover:bg-white transition
        ${i === 0 ? "bg-green-400 text-white" : ""}">
        ${k}
      </button>
    `
    )
    .join("");

  function render(cat) {
    const d = MEGA_SERVICIOS[cat];
    if (!d) return;

    icons.innerHTML = d.icons
      .map(
        (x) => `
      <div class="text-center">
        <div class="mx-auto w-20 h-20 rounded-full bg-green-100 flex items-center justify-center shadow">
          <img src="${x.img}" class="w-10 h-10" alt="${x.t}">
        </div>
        <div class="mt-2 text-xs font-semibold text-gray-800">${x.t}</div>
      </div>
    `
      )
      .join("");

    cols.innerHTML = d.cols
      .map(
        (c) => `
      <ul class="space-y-2 font-semibold text-[13px] text-gray-800">
        ${c.map((i) => `<li><a href="#" class="hover:text-sky-500">${i}</a></li>`).join("")}
      </ul>
    `
      )
      .join("");

    document.querySelectorAll("#servCats button").forEach((b) => {
      const active = b.dataset.cat === cat;
      b.classList.toggle("bg-green-400", active);
      b.classList.toggle("text-white", active);
      b.classList.toggle("text-gray-800", !active);
    });
  }

  if (keys[0]) render(keys[0]);

  cats.addEventListener("mouseover", (e) => {
    const b = e.target.closest("[data-cat]");
    if (b) render(b.dataset.cat);
  });

  cats.addEventListener("click", (e) => {
    const b = e.target.closest("[data-cat]");
    if (b) render(b.dataset.cat);
  });
}

// ===================== MEGA MENUS: CONTROL ÚNICO (NO CONFLICTOS) =====================
(function () {
  const megaItems = [
    { triggerId: "productosTrigger", menuId: "menu-productos" },
    { triggerId: "serviciosTrigger", menuId: "menu-servicios" },
  ];

  const state = { openId: null, timer: null };

  function getEl(id) {
    return document.getElementById(id);
  }

  function positionMenu(trigger, menu) {
    const r = trigger.getBoundingClientRect();
    menu.style.top = `${Math.round(r.bottom + 10)}px`;
  }

  function show(menu) {
    menu.classList.remove("opacity-0", "pointer-events-none", "translate-y-2");
    menu.classList.add("opacity-100", "pointer-events-auto", "translate-y-0");
  }

  function hide(menu) {
    menu.classList.add("opacity-0", "pointer-events-none", "translate-y-2");
    menu.classList.remove("opacity-100", "pointer-events-auto", "translate-y-0");
  }

  function closeAll(exceptMenuId = null) {
    megaItems.forEach(({ menuId }) => {
      if (menuId === exceptMenuId) return;
      const m = getEl(menuId);
      if (m) hide(m);
    });
  }

  function openMenuBy(menuId) {
    const item = megaItems.find((x) => x.menuId === menuId);
    if (!item) return;

    const trigger = getEl(item.triggerId);
    const menu = getEl(item.menuId);
    if (!trigger || !menu) return;

    if (state.timer) clearTimeout(state.timer);

    closeAll(menuId);
    positionMenu(trigger, menu);
    show(menu);
    state.openId = menuId;
  }

  function closeMenuDelayed(menuId) {
    if (state.timer) clearTimeout(state.timer);
    state.timer = setTimeout(() => {
      const menu = getEl(menuId);
      if (menu) hide(menu);
      if (state.openId === menuId) state.openId = null;
    }, 180);
  }

  megaItems.forEach(({ triggerId, menuId }) => {
    const trigger = getEl(triggerId);
    const menu = getEl(menuId);
    if (!trigger || !menu) return;

    trigger.addEventListener("mouseenter", () => openMenuBy(menuId));
    trigger.addEventListener("mouseleave", () => closeMenuDelayed(menuId));

    menu.addEventListener("mouseenter", () => openMenuBy(menuId));
    menu.addEventListener("mouseleave", () => closeMenuDelayed(menuId));
  });

  window.addEventListener(
    "scroll",
    () => {
      if (!state.openId) return;
      const item = megaItems.find((x) => x.menuId === state.openId);
      if (!item) return;
      const trigger = getEl(item.triggerId);
      const menu = getEl(item.menuId);
      if (trigger && menu) positionMenu(trigger, menu);
    },
    { passive: true }
  );

  window.addEventListener("resize", () => {
    if (!state.openId) return;
    const item = megaItems.find((x) => x.menuId === state.openId);
    if (!item) return;
    const trigger = getEl(item.triggerId);
    const menu = getEl(item.menuId);
    if (trigger && menu) positionMenu(trigger, menu);
  });

  document.addEventListener("click", (e) => {
    const clickedInsideAny = megaItems.some(({ triggerId, menuId }) => {
      const t = getEl(triggerId);
      const m = getEl(menuId);
      return (t && t.contains(e.target)) || (m && m.contains(e.target));
    });
    if (!clickedInsideAny) closeAll(null);
  });
})();

// ===================== FUNCIÓN GENÉRICA DE CARRUSEL (CON SWIPE) =====================
function initCarousel(trackId, prevId, nextId, intervalMs) {
  const track = document.getElementById(trackId);
  if (!track) return;

  const slides = Array.from(track.children);
  if (!slides.length) return;

  const prevBtn = document.getElementById(prevId);
  const nextBtn = document.getElementById(nextId);
  let index = 0;
  let startX = 0;
  let currentX = 0;
  let isDragging = false;
  let autoTimer = null;

  function showSlide(i) {
    index = (i + slides.length) % slides.length;
    track.style.transition = "transform 0.7s cubic-bezier(0.22, 0.61, 0.36, 1)";
    track.style.transform = `translateX(-${index * 100}%)`;
  }

  function handleTouchStart(e) {
    startX = e.touches[0].clientX;
    isDragging = true;
    if (autoTimer) clearInterval(autoTimer);
    track.style.transition = "none";
  }

  function handleTouchMove(e) {
    if (!isDragging) return;
    currentX = e.touches[0].clientX;
    const diff = currentX - startX;
    const moveX = -(index * 100) + (diff / track.offsetWidth) * 100;
    track.style.transform = `translateX(${moveX}%)`;
  }

  function handleTouchEnd() {
    if (!isDragging) return;
    isDragging = false;
    const diff = currentX - startX;
    if (Math.abs(diff) > 50) {
      if (diff > 0) index--;
      else index++;
    }
    showSlide(index);
    startAuto();
  }

  function startAuto() {
    if (intervalMs && intervalMs > 0) {
      if (autoTimer) clearInterval(autoTimer);
      autoTimer = setInterval(() => {
        index++;
        showSlide(index);
      }, intervalMs);
    }
  }

  if (nextBtn) nextBtn.addEventListener("click", () => { showSlide(index + 1); startAuto(); });
  if (prevBtn) prevBtn.addEventListener("click", () => { showSlide(index - 1); startAuto(); });

  track.addEventListener("touchstart", handleTouchStart, { passive: true });
  track.addEventListener("touchmove", handleTouchMove, { passive: true });
  track.addEventListener("touchend", handleTouchEnd);

  startAuto();
}

/**
 * Función para carruseles que muestran múltiples items por vista (ej: 3 en desktop, 1 en móvil)
 */
function initMultiCarousel(trackId, prevId, nextId, intervalMs) {
  const track = document.getElementById(trackId);
  if (!track) return;

  const items = Array.from(track.children);
  if (!items.length) return;

  const prevBtn = document.getElementById(prevId);
  const nextBtn = document.getElementById(nextId);
  let currentIndex = 0;
  let autoTimer = null;

  function getItemsPerView() {
    if (window.innerWidth < 640) return 1;
    if (window.innerWidth < 1024) return 2;
    return 3;
  }

  function getTotalSteps() {
    const perView = getItemsPerView();
    return Math.ceil(items.length / perView);
  }

  function showStep(step) {
    const total = getTotalSteps();
    currentIndex = (step + total) % total;
    track.style.transition = "transform 0.7s cubic-bezier(0.22, 0.61, 0.36, 1)";
    track.style.transform = `translateX(-${currentIndex * 100}%)`;
  }

  function startAuto() {
    if (intervalMs && intervalMs > 0) {
      if (autoTimer) clearInterval(autoTimer);
      autoTimer = setInterval(() => {
        showStep(currentIndex + 1);
      }, intervalMs);
    }
  }

  if (nextBtn) nextBtn.addEventListener("click", () => { showStep(currentIndex + 1); startAuto(); });
  if (prevBtn) prevBtn.addEventListener("click", () => { showStep(currentIndex - 1); startAuto(); });

  window.addEventListener("resize", () => {
    // Re-ajustar posición al cambiar el tamaño de ventana si el índice queda fuera de rango
    const total = getTotalSteps();
    if (currentIndex >= total) currentIndex = total - 1;
    showStep(currentIndex);
  });

  startAuto();
}

// ===================== MODAL GENÉRICA PARA TODAS LAS IMÁGENES =====================
const modal = document.getElementById("modalProducto");
const modalImg = document.getElementById("modalImg");
const modalTitulo = document.getElementById("modalTitulo");
const modalPrecio = document.getElementById("modalPrecio");
const modalDescripcion = document.getElementById("modalDescripcion");
const modalElements = document.querySelectorAll('[data-modal="producto"]');

function cerrarModal() {
  if (!modal) return;
  modal.classList.add("hidden");
  modal.classList.remove("flex");
}

if (modal && modalImg && modalTitulo && modalPrecio && modalDescripcion && modalElements.length > 0) {
  modalElements.forEach((el) => {
    el.addEventListener("click", () => {
      modalImg.src = el.src || "";
      modalTitulo.textContent = el.dataset.title || "Detalle";
      modalPrecio.textContent = el.dataset.price || "";
      modalDescripcion.textContent = el.dataset.desc || "";

      modal.classList.remove("hidden");
      modal.classList.add("flex");
    });
  });

  modal.addEventListener("click", (e) => {
    if (e.target === modal) cerrarModal();
  });
}

// ===================== PARALLAX HERO =====================
function initHeroParallax() {
  const img = document.getElementById("heroParallaxImg");
  const hero = document.getElementById("heroLyrium");
  if (!img || !hero) return;

  function actualizarParallax() {
    const rect = hero.getBoundingClientRect();
    const ventana = window.innerHeight || document.documentElement.clientHeight;

    if (rect.bottom > 0 && rect.top < ventana) {
      const factor = 40;
      const progreso = (ventana - rect.top) / (ventana + rect.height);
      const desplazamiento = (progreso - 0.5) * factor;

      img.style.transform = `translateY(${desplazamiento}px) scale(1.12)`;
    }
  }

  window.addEventListener("scroll", actualizarParallax, { passive: true });
  window.addEventListener("resize", actualizarParallax);
  actualizarParallax();
}

// ===================== ZOOM SUAVE OTRA IMAGEN =====================
function initZoomImage() {
  function onScroll() {
    const container = document.getElementById("zoomContainer");
    const img = document.getElementById("zoomImage");
    if (!container || !img) return;

    const rect = container.getBoundingClientRect();
    const viewH = window.innerHeight;

    if (rect.top < viewH && rect.bottom > 0) {
      const progress = 1 - Math.abs(rect.top) / viewH;
      const scale = 1 + progress * 0.18;
      img.style.transform = `scale(${scale})`;
    }
  }

  document.addEventListener("scroll", onScroll, { passive: true });
  onScroll();
}

// ===================== CARRUSELES DE OFERTAS (CON SWIPE Y 1-A-1) =====================
function initOfertas() {
  const wrappers = document.querySelectorAll(".ofertas-wrapper");
  if (!wrappers.length) return;

  wrappers.forEach((wrapper) => {
    const track = wrapper.querySelector(".ofertas-track");
    const dotsContainer = wrapper.querySelector(".ofertas-dots");
    const items = track ? Array.from(track.querySelectorAll(".oferta-card")) : [];

    if (!track || !items.length || !dotsContainer) return;

    let currentIndex = 0;
    let startX = 0;
    let currentX = 0;
    let isDragging = false;
    let autoTimer = null;

    function getItemsPerView() {
      if (window.innerWidth < 640) return 1;
      if (window.innerWidth < 1024) return 2;
      return 3;
    }

    function getItemWidth() {
      const styles = window.getComputedStyle(items[0]);
      const marginRight = parseFloat(styles.marginRight || "0");
      return items[0].offsetWidth + marginRight;
    }

    function getTotalSlides() {
      const perView = getItemsPerView();
      return Math.max(1, items.length - perView + 1);
    }

    function renderDots() {
      const total = getTotalSlides();
      dotsContainer.innerHTML = "";
      for (let i = 0; i < total; i++) {
        const dot = document.createElement("button");
        dot.type = "button";
        if (i === currentIndex) dot.classList.add("is-active");
        dot.addEventListener("click", () => goToSlide(i, true));
        dotsContainer.appendChild(dot);
      }
    }

    function goToSlide(index, stopAuto = false) {
      const total = getTotalSlides();
      currentIndex = Math.min(Math.max(0, index), total - 1);
      const offset = -(currentIndex * getItemWidth());

      track.style.transition = "transform 0.6s cubic-bezier(0.22, 0.61, 0.36, 1)";
      track.style.transform = `translateX(${offset}px)`;

      const dots = dotsContainer.querySelectorAll("button");
      dots.forEach((d, i) => d.classList.toggle("is-active", i === currentIndex));

      if (stopAuto) startAuto();
    }

    function handleTouchStart(e) {
      startX = e.touches[0].clientX;
      isDragging = true;
      track.style.transition = "none";
      if (autoTimer) clearInterval(autoTimer);
      wrapper.classList.add("mobile-active");
    }

    function handleTouchMove(e) {
      if (!isDragging) return;
      currentX = e.touches[0].clientX;
      const diff = currentX - startX;
      const offset = -(currentIndex * getItemWidth()) + diff;
      track.style.transform = `translateX(${offset}px)`;
    }

    function handleTouchEnd() {
      if (!isDragging) return;
      isDragging = false;
      const diff = currentX - startX;
      if (Math.abs(diff) > 50) {
        if (diff > 0) goToSlide(currentIndex - 1);
        else goToSlide(currentIndex + 1);
      } else {
        goToSlide(currentIndex);
      }
      setTimeout(() => wrapper.classList.remove("mobile-active"), 300);
      startAuto();
    }

    function startAuto() {
      if (autoTimer) clearInterval(autoTimer);
      autoTimer = setInterval(() => {
        let next = currentIndex + 1;
        if (next >= getTotalSlides()) next = 0;
        goToSlide(next);
      }, 6000);
    }

    track.addEventListener("touchstart", handleTouchStart, { passive: true });
    track.addEventListener("touchmove", handleTouchMove, { passive: true });
    track.addEventListener("touchend", handleTouchEnd);

    window.addEventListener("resize", () => {
      renderDots();
      goToSlide(currentIndex);
    });

    renderDots();
    startAuto();
  });
}

// ===================== PARALLAX FONDO .ofertas-wrapper (UNIFICADO) =====================
function initOfertasParallax() {
  const wrappers = document.querySelectorAll(".ofertas-wrapper");
  if (!wrappers.length) return;

  function updateParallax() {
    const windowHeight = window.innerHeight || document.documentElement.clientHeight;
    const scrollY = window.scrollY || window.pageYOffset;

    wrappers.forEach((wrapper) => {
      const bg = wrapper.querySelector(".ofertas-bg");
      if (!bg) return;

      const rect = wrapper.getBoundingClientRect();
      if (!(rect.bottom > 0 && rect.top < windowHeight)) return;

      const wrapperTop = scrollY + rect.top;

      let progress = (windowHeight - rect.top) / (windowHeight + rect.height);
      if (progress < 0) progress = 0;
      if (progress > 1) progress = 1;

      const offset = (progress - 0.5) * 80;
      const speedOffset = (scrollY - wrapperTop) * 0.45;

      // mezcla suave (elige 1 solo si quieres)
      const finalOffset = (offset * 0.65) + (speedOffset * 0.35);

      bg.style.transform = `translate3d(0, ${finalOffset}px, 0) scale(1.10)`;
    });
  }

  let ticking = false;
  function onScroll() {
    if (ticking) return;
    ticking = true;
    requestAnimationFrame(() => {
      updateParallax();
      ticking = false;
    });
  }

  document.addEventListener("scroll", onScroll, { passive: true });
  window.addEventListener("load", updateParallax);
  window.addEventListener("resize", updateParallax);
  updateParallax();
}

// ===================== BANNER PRINCIPAL (data-banner-track) =====================
function initBanner() {
  const track = document.querySelector("[data-banner-track]");
  if (!track) return;

  const slides = Array.from(track.querySelectorAll("[data-banner-slide]"));
  const dotsContainer = document.getElementById("bannerDots");
  if (!dotsContainer || !slides.length) return;

  let current = 0;
  let autoTimer = null;

  slides.forEach((_, index) => {
    const btn = document.createElement("button");
    if (index === 0) btn.classList.add("is-active");
    btn.addEventListener("click", () => goToSlide(index, true));
    dotsContainer.appendChild(btn);
  });

  const dots = Array.from(dotsContainer.querySelectorAll("button"));

  function updateDots() {
    dots.forEach((d, i) => d.classList.toggle("is-active", i === current));
  }

  function goToSlide(index, stopAuto = false) {
    current = (index + slides.length) % slides.length;
    track.style.transform = `translateX(-${current * 100}%)`;
    updateDots();
    if (stopAuto) resetAuto();
  }

  function startAuto() {
    autoTimer = setInterval(() => goToSlide(current + 1, false), 6000);
  }

  function resetAuto() {
    if (autoTimer) clearInterval(autoTimer);
    startAuto();
  }

  track.addEventListener("mouseenter", () => {
    if (autoTimer) clearInterval(autoTimer);
  });

  track.addEventListener("mouseleave", () => {
    resetAuto();
  });

  startAuto();
}

// ===================== CARRUSELES DE CATEGORÍAS (cat-carousel con swipe y 1-a-1) =====================
function initCatCarousel(root) {
  const track = root.querySelector(".cat-track");
  const items = track ? Array.from(track.querySelectorAll(".cat-item")) : [];
  const dotsContainer = root.querySelector(".cat-dots");

  if (!track || items.length === 0 || !dotsContainer) return;

  let currentIndex = 0;
  let startX = 0;
  let currentX = 0;
  let isDragging = false;
  let autoTimer = null;

  function getItemsPerView() {
    if (window.innerWidth < 640) return 1;
    if (window.innerWidth < 1024) return 2;
    return parseInt(root.dataset.items || "3", 10);
  }

  function getItemWidth() {
    const styles = window.getComputedStyle(items[0]);
    const marginRight = parseFloat(styles.marginRight || "0");
    return items[0].offsetWidth + marginRight;
  }

  function getTotalSlides() {
    const perView = getItemsPerView();
    return Math.max(1, items.length - perView + 1);
  }

  function renderDots() {
    const total = getTotalSlides();
    dotsContainer.innerHTML = "";
    for (let i = 0; i < total; i++) {
      const dot = document.createElement("button");
      if (i === currentIndex) dot.classList.add("active");
      dot.addEventListener("click", () => goToSlide(i, true));
      dotsContainer.appendChild(dot);
    }
  }

  function goToSlide(index, stopAuto = false) {
    const total = getTotalSlides();
    currentIndex = Math.min(Math.max(0, index), total - 1);
    const offset = -(currentIndex * getItemWidth());
    track.style.transition = "transform 0.6s cubic-bezier(0.22, 0.61, 0.36, 1)";
    track.style.transform = `translateX(${offset}px)`;

    const dots = dotsContainer.querySelectorAll("button");
    dots.forEach((d, i) => d.classList.toggle("active", i === currentIndex));

    if (stopAuto) startAuto();
  }

  function handleTouchStart(e) {
    startX = e.touches[0].clientX;
    isDragging = true;
    track.style.transition = "none";
    if (autoTimer) clearInterval(autoTimer);
  }

  function handleTouchMove(e) {
    if (!isDragging) return;
    currentX = e.touches[0].clientX;
    const diff = currentX - startX;
    const offset = -(currentIndex * getItemWidth()) + diff;
    track.style.transform = `translateX(${offset}px)`;
  }

  function handleTouchEnd() {
    if (!isDragging) return;
    isDragging = false;
    const diff = currentX - startX;
    if (Math.abs(diff) > 50) {
      if (diff > 0) goToSlide(currentIndex - 1);
      else goToSlide(currentIndex + 1);
    } else {
      goToSlide(currentIndex);
    }
    startAuto();
  }

  function startAuto() {
    if (autoTimer) clearInterval(autoTimer);
    autoTimer = setInterval(() => {
      let next = currentIndex + 1;
      if (next >= getTotalSlides()) next = 0;
      goToSlide(next);
    }, 5000);
  }

  track.addEventListener("touchstart", handleTouchStart, { passive: true });
  track.addEventListener("touchmove", handleTouchMove, { passive: true });
  track.addEventListener("touchend", handleTouchEnd);

  window.addEventListener("resize", () => {
    renderDots();
    goToSlide(currentIndex);
  });

  renderDots();
  startAuto();
}

// ===================== CARRUSEL DE BENEFICIOS =====================
function initBeneficios() {
  const slider = document.querySelector("[data-beneficios-slider]");
  if (!slider) return;

  const track = slider.querySelector("[data-beneficios-track]");
  if (!track) return;

  const slides = Array.from(track.children || []);
  const dotsContainer = slider.parentElement.querySelector("[data-beneficios-dots]");
  if (!slides.length || !dotsContainer) return;

  let current = 0;
  let autoTimer = null;

  dotsContainer.innerHTML = "";
  slides.forEach((_, index) => {
    const btn = document.createElement("button");
    if (index === 0) btn.classList.add("is-active");
    btn.addEventListener("click", () => goToSlide(index, true));
    dotsContainer.appendChild(btn);
  });

  const dots = Array.from(dotsContainer.querySelectorAll("button"));

  function updateDots() {
    dots.forEach((d, i) => d.classList.toggle("is-active", i === current));
  }

  function goToSlide(index, stopAuto) {
    current = (index + slides.length) % slides.length;
    track.style.transform = `translateX(-${current * 100}%)`;
    updateDots();
    if (stopAuto) resetAuto();
  }

  function startAuto() {
    autoTimer = setInterval(() => goToSlide(current + 1, false), 6000);
  }

  function resetAuto() {
    if (autoTimer) clearInterval(autoTimer);
    startAuto();
  }

  slider.addEventListener("mouseenter", () => {
    if (autoTimer) clearInterval(autoTimer);
  });
  slider.addEventListener("mouseleave", () => {
    resetAuto();
  });

  startAuto();
}

// ===================== PARALLAX DE FONDO (solo beneficios) =====================
function initBeneficiosParallax() {
  const layers = document.querySelectorAll("[data-beneficios-parallax]");
  if (!layers.length) return;

  function updateParallax() {
    const scrollY = window.scrollY || window.pageYOffset;

    layers.forEach((layer) => {
      const rect = layer.getBoundingClientRect();
      const elementTop = scrollY + rect.top;

      const speed = 0.5;
      const offset = (scrollY - elementTop) * speed;

      layer.style.transform = `translate3d(0, ${offset}px, 0) scale(1.08)`;
    });
  }

  let ticking = false;
  function onScroll() {
    if (ticking) return;
    ticking = true;
    requestAnimationFrame(() => {
      updateParallax();
      ticking = false;
    });
  }

  window.addEventListener("scroll", onScroll, { passive: true });
  window.addEventListener("load", updateParallax);
  window.addEventListener("resize", updateParallax);
  updateParallax();
}

// ===================== INIT GLOBAL =====================
document.addEventListener("DOMContentLoaded", () => {
  // Render data de los mega menús
  initMegaMenuProductos();
  initMegaMenuServicios();

  // Carruseles clásicos
  initCarousel("carouselServiciosTrack", "serviciosPrev", "serviciosNext", 4000);
  initMultiCarousel("categoriasLimpiezaTrack", "categoriasLimpiezaPrev", "categoriasLimpiezaNext", 4800);
  initMultiCarousel("categoriasEquiposTrack", "categoriasEquiposPrev", "categoriasEquiposNext", 4800);
  initMultiCarousel("categoriasSuplementosTrack", "categoriasSuplementosPrev", "categoriasSuplementosNext", 4800);
  initCarousel("beneficiosTrack", "beneficiosPrev", "beneficiosNext", 4500);
  initCarousel("marcasTrack", "marcasPrev", "marcasNext", 5000);
  initCarousel("bannersTrack", "bannersPrev", "bannersNext", 5000);

  // Efectos
  initHeroParallax();
  initZoomImage();

  // Ofertas
  initOfertas();
  initOfertasParallax();

   // 🆕 CARRUSEL DE FONDOS CADA 1 MINUTO
  initBannerBackgroundCarousel();

  // Banner + beneficios
  initBanner();
  initBeneficios();

  // Carouseles de categorías
  window.addEventListener("load", () => {
    document.querySelectorAll(".cat-carousel").forEach(initCatCarousel);
  });

  // Parallax beneficios
  initBeneficiosParallax();
});

// ===================== CARRUSEL DE FONDOS RETRO (1 minuto) =====================
function initBannerBackgroundCarousel() {
  const wrappers = {
    productos: {
      element: document.querySelector('.ofertas-bg-productos'),
      images: [
        'img/Inicio/6.png',  // Moderna
         'img/Inicio/retro/1.png',
         'img/Inicio/retro/4.png',
         'img/Inicio/retro/7.png',
      ],
      currentIndex: 0
    },
    servicios: {
      element: document.querySelector('.ofertas-bg-servicios'),
      images: [
        'img/Inicio/7.png',
        'img/Inicio/retro/2.png',  
        'img/Inicio/retro/5.png',  
        'img/Inicio/retro/8.png', 
      ],
      currentIndex: 0
    },
    nuevos: {
      element: document.querySelector('.ofertas-bg-nuevos'),
      images: [
        'img/Inicio/8.png',
        'img/Inicio/retro/3.png',
        'img/Inicio/retro/6.png',
        'img/Inicio/retro/9.png',
      ],
      currentIndex: 0
    }
  };

  function changeBackground(wrapper) {
    if (! wrapper. element) return;
    
    wrapper.currentIndex = (wrapper.currentIndex + 1) % wrapper.images.length;
    const nextImage = wrapper.images[wrapper. currentIndex];
    
    // Transición suave
    wrapper.element.style.transition = 'opacity 1s ease-in-out';
    wrapper.element.style.opacity = '0';
    
    setTimeout(() => {
      wrapper.element.style.backgroundImage = `url('${nextImage}')`;
      wrapper.element.style.opacity = '1';
    }, 1000);
  }

  function startCarousel() {
    // Cambiar cada 60 segundos (1 minuto)
    setInterval(() => {
      changeBackground(wrappers.productos);
      changeBackground(wrappers.servicios);
      changeBackground(wrappers.nuevos);
    }, 60000); // 60000 ms = 1 minuto
  }

  startCarousel();
}