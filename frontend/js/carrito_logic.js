/* ================= CONFIG ================= */
const ORIGIN = window.location.origin;
let BASE_APP = `${ORIGIN}/LYRIUM`;
let API_BASE = `${BASE_APP}/backend/api`;
let FRONT_IMG = `${BASE_APP}/frontend/img`;

async function ensureBaseApp() {
    try {
        const r = await fetch(`${API_BASE}/producto.php`, { cache: "no-store" });
        if (r.ok) return;
    } catch (e) { }
    BASE_APP = `${ORIGIN}/lyrium`;
    API_BASE = `${BASE_APP}/backend/api`;
    FRONT_IMG = `${BASE_APP}/frontend/img`;
}

function money(n) { return "S/ " + Number(n || 0).toFixed(2); }
function normalize(s) { return String(s || "").toLowerCase().trim(); }

function resolveImg(url) {
    const fallback = `${FRONT_IMG}/no-image.png`;
    if (!url) return fallback;
    if (/^https?:\/\//i.test(url)) return url;
    if (String(url).startsWith("/")) return ORIGIN + url;
    if (String(url).includes("LYRIUM") || String(url).includes("lyrium")) return url;
    return `${BASE_APP}/${String(url).replace(/^\.?\//, "")}`;
}

/* ============================================================
   ✅ FIX: si header.php no trae el drawer, lo inyectamos
============================================================ */
function ensureCartDrawerMarkup() {
    if (document.getElementById("cartOverlay") && document.getElementById("cartDrawer")) return;

    const wrap = document.createElement("div");
    wrap.innerHTML = `
<div id="cartOverlay" class="hidden fixed inset-0 z-[200] modal-backdrop"></div>

<aside id="cartDrawer"
  class="fixed top-0 right-0 h-full w-full sm:w-[760px] bg-white z-[220] shadow-2xl border-l border-sky-100 translate-x-full transition-transform duration-300">
  <div class="h-full flex flex-col">

    <div class="px-4 py-4 border-b border-sky-50"
         style="background: linear-gradient(90deg, rgba(35,180,254,.10), rgba(132,204,22,.07));">
      <div class="flex items-center justify-between">
        <div class="flex items-center gap-2">
          <span class="w-10 h-10 rounded-2xl bg-white/85 border border-sky-100 grid place-items-center">
            <i class="ph-shopping-cart icon-brand text-xl"></i>
          </span>
          <div>
            <p class="text-sm text-ink leading-tight">Tu carrito</p>
            <p class="text-xs text-muted leading-tight" id="cartItemsLabel">0 items</p>
          </div>
        </div>
        <button id="btnCloseCart"
                class="w-10 h-10 rounded-2xl bg-white/85 border border-sky-100 text-ink hover:bg-white grid place-items-center">
          <i class="ph-x text-xl"></i>
        </button>
      </div>
    </div>

    <div class="flex-1 overflow-auto">
      <div class="grid grid-cols-1 md:grid-cols-2 gap-0">

        <div class="p-4 border-b md:border-b-0 md:border-r border-sky-50 bg-white/70">
          <div class="flex items-center justify-between">
            <p class="text-sm text-ink inline-flex items-center gap-2">
              <span class="w-8 h-8 rounded-xl bg-sky-50 grid place-items-center">
                <i class="ph-sparkle icon-brand text-base"></i>
              </span>
              Recomendados para ti
            </p>
            <button id="btnRefreshSugLeft"
                    class="text-xs px-3 py-2 rounded-xl bg-gray-100 hover:bg-gray-200 text-ink inline-flex items-center gap-2">
              <i class="ph-arrows-clockwise icon-brand text-base"></i> Cambiar
            </button>
          </div>

          <div id="cartSuggestions" class="mt-3"></div>
        </div>

        <div class="p-4">
          <div id="cartList"></div>

          <div class="mt-4 rounded-2xl border border-sky-100 bg-white/90 p-4">
            <div class="flex items-center justify-between text-sm text-muted">
              <span class="inline-flex items-center gap-2">
                <i class="ph-calculator icon-brand"></i> Subtotal
              </span>
              <span id="cartSubtotal">S/ 0.00</span>
            </div>

            <div class="mt-3 h-px bg-sky-50"></div>

            <div class="mt-3 flex items-center justify-between text-[15px] text-ink">
              <span class="inline-flex items-center gap-2">
                <i class="ph-credit-card icon-brand"></i> Total
              </span>
              <span id="cartTotal">S/ 0.00</span>
            </div>

            <button id="btnProceedToPay"
                    class="mt-3 w-full px-4 py-3 rounded-2xl btn-brand inline-flex items-center justify-center gap-2">
              <i class="ph-lock-key-open"></i>
              Proceder al pago
            </button>
          </div>
        </div>

      </div>
    </div>

  </div>
</aside>
`;
    document.body.appendChild(wrap);
}

/* ================= DOM CARRITO ================= */
function getCartDom() {
    return {
        overlay: document.getElementById("cartOverlay"),
        drawer: document.getElementById("cartDrawer"),
        btnOpen: document.getElementById("btnOpenCart"),
        btnOpenMobile: document.getElementById("btnOpenCartMobile"),
        btnClose: document.getElementById("btnCloseCart"),
        badge: document.getElementById("cartBadge"),
        badgeMobile: document.getElementById("cartBadgeMobile"),
        cartList: document.getElementById("cartList"),
        cartSubtotal: document.getElementById("cartSubtotal"),
        cartTotal: document.getElementById("cartTotal"),
        cartItemsLabel: document.getElementById("cartItemsLabel"),
        cartSuggestions: document.getElementById("cartSuggestions"),
    };
}

function openCart() {
    const { overlay, drawer } = getCartDom();
    overlay?.classList.remove("hidden");
    drawer?.classList.remove("translate-x-full");
}
function closeCart() {
    const { overlay, drawer } = getCartDom();
    overlay?.classList.add("hidden");
    drawer?.classList.add("translate-x-full");
}

/* ================= API ================= */
async function apiGetCart() {
    const r = await fetch(`${API_BASE}/carrito.php?cliente_id=${CLIENTE_ID}&moneda=PEN`, { cache: "no-store" });
    return r.json();
}
async function apiGetProducts() {
    const r = await fetch(`${API_BASE}/producto.php`, { cache: "no-store" });
    return r.json();
}
async function apiGetProductDetail(id) {
    const r = await fetch(`${API_BASE}/producto.php?id=${encodeURIComponent(id)}`, { cache: "no-store" });
    return r.json();
}
async function apiAddItem(productoId) {
    const r = await fetch(`${API_BASE}/carrito_item.php`, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ cliente_id: CLIENTE_ID, producto_id: Number(productoId), cantidad: 1 })
    });
    return r.json();
}
async function apiSetItemCantidad(itemId, cantidad) {
    const r = await fetch(`${API_BASE}/carrito_item.php`, {
        method: "PUT",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ item_id: Number(itemId), cantidad: Number(cantidad) })
    });
    return r.json();
}
async function apiDeleteItem(itemId) {
    const r = await fetch(`${API_BASE}/carrito_item.php`, {
        method: "DELETE",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: `id=${encodeURIComponent(itemId)}`
    });
    return r.json();
}

