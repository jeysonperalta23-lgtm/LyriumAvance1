// =======================
//  C:\xampp\htdocs\schedule\frontend\js\usuario.js
// =======================

// ------------ Usuario logueado -------------
const usuarioLog = JSON.parse(localStorage.getItem("usuario")) || {};
const USUARIO_ID = usuarioLog.id || null;

// ------------ Referencias DOM -------------
const inputBuscar      = document.getElementById("inputBuscar");
const selectPagination = document.getElementById("selectPagination");
const modal            = document.getElementById("modalUsuario");
const btnAbrir         = document.getElementById("btnAbrirModal");
const btnCerrar        = document.getElementById("btnCerrarModal");
const btnGuardar       = document.getElementById("btnGuardarUsuario");
const btnCancelar      = document.getElementById("btnCancelarUsuario");
const chkModalConfirmarCambios = document.getElementById("modalConfirmarCambios");

// Campos de persona
const inputNombrePersona       = document.getElementById("modalNombrePersona");
const inputDocumentoIdentidad  = document.getElementById("modalDocumentoIdentidad");
const inputFechaNacimiento     = document.getElementById("modalFechaNacimiento");
const selectSexo               = document.getElementById("modalSexo");

// Campos de usuario
const inputCorreo       = document.getElementById("modalCorreo");
const inputUsername     = document.getElementById("modalUsername");
const inputContrasena   = document.getElementById("modalContrasena");
const selectRol         = document.getElementById("modalRol");

// Avatar / color (modal grande de usuario)
const inputAvatar           = document.getElementById("modalAvatar");
const inputAvatarColor      = document.getElementById("modalColorAvatar");
const inputAvatarFilename   = document.getElementById("modalAvatarFilename");
const avatarPreviewWrapper  = document.getElementById("avatarPreviewWrapper");
const avatarPreviewInitials = document.getElementById("avatarPreviewInitials");
const avatarPreviewImg      = document.getElementById("avatarPreviewImg");

// Panel colapsable (header + filtros)
const panelControlesUsuarios = document.getElementById("panelControlesUsuarios");
const btnTogglePanelUsuarios = document.getElementById("btnTogglePanelUsuarios");

// Contenedor de tarjetas
const usuariosCardsContainer = document.getElementById("usuariosCardsContainer");

// Modal AVATAR (desde catálogo)
const modalAvatarUsuario            = document.getElementById("modalAvatarUsuario");
const avatarUsuarioNombre           = document.getElementById("avatarUsuarioNombre");
const avatarUserPreviewWrapper      = document.getElementById("avatarUserPreviewWrapper");
const avatarUserPreviewInitials     = document.getElementById("avatarUserPreviewInitials");
const avatarUserPreviewImg          = document.getElementById("avatarUserPreviewImg");
const inputAvatarUsuarioFile        = document.getElementById("modalAvatarUsuarioFile");
const inputAvatarUsuarioColor       = document.getElementById("modalAvatarUsuarioColor");
const btnCerrarModalAvatarUsuario   = document.getElementById("btnCerrarModalAvatarUsuario");
const btnCancelarModalAvatarUsuario = document.getElementById("btnCancelarModalAvatarUsuario");
const btnGuardarModalAvatarUsuario  = document.getElementById("btnGuardarModalAvatarUsuario");

// Modal CONFIG SCHEDULE (desde catálogo)
const modalScheduleConfig            = document.getElementById("modalScheduleConfig");
const scheduleConfigNombre           = document.getElementById("scheduleConfigNombre");
const btnCerrarModalScheduleConfig   = document.getElementById("btnCerrarModalScheduleConfig");
const btnCancelarModalScheduleConfig = document.getElementById("btnCancelarModalScheduleConfig");
const btnGuardarModalScheduleConfig  = document.getElementById("btnGuardarModalScheduleConfig");

// Estado inicial: panel oculto superior
let panelVisible = false;

// Datos en memoria
let datosOriginales = [];
let datosFiltrados  = [];

// Archivo de avatar seleccionado en el modal grande (no subido aún)
let avatarFile = null;

// Para los modales desde catálogo
let usuarioAvatarActual   = null;
let avatarUserFile        = null;
let usuarioScheduleActual = null;

// Ruta base donde se guardarán las imágenes de avatar (ajusta si es necesario)
const AVATAR_BASE_URL = "../../uploads/avatars/";

// =======================
//  Helpers generales
// =======================

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

// =======================
//  Helpers Columnas Schedule
// =======================

function getScheduleColsCheckboxes() {
  return document.querySelectorAll(".chkScheduleCol");
}

function obtenerColumnasScheduleSeleccionadas() {
  const checks = getScheduleColsCheckboxes();
  const cols = [];
  checks.forEach(chk => {
    if (chk.checked) {
      cols.push(chk.value);
    }
  });
  return cols;
}

function marcarColumnasScheduleDesdeConfig(config) {
  const checks = getScheduleColsCheckboxes();

  // Si no hay config -> marcar todo
  if (!config || !Array.isArray(config) || config.length === 0) {
    checks.forEach(chk => (chk.checked = true));
    return;
  }

  checks.forEach(chk => {
    chk.checked = config.includes(chk.value);
  });
}

// =======================
//  Helpers Avatar (modal grande)
// =======================

