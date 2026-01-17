<?php
// C:\xampp\htdocs\lyrium\frontend\checkout.php
session_start();

if (!isset($_SESSION['cliente_id'])) {
  $_SESSION['cliente_id'] = 1; // DEMO
}
$clienteId = (int)$_SESSION['cliente_id'];
$method = isset($_GET["method"]) ? $_GET["method"] : "card";
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Checkout | Lyrium</title>

  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://unpkg.com/phosphor-icons"></script>

  <link href="utils/css/index.css?v=<?= time() ?>" rel="stylesheet">
  <link rel="icon" type="image/png" href="img/logo.png?v=<?= time() ?>" />

  <style>
    :root{
      --brand: 35,180,254;
      --lime: 132,204,22;
      --ink: 15,23,42;
      --muted: 100,116,139;
    }
    .text-ink{ color: rgba(var(--ink), .92); }
    .text-muted{ color: rgba(var(--muted), .95); }
    .icon-brand{ color: rgba(var(--brand), 1); }

    .modal-backdrop{
      background: rgba(15,23,42,.58);
      backdrop-filter: blur(2px);
    }
    .btn-brand{ background: rgba(var(--brand), 1); color:#fff; }
    .btn-brand:hover{ filter: brightness(.96); }
    .btn-outline{
      border: 1px solid rgba(var(--brand), .26);
      background: rgba(255,255,255,.92);
      color: rgba(var(--ink), .92);
    }
    .btn-outline:hover{ background: rgba(var(--brand), .06); }

    .checkout-shell{
      border: 1px solid rgba(var(--brand), .16);
      background:
        radial-gradient(900px 220px at 40% -10%, rgba(var(--brand),.08), transparent 60%),
        radial-gradient(900px 220px at 90% 0%, rgba(var(--lime),.07), transparent 55%),
        #fff;
      border-radius: 24px;
      box-shadow: 0 18px 52px rgba(2,132,199,.10);
    }
  </style>
</head>

<body class="bg-gray-50 min-h-screen flex flex-col">
<?php include 'header.php'; ?>

<main class="max-w-6xl mx-auto px-4 py-8 flex-1">
  <div class="flex items-center justify-between gap-3 mb-5">
    <div class="flex items-center gap-2">
      <span class="w-10 h-10 rounded-2xl bg-white border border-sky-100 grid place-items-center">
        <i class="ph-credit-card icon-brand text-xl"></i>
      </span>
      <div>
        <h1 class="text-lg md:text-2xl text-ink font-semibold leading-tight">Detalle del pago</h1>
        <p class="text-sm text-muted leading-tight">Revisa tu compra antes de confirmar</p>
      </div>
    </div>

    <a href="carrito.php" class="px-4 py-3 rounded-2xl btn-outline inline-flex items-center gap-2">
      <i class="ph-arrow-left icon-brand"></i> Volver al carrito
    </a>
  </div>

  <div class="checkout-shell p-4 md:p-6">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

      <!-- LEFT: Items -->
      <section>
        <div class="flex items-center justify-between">
          <p class="text-sm text-ink inline-flex items-center gap-2">
            <span class="w-8 h-8 rounded-xl bg-sky-50 grid place-items-center">
              <i class="ph-bag icon-brand"></i>
            </span>
            <span id="chkItemsLabel">0 items</span>
          </p>

          <a href="carrito.php" class="text-xs px-3 py-2 rounded-xl btn-outline inline-flex items-center gap-2">
            <i class="ph-pencil-simple icon-brand"></i> Editar carrito
          </a>
        </div>

        <div id="chkItems" class="mt-3 space-y-2"></div>
      </section>

      <!-- RIGHT: Totals + Form -->
      <section>
        <div class="rounded-2xl border border-sky-100 bg-white/90 p-4">
          <div class="flex items-center justify-between text-sm text-muted">
            <span class="inline-flex items-center gap-2">
              <i class="ph-calculator icon-brand"></i> Subtotal
            </span>
            <span id="chkSubtotal">S/ 0.00</span>
          </div>

          <div class="mt-2 flex items-center justify-between text-sm text-muted">
            <span class="inline-flex items-center gap-2">
              <i class="ph-tag icon-brand"></i> Descuentos
            </span>
            <span class="text-emerald-700" id="chkDiscount">- S/ 0.00</span>
          </div>

          <div class="mt-3 h-px bg-sky-50"></div>

          <div class="mt-3 flex items-center justify-between text-[15px] text-ink">
            <span class="inline-flex items-center gap-2">
              <i class="ph-receipt icon-brand"></i> Total a pagar
            </span>
            <span id="chkTotal" class="text-ink">S/ 0.00</span>
          </div>

          <p class="mt-2 text-xs text-muted inline-flex items-center gap-2">
            <i class="ph-info icon-brand"></i>
            El IGV/envío se confirmará si aplica.
          </p>
        </div>

        <div class="mt-4">
          <p class="text-sm text-ink inline-flex items-center gap-2">
            <i class="ph-wallet icon-brand"></i> Método de pago
          </p>

          <div class="mt-2 grid grid-cols-1 sm:grid-cols-3 gap-2">
            <button class="payMethod rounded-2xl border border-sky-100 bg-white hover:bg-sky-50 p-3 text-left" data-method="card">
              <p class="text-sm text-ink inline-flex items-center gap-2">
                <i class="ph-credit-card icon-brand"></i> Tarjeta
              </p>
              <p class="text-xs text-muted mt-1">Visa / MasterCard</p>
            </button>

            <button class="payMethod rounded-2xl border border-sky-100 bg-white hover:bg-sky-50 p-3 text-left" data-method="yape">
              <p class="text-sm text-ink inline-flex items-center gap-2">
                <i class="ph-device-mobile-camera icon-brand"></i> Yape/Plin
              </p>
              <p class="text-xs text-muted mt-1">Pago rápido</p>
            </button>

            <button class="payMethod rounded-2xl border border-sky-100 bg-white hover:bg-sky-50 p-3 text-left" data-method="transfer">
              <p class="text-sm text-ink inline-flex items-center gap-2">
                <i class="ph-bank icon-brand"></i> Transferencia
              </p>
              <p class="text-xs text-muted mt-1">Banca por internet</p>
            </button>
          </div>

          <input type="hidden" id="chkMethod" value="<?= htmlspecialchars($method) ?>">
        </div>

        <!-- Form web (puedes conectarlo a tu backend) -->
        <form id="chkForm" class="mt-4 grid grid-cols-1 gap-3">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
            <input required name="nombres" placeholder="Nombres"
                   class="w-full px-4 py-3 rounded-2xl border border-sky-100 bg-white outline-none">
            <input required name="apellidos" placeholder="Apellidos"
                   class="w-full px-4 py-3 rounded-2xl border border-sky-100 bg-white outline-none">
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
            <input required type="email" name="email" placeholder="Email"
                   class="w-full px-4 py-3 rounded-2xl border border-sky-100 bg-white outline-none">
            <input required name="telefono" placeholder="Teléfono"
                   class="w-full px-4 py-3 rounded-2xl border border-sky-100 bg-white outline-none">
          </div>

          <input required name="direccion" placeholder="Dirección de entrega"
                 class="w-full px-4 py-3 rounded-2xl border border-sky-100 bg-white outline-none">

          <div class="flex flex-col sm:flex-row gap-2 sm:items-center sm:justify-between mt-2">
            <a href="carrito.php" class="w-full sm:w-auto px-4 py-3 rounded-2xl btn-outline inline-flex items-center justify-center gap-2">
              <i class="ph-arrow-left icon-brand"></i> Seguir comprando
            </a>

            <button type="submit"
                    class="w-full sm:w-auto px-4 py-3 rounded-2xl btn-brand inline-flex items-center justify-center gap-2">
              <i class="ph-check-circle"></i> Confirmar pago
            </button>
          </div>

          <p class="text-xs text-muted mt-2">
            Al confirmar, se generará el pedido y se procesará el método seleccionado.
          </p>
        </form>

      </section>
    </div>
  </div>
</main>

<?php include 'footer.php'; ?>

<script>
function money(n){ return "S/ " + Number(n || 0).toFixed(2); }

function resolveImg(url){
  const ORIGIN = window.location.origin;
  let BASE_APP  = `${ORIGIN}/LYRIUM`;
  let FRONT_IMG = `${BASE_APP}/frontend/img`;
  const fallback = `${FRONT_IMG}/no-image.png`;
  if (!url) return fallback;
  if (/^https?:\/\//i.test(url)) return url;
  if (String(url).startsWith("/")) return ORIGIN + url;
  if (String(url).includes("LYRIUM") || String(url).includes("lyrium")) return url;
  return `${BASE_APP}/${String(url).replace(/^\.?\//, "")}`;
}

function render(){
  const raw = sessionStorage.getItem("ly_checkout_payload");
  if (!raw){
    document.getElementById("chkItems").innerHTML = `
      <div class="text-center py-10 text-gray-500">
        <p class="text-ink">No hay información de checkout.</p>
        <p class="text-sm">Vuelve al carrito y haz clic en “Confirmar pago”.</p>
      </div>
    `;
    return;
  }

  const payload = JSON.parse(raw);
  const items = payload.items || [];

  document.getElementById("chkItemsLabel").textContent =
    (items.reduce((a,i)=>a+Number(i.cantidad||0),0)) + " items";

  document.getElementById("chkSubtotal").textContent = payload.totals?.subtotal || "S/ 0.00";
  document.getElementById("chkDiscount").textContent = payload.totals?.descuento || "- S/ 0.00";
  document.getElementById("chkTotal").textContent = payload.totals?.total || "S/ 0.00";

  document.getElementById("chkItems").innerHTML = items.map(it=>{
    const cant = Number(it.cantidad||0);
    const pu = Number(it.precio_unitario||0);
    const lt = cant * pu;
    return `
      <div class="rounded-2xl border border-sky-50 bg-white p-3 flex gap-3">
        <div class="w-12 h-12 rounded-2xl overflow-hidden bg-gray-100 border border-sky-100 shrink-0">
          <img src="${resolveImg(it.imagen_url)}" class="w-full h-full object-cover" />
        </div>
        <div class="flex-1 min-w-0">
          <p class="text-sm text-ink truncate">${it.nombre || "Producto"}</p>
          <p class="text-xs text-gray-500 mt-1 inline-flex items-center gap-2">
            <span class="inline-flex items-center gap-1"><i class="ph-x"></i> ${cant}</span>
            <span class="inline-flex items-center gap-1"><i class="ph-tag"></i> ${money(pu)}</span>
          </p>
        </div>
        <div class="text-right">
          <p class="text-sm text-ink inline-flex items-center gap-1">
            <i class="ph-receipt"></i> ${money(lt)}
          </p>
        </div>
      </div>
    `;
  }).join("");

  // Método seleccionado desde carrito
  const m = payload.method || "card";
  document.getElementById("chkMethod").value = m;

  document.querySelectorAll(".payMethod").forEach(x=>x.classList.remove("ring-2","ring-sky-200"));
  const btn = document.querySelector(`.payMethod[data-method="${m}"]`);
  btn?.classList.add("ring-2","ring-sky-200");
}

document.querySelectorAll(".payMethod").forEach(btn=>{
  btn.addEventListener("click", ()=>{
    document.querySelectorAll(".payMethod").forEach(x=>x.classList.remove("ring-2","ring-sky-200"));
    btn.classList.add("ring-2","ring-sky-200");
    document.getElementById("chkMethod").value = btn.dataset.method || "card";
  });
});

document.getElementById("chkForm").addEventListener("submit", async (e)=>{
  e.preventDefault();

  // Aquí conectas tu backend real:
  // - crear pedido
  // - registrar pago según método
  // - redirigir a "gracias.php" o "pedido.php?id=..."
  alert("✅ Pedido confirmado (demo). Aquí conectas tu backend.");
});

render();
</script>

</body>
</html>