/* ================= BADGES ================= */
function setBadges(n) {
    const { badge, badgeMobile } = getCartDom();
    if (badge) badge.textContent = n;
    if (badgeMobile) badgeMobile.textContent = n;
}

/* ================= Estrellas ================= */
function renderStars(rating) {
    const r = Number(rating || 0);
    let html = `<span class="stars-row">`;
    for (let i = 1; i <= 5; i++) {
        const filled = r >= i - 0.25;
        html += `<i class="ph-star${filled ? '-fill' : ''} ${filled ? 'text-amber-400' : 'text-gray-300'}"></i>`;
    }
    html += `</span>`;
    return html;
}

/* ================= CARRITO ================= */
let currentItems = [];
let allProductsCache = [];

function getProductFromCache(productoId) {
    return (allProductsCache || []).find(p => String(p.id) === String(productoId)) || null;
}
function pickSuggestionsFromProducts(products, excludeIds = [], max = 6) {
    const pool = (products || []).filter(p => !excludeIds.includes(Number(p.id)));
    const shuffled = pool.sort(() => Math.random() - 0.5);
    return shuffled.slice(0, max);
}

function renderSuggestionsHTML(list) {
    const { cartSuggestions } = getCartDom();
    if (!cartSuggestions) return;

    if (!list || !list.length) { cartSuggestions.innerHTML = ""; return; }

    cartSuggestions.innerHTML = `
<div class="grid grid-cols-2 gap-3">
  ${list.map(p => {
        const price = getPrice(p);
        const cat = p.categoria_nombre ? ` • ${p.categoria_nombre}` : "";
        return `
      <div class="lyrium-card rounded-2xl p-2">
        <div class="aspect-square rounded-xl overflow-hidden bg-gray-100 relative">
          <img src="${resolveImg(p.imagen_url)}"
               onerror="this.src='${FRONT_IMG}/no-image.png'"
               class="w-full h-full object-cover">
          <span class="absolute bottom-2 left-2 text-[10px] px-2 py-1 rounded-full soft-pill text-ink">
            <i class="ph-leaf icon-brand"></i> Lyrium
          </span>
        </div>

        <p class="mt-2 text-xs text-ink line-clamp-2">${p.nombre}</p>

        <div class="mt-1 flex items-center justify-between gap-2">
          <span class="text-[12px] text-emerald-700 inline-flex items-center gap-1">
            <i class="ph-currency-dollar icon-brand"></i> ${money(price)}
          </span>

          <span class="rating-pill text-[11px]">
            ${renderStars(p.rating_promedio || 0)}
            <span class="text-muted">(${Number(p.rating_total || 0)})</span>
          </span>
        </div>

        <p class="text-[11px] text-muted mt-1 line-clamp-1"><i class="ph-hash icon-brand"></i> ${p.sku || "—"}${cat}</p>

        <button data-sug-add="${p.id}"
                class="mt-2 w-full py-2 rounded-xl btn-brand text-xs inline-flex items-center justify-center gap-2">
          <i class="ph-plus-circle text-base"></i> Añadir
        </button>
      </div>
    `;
    }).join("")}
</div>
`;

    cartSuggestions.querySelectorAll("[data-sug-add]").forEach(b => {
        b.onclick = async () => {
            const id = b.getAttribute("data-sug-add");
            await apiAddItem(id);
            await refreshCart();
            openCart();
        };
    });

    document.getElementById("btnRefreshSugLeft")?.addEventListener("click", () => {
        const exclude = currentItems.map(i => Number(i.producto_id));
        renderSuggestionsHTML(pickSuggestionsFromProducts(allProductsCache, exclude, 6));
    });
}

function calcCartTotals(items) {
    let totalFinal = 0;
    let subtotalBase = 0;
    let baseFound = false;

    (items || []).forEach(it => {
        const cant = Number(it.cantidad || 0);
        const puFinal = Number(it.precio_unitario || 0);
        totalFinal += (puFinal * cant);

        const p = getProductFromCache(it.producto_id);
        if (p) {
            const base = Number(p.precio || 0);
            if (base > 0) {
                subtotalBase += (base * cant);
                baseFound = true;
            }
        }
    });

    const descuento = baseFound ? Math.max(0, subtotalBase - totalFinal) : 0;
    return { totalFinal, subtotalBase: baseFound ? subtotalBase : null, descuento: baseFound ? descuento : null };
}