function getIniciales(nombre, username) {
  const base = (nombre && nombre.trim()) || (username && username.trim()) || "U";
  const partes = base.split(" ").filter(Boolean);
  if (partes.length === 1) {
    return partes[0].charAt(0).toUpperCase();
  }
  return (partes[0].charAt(0) + partes[1].charAt(0)).toUpperCase();
}

function actualizarPreviewAvatarDesdeDatos(nombre, username, avatarColor, avatarFilename) {
  const color = avatarColor || "#4f46e5";

  if (avatarPreviewWrapper) {
    avatarPreviewWrapper.style.background = color;
  }

  const iniciales = getIniciales(nombre, username);

  if (avatarFilename) {
    if (avatarPreviewImg) {
      avatarPreviewImg.src = AVATAR_BASE_URL + avatarFilename;
      avatarPreviewImg.classList.remove("hidden");
    }
    if (avatarPreviewInitials) {
      avatarPreviewInitials.textContent = iniciales;
      avatarPreviewInitials.classList.add("hidden");
    }
  } else {
    if (avatarPreviewImg) {
      avatarPreviewImg.src = "";
      avatarPreviewImg.classList.add("hidden");
    }
    if (avatarPreviewInitials) {
      avatarPreviewInitials.textContent = iniciales;
      avatarPreviewInitials.classList.remove("hidden");
    }
  }
}

function resetPreviewAvatar() {
  avatarFile = null;
  if (inputAvatar) {
    inputAvatar.value = "";
  }
  if (inputAvatarFilename) {
    inputAvatarFilename.value = "";
  }
  const nombre = inputNombrePersona ? inputNombrePersona.value : "";
  const username = inputUsername ? inputUsername.value : "";
  const color = inputAvatarColor ? inputAvatarColor.value || "#4f46e5" : "#4f46e5";
  actualizarPreviewAvatarDesdeDatos(nombre, username, color, "");
}

// Previsualizar imagen al elegir archivo en modal grande
if (inputAvatar) {
  inputAvatar.addEventListener("change", (e) => {
    const file = e.target.files[0];
    if (!file) {
      avatarFile = null;
      resetPreviewAvatar();
      return;
    }

    const validTypes = ["image/jpeg", "image/jpg", "image/png", "image/webp"];
    if (!validTypes.includes(file.type)) {
      alert("Formato de imagen no válido. Usa JPG, PNG o WebP.");
      inputAvatar.value = "";
      avatarFile = null;
      resetPreviewAvatar();
      return;
    }

    const maxSize = 2 * 1024 * 1024;
    if (file.size > maxSize) {
      alert("La imagen excede los 2 MB de tamaño máximo permitido.");
      inputAvatar.value = "";
      avatarFile = null;
      resetPreviewAvatar();
      return;
    }

    avatarFile = file;

    const reader = new FileReader();
    reader.onload = (ev) => {
      const color = inputAvatarColor ? inputAvatarColor.value || "#4f46e5" : "#4f46e5";
      if (avatarPreviewWrapper) {
        avatarPreviewWrapper.style.background = color;
      }

      if (avatarPreviewImg) {
        avatarPreviewImg.src = ev.target.result;
        avatarPreviewImg.classList.remove("hidden");
      }

      const nombre = inputNombrePersona ? inputNombrePersona.value : "";
      const username = inputUsername ? inputUsername.value : "";
      const iniciales = getIniciales(nombre, username);

      if (avatarPreviewInitials) {
        avatarPreviewInitials.textContent = iniciales;
        avatarPreviewInitials.classList.add("hidden");
      }
    };
    reader.readAsDataURL(file);
  });
}

// Cambiar color de fondo cuando se cambia el color de avatar
if (inputAvatarColor) {
  inputAvatarColor.addEventListener("input", () => {
    const color = inputAvatarColor.value || "#4f46e5";
    if (avatarPreviewWrapper) {
      avatarPreviewWrapper.style.background = color;
    }
  });
}

// Actualizar iniciales del avatar cuando cambia el nombre/username
if (inputNombrePersona) {
  inputNombrePersona.addEventListener("input", () => {
    const nombre = inputNombrePersona.value;
    const username = inputUsername ? inputUsername.value : "";
    const color = inputAvatarColor ? inputAvatarColor.value || "#4f46e5" : "#4f46e5";
    const filename = inputAvatarFilename ? inputAvatarFilename.value : "";
    actualizarPreviewAvatarDesdeDatos(nombre, username, color, filename);
  });
}
if (inputUsername) {
  inputUsername.addEventListener("input", () => {
    const nombre = inputNombrePersona ? inputNombrePersona.value : "";
    const username = inputUsername.value;
    const color = inputAvatarColor ? inputAvatarColor.value || "#4f46e5" : "#4f46e5";
    const filename = inputAvatarFilename ? inputAvatarFilename.value : "";
    actualizarPreviewAvatarDesdeDatos(nombre, username, color, filename);
  });
}

// =======================
//  FOCUS en buscador
// =======================

if (inputBuscar) {
  inputBuscar.addEventListener("focus", () => {
    if (!inputBuscar.dataset.limpio) {
      inputBuscar.value = "";
      inputBuscar.dataset.limpio = "true";
    }
  });
}

// =======================
//  CONFIG SCHEDULE USUARIO LOGUEADO
// =======================

let configColumnasScheduleUsuario = null;

