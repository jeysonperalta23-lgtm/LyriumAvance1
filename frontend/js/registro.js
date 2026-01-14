document.getElementById("registroForm").addEventListener("submit", async function(e) {
  e.preventDefault();

  const form = new FormData(this);
  const data = Object.fromEntries(form.entries());

  const mensaje = document.getElementById("error");
  mensaje.classList.add("hidden");
  mensaje.textContent = "";

  // Validación de campos obligatorios
  if (!data.nombre ||
      !data.documento_identidad ||
      !data.username ||
      !data.password ||
      !data.sexo ||
      !data.rol) {
    mensaje.textContent = "Por favor, complete todos los campos obligatorios.";
    mensaje.className = "text-red-600 font-semibold mt-2 text-center text-sm bg-red-100 rounded-lg p-2 shadow-sm";
    mensaje.classList.remove("hidden");
    return;
  }

  try {
    const res = await fetch("../backend/api/registro.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/json"
      },
      body: JSON.stringify(data)
    });

    const json = await res.json();

    mensaje.textContent = json.message || "Ocurrió un error inesperado.";
    mensaje.classList.remove("hidden");

    if (json.success) {
      mensaje.className = "text-green-600 font-semibold mt-2 text-center text-sm bg-green-100 rounded-lg p-2 shadow-sm";
      // Opcional: limpiar el formulario
      this.reset();
    } else {
      mensaje.className = "text-red-600 font-semibold mt-2 text-center text-sm bg-red-100 rounded-lg p-2 shadow-sm";
    }
  } catch (err) {
    console.error(err);
    mensaje.textContent = "Error de conexión con el servidor.";
    mensaje.className = "text-red-600 font-semibold mt-2 text-center text-sm bg-red-100 rounded-lg p-2 shadow-sm";
    mensaje.classList.remove("hidden");
  }
});
