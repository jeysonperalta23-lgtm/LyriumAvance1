// C:\xampp\htdocs\schedule\frontend\js\producto.js

const usuarioLog = JSON.parse(localStorage.getItem("usuario")) || {};
const USUARIO_ID = usuarioLog.id || null;

// ------------------- DOM PRINCIPAL -------------------
const inputBuscarProducto      = document.getElementById("inputBuscarProducto");
const selectPaginationProducto = document.getElementById("selectPaginationProducto");

const panelControlesProductos = document.getElementById("panelControlesProductos");
const btnTogglePanelProductos = document.getElementById("btnTogglePanelProductos");
const productosCardsContainer = document.getElementById("productosCardsContainer");

// Modal Producto (datos básicos)
const modalProducto          = document.getElementById("modalProducto");
const modalProductoTitulo    = document.getElementById("modalProductoTitulo");
const btnAbrirModalProducto  = document.getElementById("btnAbrirModalProducto");
const btnCerrarModalProducto = document.getElementById("btnCerrarModalProducto");
const btnCancelarProducto    = document.getElementById("btnCancelarProducto");
const btnGuardarProducto     = document.getElementById("btnGuardarProducto");

// Campos modal Producto
const inputProdNombre        = document.getElementById("modalProductoNombre");
const inputProdSku           = document.getElementById("modalProductoSku");
const inputProdPrecio        = document.getElementById("modalProductoPrecio");
const inputProdPrecioOferta  = document.getElementById("modalProductoPrecioOferta");
const selectProdMoneda       = document.getElementById("modalProductoMoneda");
const inputProdStock         = document.getElementById("modalProductoStock");
const selectProdTipo         = document.getElementById("modalProductoTipo");
const selectProdEstado       = document.getElementById("modalProductoEstado");
const selectProdPublicado    = document.getElementById("modalProductoPublicado");
const selectProdDestacado    = document.getElementById("modalProductoDestacado");
const textareaProdDescCorta  = document.getElementById("modalProductoDescripcionCorta");

// --------- Modal IMÁGENES de producto ---------
const modalProductoImagenes           = document.getElementById("modalProductoImagenes");
const modalProductoImagenesTitulo     = document.getElementById("modalProductoImagenesTitulo");
const btnCerrarModalProductoImagenes  = document.getElementById("btnCerrarModalProductoImagenes");
const btnCerrarModalProductoImagenesF = document.getElementById("btnCerrarModalProductoImagenesFooter");
const inputNuevaImagenUrl             = document.getElementById("inputNuevaImagenUrl");
const chkNuevaImagenPrincipal         = document.getElementById("chkNuevaImagenPrincipal");
const btnAgregarImagenProducto        = document.getElementById("btnAgregarImagenProducto");
const contenedorImagenesProducto      = document.getElementById("contenedorImagenesProducto");
const mensajeSinImagenesProducto      = document.getElementById("mensajeSinImagenesProducto");

// ------------------- ESTADO -------------------
let panelProductosVisible = false;
let productosOriginales   = [];
let productosFiltrados    = [];
let productoImagenesActualId   = null;
let productoImagenesActualNombre = "";

// ------------------- HELPERS -------------------
function escapeHTML(str) {
  return (str || "").replace(/[&<>"']/g, (c) => {
    const map = {
      "&": "&amp;",
      "<": "&lt;",
      ">": "&gt;",
      '"': "&quot;",
      "'": "&#39;"
    };
    return map[c] || c;
  });
}

function getTextoFiltro() {
  return (inputBuscarProducto?.value || "").toLowerCase();
}

// ------------------- FILTRO -------------------
function filtrarProductos() {
  const texto = getTextoFiltro();
  if (!texto) return [...productosOriginales];

  return productosOriginales.filter(p =>
    [
      p.nombre,
      p.sku,
      p.tipo_producto,
      p.descripcion_corta,
      p.slug
    ]
      .map(v => (v || "").toLowerCase())
      .some(v => v.includes(texto))
  );
}