function renderCart(data) {
    const { cartList, cartSubtotal, cartTotal, cartItemsLabel } = getCartDom();
    const items = data.items || [];
    currentItems = items;

    const totalItems = data.cantidad_total || items.reduce((a, i) => a + Number(i.cantidad || 0), 0);
    setBadges(totalItems);
    if (cartItemsLabel) cartItemsLabel.textContent = `${totalItems} items`;

    if (!cartList) return;

    if (!items.length) {
        cartList.innerHTML = `
  <div class="text-center py-10 text-muted">
    <div class="mx-auto w-14 h-14 rounded-2xl bg-sky-50 grid place-items-center">
      <i class="ph-shopping-cart icon-brand text-3xl"></i>
    </div>
    <p class="mt-3 text-ink">Tu carrito está vacío</p>
    <p class="text-xs mt-1">Agrega productos para verlos aquí.</p>
  </div>`;
        cartSubtotal && (cartSubtotal.textContent = money(0));
        cartTotal && (cartTotal.textContent = money(0));
        renderSuggestionsHTML(pickSuggestionsFromProducts(allProductsCache, [], 6));
        return;
    }

    const totals = calcCartTotals(items);

    const apiSubtotal = (data.subtotal !== undefined) ? Number(data.subtotal) : null;
    const apiDescuento = (data.descuento_total !== undefined) ? Number(data.descuento_total) : null;
    const apiTotal = (data.total !== undefined) ? Number(data.total) : null;

    const totalFinal = (apiTotal !== null) ? apiTotal : totals.totalFinal;
    const subtotalShow =
        (apiSubtotal !== null) ? apiSubtotal :
            (totals.subtotalBase !== null) ? totals.subtotalBase :
                totalFinal;

    const descuentoShow =
        (apiDescuento !== null) ? apiDescuento :
            (totals.descuento !== null) ? totals.descuento :
                0;

    cartList.innerHTML = `
<div class="space-y-3">
  ${items.map(it => {
        const cant = Number(it.cantidad || 0);
        const puFinal = Number(it.precio_unitario || 0);
        const lineTotal = cant * puFinal;

        const p = getProductFromCache(it.producto_id);
        const base = p ? Number(p.precio || 0) : 0;
        const hasDisc = base > 0 && puFinal < base;
        const discPct = hasDisc ? Math.round(((base - puFinal) / base) * 100) : 0;

        const cat = p?.categoria_nombre ? p.categoria_nombre : "";
        const rating = Number(p?.rating_promedio || 0);
        const ratingTotal = Number(p?.rating_total || 0);

        return `
      <div class="lyrium-card rounded-2xl p-3">
        <div class="flex gap-3">
          <button class="w-16 h-16 rounded-2xl overflow-hidden bg-gray-100 border border-sky-100 shrink-0"
                  title="Ver detalle"
                  data-view="${it.producto_id}">
            <img src="${resolveImg(it.imagen_url || p?.imagen_url)}"
                 onerror="this.src='${FRONT_IMG}/no-image.png'"
                 class="w-full h-full object-cover">
          </button>

          <div class="flex-1 min-w-0">
            <div class="flex justify-between gap-2">
              <div class="min-w-0">
                <button class="text-left w-full" data-view="${it.producto_id}">
                  <p class="text-ink text-sm truncate">${it.producto_nombre || p?.nombre || "Producto"}</p>
                </button>

                <div class="mt-1 flex flex-wrap items-center gap-2">
                  <span class="text-xs text-muted inline-flex items-center gap-1">
                    <i class="ph-tag icon-brand"></i> Unit: ${money(puFinal)}
                  </span>

                  ${hasDisc ? `
                    <span class="text-[11px] px-2 py-1 rounded-full soft-chip text-emerald-700 inline-flex items-center gap-1">
                      <i class="ph-percent icon-brand"></i> -${discPct}%
                    </span>
                  ` : ``}

                  ${cat ? `
                    <span class="text-[11px] px-2 py-1 rounded-full soft-chip text-muted inline-flex items-center gap-1">
                      <i class="ph-folder-simple icon-brand"></i> ${cat}
                    </span>
                  ` : ``}

                  ${(ratingTotal > 0) ? `
                    <span class="rating-pill text-[11px]">
                      ${renderStars(rating)}
                      <span class="text-muted">(${ratingTotal})</span>
                    </span>
                  ` : ``}
                </div>
              </div>

              <button class="text-red-600 text-xs hover:underline inline-flex items-center gap-1" data-del="${it.id}">
                <i class="ph-trash"></i> Eliminar
              </button>
            </div>

            <div class="mt-2 flex justify-between items-center">
              <div class="flex items-center border border-sky-100 rounded-2xl overflow-hidden bg-white">
                <button data-dec="${it.id}" class="px-3 py-2 hover:bg-gray-50 inline-flex items-center gap-1">
                  <i class="ph-minus icon-brand"></i>
                </button>
                <span class="px-3 text-sm text-ink">${cant}</span>
                <button data-inc="${it.id}" class="px-3 py-2 hover:bg-gray-50 inline-flex items-center gap-1">
                  <i class="ph-plus icon-brand"></i>
                </button>
              </div>

              <span class="text-ink inline-flex items-center gap-1">
                <i class="ph-receipt icon-brand"></i> ${money(lineTotal)}
              </span>
            </div>

            ${hasDisc ? `
              <div class="mt-2 text-[12px] text-muted">
                <span class="inline-flex items-center gap-2">
                  <i class="ph-sparkle text-emerald-600"></i>
                  Ahorro aprox.: ${money((base - puFinal) * cant)}
                  <span class="text-gray-400">(${money(base)} → ${money(puFinal)})</span>
                </span>
              </div>
            ` : ``}
          </div>
        </div>
      </div>
    `;
    }).join("")}

  <div class="rounded-2xl border border-sky-100 bg-white/90 p-4">
    <div class="flex items-center justify-between text-sm text-muted">
      <span class="inline-flex items-center gap-2">
        <i class="ph-calculator icon-brand"></i> Subtotal
      </span>
      <span>${money(subtotalShow)}</span>
    </div>

    <div class="mt-2 flex items-center justify-between text-sm text-muted">
      <span class="inline-flex items-center gap-2">
        <i class="ph-tag icon-brand"></i> Descuentos
      </span>
      <span class="text-emerald-700">- ${money(descuentoShow)}</span>
    </div>

    <div class="mt-3 h-px bg-sky-50"></div>

    <div class="mt-3 flex items-center justify-between text-[15px] text-ink">
      <span class="inline-flex items-center gap-2">
        <i class="ph-credit-card icon-brand"></i> Total
      </span>
      <span>${money(totalFinal)}</span>
    </div>

    <p class="mt-2 text-xs text-muted inline-flex items-center gap-2">
      <i class="ph-info icon-brand"></i>
      Totales estimados. Si tu API entrega IGV/envío, se mostrará en checkout.
    </p>

    <div class="mt-3">
      <button id="btnOpenPayModalFromCart"
              class="w-full px-4 py-3 rounded-2xl btn-brand inline-flex items-center justify-center gap-2">
        <i class="ph-lock-key-open"></i>
        Proceder al pago
      </button>
    </div>
  </div>
</div>
`;

    if (cartSubtotal) cartSubtotal.textContent = money(subtotalShow);
    if (cartTotal) cartTotal.textContent = money(totalFinal);

    const exclude = items.map(i => Number(i.producto_id));
    renderSuggestionsHTML(pickSuggestionsFromProducts(allProductsCache, exclude, 6));

    cartList.querySelectorAll("[data-inc]").forEach(b => {
        b.onclick = () => {
            const it = currentItems.find(i => String(i.id) === String(b.dataset.inc));
            if (!it) return;
            apiSetItemCantidad(it.id, Number(it.cantidad) + 1).then(refreshCart);
        };
    });
    cartList.querySelectorAll("[data-dec]").forEach(b => {
        b.onclick = () => {
            const it = currentItems.find(i => String(i.id) === String(b.dataset.dec));
            if (!it) return;
            if (Number(it.cantidad) > 1) {
                apiSetItemCantidad(it.id, Number(it.cantidad) - 1).then(refreshCart);
            }
        };
    });
    cartList.querySelectorAll("[data-del]").forEach(b => {
        b.onclick = () => apiDeleteItem(b.dataset.del).then(refreshCart);
    });

    cartList.querySelectorAll("[data-view]").forEach(b => {
        b.onclick = async () => {
            const pid = b.getAttribute("data-view");
            if (!pid) return;
            await openProductDetailModal(pid);
        };
    });

    document.getElementById("btnOpenPayModalFromCart")?.addEventListener("click", async () => {
        await refreshCart();
        buildPaymentModalFromCart();
        renderPaySuggestions();
        closeCart();
        openPaymentModal();
    });
}

async function refreshCart() {
    const { cartList } = getCartDom();
    try {
        const data = await apiGetCart();
        if (data && data.success) {
            renderCart(data);
            bindProceedToPayButtons();
        } else {
            setBadges(0);
            if (cartList) cartList.innerHTML = `<p class="text-sm text-red-500">No se pudo cargar el carrito.</p>`;
        }
    } catch (e) {
        setBadges(0);
        if (cartList) cartList.innerHTML = `<p class="text-sm text-red-500">Error al conectar con el carrito.</p>`;
    }
}

