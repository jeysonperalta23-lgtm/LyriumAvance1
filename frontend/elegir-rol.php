<?php
session_start();
require_once __DIR__ . '/../backend/config/Conexion.php';

$uid = $_SESSION["usuario_pre_id"] ?? null;
if (!$uid) {
  header("Location: login.php");
  exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $rol = $_POST["rol"] ?? "";
  if (!in_array($rol, ["Cliente","Vendedor"], true)) {
    $err = "Rol inválido";
  } else {
    $stmt = $conn->prepare("UPDATE usuarios SET rol = ? WHERE id = ?");
    $stmt->execute([$rol, (int)$uid]);

    // redirecciones a completar perfil
    if ($rol === "Cliente") header("Location: completar-cliente.php");
    else header("Location: completar-vendedor.php");
    exit;
  }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Lyrium | Elegir tipo de cuenta</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50 min-h-screen flex items-center justify-center p-4">
  <div class="bg-white rounded-2xl shadow-xl border border-slate-200 w-full max-w-lg p-6">
    <h1 class="text-xl font-semibold text-slate-800">¿Cómo deseas registrarte?</h1>
    <p class="text-slate-500 text-sm mt-1">Elige una opción para completar tu perfil.</p>

    <?php if (!empty($err)): ?>
      <p class="mt-4 text-red-600 font-semibold"><?= htmlspecialchars($err) ?></p>
    <?php endif; ?>

    <form method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-3 mt-6">
      <button name="rol" value="Cliente" class="rounded-full py-3 px-4 bg-sky-500 text-white font-semibold hover:opacity-90">
        Soy Cliente
      </button>
      <button name="rol" value="Vendedor" class="rounded-full py-3 px-4 bg-emerald-600 text-white font-semibold hover:opacity-90">
        Soy Vendedor
      </button>
    </form>

    <p class="text-xs text-slate-500 mt-4 text-center">Después podrás completar tu perfil.</p>
  </div>
</body>
</html>
