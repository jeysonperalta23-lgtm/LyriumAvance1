// C:\xampp\htdocs\schedule\frontend\js\cliente.js

// Usuario logueado (por si lo necesitas para auditoría)
const usuarioLog = JSON.parse(localStorage.getItem("usuario")) || {};
const USUARIO_ID = usuarioLog.id || null;

// ------------ Referencias DOM -------------
const inputBuscarCliente      = document.getElementById("inputBuscarCliente");
const selectPaginationCliente = document.getElementById("selectPaginationCliente");
const clientesCardsContainer  = document.getElementById("clientesCardsContainer");
const panelControlesClientes  = document.getElementById("panelControlesClientes");
const btnTogglePanelClientes  = document.getElementById("btnTogglePanelClientes");

// Modal cliente
const modalCliente           = document.getElementById("modalCliente");
const modalTituloCliente     = document.getElementById("modalTituloCliente");
const btnAbrirModalCliente   = document.getElementById("btnAbrirModalCliente");
const btnCerrarModalCliente  = document.getElementById("btnCerrarModalCliente");
const btnCancelarCliente     = document.getElementById("btnCancelarCliente");
const btnGuardarCliente      = document.getElementById("btnGuardarCliente");

// Campos modal
const inputClienteNombre          = document.getElementById("clienteNombre");
const selectClienteTipo           = document.getElementById("clienteTipo");
const selectClienteDocumentoTipo  = document.getElementById("clienteDocumentoTipo");
const inputClienteDocumentoNumero = document.getElementById("clienteDocumentoNumero");
const inputClienteCorreo          = document.getElementById("clienteCorreo");
const inputClienteTelefono        = document.getElementById("clienteTelefono");
const inputClienteDireccion       = document.getElementById("clienteDireccion");
const inputClienteCiudad          = document.getElementById("clienteCiudad");
const textareaClienteObservaciones= document.getElementById("clienteObservaciones");

// Toggle secciones modal
let panelClientesVisible = false;

// Datos en memoria
let clientesOriginales = [];
let clientesFiltrados  = [];

// ======================= Helpers =======================

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

function obtenerTextoFiltroCliente() {
  return (inputBuscarCliente?.value || "").toLowerCase();
}

function filtrarClientes() {
  const texto = obtenerTextoFiltroCliente();
  if (!texto) return [...clientesOriginales];

  return clientesOriginales.filter((c) =>
    [
      c.nombre_razon_social,
      c.documento_tipo + " " + c.documento_numero,
      c.correo_contacto,
      c.telefono_contacto,
      c.tipo_cliente
    ]
      .map(f => (f || "").toLowerCase())
      .some(v => v.includes(texto))
  );
}

// ======================= Render tarjetas =======================