/* ================= MODAL PAGO ================= */
const payModal = document.getElementById("payModal");
const payModalOverlay = document.getElementById("payModalOverlay");
const payModalClose = document.getElementById("payModalClose");

const payItemsLabel = document.getElementById("payItemsLabel");
const payItems = document.getElementById("payItems");
const paySubtotal = document.getElementById("paySubtotal");
const payDiscount = document.getElementById("payDiscount");
const payTotal = document.getElementById("payTotal");
const payEditCart = document.getElementById("payEditCart");

const payContinueShopping = document.getElementById("payContinueShopping");
const payGoToCheckout = document.getElementById("payGoToCheckout");
const payMethodSelected = document.getElementById("payMethodSelected");

const paySuggestions = document.getElementById("paySuggestions");
const payProcessing = document.getElementById("payProcessing");

function openPaymentModal() { payModal?.classList.remove("hidden"); }
function closePaymentModal() { payModal?.classList.add("hidden"); }
function showPayProcessing() { payProcessing?.classList.remove("hidden"); }
function hidePayProcessing() { payProcessing?.classList.add("hidden"); }

payModalOverlay?.addEventListener("click", closePaymentModal);
payModalClose?.addEventListener("click", closePaymentModal);
document.addEventListener("keydown", (e) => {
    if (e.key === "Escape" && payModal && !payModal.classList.contains("hidden")) closePaymentModal();
});

document.querySelectorAll(".payMethod").forEach(btn => {
    btn.addEventListener("click", () => {
        document.querySelectorAll(".payMethod").forEach(x => x.classList.remove("ring-2", "ring-sky-200"));
        btn.classList.add("ring-2", "ring-sky-200");
        if (payMethodSelected) payMethodSelected.value = btn.dataset.method || "card";
    });
});
document.querySelector('.payMethod[data-method="card"]')?.classList.add("ring-2", "ring-sky-200");

function buildPaymentModalFromCart() {
    const items = currentItems || [];
    const totalItems = items.reduce((a, i) => a + Number(i.cantidad || 0), 0);

    if (payItemsLabel) payItemsLabel.textContent = `${totalItems} items`;

    if (!items.length) {
        if (payItems) {
            payItems.innerHTML = `
    <div class="text-center py-8 text-muted">
      <div class="mx-auto w-14 h-14 rounded-2xl bg-sky-50 grid place-items-center">
        <i class="ph-warning-circle icon-brand text-3xl"></i>
      </div>
      <p class="mt-3 text-ink">Tu carrito está vacío</p>
      <p class="text-xs mt-1">Agrega productos antes de pagar.</p>
    </div>
  `;
        }
        paySubtotal && (paySubtotal.textContent = money(0));
        payDiscount && (payDiscount.textContent = `- ${money(0)}`);
        payTotal && (payTotal.textContent = money(0));
        if (paySuggestions) paySuggestions.innerHTML = "";
        return;
    }

    const totals = calcCartTotals(items);
    const { cartTotal, cartSubtotal } = getCartDom();

    const drawerTotal = Number((cartTotal?.textContent || "0").replace(/[^\d.]/g, "")) || totals.totalFinal;
    const drawerSub = Number((cartSubtotal?.textContent || "0").replace(/[^\d.]/g, "")) || (totals.subtotalBase ?? drawerTotal);
    const discount = Math.max(0, drawerSub - drawerTotal);

    if (payItems) {
        payItems.innerHTML = items.map(it => {
            const cant = Number(it.cantidad || 0);
            const pu = Number(it.precio_unitario || 0);
            const lt = cant * pu;

            const p = getProductFromCache(it.producto_id);
            const img = resolveImg(it.imagen_url || p?.imagen_url);

            return `
    <div class="rounded-2xl border border-sky-50 bg-white p-3 flex gap-3">
      <div class="w-12 h-12 rounded-2xl overflow-hidden bg-gray-100 border border-sky-100 shrink-0">
        <img src="${img}" onerror="this.src='${FRONT_IMG}/no-image.png'" class="w-full h-full object-cover">
      </div>

      <div class="flex-1 min-w-0">
        <p class="text-sm text-ink truncate">${it.producto_nombre || p?.nombre || "Producto"}</p>
        <p class="text-xs text-muted mt-1 inline-flex items-center gap-2">
          <span class="inline-flex items-center gap-1"><i class="ph-hash icon-brand"></i> ${p?.sku || "—"}</span>
          <span class="inline-flex items-center gap-1"><i class="ph-x icon-brand"></i> ${cant}</span>
          <span class="inline-flex items-center gap-1"><i class="ph-tag icon-brand"></i> ${money(pu)}</span>
        </p>
      </div>

      <div class="text-right">
        <p class="text-sm text-ink inline-flex items-center gap-1">
          <i class="ph-receipt icon-brand"></i> ${money(lt)}
        </p>
      </div>
    </div>
  `;
        }).join("");
    }

    paySubtotal && (paySubtotal.textContent = money(drawerSub));
    payDiscount && (payDiscount.textContent = `- ${money(discount)}`);
    payTotal && (payTotal.textContent = money(drawerTotal));
}

function renderPaySuggestions() {
    if (!paySuggestions) return;

    const exclude = (currentItems || []).map(i => Number(i.producto_id));
    const list = pickSuggestionsFromProducts(allProductsCache, exclude, 4);

    if (!list || !list.length) {
        paySuggestions.innerHTML = "";
        return;
    }

    paySuggestions.innerHTML = `
<div class="rounded-2xl border border-sky-100 bg-white/90 p-4">
  <div class="flex items-center justify-between">
    <p class="text-sm text-ink inline-flex items-center gap-2">
      <span class="w-8 h-8 rounded-xl bg-sky-50 grid place-items-center">
        <i class="ph-sparkle icon-brand text-base"></i>
      </span>
      Sugerencias
    </p>
    <button id="btnPayRefreshSug"
            class="text-xs px-3 py-2 rounded-xl bg-gray-100 hover:bg-gray-200 text-ink inline-flex items-center gap-2">
      <i class="ph-arrows-clockwise icon-brand text-base"></i> Cambiar
    </button>
  </div>

  <div class="mt-3 grid grid-cols-2 gap-3">
    ${list.map(p => {
        const price = getPrice(p);
        const rating = Number(p.rating_promedio || 0);
        const ratingTotal = Number(p.rating_total || 0);

        return `
        <div class="lyrium-card sug-card-sm">
          <div class="sug-img aspect-[4/3]">
            <img src="${resolveImg(p.imagen_url)}"
                 onerror="this.src='${FRONT_IMG}/no-image.png'"
                 class="w-full h-full object-cover">
          </div>

          <p class="mt-2 sug-title text-ink line-clamp-2">${p.nombre}</p>

          <div class="mt-1 flex items-center justify-between gap-2">
            <span class="sug-price text-emerald-700 inline-flex items-center gap-1">
              <i class="ph-currency-dollar icon-brand"></i> ${money(price)}
            </span>

            ${(ratingTotal > 0) ? `
              <span class="rating-pill text-[11px]">
                ${renderStars(rating)}
                <span class="text-muted">(${ratingTotal})</span>
              </span>
            ` : ``}
          </div>

          <button data-pay-sug-add="${p.id}"
                  class="mt-2 w-full btn-brand sug-btn inline-flex items-center justify-center gap-2">
            <i class="ph-plus-circle text-base"></i> Añadir
          </button>
        </div>
      `;
    }).join("")}
  </div>
</div>
`;

    paySuggestions.querySelectorAll("[data-pay-sug-add]").forEach(b => {
        b.onclick = async () => {
            const id = b.getAttribute("data-pay-sug-add");
            await apiAddItem(id);
            await refreshCart();
            buildPaymentModalFromCart();
            renderPaySuggestions();
        };
    });

    document.getElementById("btnPayRefreshSug")?.addEventListener("click", () => {
        renderPaySuggestions();
    });
}