async function cargarConfigScheduleUsuario() {
  if (!USUARIO_ID) return;

  try {
    const res = await fetch(`../../backend/api/usuario.php?id=${USUARIO_ID}`);
    const data = await res.json();

    if (!data.success) {
      console.warn("No se pudo obtener config de usuario", data.error);
      return;
    }

    let usuario = null;

    if (Array.isArray(data.usuarios)) {
      usuario = data.usuarios.find(u => Number(u.id) === Number(USUARIO_ID));
    } else if (data.usuario) {
      usuario = data.usuario;
    }

    if (!usuario) return;

    if (usuario.config_schedule_columnas) {
      if (typeof usuario.config_schedule_columnas === "string") {
        configColumnasScheduleUsuario = JSON.parse(usuario.config_schedule_columnas);
      } else if (Array.isArray(usuario.config_schedule_columnas)) {
        configColumnasScheduleUsuario = usuario.config_schedule_columnas;
      }
    }
  } catch (err) {
    console.error("Error obteniendo configuración de columnas Permisos:", err);
  }
}

// =======================
//  FILTRO + RENDER TARJETAS
// =======================

function obtenerTextoFiltro() {
  return (inputBuscar?.value || "").toLowerCase();
}

function filtrarUsuarios() {
  const texto = obtenerTextoFiltro();
  if (!texto) return [...datosOriginales];

  return datosOriginales.filter((item) =>
    [
      item.nombre_razon_social,
      item.documento_identidad,
      item.correo,
      item.username,
      item.rol
    ]
      .map(f => (f || "").toLowerCase())
      .some(v => v.includes(texto))
  );
}

function renderUsuariosCards() {
  if (!usuariosCardsContainer) return;

  usuariosCardsContainer.innerHTML = "";

  if (!Array.isArray(datosFiltrados) || datosFiltrados.length === 0) {
    usuariosCardsContainer.innerHTML = `
      <div class="col-span-1 sm:col-span-2 lg:col-span-3 xl:col-span-4">
        <div class="w-full border border-dashed border-sky-200 rounded-2xl bg-sky-50/60 p-4 flex items-center justify-center">
          <div class="flex items-center gap-3 text-sky-700 text-base">
            <i class="fas fa-info-circle"></i>
            <span>No se encontraron usuarios para mostrar.</span>
          </div>
        </div>
      </div>
    `;
    return;
  }

  let max = datosFiltrados.length;
  if (selectPagination) {
    const val = selectPagination.value;
    if (val !== "all") {
      const n = parseInt(val, 10);
      if (!isNaN(n)) {
        max = Math.min(max, n);
      }
    }
  }

  for (let i = 0; i < max; i++) {
    const u = datosFiltrados[i];
    const nombre    = u.nombre_razon_social || "Sin nombre";
    const username  = u.username || "";
    const documento = u.documento_identidad || "";
    const correo    = u.correo || "";
    const rol       = u.rol || "Sin rol";
    const iniciales = getIniciales(nombre, username);
    const avatarColor    = u.avatar_color || "#4f46e5";
    const avatarFilename = u.avatar_filename || "";
    const tieneAvatarImagen = avatarFilename.trim() !== "";
    const avatarUrl = AVATAR_BASE_URL + avatarFilename;

    let rolClass = "bg-slate-100 text-slate-700 border-slate-200";
    if (rol === "Administrador") {
      rolClass = "bg-rose-50 text-rose-700 border-rose-200";
    } else if (rol === "Cliente") {
      rolClass = "bg-emerald-50 text-emerald-700 border-emerald-200";
    } else if (rol === "Vendedor") {
      rolClass = "bg-amber-50 text-amber-700 border-amber-200";
    }

    const activo      = String(u.estado) === "1";
    const estadoText  = activo ? "Activo" : "Inactivo";
    const estadoClass = activo
      ? "bg-emerald-50 text-emerald-700 border-emerald-200"
      : "bg-slate-100 text-slate-600 border-slate-200";
    const iconEstado  = activo ? "fa-toggle-on text-emerald-500" : "fa-toggle-off text-slate-400";

    const usernameLabel = username ? `@${username}` : "Sin usuario";

    const card = document.createElement("article");
    // 👇 Aumentamos tamaño base del texto de toda la tarjeta
    card.className = "user-card-hover border border-sky-100 bg-white rounded-2xl p-4 flex flex-col gap-3 shadow-sm text-[14px]";

    card.innerHTML = `
      <div class="flex items-start gap-3">
        <button type="button"
                class="shrink-0"
                title="Configurar avatar"
                onclick="abrirModalAvatarUsuario(${u.id})">
          <div class="w-11 h-11 rounded-full border border-sky-100 shadow-sm overflow-hidden flex items-center justify-center"
               style="background:${avatarColor};">
            ${
              tieneAvatarImagen
                ? `<img src="${avatarUrl}" alt="Avatar" class="w-full h-full object-cover" />`
                : `<span class="text-sm font-bold text-white">${escapeHTML(iniciales)}</span>`
            }
          </div>
        </button>

        <div class="flex-1 space-y-1">
          <div class="flex items-start justify-between gap-2">
            <div>
              <!-- Nombre más grande -->
              <p class="text-base font-semibold text-slate-800 leading-tight">
                ${escapeHTML(nombre)}
              </p>
              <!-- Username un poco más grande -->
              <p class="text-xs text-slate-500">
                ${escapeHTML(usernameLabel)}
              </p>
            </div>

            <div class="flex flex-col items-end gap-1">
              <!-- Chip de rol más grande -->
              <span class="px-2 py-0.5 rounded-full text-xs font-semibold border ${rolClass}">
                ${escapeHTML(rol)}
              </span>
              <!-- Botón de estado más grande -->
              <button type="button"
                      class="flex items-center gap-1 px-2 py-0.5 rounded-full border text-xs ${estadoClass}"
                      onclick="toggleEstadoUsuario(${u.id})">
                <i class="fas ${iconEstado} text-sm"></i>
                <span>${estadoText}</span>
              </button>
            </div>
          </div>

          <!-- Detalles Documento / Correo más grandes -->
          <div class="mt-2 space-y-1 text-sm text-slate-600">
            <p>
              <span class="font-semibold text-slate-700">Documento:</span>
              <span>${escapeHTML(documento || "—")}</span>
            </p>
            <p class="break-all">
              <span class="font-semibold text-slate-700">Correo:</span>
              <span>${escapeHTML(correo || "—")}</span>
            </p>
          </div>
        </div>
      </div>

      <div class="flex items-center justify-between pt-2 border-t border-slate-100 mt-2">
        <!-- Botón Permisos más grande -->
        <button type="button"
                class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs border border-sky-200 bg-sky-50 hover:bg-sky-100 text-sky-700"
                onclick="abrirModalConfigSchedule(${u.id})">
          <i class="fas fa-calendar-check text-sm"></i>
          <span>Permisos</span>
        </button>

        <div class="flex items-center gap-2">
          <button type="button"
                  class="w-9 h-9 flex items-center justify-center rounded-full border border-gray-200 bg-gray-50 hover:bg-gray-100"
                  title="Editar usuario"
                  onclick="abrirModalEditar(${u.id})">
            <i class="fas fa-cog text-gray-700 text-sm"></i>
          </button>
        </div>
      </div>
    `;

    usuariosCardsContainer.appendChild(card);
  }
}