// ------------------- RENDER TARJETAS -------------------
function renderProductosCards() {
  if (!productosCardsContainer) return;

  productosCardsContainer.innerHTML = "";

  if (!Array.isArray(productosFiltrados) || productosFiltrados.length === 0) {
    productosCardsContainer.innerHTML = `
      <div class="col-span-1 sm:col-span-2 lg:col-span-3 xl:col-span-4">
        <div class="w-full border border-dashed border-sky-200 rounded-2xl bg-sky-50/60 p-4 flex items-center justify-center">
          <div class="flex items-center gap-3 text-sky-700 text-sm">
            <i class="fas fa-info-circle"></i>
            <span>No se encontraron productos para mostrar.</span>
          </div>
        </div>
      </div>
    `;
    return;
  }

  let max = productosFiltrados.length;
  if (selectPaginationProducto) {
    const val = selectPaginationProducto.value;
    if (val !== "all") {
      const n = parseInt(val, 10);
      if (!isNaN(n)) max = Math.min(max, n);
    }
  }

  for (let i = 0; i < max; i++) {
    const p = productosFiltrados[i];

    const id     = p.id;
    const nombre = p.nombre || "Sin nombre";
    const sku    = p.sku || "";
    const precio = Number(p.precio || 0).toFixed(2);
    const pOferta= p.precio_oferta != null ? Number(p.precio_oferta).toFixed(2) : null;
    const moneda = p.moneda || "PEN";
    const stock  = p.stock ?? 0;
    const tipo   = p.tipo_producto || "simple";
    const estado = String(p.estado) === "1";
    const publicado = String(p.publicado) === "1";
    const destacado = String(p.destacado) === "1";
    const estadoStock = p.estado_stock || (stock > 0 ? "in_stock" : "out_of_stock");
    const descCorta = p.descripcion_corta || "";

    const estadoText = estado ? "Activo" : "Inactivo";
    const estadoClass = estado
      ? "bg-emerald-50 text-emerald-700 border-emerald-200"
      : "bg-slate-100 text-slate-600 border-slate-200";
    const iconEstado = estado ? "fa-toggle-on text-emerald-500" : "fa-toggle-off text-slate-400";

    const stockClass = estadoStock === "in_stock"
      ? "bg-emerald-50 text-emerald-700 border-emerald-200"
      : estadoStock === "preorder"
        ? "bg-amber-50 text-amber-700 border-amber-200"
        : "bg-rose-50 text-rose-600 border-rose-200";

    const tipoLabel = tipo === "servicio" ? "Servicio"
                    : tipo === "variable" ? "Variable"
                    : "Simple";

    const card = document.createElement("article");
    card.className = "product-card-hover border border-sky-100 bg-white rounded-2xl p-4 flex flex-col gap-3 shadow-sm text-[14px]";

    card.innerHTML = `
      <div class="flex items-start gap-3">
        <div class="shrink-0">
          <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-sky-400 to-sky-700 shadow-sm flex items-center justify-center">
            <span class="text-sm font-bold text-white">
              ${escapeHTML(nombre.charAt(0).toUpperCase())}
            </span>
          </div>
        </div>

        <div class="flex-1 space-y-1">
          <div class="flex items-start justify-between gap-2">
            <div class="min-w-0">
              <p class="text-base font-semibold text-slate-800 leading-tight truncate">
                ${escapeHTML(nombre)}
              </p>
              <p class="text-xs text-slate-500">
                SKU: <span class="font-mono">${escapeHTML(sku || "—")}</span>
              </p>
            </div>

            <div class="flex flex-col items-end gap-1">
              <span class="px-2 py-0.5 rounded-full text-[11px] font-semibold border bg-sky-50 text-sky-700 border-sky-200">
                ${escapeHTML(tipoLabel)}
              </span>

              <button type="button"
                      class="flex items-center gap-1 px-2 py-0.5 rounded-full border text-[11px] ${estadoClass}"
                      onclick="toggleEstadoProducto(${id})">
                <i class="fas ${iconEstado} text-sm"></i>
                <span>${estadoText}</span>
              </button>
            </div>
          </div>

          <div class="mt-2 grid grid-cols-2 gap-2 text-sm text-slate-600">
            <p>
              <span class="font-semibold text-slate-700">Precio:</span>
              <span>${precio} ${escapeHTML(moneda)}</span>
            </p>
            <p>
              <span class="font-semibold text-slate-700">Stock:</span>
              <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[11px] border ${stockClass}">
                ${stock} <span class="uppercase tracking-wide text-[10px]">
                ${estadoStock === "in_stock" ? "En stock" : estadoStock === "preorder" ? "Pre-orden" : "Agotado"}
                </span>
              </span>
            </p>
            ${
              pOferta
                ? `<p class="col-span-2 text-xs">
                     <span class="font-semibold text-rose-600">Oferta:</span>
                     <span class="line-through text-slate-400 mr-1">${precio}</span>
                     <span class="font-semibold text-rose-600">${pOferta} ${escapeHTML(moneda)}</span>
                   </p>`
                : ""
            }
          </div>

          ${
            descCorta
              ? `<p class="mt-1 text-xs text-slate-500 line-clamp-2">${escapeHTML(descCorta)}</p>`
              : ""
          }

          <div class="mt-1 flex flex-wrap gap-1 text-[10px]">
            ${
              publicado
                ? `<span class="px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200 flex items-center gap-1">
                     <i class="fas fa-globe-americas text-[9px]"></i> Publicado
                   </span>`
                : `<span class="px-2 py-0.5 rounded-full bg-slate-100 text-slate-600 border border-slate-200 flex items-center gap-1">
                     <i class="fas fa-eye-slash text-[9px]"></i> No publicado
                   </span>`
            }
            ${
              destacado
                ? `<span class="px-2 py-0.5 rounded-full bg-amber-50 text-amber-700 border-amber-200 flex items-center gap-1">
                     <i class="fas fa-star text-[9px]"></i> Destacado
                   </span>`
                : ""
            }
          </div>
        </div>
      </div>

      <div class="flex flex-wrap items-center justify-between gap-2 pt-2 border-t border-slate-100 mt-2">
        <div class="flex flex-wrap gap-2">
          <button type="button"
                  class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs border border-sky-200 bg-sky-50 hover:bg-sky-100 text-sky-700"
                  onclick="abrirModalEditarProducto(${id})">
            <i class="fas fa-pen text-sm"></i>
            <span>Editar</span>
          </button>

          <button type="button"
                  class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs border border-sky-200 bg-sky-50 hover:bg-sky-100 text-sky-700"
                  onclick="abrirModalImagenesProducto(${id}, '${escapeHTML(nombre)}')">
            <i class="fas fa-images text-sm"></i>
            <span>Imágenes</span>
          </button>
        </div>

        <button type="button"
                class="w-9 h-9 flex items-center justify-center rounded-full border border-rose-200 bg-rose-50 hover:bg-rose-100"
                title="Eliminar producto"
                onclick="eliminarProducto(${id})">
          <i class="fas fa-trash text-rose-600 text-sm"></i>
        </button>
      </div>
    `;

    productosCardsContainer.appendChild(card);
  }
}