payEditCart?.addEventListener("click", () => {
    closePaymentModal();
    openCart();
});

payContinueShopping?.addEventListener("click", () => {
    closePaymentModal();
    closeCart();
    window.scrollTo({ top: 0, behavior: "smooth" });
});

payGoToCheckout?.addEventListener("click", async () => {
    const total = Number((payTotal?.textContent || "0").replace(/[^\d.]/g, "")) || 0;
    const items = (currentItems || []).reduce((a, i) => a + Number(i.cantidad || 0), 0);

    if (items <= 0 || total <= 0) {
        alert("Tu carrito está vacío. Agrega productos antes de pagar.");
        return;
    }

    // ✅ Guardar “resumen de compra” para que checkout.php lo use (misma UI)
    const payload = {
        cliente_id: CLIENTE_ID,
        method: (payMethodSelected?.value || "card"),
        items: (currentItems || []).map(it => ({
            id: it.id,
            producto_id: it.producto_id,
            nombre: it.producto_nombre,
            cantidad: it.cantidad,
            precio_unitario: it.precio_unitario,
            imagen_url: it.imagen_url
        })),
        totals: {
            subtotal: (paySubtotal?.textContent || "S/ 0.00"),
            descuento: (payDiscount?.textContent || "- S/ 0.00"),
            total: (payTotal?.textContent || "S/ 0.00")
        },
        ts: Date.now()
    };

    sessionStorage.setItem("ly_checkout_payload", JSON.stringify(payload));

    // ✅ OPCIÓN 1: seguir usando MODAL “processing” (tu mismo efecto)
    showPayProcessing();
    await new Promise(r => setTimeout(r, 650));

    // ✅ OPCIÓN 2: ir a página checkout (formulario web)
    const method = encodeURIComponent(payload.method);
    window.location.href = `${BASE_APP}/frontend/checkout.php?method=${method}`;
});

/* ✅ Hook universal a “PROCEDER AL PAGO” */
function bindProceedToPayButtons() {
    const candidates = [
        document.getElementById("btnProceedToPay"),
        document.getElementById("btnOpenPayModalFromCart"),
    ].filter(Boolean);

    document.querySelectorAll("button,a").forEach(el => {
        const t = (el.textContent || "").toLowerCase().trim();
        if (t.includes("proceder") && t.includes("pago")) candidates.push(el);
    });

    const unique = Array.from(new Set(candidates));
    unique.forEach(btn => {
        if (btn.dataset.boundPayModal === "1") return;
        btn.dataset.boundPayModal = "1";

        btn.addEventListener("click", async (e) => {
            e.preventDefault();
            e.stopPropagation();

            await refreshCart();
            buildPaymentModalFromCart();
            renderPaySuggestions();
            closeCart();
            openPaymentModal();
        });
    });
}

/* ================= PRODUCTOS ================= */
const searchInput = document.getElementById("productSearch");
const btnClearSearch = document.getElementById("btnClearSearch");
const sortSelect = document.getElementById("sortSelect");
const emptyStateProducts = document.getElementById("emptyStateProducts");
const countLabel = document.getElementById("countLabel");
const countLabelMobile = document.getElementById("countLabelMobile");
const grid = document.getElementById("productsGrid");

let productsAll = [];
let productsFiltered = [];

function getPrice(p) { return Number(p.precio_final || p.precio_oferta || p.precio || 0); }
function getBase(p) { return Number(p.precio || 0); }

function applySort(list) {
    const v = sortSelect?.value || "recent";
    const arr = [...list];

    if (v === "priceAsc") arr.sort((a, b) => getPrice(a) - getPrice(b));
    else if (v === "priceDesc") arr.sort((a, b) => getPrice(b) - getPrice(a));
    else if (v === "nameAsc") arr.sort((a, b) => String(a.nombre || "").localeCompare(String(b.nombre || ""), "es"));
    else if (v === "ratingDesc") arr.sort((a, b) => Number(b.rating_promedio || 0) - Number(a.rating_promedio || 0));

    return arr;
}

/* ====== PRECIO: state ====== */
let priceBounds = { min: 0, max: 0 };
let priceFilter = { min: 0, max: 0 };

const priceMinEl = document.getElementById("priceMin");
const priceMaxEl = document.getElementById("priceMax");
const lblPriceMin = document.getElementById("lblPriceMin");
const lblPriceMax = document.getElementById("lblPriceMax");
const rangeFill = document.getElementById("rangeFill");
const btnPriceReset = document.getElementById("btnPriceReset");

function fmtIntS(n) { return "S/ " + Math.round(Number(n || 0)); }

function updateRangeFill() {
    if (!rangeFill || !priceMinEl || !priceMaxEl) return;
    const min = Number(priceMinEl.value);
    const max = Number(priceMaxEl.value);
    const a = Number(priceMinEl.min);
    const b = Number(priceMinEl.max);
    const left = ((min - a) / (b - a)) * 100;
    const right = ((max - a) / (b - a)) * 100;
    rangeFill.style.left = left + "%";
    rangeFill.style.right = (100 - right) + "%";
}
function setPriceUI(minVal, maxVal) {
    if (lblPriceMin) lblPriceMin.textContent = fmtIntS(minVal);
    if (lblPriceMax) lblPriceMax.textContent = fmtIntS(maxVal);
    updateRangeFill();
}
function initPriceFilterFromProducts(products) {
    if (!priceMinEl || !priceMaxEl) return;

    const prices = (products || []).map(getPrice).filter(n => !isNaN(n) && n > 0);
    const minP = prices.length ? Math.floor(Math.min(...prices)) : 0;
    const maxP = prices.length ? Math.ceil(Math.max(...prices)) : 0;

    priceBounds = { min: minP, max: maxP };
    priceFilter = { min: minP, max: maxP };

    priceMinEl.min = String(minP);
    priceMinEl.max = String(maxP);
    priceMaxEl.min = String(minP);
    priceMaxEl.max = String(maxP);

    priceMinEl.value = String(minP);
    priceMaxEl.value = String(maxP);

    setPriceUI(minP, maxP);
}
function bindPriceFilterEvents() {
    if (!priceMinEl || !priceMaxEl) return;

    const sync = () => {
        let minV = Number(priceMinEl.value);
        let maxV = Number(priceMaxEl.value);

        if (minV > maxV - 1) {
            minV = maxV - 1;
            priceMinEl.value = String(minV);
        }
        if (maxV < minV + 1) {
            maxV = minV + 1;
            priceMaxEl.value = String(maxV);
        }

        priceFilter = { min: minV, max: maxV };
        setPriceUI(minV, maxV);
        filterProducts();
    };

    priceMinEl.addEventListener("input", sync);
    priceMaxEl.addEventListener("input", sync);

    btnPriceReset?.addEventListener("click", () => {
        priceMinEl.value = String(priceBounds.min);
        priceMaxEl.value = String(priceBounds.max);
        priceFilter = { ...priceBounds };
        setPriceUI(priceBounds.min, priceBounds.max);
        filterProducts();
    });

    updateRangeFill();
}

