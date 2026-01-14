// C:\xampp\htdocs\schedule\frontend\js\login.js

// Limpiar cookies
document.cookie.split(";").forEach(function (cookie) {
  document.cookie = cookie
    .replace(/^ +/, "")
    .replace(/=.*/, "=;expires=" + new Date().toUTCString() + ";path=/");
});

// Al cargar DOM en el login
window.addEventListener("DOMContentLoaded", () => {
  const token = localStorage.getItem("token");

  // Si existe token y estamos en index.php, limpiarlo y recargar
  if (token && window.location.pathname.includes("index")) {
    localStorage.removeItem("token");
    sessionStorage.removeItem("token");
    localStorage.removeItem("usuario");

    const form = document.getElementById("loginForm");
    if (form) {
      form.reset();

      const formElements = form.querySelectorAll("input");
      for (let element of formElements) {
        element.disabled = false;
      }
    }

    // Evitar que se mantengan datos en caché
    window.history.replaceState(null, "", window.location.href);

    // Recargar la página solo una vez
    if (!sessionStorage.getItem("reloadDone")) {
      sessionStorage.setItem("reloadDone", "true");
      window.location.reload();
    }
  }
});

// Manejar retroceso en el navegador
window.addEventListener("popstate", () => {
  localStorage.removeItem("token");
  sessionStorage.removeItem("token");
  localStorage.removeItem("usuario");

  const form = document.getElementById("loginForm");
  if (form) {
    form.reset();
  }

  window.location.href = "index";
});

// Manejo del formulario de login
document.getElementById("loginForm").addEventListener("submit", async function (e) {
  e.preventDefault();
  const form = e.target;

  const data = {
    username: form.username.value,
    password: form.password.value,
  };

  if (!data.username || !data.password) {
    const errorEl = document.getElementById("error");
    if (errorEl) {
      errorEl.classList.remove("hidden");
      errorEl.innerText = "Por favor, completa todos los campos.";
    }
    return;
  }

  try {
    const response = await fetch("../backend/api/login.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(data),
    });

    const result = await response.json();
    console.log("Respuesta login.php:", result);

    if (result.success) {
      // Guardar token
      localStorage.setItem("token", result.token);
      sessionStorage.setItem("token", result.token);

      // Guardar info de usuario (incluye avatar_filename)
      if (result.usuario) {
        console.log("Guardando usuario en localStorage:", result.usuario);
        localStorage.setItem("usuario", JSON.stringify(result.usuario));
      } else {
        localStorage.removeItem("usuario");
      }

      form.reset();
      // Redirigir al panel principal
      location.href = "view/home";
    } else {
      // Limpiar cualquier token previo en caso de intento fallido
      localStorage.removeItem("token");
      sessionStorage.removeItem("token");
      localStorage.removeItem("usuario");

      const errorEl = document.getElementById("error");
      if (errorEl) {
        errorEl.classList.remove("hidden");
        errorEl.innerText = result.message || "Credenciales inválidas";
      }
    }
  } catch (err) {
    console.error("Error en login:", err);
    const errorEl = document.getElementById("error");
    if (errorEl) {
      errorEl.classList.remove("hidden");
      errorEl.innerText = "Error de comunicación con el servidor.";
    }
  }
});

// Verificar si el usuario ya está logueado y redirigir
window.addEventListener("load", () => {
  const token = localStorage.getItem("token");
  if (token && window.location.pathname.includes("index.php")) {
    location.href = "view/home";
  }
});

function limpiarYRecargarUnaVez() {
  if (window.location.pathname.includes("index.php")) {
    const yaRecargado = sessionStorage.getItem("paginaRecargada");

    if (!yaRecargado) {
      // Limpiar token
      localStorage.removeItem("token");
      sessionStorage.removeItem("token");
      localStorage.removeItem("usuario");

      // Limpiar formulario si existe
      const form = document.getElementById("loginForm");
      if (form) form.reset();

      // Marcar como recargado
      sessionStorage.setItem("paginaRecargada", "true");
    }
  }
}

// Ejecutar en carga inicial y cuando se vuelve desde el historial
window.addEventListener("DOMContentLoaded", limpiarYRecargarUnaVez);
window.addEventListener("pageshow", limpiarYRecargarUnaVez);
