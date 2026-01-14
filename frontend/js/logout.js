document.addEventListener("DOMContentLoaded", function () {
  const token = localStorage.getItem("token");

  if (!token) {
    location.href = "../";
    return;
  }

  try {
    const payload = JSON.parse(atob(token.split('.')[1]));
    const infoDiv = document.getElementById("info");

    if (infoDiv && payload.data) {
      const username = payload.data.username || "desconocido";
      const rol      = payload.data.rol || "sin rol";
      infoDiv.innerText = `Usuario: ${username} | Rol: ${rol}`;
    } else {
      infoDiv.innerText = "Usuario no disponible";
    }
  } catch (err) {
    console.error("Token inválido", err);
    location.href = "../";
  }
});