function renderClientesCards() {
  if (!clientesCardsContainer) return;

  clientesCardsContainer.innerHTML = "";

  if (!Array.isArray(clientesFiltrados) || clientesFiltrados.length === 0) {
    clientesCardsContainer.innerHTML = `
      <div class="col-span-1 sm:col-span-2 lg:col-span-3 xl:col-span-4">
        <div class="w-full border border-dashed border-sky-200 rounded-2xl bg-sky-50/60 p-4 flex items-center justify-center">
          <div class="flex items-center gap-3 text-sky-700 text-base">
            <i class="fas fa-info-circle"></i>
            <span>No se encontraron clientes para mostrar.</span>
          </div>
        </div>
      </div>
    `;
    return;
  }

  let max = clientesFiltrados.length;
  if (selectPaginationCliente) {
    const val = selectPaginationCliente.value;
    if (val !== "all") {
      const n = parseInt(val, 10);
      if (!isNaN(n)) {
        max = Math.min(max, n);
      }
    }
  }

  for (let i = 0; i < max; i++) {
    const c = clientesFiltrados[i];

    const nombre  = c.nombre_razon_social || "Cliente sin nombre";
    const tipo    = c.tipo_cliente || "Persona";
    const docTipo = c.documento_tipo || "";
    const docNum  = c.documento_numero || c.documento_identidad || "";
    const correo  = c.correo_contacto || "—";
    const telefono= c.telefono_contacto || "—";
    const ciudad  = c.ciudad_contacto || "";
    const observ  = c.observaciones || "";

    const activo      = String(c.estado) === "1";
    const estadoText  = activo ? "Activo" : "Inactivo";
    const estadoClass = activo
      ? "bg-emerald-50 text-emerald-700 border-emerald-200"
      : "bg-slate-100 text-slate-600 border-slate-200";
    const iconEstado  = activo ? "fa-toggle-on text-emerald-500" : "fa-toggle-off text-slate-400";

    let tipoClass = "bg-slate-100 text-slate-700 border-slate-200";
    if (tipo === "Persona") {
      tipoClass = "bg-sky-50 text-sky-700 border-sky-200";
    } else if (tipo === "Empresa") {
      tipoClass = "bg-amber-50 text-amber-700 border-amber-200";
    }

    const card = document.createElement("article");
    card.className = "cliente-card-hover border border-sky-100 bg-white rounded-2xl p-4 flex flex-col gap-3 shadow-sm text-[14px]";

    card.innerHTML = `
      <div class="flex items-start gap-3">
        <div class="shrink-0">
          <div class="w-11 h-11 rounded-full border border-sky-100 shadow-sm overflow-hidden flex items-center justify-center
                      bg-gradient-to-br from-sky-400 to-sky-600">
            <span class="text-sm font-bold text-white">
              ${escapeHTML(nombre.charAt(0).toUpperCase())}
            </span>
          </div>
        </div>

        <div class="flex-1 space-y-1">
          <div class="flex items-start justify-between gap-2">
            <div>
              <p class="text-base font-semibold text-slate-800 leading-tight">
                ${escapeHTML(nombre)}
              </p>
              <p class="text-xs text-slate-500">
                ${escapeHTML(ciudad || "Sin ciudad")}
              </p>
            </div>

            <div class="flex flex-col items-end gap-1">
              <span class="px-2 py-0.5 rounded-full text-xs font-semibold border ${tipoClass}">
                ${escapeHTML(tipo)}
              </span>
              <button type="button"
                      class="flex items-center gap-1 px-2 py-0.5 rounded-full border text-xs ${estadoClass}"
                      onclick="toggleEstadoCliente(${c.id})">
                <i class="fas ${iconEstado} text-sm"></i>
                <span>${estadoText}</span>
              </button>
            </div>
          </div>

          <div class="mt-2 space-y-1 text-sm text-slate-600">
            <p>
              <span class="font-semibold text-slate-700">Documento:</span>
              <span>${escapeHTML((docTipo + " " + docNum).trim() || "—")}</span>
            </p>
            <p class="break-all">
              <span class="font-semibold text-slate-700">Correo:</span>
              <span>${escapeHTML(correo)}</span>
            </p>
            <p>
              <span class="font-semibold text-slate-700">Teléfono:</span>
              <span>${escapeHTML(telefono)}</span>
            </p>
            ${
              observ
                ? `<p class="text-xs text-slate-500 mt-1 line-clamp-2">
                     <span class="font-semibold text-slate-600">Notas:</span>
                     ${escapeHTML(observ)}
                   </p>`
                : ""
            }
          </div>
        </div>
      </div>

      <div class="flex items-center justify-between pt-2 border-t border-slate-100 mt-2">
        <button type="button"
                class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs border border-sky-200 bg-sky-50 hover:bg-sky-100 text-sky-700"
                onclick="abrirModalEditarCliente(${c.id})">
          <i class="fas fa-edit text-sm"></i>
          <span>Editar</span>
        </button>
        <!--
        <div class="flex items-center gap-2">
          <button type="button"
                  class="w-9 h-9 flex items-center justify-center rounded-full border border-red-100 bg-red-50 hover:bg-red-100"
                  title="Eliminar cliente"
                  onclick="eliminarCliente(${c.id})">
            <i class="fas fa-trash text-red-500 text-sm"></i>
          </button>
        </div>
        -->
      </div>
    `;

    clientesCardsContainer.appendChild(card);
  }
}