function aplicarFiltroYRender() {
  datosFiltrados = filtrarUsuarios();
  renderUsuariosCards();
}

function aplicarFiltroRapido() {
  aplicarFiltroYRender();
}

if (inputBuscar) {
  inputBuscar.addEventListener("input", aplicarFiltroRapido);
}

// =======================
//  PAGINACIÓN (select)
// =======================

if (selectPagination) {
  selectPagination.addEventListener("change", () => {
    renderUsuariosCards();
  });
}

// =======================
//  INICIALIZACIÓN
// =======================

window.addEventListener("DOMContentLoaded", () => {
  cargarUsuarios();
  cargarConfigScheduleUsuario();

  if (panelControlesUsuarios && btnTogglePanelUsuarios) {
    panelControlesUsuarios.classList.add("hidden");
    btnTogglePanelUsuarios.innerHTML =
      '<i class="fas fa-chevron-down text-[10px]"></i> Mostrar filtros';

    btnTogglePanelUsuarios.addEventListener("click", () => {
      panelVisible = !panelVisible;

      if (panelVisible) {
        panelControlesUsuarios.classList.remove("hidden");
        btnTogglePanelUsuarios.innerHTML =
          '<i class="fas fa-chevron-up text-[10px]"></i> Ocultar filtros';
      } else {
        panelControlesUsuarios.classList.add("hidden");
        btnTogglePanelUsuarios.innerHTML =
          '<i class="fas fa-chevron-down text-[10px]"></i> Mostrar filtros';
      }
    });
  }
});

// =======================
//  CARGAR USUARIOS
// =======================

function cargarUsuarios() {
  fetch("../../backend/api/usuario.php")
    .then(res => res.json())
    .then(data => {
      if (!data.success) {
        throw new Error(data.error || "Error al cargar usuarios");
      }

      datosOriginales = (data.usuarios || []).map(u => ({
        ...u,
        estado: String(u.estado ?? "1").trim(),
        avatar_color: u.avatar_color || "#4f46e5",
        avatar_filename: u.avatar_filename || "",
        confirmar_cambios_estado_realizado_por: Number(
          u.confirmar_cambios_estado_realizado_por ?? 1
        )
      }));

      aplicarFiltroYRender();
    })
    .catch(err => {
      console.error("Error cargando usuarios:", err);
      alert("Error al cargar usuarios");
    });
}

// =======================
//  EXPORTAR CSV
// =======================

function exportarCSV() {
  if (!Array.isArray(datosOriginales)) {
    return alert("No hay datos para exportar.");
  }

  const filas = filtrarUsuarios();
  const encabezados = [
    "ID",
    "Nombre / Razón social",
    "Documento",
    "Correo",
    "Username",
    "Rol",
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
        row.documento_identidad || "",
        row.correo || "",
        row.username || "",
        row.rol || "",
        row.estado === "1" ? "Activo" : "Inactivo"
      ].join(";")
    )
  ].join("\n");

  const blob = new Blob([csv], { type: "text/csv;charset=utf-8;" });
  const link = document.createElement("a");
  link.href = URL.createObjectURL(blob);
  link.download = "usuarios.csv";
  link.click();
}

// =======================
//  MODAL NUEVO / EDITAR (grande)
// =======================

if (btnAbrir) {
  btnAbrir.addEventListener("click", () => {
    limpiarModal();
    document.getElementById("modalTitulo").innerText = "Nuevo usuario";
    modal.dataset.editando = "false";
    modal.dataset.id = "";
    modal.dataset.personaId = "";
    modal.classList.remove("hidden");

    if (inputAvatarColor) inputAvatarColor.value = "#4f46e5";
    resetPreviewAvatar();
    mostrarTodasLasSeccionesUsuario();
  });
}