// Aplicar filtro y re-render
function aplicarFiltroYRenderProductos() {
  productosFiltrados = filtrarProductos();
  renderProductosCards();
}

// Eventos filtro
if (inputBuscarProducto) {
  inputBuscarProducto.addEventListener("focus", () => {
    if (!inputBuscarProducto.dataset.limpio) {
      inputBuscarProducto.value = "";
      inputBuscarProducto.dataset.limpio = "true";
    }
  });
  inputBuscarProducto.addEventListener("input", aplicarFiltroYRenderProductos);
}

// Paginación
if (selectPaginationProducto) {
  selectPaginationProducto.addEventListener("change", renderProductosCards);
}

// DOMContentLoaded
window.addEventListener("DOMContentLoaded", () => {
  cargarProductos();

  if (panelControlesProductos && btnTogglePanelProductos) {
    panelControlesProductos.classList.add("hidden");
    btnTogglePanelProductos.innerHTML =
      '<i class="fas fa-chevron-down text-[10px]"></i> Mostrar filtros';

    btnTogglePanelProductos.addEventListener("click", () => {
      panelProductosVisible = !panelProductosVisible;
      if (panelProductosVisible) {
        panelControlesProductos.classList.remove("hidden");
        btnTogglePanelProductos.innerHTML =
          '<i class="fas fa-chevron-up text-[10px]"></i> Ocultar filtros';
      } else {
        panelControlesProductos.classList.add("hidden");
        btnTogglePanelProductos.innerHTML =
          '<i class="fas fa-chevron-down text-[10px]"></i> Mostrar filtros';
      }
    });
  }
});