function filterProducts() {
    const q = normalize(searchInput?.value);
    btnClearSearch?.classList.toggle("hidden", !q);

    // 1) texto
    let list = (!q)
        ? [...productsAll]
        : productsAll.filter(p => {
            const name = normalize(p.nombre);
            const sku = normalize(p.sku);
            const cat = normalize(p.categoria_nombre);
            const desc = normalize(p.descripcion_corta);
            return name.includes(q) || sku.includes(q) || cat.includes(q) || desc.includes(q);
        });

    // 2) precio
    if (priceMinEl && priceMaxEl) {
        const minP = Number(priceFilter.min);
        const maxP = Number(priceFilter.max);
        list = list.filter(p => {
            const pr = getPrice(p);
            return pr >= minP && pr <= maxP;
        });
    }

    // 3) sort
    productsFiltered = applySort(list);
    renderProductsGrid(productsFiltered);
}

function renderProductsGrid(list) {
    const items = list || [];
    if (countLabel) countLabel.textContent = items.length;
    if (countLabelMobile) countLabelMobile.textContent = items.length;

    if (!grid) return;

    if (!items.length) {
        grid.innerHTML = "";
        emptyStateProducts?.classList.remove("hidden");
        return;
    }
    emptyStateProducts?.classList.add("hidden");

    grid.innerHTML = items.map(p => {
        const finalPrice = getPrice(p);
        const base = getBase(p);
        const hasOffer = finalPrice > 0 && base > 0 && finalPrice < base;
        const pct = hasOffer ? (Number(p.descuento_pct || 0) || Math.round(((base - finalPrice) / base) * 100)) : 0;

        const stock = Number(p.stock || 0);
        const out = (p.estado_stock === "out_of_stock") || (stock <= 0);

        const cat = p.categoria_nombre ? p.categoria_nombre : "General";
        const rating = Number(p.rating_promedio || 0);
        const ratingTotal = Number(p.rating_total || 0);

        return `
  <article class="group lyrium-card rounded-3xl overflow-hidden">
    <div class="relative">
      <button data-view="${p.id}" class="block w-full text-left">
        <div class="aspect-square bg-gray-100 overflow-hidden">
          <img src="${resolveImg(p.imagen_url)}"
               onerror="this.src='${FRONT_IMG}/no-image.png'"
               class="w-full h-full object-cover group-hover:scale-[1.05] transition duration-300">
        </div>
      </button>

      <span class="absolute top-3 left-3 inline-flex items-center gap-1 text-[11px] px-3 py-1 rounded-full soft-pill text-ink shadow-sm">
        <i class="ph-leaf icon-brand text-sm"></i> Lyrium
      </span>

      ${hasOffer ? `
        <span class="absolute top-3 right-3 inline-flex items-center gap-1 text-[11px] px-3 py-1 rounded-full bg-emerald-600 text-white shadow">
          <i class="ph-tag text-sm"></i> -${pct}%
        </span>` : ``}

      <button data-quickadd="${p.id}"
              title="Añadir rápido"
              ${out ? "disabled" : ""}
              class="absolute bottom-3 right-3 w-11 h-11 rounded-2xl bg-white/95 border border-sky-100 text-ink shadow-sm grid place-items-center hover:shadow transition disabled:opacity-50 disabled:cursor-not-allowed">
        <i class="ph-plus-circle icon-brand text-2xl"></i>
      </button>
    </div>

    <div class="p-4">
      <div class="flex items-start justify-between gap-2">
        <button data-view="${p.id}" class="text-left flex-1">
          <p class="text-ink leading-snug line-clamp-2 min-h-[42px]">${p.nombre}</p>
        </button>

        <span class="shrink-0 text-[11px] px-2 py-1 rounded-full bg-gray-100 text-ink inline-flex items-center gap-1">
          <i class="ph-barcode icon-brand text-sm"></i> ${p.sku ? p.sku : "—"}
        </span>
      </div>

      <p class="mt-2 text-[12px] text-muted line-clamp-2">
        ${p.descripcion_corta ? p.descripcion_corta : "Producto seleccionado para tu bienestar."}
      </p>

      <div class="mt-3 flex items-center justify-between gap-2">
        <span class="text-[12px] px-2 py-1 rounded-full soft-chip text-ink inline-flex items-center gap-1">
          <i class="ph-folder-simple icon-brand"></i> ${cat}
        </span>

        ${(ratingTotal > 0) ? `
          <span class="rating-pill text-[11px]">
            ${renderStars(rating)}
            <span class="text-muted">${rating.toFixed(1)} • ${ratingTotal}</span>
          </span>
        ` : `
          <span class="text-[11px] px-2 py-1 rounded-full soft-chip text-muted inline-flex items-center gap-1">
            <i class="ph-shield-check icon-brand"></i> Verificado
          </span>
        `}
      </div>

      <div class="mt-3 flex items-end justify-between">
        <div>
          <p class="text-emerald-700 text-xl inline-flex items-center gap-1">
            <i class="ph-currency-dollar icon-brand text-base"></i> ${money(finalPrice)}
          </p>
          ${hasOffer ? `<p class="text-xs text-gray-400 line-through">${money(base)}</p>` : `<p class="text-xs text-gray-400">&nbsp;</p>`}
        </div>

        <span class="text-xs ${out ? "text-rose-600" : "text-muted"} inline-flex items-center gap-1">
          <i class="ph-package icon-brand"></i>
          ${out ? "Agotado" : (stock ? `Stock: ${stock}` : "Disponible")}
        </span>
      </div>

      <div class="card-actions">
        <button data-add="${p.id}"
                ${out ? "disabled" : ""}
                class="btn-mini btn-brand inline-flex items-center justify-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed">
          <i class="ph-shopping-cart text-lg"></i>
          ${out ? "No disponible" : "Añadir"}
        </button>

        <button data-view="${p.id}"
                class="btn-mini btn-outline inline-flex items-center justify-center gap-2">
          <i class="ph-magnifying-glass-plus icon-brand text-lg"></i>
          Ver
        </button>
      </div>
    </div>
  </article>
`;
    }).join("");

    grid.querySelectorAll("[data-add]").forEach(b => {
        b.onclick = async () => {
            const id = b.getAttribute("data-add");
            if (!id) return;
            await apiAddItem(id);
            await refreshCart();
            openCart();
        };
    });

    grid.querySelectorAll("[data-quickadd]").forEach(b => {
        b.onclick = async () => {
            const id = b.getAttribute("data-quickadd");
            if (!id) return;
            await apiAddItem(id);
            await refreshCart();
        };
    });

    grid.querySelectorAll("[data-view]").forEach(b => {
        b.onclick = async () => {
            const id = b.getAttribute("data-view");
            if (!id) return;
            await openProductDetailModal(id);
        };
    });
}