if (btnCerrar) {
  btnCerrar.addEventListener("click", () => {
    modal.classList.add("hidden");
  });
}

if (btnCancelar) {
  btnCancelar.addEventListener("click", () => {
    modal.classList.add("hidden");
  });
}

// Guardar usuario (nuevo / editar) con avatar + config schedule + flag confirmar cambios
if (btnGuardar) {
  btnGuardar.addEventListener("click", async () => {
    const correo     = inputCorreo.value.trim();
    const username   = inputUsername.value.trim();
    const contrasena = inputContrasena.value.trim();
    const rol        = selectRol.value;

    const nombrePersona      = inputNombrePersona.value.trim();
    const documentoIdentidad = inputDocumentoIdentidad.value.trim();
    const fechaNacimiento    = inputFechaNacimiento.value || null;
    const sexo               = selectSexo.value || null;

    const editando = modal.dataset.editando === "true";
    const id        = modal.dataset.id || null;
    const personaId = modal.dataset.personaId || null;

    if (!correo || !username || !rol || !nombrePersona || !documentoIdentidad || (!editando && !contrasena)) {
      alert("Nombre, documento, correo, usuario, rol y contraseña (para nuevos) son obligatorios.");
      return;
    }

    const avatarColor = inputAvatarColor ? (inputAvatarColor.value || "#4f46e5") : "#4f46e5";
    let avatarFilename = inputAvatarFilename ? (inputAvatarFilename.value || "") : "";

    // Subir imagen si se seleccionó un archivo nuevo en modal grande
    if (avatarFile) {
      try {
        const formData = new FormData();
        formData.append("avatar", avatarFile);

        const respUpload = await fetch("../../backend/api/upload_avatar.php", {
          method: "POST",
          body: formData
        });

        const dataUpload = await respUpload.json();
        if (!dataUpload.success) {
          alert(dataUpload.error || "Error al subir la imagen de avatar.");
          return;
        }

        avatarFilename = dataUpload.filename || "";
        if (inputAvatarFilename) {
          inputAvatarFilename.value = avatarFilename;
        }
      } catch (err) {
        console.error("Error subiendo avatar:", err);
        alert("Error de comunicación al subir la imagen de avatar.");
        return;
      }
    }

    const confirmarCambios = chkModalConfirmarCambios
      ? (chkModalConfirmarCambios.checked ? 1 : 0)
      : 1;

    // Payload
    const payload = {
      correo,
      username,
      rol,
      nombre_razon_social: nombrePersona,
      documento_identidad: documentoIdentidad,
      fecha_nacimiento: fechaNacimiento,
      sexo: sexo,
      avatar_color: avatarColor,
      avatar_filename: avatarFilename,
      config_schedule_columnas: obtenerColumnasScheduleSeleccionadas(),
      confirmar_cambios_estado_realizado_por: confirmarCambios
    };

    if (contrasena) {
      payload.contrasena = contrasena;
    }

    if (editando && id) {
      payload.id = id;
      if (personaId) {
        payload.persona_id = personaId;
      }
    }

    try {
      const res = await fetch("../../backend/api/usuario.php", {
        method: editando ? "PUT" : "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(payload)
      });

      const data = await res.json();
      if (!data.success) {
        throw new Error(data.error || "Error al guardar usuario");
      }

      modal.classList.add("hidden");
      avatarFile = null;
      if (inputAvatarFilename) inputAvatarFilename.value = "";
      cargarUsuarios();
    } catch (err) {
      console.error("Error guardando usuario:", err);
      alert("Error al guardar usuario");
    }
  });
}

function abrirModalEditar(dataOrId) {
  let data = null;

  if (typeof dataOrId === "object") {
    data = dataOrId;
  } else {
    const id = Number(dataOrId);
    data = datosOriginales.find(u => Number(u.id) === id);
  }

  if (!data) {
    alert("No se encontró el usuario en memoria.");
    return;
  }

  document.getElementById("modalTitulo").innerText = "Editar usuario";

  inputCorreo.value     = data.correo || "";
  inputUsername.value   = data.username || "";
  selectRol.value       = data.rol || "";
  inputContrasena.value = "";

  inputNombrePersona.value      = data.nombre_razon_social || "";
  inputDocumentoIdentidad.value = data.documento_identidad || "";
  inputFechaNacimiento.value    = data.fecha_nacimiento || "";
  selectSexo.value              = data.sexo || "";

  const avatarColor   = data.avatar_color || "#4f46e5";
  const avatarFileSrv = data.avatar_filename || "";
  if (inputAvatarColor) inputAvatarColor.value = avatarColor;
  if (inputAvatarFilename) inputAvatarFilename.value = avatarFileSrv;
  avatarFile = null;
  if (inputAvatar) inputAvatar.value = "";

  actualizarPreviewAvatarDesdeDatos(
    data.nombre_razon_social,
    data.username,
    avatarColor,
    avatarFileSrv
  );

  // Setear checkbox Confirmar cambios Estado/Realizado por
  if (chkModalConfirmarCambios) {
    const flag = Number(data.confirmar_cambios_estado_realizado_por ?? 1);
    chkModalConfirmarCambios.checked = flag === 1;
  }

  modal.dataset.editando  = "true";
  modal.dataset.id        = data.id;
  modal.dataset.personaId = data.persona_id || "";
  modal.classList.remove("hidden");
  mostrarTodasLasSeccionesUsuario();
}

