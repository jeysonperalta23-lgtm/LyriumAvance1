// C:\xampp\htdocs\schedule\frontend\js\carrito.js

const usuarioCarrito = JSON.parse(localStorage.getItem("usuario")) || {};

// De momento, para pruebas siempre usamos cliente 1
// (coincide con tu tabla clientes: id = 1)
const CLIENTE_ID = usuarioCarrito.cliente_id || 1;

// DOM
const carritoItemsContainer = document.getElementById("carritoItemsContainer");
const carritoVacio          = document.getElementById("carritoVacio");

const lblCarritoSubtotal    = document.getElementById("lblCarritoSubtotal");
const lblCarritoEnvio       = document.getElementById("lblCarritoEnvio");
const lblCarritoTotal       = document.getElementById("lblCarritoTotal");

// Si tienes badges en el header (opcional)
const carritoTotalSpan      = document.getElementById("carritoTotalSpan");
const carritoCantidadSpan   = document.getElementById("carritoCantidadSpan");

let carritoActual = null;
let carritoItems  = [];

// ------------------- CARGAR CARRITO -------------------
async function cargarCarrito() {
  if (!CLIENTE_ID) {
    console.warn("No hay CLIENTE_ID definido para el carrito.");
    return;
  }

  try {
    const res = await fetch(`../../backend/api/carrito.php?cliente_id=${encodeURIComponent(CLIENTE_ID)}&moneda=PEN`);
    const data = await res.json();

    console.log("Respuesta carrito.php:", data); // 👀 para depurar

    if (!data.success) {
      throw new Error(data.error || "Error al cargar carrito");
    }

    carritoActual = data.carrito;
    carritoItems  = Array.isArray(data.items) ? data.items : [];

    renderCarrito();
  } catch (err) {
    console.error("Error cargando carrito:", err);
    alert("Error al cargar carrito: " + err.message);
  }
}

// ------------------- RENDER CARRITO -------------------
function renderCarrito() {
  if (!carritoItemsContainer) return;

  carritoItemsContainer.innerHTML = "";

  let totalCantidad = 0;
  let totalMonto    = 0;

  if (!Array.isArray(carritoItems) || carritoItems.length === 0) {
    if (carritoVacio) carritoVacio.classList.remove("hidden");

    if (lblCarritoSubtotal) lblCarritoSubtotal.textContent = "S/ 0.00";
    if (lblCarritoTotal)    lblCarritoTotal.textContent    = "S/ 0.00";

    if (carritoTotalSpan)    carritoTotalSpan.textContent    = "0.00";
    if (carritoCantidadSpan) carritoCantidadSpan.textContent = "0";

    return;
  }

  if (carritoVacio) carritoVacio.classList.add("hidden");

  carritoItems.forEach(item => {
    const idItem   = item.id;
    const nombre   = item.producto_nombre || "Producto";
    const sku      = item.producto_sku || "";
    const moneda   = item.moneda || "PEN";
    const cantidad = parseInt(item.cantidad || 0, 10);
    const precio   = parseFloat(item.precio_unitario || 0);

    const subtotal = cantidad * precio;
    totalCantidad += cantidad;
    totalMonto    += subtotal;

    const fila = document.createElement("div");
    fila.className = "flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 border-b border-slate-100 py-3";

    fila.innerHTML = `
      <div class="flex-1 min-w-0">
        <p class="text-sm font-semibold text-slate-800 truncate">
          ${nombre}
        </p>
        <p class="text-xs text-slate-500">
          SKU: <span class="font-mono">${sku || "—"}</span>
        </p>
        <p class="text-xs text-slate-500 mt-1">
          Precio: <span class="font-semibold">${precio.toFixed(2)} ${moneda}</span>
        </p>
      </div>

      <div class="flex items-center gap-3">
        <div class="flex items-center border border-slate-200 rounded-full overflow-hidden text-sm">
          <button type="button"
                  class="w-8 h-8 flex items-center justify-center hover:bg-slate-100"
                  onclick="cambiarCantidadItem(${idItem}, ${cantidad - 1})">
            <i class="fas fa-minus text-xs"></i>
          </button>
          <span class="w-10 text-center text-sm">${cantidad}</span>
          <button type="button"
                  class="w-8 h-8 flex items-center justify-center hover:bg-slate-100"
                  onclick="cambiarCantidadItem(${idItem}, ${cantidad + 1})">
            <i class="fas fa-plus text-xs"></i>
          </button>
        </div>

        <div class="text-right min-w-[80px]">
          <p class="text-sm font-semibold text-slate-800">
            ${subtotal.toFixed(2)} ${moneda}
          </p>
          <button type="button"
                  class="text-xs text-rose-500 hover:text-rose-600 mt-1 inline-flex items-center gap-1"
                  onclick="eliminarItemCarrito(${idItem})">
            <i class="fas fa-trash text-[11px]"></i>
            Quitar
          </button>
        </div>
      </div>
    `;

    carritoItemsContainer.appendChild(fila);
  });

  const totalTxt = `S/ ${totalMonto.toFixed(2)}`;

  if (lblCarritoSubtotal) lblCarritoSubtotal.textContent = totalTxt;
  if (lblCarritoTotal)    lblCarritoTotal.textContent    = totalTxt;

  if (carritoTotalSpan)    carritoTotalSpan.textContent    = totalMonto.toFixed(2);
  if (carritoCantidadSpan) carritoCantidadSpan.textContent = String(totalCantidad);
}

// ------------------- VACIAR CARRITO (opcional, si ya tienes carrito_item.php) -------------------
async function vaciarCarrito() {
  if (!carritoActual || !carritoActual.id) return;
  if (!confirm("¿Vaciar todo el carrito?")) return;

  const itemsIds = carritoItems.map(i => i.id);

  try {
    for (const idItem of itemsIds) {
      await fetch("../../backend/api/carrito_item.php", {
        method: "DELETE",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: `id=${encodeURIComponent(idItem)}`
      });
    }
    await cargarCarrito();
  } catch (err) {
    console.error("Error vaciando carrito:", err);
    alert("Error al vaciar el carrito.");
  }
}

window.addEventListener("DOMContentLoaded", () => {
  cargarCarrito();

  const btnVaciar = document.getElementById("btnVaciarCarrito");
  if (btnVaciar) {
    btnVaciar.addEventListener("click", vaciarCarrito);
  }
});