async function loadProducts() {
    try {
        const data = await apiGetProducts();
        if (!data || !data.success) {
            productsAll = [];
            allProductsCache = [];
            filterProducts();
            return;
        }
        const productos = data.productos || [];
        productsAll = productos;
        allProductsCache = productos;

        initPriceFilterFromProducts(productsAll);
        bindPriceFilterEvents();

        filterProducts();
    } catch (e) {
        productsAll = [];
        allProductsCache = [];
        initPriceFilterFromProducts([]);
        bindPriceFilterEvents();
        filterProducts();
    }
}

/* ================= MODAL DETALLE (zoom + galería) ================= */
const prodModal = document.getElementById("prodModal");
const prodModalOverlay = document.getElementById("prodModalOverlay");
const prodModalClose = document.getElementById("prodModalClose");

const zoomStage = document.getElementById("zoomStage");
const zoomImg = document.getElementById("zoomImg");
const thumbs = document.getElementById("thumbs");

const pNombre = document.getElementById("pNombre");
const pCategoria = document.getElementById("pCategoria");
const pSku = document.getElementById("pSku");
const pRating = document.getElementById("pRating");
const pStock = document.getElementById("pStock");
const pPrecioFinal = document.getElementById("pPrecioFinal");
const pPrecioBase = document.getElementById("pPrecioBase");
const pAhorro = document.getElementById("pAhorro");
const pDescCorta = document.getElementById("pDescCorta");

const pAtributos = document.getElementById("pAtributos");
const variacionesWrap = document.getElementById("variacionesWrap");
const pVariaciones = document.getElementById("pVariaciones");

const descLargaWrap = document.getElementById("descLargaWrap");
const pDescLarga = document.getElementById("pDescLarga");

const btnModalAdd = document.getElementById("btnModalAdd");
const btnModalOpenCart = document.getElementById("btnModalOpenCart");

const zoomInBtn = document.getElementById("zoomIn");
const zoomOutBtn = document.getElementById("zoomOut");
const zoomResetBtn = document.getElementById("zoomReset");

let modalCurrentProductId = null;
let modalImages = [];
let zoom = { scale: 1, x: 0, y: 0, dragging: false, startX: 0, startY: 0 };

function starsHTML(rating) {
    const r = Number(rating || 0);
    let html = `<span class="stars-row">`;
    for (let i = 1; i <= 5; i++) {
        const filled = r >= i - 0.25;
        html += `<i class="ph-star${filled ? '-fill' : ''} ${filled ? 'text-amber-400' : 'text-slate-300'}"></i>`;
    }
    html += `</span>`;
    return html;
}

function setZoomTransform() {
    if (!zoomImg) return;
    zoomImg.style.transform = `translate(${zoom.x}px, ${zoom.y}px) scale(${zoom.scale})`;
}
function zoomReset() { zoom.scale = 1; zoom.x = 0; zoom.y = 0; setZoomTransform(); }
function zoomStep(delta) {
    const next = Math.max(1, Math.min(3, Number((zoom.scale + delta).toFixed(2))));
    zoom.scale = next;
    if (zoom.scale === 1) { zoom.x = 0; zoom.y = 0; }
    setZoomTransform();
}
function setMainImage(url) {
    if (!zoomImg) return;
    zoomImg.src = resolveImg(url);
    zoomReset();
}
function openProdModal() { prodModal?.classList.remove("hidden"); }
function closeProdModal() {
    prodModal?.classList.add("hidden");
    modalCurrentProductId = null;
    modalImages = [];
    zoomReset();
}
prodModalOverlay?.addEventListener("click", closeProdModal);
prodModalClose?.addEventListener("click", closeProdModal);
document.addEventListener("keydown", (e) => {
    if (e.key === "Escape" && prodModal && !prodModal.classList.contains("hidden")) closeProdModal();
});

zoomInBtn?.addEventListener("click", () => zoomStep(0.2));
zoomOutBtn?.addEventListener("click", () => zoomStep(-0.2));
zoomResetBtn?.addEventListener("click", zoomReset);

zoomStage?.addEventListener("wheel", (e) => {
    e.preventDefault();
    const delta = (e.deltaY < 0) ? 0.12 : -0.12;
    zoomStep(delta);
}, { passive: false });

zoomStage?.addEventListener("dblclick", (e) => { e.preventDefault(); zoomReset(); });

zoomStage?.addEventListener("mousedown", (e) => {
    if (zoom.scale <= 1) return;
    zoom.dragging = true;
    zoom.startX = e.clientX - zoom.x;
    zoom.startY = e.clientY - zoom.y;
});
document.addEventListener("mousemove", (e) => {
    if (!zoom.dragging) return;
    zoom.x = e.clientX - zoom.startX;
    zoom.y = e.clientY - zoom.startY;

    const limit = 220 * zoom.scale;
    zoom.x = Math.max(-limit, Math.min(limit, zoom.x));
    zoom.y = Math.max(-limit, Math.min(limit, zoom.y));

    setZoomTransform();
});
document.addEventListener("mouseup", () => { zoom.dragging = false; });

function renderThumbs(images) {
    if (!thumbs) return;
    const list = (images || []).slice(0, 12);
    thumbs.innerHTML = list.map((img, idx) => {
        const url = img.url || img.imagen_url || img;
        return `
  <button class="rounded-xl overflow-hidden border border-sky-100 bg-slate-50 hover:bg-white"
          data-thumb="${idx}" title="Ver imagen">
    <div class="aspect-square">
      <img src="${resolveImg(url)}" onerror="this.src='${FRONT_IMG}/no-image.png'" class="w-full h-full object-cover">
    </div>
  </button>
`;
    }).join("");

    thumbs.querySelectorAll("[data-thumb]").forEach(btn => {
        btn.addEventListener("click", () => {
            const i = Number(btn.getAttribute("data-thumb"));
            const it = list[i];
            const url = it?.url || it?.imagen_url || it;
            setMainImage(url);
        });
    });
}

function renderAtributos(attrs) {
    if (!pAtributos) return;
    if (!attrs || !attrs.length) {
        pAtributos.innerHTML = `<div class="text-sm text-muted">Sin atributos registrados.</div>`;
        return;
    }
    pAtributos.innerHTML = attrs.map(a => `
<div class="rounded-2xl border border-sky-50 bg-white p-3">
  <div class="text-xs text-muted">${a.nombre}</div>
  <div class="text-sm text-ink mt-1">${a.valor}</div>
</div>
`).join("");
}