function limpiarModal() {
  inputCorreo.value     = "";
  inputUsername.value   = "";
  inputContrasena.value = "";
  selectRol.value       = "";

  inputNombrePersona.value      = "";
  inputDocumentoIdentidad.value = "";
  inputFechaNacimiento.value    = "";
  selectSexo.value              = "";

  if (inputAvatarColor) inputAvatarColor.value = "#4f46e5";
  if (inputAvatarFilename) inputAvatarFilename.value = "";
  if (inputAvatar) inputAvatar.value = "";
  avatarFile = null;
  resetPreviewAvatar();

  // Por defecto, que confirme cambios (1)
  if (chkModalConfirmarCambios) {
    chkModalConfirmarCambios.checked = true;
  }

  // Dejar todas las columnas de Schedule marcadas por defecto
  const checks = getScheduleColsCheckboxes();
  checks.forEach(chk => (chk.checked = true));
}

// =======================
//  ELIMINAR USUARIO
// =======================

async function eliminarUsuario(id) {
  if (!confirm("¿Eliminar este usuario?")) return;

  try {
    const response = await fetch("../../backend/api/usuario.php", {
      method: "DELETE",
      headers: { "Content-Type": "application/x-www-form-urlencoded" },
      body: `id=${encodeURIComponent(id)}`
    });

    const data = await response.json();

    if (!data.success) {
      throw new Error(data.error || "Error al eliminar usuario");
    }

    cargarUsuarios();
  } catch (err) {
    console.error("Error eliminando usuario:", err);
    alert("Error al eliminar usuario");
  }
}

// =======================
//  ACTUALIZAR ROL (no usado en tarjetas, pero disponible)
// =======================

async function actualizarRolUsuario(usuarioId, nuevoRol) {
  const usuario = datosOriginales.find(u => Number(u.id) === Number(usuarioId));
  if (!usuario) {
    alert("No se encontró el usuario en memoria.");
    return;
  }

  const payload = {
    id: usuario.id,
    correo: usuario.correo,
    username: usuario.username,
    rol: nuevoRol || usuario.rol,
    estado: usuario.estado,
    avatar_color: usuario.avatar_color || "#4f46e5",
    avatar_filename: usuario.avatar_filename || ""
  };

  try {
    const res = await fetch("../../backend/api/usuario.php", {
      method: "PUT",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(payload)
    });

    const data = await res.json();

    if (!data.success) {
      alert(data.error || "Error al actualizar el rol.");
      cargarUsuarios();
      return;
    }

    usuario.rol = nuevoRol || usuario.rol;
    aplicarFiltroYRender();

  } catch (err) {
    console.error("Error al actualizar rol:", err);
    alert("Error de comunicación al actualizar el rol.");
    cargarUsuarios();
  }
}

// =======================
//  TOGGLE ESTADO
// =======================

async function toggleEstadoUsuario(usuarioId) {
  const usuario = datosOriginales.find(u => Number(u.id) === Number(usuarioId));
  if (!usuario) {
    alert("No se encontró el usuario en memoria.");
    return;
  }

  const nuevoEstado = String(usuario.estado) === "1" ? 0 : 1;

  const payload = {
    id: usuario.id,
    correo: usuario.correo,
    username: usuario.username,
    rol: usuario.rol,
    estado: nuevoEstado,
    avatar_color: usuario.avatar_color || "#4f46e5",
    avatar_filename: usuario.avatar_filename || ""
  };

  try {
    const res = await fetch("../../backend/api/usuario.php", {
      method: "PUT",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(payload)
    });

    const data = await res.json();

    if (!data.success) {
      alert(data.error || "Error al actualizar estado.");
      cargarUsuarios();
      return;
    }

    usuario.estado = String(nuevoEstado);
    aplicarFiltroYRender();

  } catch (err) {
    console.error("Error al actualizar estado:", err);
    alert("Error de comunicación al actualizar el estado.");
    cargarUsuarios();
  }
}

// =======================
//  MODAL AVATAR USUARIO (desde catálogo)
// =======================

function actualizarPreviewAvatarUsuarioModal(usuario, filename, color) {
  const nombre   = usuario.nombre_razon_social || "";
  const username = usuario.username || "";
  const iniciales = getIniciales(nombre, username);
  const fondo = color || "#4f46e5";

  if (avatarUserPreviewWrapper) {
    avatarUserPreviewWrapper.style.background = fondo;
  }

  if (filename) {
    const url = AVATAR_BASE_URL + filename;
    if (avatarUserPreviewImg) {
      avatarUserPreviewImg.src = url;
      avatarUserPreviewImg.classList.remove("hidden");
    }
    if (avatarUserPreviewInitials) {
      avatarUserPreviewInitials.textContent = iniciales;
      avatarUserPreviewInitials.classList.add("hidden");
    }
  } else {
    if (avatarUserPreviewImg) {
      avatarUserPreviewImg.src = "";
      avatarUserPreviewImg.classList.add("hidden");
    }
    if (avatarUserPreviewInitials) {
      avatarUserPreviewInitials.textContent = iniciales;
      avatarUserPreviewInitials.classList.remove("hidden");
    }
  }
}