function aplicarFiltroYRenderClientes() {
  clientesFiltrados = filtrarClientes();
  renderClientesCards();
}

// ======================= Eventos filtros =======================

if (inputBuscarCliente) {
  inputBuscarCliente.addEventListener("focus", () => {
    if (!inputBuscarCliente.dataset.limpio) {
      inputBuscarCliente.value = "";
      inputBuscarCliente.dataset.limpio = "true";
    }
  });

  inputBuscarCliente.addEventListener("input", () => {
    aplicarFiltroYRenderClientes();
  });
}

if (selectPaginationCliente) {
  selectPaginationCliente.addEventListener("change", () => {
    renderClientesCards();
  });
}

// ======================= Toggle panel filtros =======================

window.addEventListener("DOMContentLoaded", () => {
  cargarClientes();

  if (panelControlesClientes && btnTogglePanelClientes) {
    panelControlesClientes.classList.add("hidden");
    btnTogglePanelClientes.innerHTML =
      '<i class="fas fa-chevron-down text-[10px]"></i> Mostrar filtros';

    btnTogglePanelClientes.addEventListener("click", () => {
      panelClientesVisible = !panelClientesVisible;

      if (panelClientesVisible) {
        panelControlesClientes.classList.remove("hidden");
        btnTogglePanelClientes.innerHTML =
          '<i class="fas fa-chevron-up text-[10px]"></i> Ocultar filtros';
      } else {
        panelControlesClientes.classList.add("hidden");
        btnTogglePanelClientes.innerHTML =
          '<i class="fas fa-chevron-down text-[10px]"></i> Mostrar filtros';
      }
    });
  }
});

// ======================= Cargar clientes =======================

function cargarClientes() {
  fetch("../../backend/api/cliente.php")
    .then(res => res.json())
    .then(data => {
      if (!data.success) {
        throw new Error(data.error || "Error al cargar clientes");
      }

      clientesOriginales = (data.clientes || []).map(c => ({
        ...c,
        estado: String(c.estado ?? "1").trim()
      }));

      aplicarFiltroYRenderClientes();
    })
    .catch(err => {
      console.error("Error cargando clientes:", err);
      alert("Error al cargar clientes");
    });
}

// ======================= Exportar CSV =======================

function exportarCSVClientes() {
  if (!Array.isArray(clientesOriginales)) {
    return alert("No hay datos para exportar.");
  }

  const filas = filtrarClientes();
  const encabezados = [
    "ID",
    "Nombre / Razón social",
    "Tipo cliente",
    "Documento tipo",
    "Documento número",
    "Correo",
    "Teléfono",
    "Estado"
  ];

  if (filas.length === 0) {
    return alert("No se encontraron resultados para exportar.");
  }

  const csv = [
    encabezados.join(";"),
    ...filas.map(row =>
      [
        row.id,
        row.nombre_razon_social || "",
        row.tipo_cliente || "",
        row.documento_tipo || "",
        row.documento_numero || row.documento_identidad || "",
        row.correo_contacto || "",
        row.telefono_contacto || "",
        row.estado === "1" ? "Activo" : "Inactivo"
      ].join(";")
    )
  ].join("\n");

  const blob = new Blob([csv], { type: "text/csv;charset=utf-8;" });
  const link = document.createElement("a");
  link.href = URL.createObjectURL(blob);
  link.download = "clientes.csv";
  link.click();
}

// ======================= Modal nuevo / editar =======================