function renderVariaciones(vars) {
    if (!variacionesWrap || !pVariaciones) return;
    if (!vars || !vars.length) {
        variacionesWrap.style.display = "none";
        pVariaciones.innerHTML = "";
        return;
    }

    variacionesWrap.style.display = "block";
    pVariaciones.innerHTML = vars.map(v => {
        const precio = (v.precio !== null && v.precio !== undefined && v.precio !== "") ? Number(v.precio) : null;
        const stock = Number(v.stock || 0);
        const det = v.detalles_json ? String(v.detalles_json) : "";
        return `
  <div class="rounded-2xl border border-sky-50 bg-white p-3 flex items-center justify-between gap-3">
    <div>
      <div class="text-sm text-ink">${v.nombre || "Variación"}</div>
      <div class="text-xs text-muted mt-1 inline-flex items-center gap-2">
        <span class="px-2 py-1 rounded-full bg-slate-50 border border-slate-100"><i class="ph-barcode icon-brand"></i> ${v.sku || "—"}</span>
        <span class="px-2 py-1 rounded-full bg-slate-50 border border-slate-100"><i class="ph-package icon-brand"></i> stock: ${stock}</span>
      </div>
      ${det ? `<div class="text-xs text-muted mt-2 line-clamp-2">${det}</div>` : ``}
    </div>
    <div class="text-right">
      <div class="text-sm text-emerald-700">${precio !== null ? money(precio) : ""}</div>
    </div>
  </div>
`;
    }).join("");
}

async function openProductDetailModal(productId) {
    modalCurrentProductId = productId;

    pNombre.textContent = "Cargando...";
    pCategoria.textContent = "";
    pSku.textContent = "";
    pRating.innerHTML = "";
    pStock.textContent = "";
    pPrecioFinal.querySelector("span").textContent = "";
    pPrecioBase.textContent = "";
    pAhorro.textContent = "";
    pDescCorta.textContent = "";
    pAtributos.innerHTML = "";
    renderVariaciones([]);

    thumbs.innerHTML = "";
    setMainImage(`${FRONT_IMG}/no-image.png`);
    openProdModal();

    const d = await apiGetProductDetail(productId);
    if (!d || !d.success) {
        pNombre.textContent = "No se pudo cargar el producto";
        pDescCorta.textContent = d?.error || "Error de servidor.";
        return;
    }

    const p = d.producto || {};
    modalImages = (d.imagenes && d.imagenes.length) ? d.imagenes : (p.imagen_url ? [{ url: p.imagen_url }] : []);

    const mainUrl = (modalImages[0]?.url) ? modalImages[0].url : (p.imagen_url || `${FRONT_IMG}/no-image.png`);
    setMainImage(mainUrl);
    renderThumbs(modalImages);

    pNombre.textContent = p.nombre || "Producto";
    pCategoria.textContent = p.categoria_nombre ? `Categoría: ${p.categoria_nombre}` : "Categoría: —";
    pSku.textContent = p.sku ? `SKU: ${p.sku}` : "SKU: —";

    const rating = Number(p.rating_promedio || 0);
    const totalR = Number(p.rating_total || 0);
    pRating.innerHTML = `
<span class="rating-pill">
  ${starsHTML(rating)}
  <span class="text-xs text-muted ml-1">${rating.toFixed(1)} (${totalR})</span>
</span>
`;

    const stock = Number(p.stock || 0);
    const out = (p.estado_stock === "out_of_stock") || (stock <= 0);
    pStock.innerHTML = out
        ? `<span class="text-rose-600 inline-flex items-center gap-1"><i class="ph-warning"></i> Agotado</span>`
        : `<span class="text-muted inline-flex items-center gap-1"><i class="ph-package icon-brand"></i> Stock: ${stock}</span>`;

    const base = Number(p.precio || 0);
    const final = Number(p.precio_final || p.precio_oferta || p.precio || 0);
    pPrecioFinal.querySelector("span").textContent = money(final);

    if (base > 0 && final > 0 && final < base) {
        pPrecioBase.textContent = money(base);
        const ahorro = (base - final);
        pAhorro.innerHTML = `<span class="inline-flex items-center gap-2"><i class="ph-tag icon-brand"></i> Ahorra ${money(ahorro)}</span>`;
    } else {
        pPrecioBase.textContent = "";
        pAhorro.textContent = "";
    }

    pDescCorta.textContent = p.descripcion_corta || "Producto seleccionado para tu bienestar.";

    if (p.descripcion_larga && String(p.descripcion_larga).trim().length) {
        descLargaWrap.style.display = "block";
        pDescLarga.textContent = String(p.descripcion_larga);
    } else {
        descLargaWrap.style.display = "none";
        pDescLarga.textContent = "";
    }

    renderAtributos(d.atributos || []);
    renderVariaciones(d.variaciones || []);

    btnModalAdd.disabled = out;
    btnModalAdd.classList.toggle("opacity-50", out);
    btnModalAdd.classList.toggle("cursor-not-allowed", out);

    btnModalAdd.onclick = async () => {
        if (!modalCurrentProductId) return;
        await apiAddItem(modalCurrentProductId);
        await refreshCart();
        openCart();
    };

    btnModalOpenCart.onclick = async () => {
        await refreshCart();
        openCart();
    };
}

/* ================= EVENTOS FILTROS ================= */
searchInput?.addEventListener("input", filterProducts);
btnClearSearch?.addEventListener("click", () => {
    searchInput.value = "";
    filterProducts();
    searchInput.focus();
});
sortSelect?.addEventListener("change", filterProducts);

document.querySelectorAll(".chipQuick").forEach(ch => {
    ch.addEventListener("click", () => {
        searchInput.value = ch.dataset.q || "";
        filterProducts();
        searchInput.focus();
    });
});

/* ================= INIT ================= */
(async function init() {
    await ensureBaseApp();

    ensureCartDrawerMarkup();

    const dom = getCartDom();
    dom.overlay?.addEventListener("click", closeCart);
    dom.btnClose?.addEventListener("click", closeCart);

    // Si tu header trae botones con estos IDs, se conectan:
    dom.btnOpen?.addEventListener("click", async () => { await refreshCart(); openCart(); });
    dom.btnOpenMobile?.addEventListener("click", async () => { await refreshCart(); openCart(); });

    await loadProducts();
    await refreshCart();
    bindProceedToPayButtons();

    // Escape cierra carrito también
    document.addEventListener("keydown", (e) => {
        if (e.key === "Escape" && dom.drawer && !dom.drawer.classList.contains("translate-x-full")) closeCart();
    });

    // Botón del drawer inyectado
    document.getElementById("btnProceedToPay")?.addEventListener("click", async (e) => {
        e.preventDefault();
        await refreshCart();
        buildPaymentModalFromCart();
        renderPaySuggestions();
        closeCart();
        openPaymentModal();
    });
})();