// ------------------- CARGAR PRODUCTOS -------------------
function cargarProductos() {
  fetch("../../backend/api/producto.php")
    .then(r => r.json())
    .then(data => {
      if (!data.success) {
        throw new Error(data.error || "Error al cargar productos");
      }
      productosOriginales = (data.productos || []).map(p => ({
        ...p,
        estado: String(p.estado ?? "1").trim(),
        publicado: String(p.publicado ?? "1").trim(),
        destacado: String(p.destacado ?? "0").trim(),
        precio: Number(p.precio ?? 0),
        precio_oferta: p.precio_oferta != null ? Number(p.precio_oferta) : null,
        stock: Number(p.stock ?? 0)
      }));
      aplicarFiltroYRenderProductos();
    })
    .catch(err => {
      console.error("Error cargando productos:", err);
      alert("Error al cargar productos");
    });
}

// ------------------- EXPORT CSV -------------------
function exportarCSVProductos() {
  if (!Array.isArray(productosOriginales) || productosOriginales.length === 0) {
    alert("No hay datos para exportar.");
    return;
  }

  const filas = filtrarProductos();
  if (filas.length === 0) {
    alert("No se encontraron resultados para exportar.");
    return;
  }

  const encabezados = [
    "ID",
    "Nombre",
    "SKU",
    "Precio",
    "Precio oferta",
    "Moneda",
    "Stock",
    "Tipo producto",
    "Estado",
    "Publicado",
    "Destacado"
  ];

  const csv = [
    encabezados.join(";"),
    ...filas.map(row =>
      [
        row.id,
        row.nombre || "",
        row.sku || "",
        Number(row.precio || 0).toFixed(2),
        row.precio_oferta != null ? Number(row.precio_oferta).toFixed(2) : "",
        row.moneda || "",
        row.stock ?? "",
        row.tipo_producto || "",
        String(row.estado) === "1" ? "Activo" : "Inactivo",
        String(row.publicado) === "1" ? "Publicado" : "No publicado",
        String(row.destacado) === "1" ? "Sí" : "No"
      ].join(";")
    )
  ].join("\n");

  const blob = new Blob([csv], { type: "text/csv;charset=utf-8;" });
  const link = document.createElement("a");
  link.href = URL.createObjectURL(blob);
  link.download = "productos.csv";
  link.click();
}

// ------------------- MODAL PRODUCTO (BÁSICO) -------------------
function limpiarModalProducto() {
  inputProdNombre.value       = "";
  inputProdSku.value          = "";
  inputProdPrecio.value       = "";
  inputProdPrecioOferta.value = "";
  selectProdMoneda.value      = "PEN";
  inputProdStock.value        = "";
  selectProdTipo.value        = "simple";
  selectProdEstado.value      = "1";
  selectProdPublicado.value   = "1";
  selectProdDestacado.value   = "0";
  textareaProdDescCorta.value = "";

  modalProducto.dataset.editando = "false";
  modalProducto.dataset.id = "";
}

function abrirModalNuevoProducto() {
  limpiarModalProducto();
  modalProductoTitulo.textContent = "Nuevo producto";
  modalProducto.classList.remove("hidden");
}

function cerrarModalProducto() {
  modalProducto.classList.add("hidden");
}

