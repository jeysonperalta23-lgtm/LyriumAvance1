// js/actualizar-pass.js
document.addEventListener("DOMContentLoaded", () => {
  const urlParams = new URLSearchParams(window.location.search);
  const token = urlParams.get("token");

  // Si de verdad no hay token en la URL, manda al login
  if (!token) {
    alert("Token no válido o faltante.");
    window.location.href = "index.php";
    return;
  }

  const form          = document.getElementById("resetPasswordForm");
  const mensajeDiv    = document.getElementById("mensaje");
  const passwordField = document.getElementById("passwordField");
  const submitButton  = form.querySelector('button[type="submit"]');
  const volverInicio  = document.getElementById("volverInicio");

  form.addEventListener("submit", (event) => {
    event.preventDefault();

    const newPassword = document.getElementById("new_password").value.trim();

    if (!newPassword) {
      mensajeDiv.textContent = "La nueva contraseña no puede estar vacía.";
      mensajeDiv.className = "p-2 mb-3 rounded text-center text-sm bg-yellow-100 text-yellow-800";
      mensajeDiv.classList.remove("hidden");
      return;
    }

    const data = { token, new_password: newPassword };

    fetch("../backend/api/resetear-pass.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(data),
    })
      .then((response) => response.json())
      .then((data) => {
        mensajeDiv.classList.remove("hidden");
        mensajeDiv.textContent = data.message || "Respuesta del servidor.";

        if (data.success) {
          mensajeDiv.className =
            "p-2 mb-3 rounded text-center text-sm bg-green-100 text-green-800";
          passwordField.style.display = "none";
          submitButton.style.display = "none";
          volverInicio.classList.remove("hidden");
        } else {
          mensajeDiv.className =
            "p-2 mb-3 rounded text-center text-sm bg-yellow-100 text-yellow-800";
        }
      })
      .catch((error) => {
        console.error("Error:", error);
        mensajeDiv.classList.remove("hidden");
        mensajeDiv.textContent = "Error al procesar la solicitud.";
        mensajeDiv.className =
          "p-2 mb-3 rounded text-center text-sm bg-red-100 text-red-800";
      });
  });
});
