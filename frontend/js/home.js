//frontend\js\home.js

document.getElementById("btnLogout").addEventListener("click", () => {
  localStorage.removeItem("token");
  document.cookie = "token=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
  location.href = "../";
});