if (btnAbrirModalProducto)  btnAbrirModalProducto.addEventListener("click", abrirModalNuevoProducto);
if (btnCerrarModalProducto) btnCerrarModalProducto.addEventListener("click", cerrarModalProducto);
if (btnCancelarProducto)    btnCancelarProducto.addEventListener("click", cerrarModalProducto);

// Guardar (crear/editar)
if (btnGuardarProducto) {
  btnGuardarProducto.addEventListener("click", async () => {
    const nombre       = inputProdNombre.value.trim();
    const sku          = inputProdSku.value.trim();
    const precio       = parseFloat(inputProdPrecio.value || "0");
    const precioOferta = inputProdPrecioOferta.value !== "" 
                           ? parseFloat(inputProdPrecioOferta.value)
                           : null;
    const moneda       = selectProdMoneda.value || "PEN";
    const stock        = parseInt(inputProdStock.value || "0", 10);
    const tipo         = selectProdTipo.value || "simple";
    const estado       = parseInt(selectProdEstado.value || "1", 10);
    const publicado    = parseInt(selectProdPublicado.value || "1", 10);
    const destacado    = parseInt(selectProdDestacado.value || "0", 10);
    const descCorta    = textareaProdDescCorta.value.trim();

    const editando = modalProducto.dataset.editando === "true";
    const id       = modalProducto.dataset.id || null;

    if (!nombre || isNaN(precio)) {
      alert("Nombre y precio son obligatorios.");
      return;
    }

    const payload = {
      nombre,
      sku,
      precio,
      precio_oferta: precioOferta,
      moneda,
      stock: isNaN(stock) ? 0 : stock,
      tipo_producto: tipo,
      estado,
      publicado,
      destacado,
      descripcion_corta: descCorta
    };

    if (editando && id) {
      payload.id = id;
    }

    try {
      const res = await fetch("../../backend/api/producto.php", {
        method: editando ? "PUT" : "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(payload)
      });

      const data = await res.json();
      if (!data.success) {
        throw new Error(data.error || "Error al guardar producto");
      }

      cerrarModalProducto();
      cargarProductos();
    } catch (err) {
      console.error("Error guardando producto:", err);
      alert("Error al guardar producto");
    }
  });
}

// Editar Producto
function abrirModalEditarProducto(id) {
  const p = productosOriginales.find(x => Number(x.id) === Number(id));
  if (!p) {
    alert("No se encontró el producto en memoria.");
    return;
  }

  inputProdNombre.value       = p.nombre || "";
  inputProdSku.value          = p.sku || "";
  inputProdPrecio.value       = p.precio ?? "";
  inputProdPrecioOferta.value = p.precio_oferta ?? "";
  selectProdMoneda.value      = p.moneda || "PEN";
  inputProdStock.value        = p.stock ?? "";
  selectProdTipo.value        = p.tipo_producto || "simple";
  selectProdEstado.value      = String(p.estado ?? "1");
  selectProdPublicado.value   = String(p.publicado ?? "1");
  selectProdDestacado.value   = String(p.destacado ?? "0");
  textareaProdDescCorta.value = p.descripcion_corta || "";

  modalProducto.dataset.editando = "true";
  modalProducto.dataset.id = p.id;

  modalProductoTitulo.textContent = "Editar producto";
  modalProducto.classList.remove("hidden");
}

// Eliminar Producto
async function eliminarProducto(id) {
  if (!confirm("¿Eliminar este producto?")) return;

  try {
    const res = await fetch("../../backend/api/producto.php", {
      method: "DELETE",
      headers: { "Content-Type": "application/x-www-form-urlencoded" },
      body: `id=${encodeURIComponent(id)}`
    });

    const data = await res.json();
    if (!data.success) {
      throw new Error(data.error || "Error al eliminar producto");
    }

    cargarProductos();
  } catch (err) {
    console.error("Error eliminando producto:", err);
    alert("Error al eliminar producto");
  }
}