function abrirModalAvatarUsuario(usuarioId) {
  const usuario = datosOriginales.find(u => Number(u.id) === Number(usuarioId));
  if (!usuario) {
    alert("No se encontró el usuario en memoria.");
    return;
  }

  usuarioAvatarActual = usuario;
  avatarUserFile = null;

  if (avatarUsuarioNombre) {
    avatarUsuarioNombre.textContent =
      (usuario.nombre_razon_social || "") + " (" + (usuario.username || "") + ")";
  }

  const color = usuario.avatar_color || "#4f46e5";
  if (inputAvatarUsuarioColor) {
    inputAvatarUsuarioColor.value = color;
  }

  if (inputAvatarUsuarioFile) {
    inputAvatarUsuarioFile.value = "";
  }

  actualizarPreviewAvatarUsuarioModal(usuario, usuario.avatar_filename || "", color);

  if (modalAvatarUsuario) {
    modalAvatarUsuario.dataset.id = usuario.id;
    modalAvatarUsuario.classList.remove("hidden");
  }
}

function cerrarModalAvatarUsuario() {
  avatarUserFile = null;
  if (inputAvatarUsuarioFile) inputAvatarUsuarioFile.value = "";
  if (modalAvatarUsuario) modalAvatarUsuario.classList.add("hidden");
}

if (btnCerrarModalAvatarUsuario) {
  btnCerrarModalAvatarUsuario.addEventListener("click", cerrarModalAvatarUsuario);
}
if (btnCancelarModalAvatarUsuario) {
  btnCancelarModalAvatarUsuario.addEventListener("click", cerrarModalAvatarUsuario);
}

if (inputAvatarUsuarioColor) {
  inputAvatarUsuarioColor.addEventListener("input", () => {
    if (!usuarioAvatarActual) return;
    const nuevoColor = inputAvatarUsuarioColor.value || "#4f46e5";
    actualizarPreviewAvatarUsuarioModal(
      usuarioAvatarActual,
      usuarioAvatarActual.avatar_filename || "",
      nuevoColor
    );
  });
}

if (inputAvatarUsuarioFile) {
  inputAvatarUsuarioFile.addEventListener("change", (e) => {
    const file = e.target.files[0];
    if (!file) {
      avatarUserFile = null;
      if (usuarioAvatarActual) {
        actualizarPreviewAvatarUsuarioModal(
          usuarioAvatarActual,
          usuarioAvatarActual.avatar_filename || "",
          inputAvatarUsuarioColor.value || usuarioAvatarActual.avatar_color || "#4f46e5"
        );
      }
      return;
    }

    const validTypes = ["image/jpeg", "image/jpg", "image/png", "image/webp"];
    if (!validTypes.includes(file.type)) {
      alert("Formato de imagen no válido. Usa JPG, PNG o WebP.");
      inputAvatarUsuarioFile.value = "";
      avatarUserFile = null;
      return;
    }

    const maxSize = 2 * 1024 * 1024;
    if (file.size > maxSize) {
      alert("La imagen excede los 2 MB de tamaño máximo permitido.");
      inputAvatarUsuarioFile.value = "";
      avatarUserFile = null;
      return;
    }

    avatarUserFile = file;

    const reader = new FileReader();
    reader.onload = (ev) => {
      const color = inputAvatarUsuarioColor.value || "#4f46e5";
      if (avatarUserPreviewWrapper) {
        avatarUserPreviewWrapper.style.background = color;
      }
      if (avatarUserPreviewImg) {
        avatarUserPreviewImg.src = ev.target.result;
        avatarUserPreviewImg.classList.remove("hidden");
      }
      if (avatarUserPreviewInitials) {
        avatarUserPreviewInitials.classList.add("hidden");
      }
    };
    reader.readAsDataURL(file);
  });
}

if (btnGuardarModalAvatarUsuario) {
  btnGuardarModalAvatarUsuario.addEventListener("click", async () => {
    if (!usuarioAvatarActual) {
      alert("No hay usuario seleccionado.");
      return;
    }

    let avatarFilename = usuarioAvatarActual.avatar_filename || "";
    const nuevoColor = inputAvatarUsuarioColor
      ? (inputAvatarUsuarioColor.value || "#4f46e5")
      : (usuarioAvatarActual.avatar_color || "#4f46e5");

    if (avatarUserFile) {
      try {
        const formData = new FormData();
        formData.append("avatar", avatarUserFile);

        const respUpload = await fetch("../../backend/api/upload_avatar.php", {
          method: "POST",
          body: formData
        });

        const dataUpload = await respUpload.json();
        if (!dataUpload.success) {
          alert(dataUpload.error || "Error al subir la imagen de avatar.");
          return;
        }

        avatarFilename = dataUpload.filename || "";
      } catch (err) {
        console.error("Error subiendo avatar:", err);
        alert("Error de comunicación al subir la imagen de avatar.");
        return;
      }
    }

    const payload = {
      id: usuarioAvatarActual.id,
      correo: usuarioAvatarActual.correo,
      username: usuarioAvatarActual.username,
      rol: usuarioAvatarActual.rol,
      estado: usuarioAvatarActual.estado,
      avatar_color: nuevoColor,
      avatar_filename: avatarFilename,
      config_schedule_columnas: usuarioAvatarActual.config_schedule_columnas
        ? (typeof usuarioAvatarActual.config_schedule_columnas === "string"
          ? JSON.parse(usuarioAvatarActual.config_schedule_columnas)
          : usuarioAvatarActual.config_schedule_columnas)
        : null
      // confirmar_cambios_estado_realizado_por se conserva en el backend
    };

    try {
      const res = await fetch("../../backend/api/usuario.php", {
        method: "PUT",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(payload)
      });

      const data = await res.json();
      if (!data.success) {
        alert(data.error || "Error al actualizar avatar.");
        return;
      }

      usuarioAvatarActual.avatar_color = nuevoColor;
      usuarioAvatarActual.avatar_filename = avatarFilename;

      aplicarFiltroYRender();
      cerrarModalAvatarUsuario();
    } catch (err) {
      console.error("Error al actualizar avatar:", err);
      alert("Error de comunicación al actualizar avatar.");
    }
  });
}