function limpiarModalCliente() {
  if (inputClienteNombre)          inputClienteNombre.value = "";
  if (selectClienteTipo)           selectClienteTipo.value = "";
  if (selectClienteDocumentoTipo)  selectClienteDocumentoTipo.value = "";
  if (inputClienteDocumentoNumero) inputClienteDocumentoNumero.value = "";
  if (inputClienteCorreo)          inputClienteCorreo.value = "";
  if (inputClienteTelefono)        inputClienteTelefono.value = "";
  if (inputClienteDireccion)       inputClienteDireccion.value = "";
  if (inputClienteCiudad)          inputClienteCiudad.value = "";
  if (textareaClienteObservaciones)textareaClienteObservaciones.value = "";

  modalCliente.dataset.editando = "false";
  modalCliente.dataset.id       = "";
  mostrarTodasLasSeccionesCliente();
}

if (btnAbrirModalCliente) {
  btnAbrirModalCliente.addEventListener("click", () => {
    limpiarModalCliente();
    modalTituloCliente.textContent = "Nuevo cliente";
    modalCliente.classList.remove("hidden");
  });
}

if (btnCerrarModalCliente) {
  btnCerrarModalCliente.addEventListener("click", () => {
    modalCliente.classList.add("hidden");
  });
}
if (btnCancelarCliente) {
  btnCancelarCliente.addEventListener("click", () => {
    modalCliente.classList.add("hidden");
  });
}

// Guardar cliente
if (btnGuardarCliente) {
  btnGuardarCliente.addEventListener("click", async () => {
    const nombre  = (inputClienteNombre.value || "").trim();
    const tipo    = (selectClienteTipo.value || "").trim();
    const docTipo = (selectClienteDocumentoTipo.value || "").trim();
    const docNum  = (inputClienteDocumentoNumero.value || "").trim();
    const correo  = (inputClienteCorreo.value || "").trim();
    const telefono= (inputClienteTelefono.value || "").trim();
    const direccion = (inputClienteDireccion.value || "").trim();
    const ciudad    = (inputClienteCiudad.value || "").trim();
    const observ    = (textareaClienteObservaciones.value || "").trim();

    const editando = modalCliente.dataset.editando === "true";
    const id       = modalCliente.dataset.id || null;

    if (!nombre || !tipo || !docTipo || !docNum) {
      alert("Nombre, tipo de cliente, tipo y número de documento son obligatorios.");
      return;
    }

    const payload = {
      nombre_razon_social: nombre,
      tipo_cliente: tipo,
      documento_tipo: docTipo,
      documento_numero: docNum,
      correo_contacto: correo,
      telefono_contacto: telefono,
      direccion_contacto: direccion,
      ciudad_contacto: ciudad,
      observaciones: observ
    };

    if (editando && id) {
      payload.id = id;
    }

    try {
      const res = await fetch("../../backend/api/cliente.php", {
        method: editando ? "PUT" : "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(payload)
      });

      const data = await res.json();
      if (!data.success) {
        throw new Error(data.error || "Error al guardar cliente");
      }

      modalCliente.classList.add("hidden");
      cargarClientes();
    } catch (err) {
      console.error("Error guardando cliente:", err);
      alert("Error al guardar cliente");
    }
  });
}

// Abrir modal en modo edición
function abrirModalEditarCliente(id) {
  const cliente = clientesOriginales.find(c => Number(c.id) === Number(id));
  if (!cliente) {
    alert("No se encontró el cliente en memoria.");
    return;
  }

  modalTituloCliente.textContent = "Editar cliente";

  inputClienteNombre.value          = cliente.nombre_razon_social || "";
  selectClienteTipo.value           = cliente.tipo_cliente || "";
  selectClienteDocumentoTipo.value  = cliente.documento_tipo || "";
  inputClienteDocumentoNumero.value = cliente.documento_numero || cliente.documento_identidad || "";
  inputClienteCorreo.value          = cliente.correo_contacto || "";
  inputClienteTelefono.value        = cliente.telefono_contacto || "";
  inputClienteDireccion.value       = cliente.direccion_contacto || "";
  inputClienteCiudad.value          = cliente.ciudad_contacto || "";
  textareaClienteObservaciones.value= cliente.observaciones || "";

  modalCliente.dataset.editando = "true";
  modalCliente.dataset.id       = cliente.id;
  modalCliente.classList.remove("hidden");
  mostrarTodasLasSeccionesCliente();
}