// Toggle estado producto
async function toggleEstadoProducto(id) {
  const p = productosOriginales.find(x => Number(x.id) === Number(id));
  if (!p) {
    alert("No se encontró el producto en memoria.");
    return;
  }

  const nuevoEstado = String(p.estado) === "1" ? 0 : 1;

  const payload = {
    id: p.id,
    estado: nuevoEstado
  };

  try {
    const res = await fetch("../../backend/api/producto.php", {
      method: "PUT",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(payload)
    });

    const data = await res.json();
    if (!data.success) {
      alert(data.error || "Error al actualizar estado.");
      cargarProductos();
      return;
    }

    p.estado = String(nuevoEstado);
    aplicarFiltroYRenderProductos();

  } catch (err) {
    console.error("Error al actualizar estado producto:", err);
    alert("Error de comunicación al actualizar estado.");
    cargarProductos();
  }
}

// ------------------- MODAL IMÁGENES -------------------
function limpiarModalImagenesProducto() {
  // 🔹 Ya NO tocamos productoImagenesActualId ni productoImagenesActualNombre aquí
  if (inputNuevaImagenUrl) inputNuevaImagenUrl.value = "";
  if (chkNuevaImagenPrincipal) chkNuevaImagenPrincipal.checked = false;
  if (contenedorImagenesProducto) contenedorImagenesProducto.innerHTML = "";
  if (mensajeSinImagenesProducto) mensajeSinImagenesProducto.classList.add("hidden");
}

function abrirModalImagenesProducto(idProducto, nombreProducto) {
  // Primero limpiamos campos visuales, pero no el ID
  limpiarModalImagenesProducto();

  // Ahora sí seteamos el producto actual
  productoImagenesActualId = idProducto;
  productoImagenesActualNombre = nombreProducto || "";

  if (modalProductoImagenesTitulo) {
    modalProductoImagenesTitulo.textContent = `Imágenes de: ${nombreProducto}`;
  }

  modalProductoImagenes.classList.remove("hidden");
  cargarImagenesProducto();
}

function cerrarModalImagenesProducto() {
  modalProductoImagenes.classList.add("hidden");
  // Al cerrar, sí limpiamos la referencia al producto
  productoImagenesActualId = null;
  productoImagenesActualNombre = "";
}

if (btnCerrarModalProductoImagenes) {
  btnCerrarModalProductoImagenes.addEventListener("click", cerrarModalImagenesProducto);
}
if (btnCerrarModalProductoImagenesF) {
  btnCerrarModalProductoImagenesF.addEventListener("click", cerrarModalImagenesProducto);
}

// Cargar imágenes de un producto
function cargarImagenesProducto() {
  if (!productoImagenesActualId) return;

  fetch(`../../backend/api/producto_imagen.php?producto_id=${encodeURIComponent(productoImagenesActualId)}`)
    .then(r => r.json())
    .then(data => {
      if (!data.success) {
        throw new Error(data.error || "Error al cargar imágenes");
      }

      const imagenes = data.imagenes || [];

      if (!contenedorImagenesProducto) return;

      contenedorImagenesProducto.innerHTML = "";

      if (imagenes.length === 0) {
        if (mensajeSinImagenesProducto) mensajeSinImagenesProducto.classList.remove("hidden");
        return;
      } else {
        if (mensajeSinImagenesProducto) mensajeSinImagenesProducto.classList.add("hidden");
      }

      imagenes.forEach(img => {
        const esPrincipal = String(img.es_principal) === "1";

        const tarjeta = document.createElement("div");
        tarjeta.className = "border border-sky-100 bg-white rounded-2xl p-2 flex flex-col gap-2 shadow-sm";

        tarjeta.innerHTML = `
          <div class="relative w-full h-32 rounded-xl overflow-hidden bg-slate-100">
            <img src="${escapeHTML(img.url)}"
                 alt="Imagen producto"
                 class="w-full h-full object-cover">
            ${
              esPrincipal
                ? `<div class="absolute top-2 left-2 px-2 py-0.5 rounded-full bg-emerald-600 text-white text-[11px] flex items-center gap-1 shadow">
                     <i class="fas fa-star"></i>
                     Principal
                   </div>`
                : ""
            }
          </div>

          <div class="flex flex-col gap-1 text-[11px] text-slate-600">
            <div class="truncate" title="${escapeHTML(img.url)}">
              <span class="font-semibold text-slate-700">URL:</span>
              <span class="font-mono text-[10px]">${escapeHTML(img.url)}</span>
            </div>
            <div>
              <span class="font-semibold text-slate-700">Orden:</span>
              <span>${img.orden ?? 0}</span>
            </div>
          </div>

          <div class="flex items-center justify-between mt-1 gap-1">
            <button type="button"
                    class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-[11px] border border-sky-200 bg-sky-50 hover:bg-sky-100 text-sky-700"
                    onclick="marcarImagenPrincipal(${img.id})">
              <i class="fas fa-check-circle text-xs"></i>
              Principal
            </button>

            <button type="button"
                    class="inline-flex items-center justify-center w-8 h-8 rounded-full border border-rose-200 bg-rose-50 hover:bg-rose-100"
                    title="Eliminar imagen"
                    onclick="eliminarImagenProducto(${img.id})">
              <i class="fas fa-trash text-rose-600 text-xs"></i>
            </button>
          </div>
        `;

        contenedorImagenesProducto.appendChild(tarjeta);
      });
    })
    .catch(err => {
      console.error("Error cargando imágenes de producto:", err);
      alert("Error al cargar imágenes del producto.");
    });
}