// =======================
//  MODAL CONFIG COLUMNAS SCHEDULE (desde catálogo)
// =======================

function abrirModalConfigSchedule(usuarioId) {
  const usuario = datosOriginales.find(u => Number(u.id) === Number(usuarioId));
  if (!usuario) {
    alert("No se encontró el usuario en memoria.");
    return;
  }

  usuarioScheduleActual = usuario;

  if (scheduleConfigNombre) {
    scheduleConfigNombre.textContent =
      (usuario.nombre_razon_social || "") + " (" + (usuario.username || "") + ")";
  }

  let configCols = [];
  if (usuario.config_schedule_columnas) {
    try {
      if (typeof usuario.config_schedule_columnas === "string") {
        configCols = JSON.parse(usuario.config_schedule_columnas);
      } else if (Array.isArray(usuario.config_schedule_columnas)) {
        configCols = usuario.config_schedule_columnas;
      }
    } catch (e) {
      console.warn("No se pudo parsear config_schedule_columnas", e);
      configCols = [];
    }
  }

  marcarColumnasScheduleDesdeConfig(configCols);

  if (modalScheduleConfig) {
    modalScheduleConfig.dataset.id = usuario.id;
    modalScheduleConfig.classList.remove("hidden");
  }
}

function cerrarModalScheduleConfig() {
  if (modalScheduleConfig) {
    modalScheduleConfig.classList.add("hidden");
  }
}

if (btnCerrarModalScheduleConfig) {
  btnCerrarModalScheduleConfig.addEventListener("click", cerrarModalScheduleConfig);
}
if (btnCancelarModalScheduleConfig) {
  btnCancelarModalScheduleConfig.addEventListener("click", cerrarModalScheduleConfig);
}

if (btnGuardarModalScheduleConfig) {
  btnGuardarModalScheduleConfig.addEventListener("click", async () => {
    if (!usuarioScheduleActual) {
      alert("No hay usuario seleccionado.");
      return;
    }

    const columnasSeleccionadas = obtenerColumnasScheduleSeleccionadas();

    const payload = {
      id: usuarioScheduleActual.id,
      correo: usuarioScheduleActual.correo,
      username: usuarioScheduleActual.username,
      rol: usuarioScheduleActual.rol,
      estado: usuarioScheduleActual.estado,
      avatar_color: usuarioScheduleActual.avatar_color || "#4f46e5",
      avatar_filename: usuarioScheduleActual.avatar_filename || "",
      config_schedule_columnas: columnasSeleccionadas
      // confirmar_cambios_estado_realizado_por se mantiene en el backend
    };

    try {
      const res = await fetch("../../backend/api/usuario.php", {
        method: "PUT",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(payload)
      });

      const data = await res.json();
      if (!data.success) {
        alert(data.error || "Error al guardar configuración de columnas.");
        return;
      }

      usuarioScheduleActual.config_schedule_columnas = columnasSeleccionadas;
      cerrarModalScheduleConfig();
    } catch (err) {
      console.error("Error guardando config columnas:", err);
      alert("Error de comunicación al guardar configuración de columnas.");
    }
  });
}

// =======================
//  LOGOUT
// =======================

const btnLogout = document.getElementById("btnLogout");
if (btnLogout) {
  btnLogout.addEventListener("click", () => {
    localStorage.removeItem("token");
    document.cookie = "token=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
    location.href = "../";
  });
}

// =======================
//  TOGGLE SECCIONES MODAL USUARIO
// =======================

function toggleSeccionUsuario(tipo) {
  let cuerpo, icono, label;

  if (tipo === "acceso") {
    cuerpo = document.getElementById("cuerpoDatosAcceso");
    icono  = document.getElementById("iconAcceso");
    label  = document.getElementById("labelAcceso");
  } else if (tipo === "persona") {
    cuerpo = document.getElementById("cuerpoDatosPersona");
    icono  = document.getElementById("iconPersona");
    label  = document.getElementById("labelPersona");
  } else if (tipo === "perfil") {
    cuerpo = document.getElementById("cuerpoPerfilUsuario");
    icono  = document.getElementById("iconPerfil");
    label  = document.getElementById("labelPerfil");
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

function mostrarTodasLasSeccionesUsuario() {
  const secciones = [
    { cuerpoId: "cuerpoDatosAcceso",   iconId: "iconAcceso",   labelId: "labelAcceso" },
    { cuerpoId: "cuerpoDatosPersona",  iconId: "iconPersona",  labelId: "labelPersona" },
    { cuerpoId: "cuerpoPerfilUsuario", iconId: "iconPerfil",   labelId: "labelPerfil" },
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
