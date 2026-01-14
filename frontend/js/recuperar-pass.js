// js/recuperar-pass.js
document.addEventListener("DOMContentLoaded", () => {
  const form = document.getElementById("resetEmailForm");
  const errorMessage = document.getElementById("error");
  const submitButton = form.querySelector("button[type='submit']");

  form.addEventListener("submit", async (event) => {
    event.preventDefault();

    const email = form.email.value.trim();

    errorMessage.classList.add("hidden");
    errorMessage.textContent = "";

    if (!email) {
      errorMessage.textContent = "Por favor, ingresa un correo electrónico válido.";
      errorMessage.classList.remove("hidden");
      return;
    }

    submitButton.disabled = true;
    const originalText = submitButton.textContent;
    submitButton.textContent = "Enviando...";

    try {
      const response = await fetch("../backend/api/resetear-pass.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ email }),
      });

      const result = await response.json();

      if (result.success) {
        errorMessage.textContent = "Te hemos enviado un correo con instrucciones para restablecer tu contraseña.";
        errorMessage.className = "text-green-600 font-semibold mt-2 text-center text-sm bg-green-100 rounded-lg p-2 shadow-sm";
      } else {
        errorMessage.textContent = result.message || "Ocurrió un error. Intenta de nuevo.";
        errorMessage.className = "text-red-600 font-semibold mt-2 text-center text-sm bg-red-100 rounded-lg p-2 shadow-sm";
      }
      errorMessage.classList.remove("hidden");
    } catch (error) {
      console.error(error);
      errorMessage.textContent = "Error al intentar enviar la solicitud. Intenta de nuevo.";
      errorMessage.className = "text-red-600 font-semibold mt-2 text-center text-sm bg-red-100 rounded-lg p-2 shadow-sm";
      errorMessage.classList.remove("hidden");
    } finally {
      submitButton.disabled = false;
      submitButton.textContent = originalText;
    }
  });
});