// Agregar nueva imagen
if (btnAgregarImagenProducto) {
  btnAgregarImagenProducto.addEventListener("click", async () => {
    if (!productoImagenesActualId) {
      alert("No se ha definido el producto.");
      return;
    }

    const url = (inputNuevaImagenUrl?.value || "").trim();
    const esPrincipal = chkNuevaImagenPrincipal?.checked ? 1 : 0;

    if (!url) {
      alert("La URL de la imagen es obligatoria.");
      return;
    }

    try {
      const res = await fetch("../../backend/api/producto_imagen.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
          producto_id: productoImagenesActualId,
          url,
          es_principal: esPrincipal
        })
      });

      const data = await res.json();
      if (!data.success) {
        throw new Error(data.error || "Error al agregar imagen");
      }

      if (inputNuevaImagenUrl) inputNuevaImagenUrl.value = "";
      if (chkNuevaImagenPrincipal) chkNuevaImagenPrincipal.checked = false;

      cargarImagenesProducto();
    } catch (err) {
      console.error("Error agregando imagen:", err);
      alert("Error al agregar imagen.");
    }
  });
}

// Marcar como principal
async function marcarImagenPrincipal(idImagen) {
  try {
    const res = await fetch("../../backend/api/producto_imagen.php", {
      method: "PUT",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({
        id: idImagen,
        es_principal: 1
      })
    });

    const data = await res.json();
    if (!data.success) {
      throw new Error(data.error || "Error al marcar como principal");
    }

    cargarImagenesProducto();
  } catch (err) {
    console.error("Error al marcar principal:", err);
    alert("Error al marcar la imagen como principal.");
  }
}

// Eliminar imagen
async function eliminarImagenProducto(idImagen) {
  if (!confirm("¿Eliminar esta imagen?")) return;

  try {
    const res = await fetch("../../backend/api/producto_imagen.php", {
      method: "DELETE",
      headers: { "Content-Type": "application/x-www-form-urlencoded" },
      body: `id=${encodeURIComponent(idImagen)}`
    });

    const data = await res.json();
    if (!data.success) {
      throw new Error(data.error || "Error al eliminar imagen");
    }

    cargarImagenesProducto();
  } catch (err) {
    console.error("Error eliminando imagen:", err);
    alert("Error al eliminar imagen.");
  }
}

// ------------------- LOGOUT (si lo usas) -------------------
const btnLogout = document.getElementById("btnLogout");
if (btnLogout) {
  btnLogout.addEventListener("click", () => {
    localStorage.removeItem("token");
    document.cookie = "token=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
    location.href = "../";
  });
}