// ======================= Eliminar cliente =======================

async function eliminarCliente(id) {
  if (!confirm("¿Eliminar este cliente?")) return;

  try {
    const response = await fetch("../../backend/api/cliente.php", {
      method: "DELETE",
      headers: { "Content-Type": "application/x-www-form-urlencoded" },
      body: `id=${encodeURIComponent(id)}`
    });

    const data = await response.json();
    if (!data.success) {
      throw new Error(data.error || "Error al eliminar cliente");
    }

    cargarClientes();
  } catch (err) {
    console.error("Error eliminando cliente:", err);
    alert("Error al eliminar cliente");
  }
}

// ======================= Toggle estado =======================

async function toggleEstadoCliente(clienteId) {
  const cliente = clientesOriginales.find(c => Number(c.id) === Number(clienteId));
  if (!cliente) {
    alert("No se encontró el cliente en memoria.");
    return;
  }

  const nuevoEstado = String(cliente.estado) === "1" ? 0 : 1;

  const payload = {
    id: cliente.id,
    nombre_razon_social: cliente.nombre_razon_social,
    tipo_cliente: cliente.tipo_cliente,
    documento_tipo: cliente.documento_tipo,
    documento_numero: cliente.documento_numero || cliente.documento_identidad,
    correo_contacto: cliente.correo_contacto,
    telefono_contacto: cliente.telefono_contacto,
    direccion_contacto: cliente.direccion_contacto,
    ciudad_contacto: cliente.ciudad_contacto,
    observaciones: cliente.observaciones,
    estado: nuevoEstado
  };

  try {
    const res = await fetch("../../backend/api/cliente.php", {
      method: "PUT",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(payload)
    });

    const data = await res.json();
    if (!data.success) {
      alert(data.error || "Error al actualizar estado.");
      cargarClientes();
      return;
    }

    cliente.estado = String(nuevoEstado);
    aplicarFiltroYRenderClientes();

  } catch (err) {
    console.error("Error al actualizar estado:", err);
    alert("Error de comunicación al actualizar el estado.");
    cargarClientes();
  }
}

// ======================= Toggle secciones modal =======================

function toggleSeccionCliente(tipo) {
  let cuerpo, icono, label;

  if (tipo === "basico") {
    cuerpo = document.getElementById("cuerpoClienteBasico");
    icono  = document.getElementById("iconClienteBasico");
    label  = document.getElementById("labelClienteBasico");
  } else if (tipo === "contacto") {
    cuerpo = document.getElementById("cuerpoClienteContacto");
    icono  = document.getElementById("iconClienteContacto");
    label  = document.getElementById("labelClienteContacto");
  }

  if (!cuerpo) return;

  const estaOculto = cuerpo.classList.toggle("hidden");

  if (icono) {
    icono.classList.remove("fa-chevron-up", "fa-chevron-down");
    icono.classList.add(estaOculto ? "fa-chevron-down" : "fa-chevron-up");
  }

  if (label) {
    label.textContent = estaOculto ? "Mostrar" : "Ocultar";
  }
}

function mostrarTodasLasSeccionesCliente() {
  const secciones = [
    { cuerpoId: "cuerpoClienteBasico",   iconId: "iconClienteBasico",   labelId: "labelClienteBasico" },
    { cuerpoId: "cuerpoClienteContacto", iconId: "iconClienteContacto", labelId: "labelClienteContacto" },
  ];

  secciones.forEach(s => {
    const cuerpo = document.getElementById(s.cuerpoId);
    const icono  = document.getElementById(s.iconId);
    const label  = document.getElementById(s.labelId);

    if (cuerpo && cuerpo.classList.contains("hidden")) {
      cuerpo.classList.remove("hidden");
    }
    if (icono) {
      icono.classList.remove("fa-chevron-down");
      icono.classList.add("fa-chevron-up");
    }
    if (label) {
      label.textContent = "Ocultar";
    }
  });
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
