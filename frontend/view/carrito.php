<!-- C:\xampp\htdocs\schedule\frontend\view\carrito.php -->
<!DOCTYPE html>
<html lang="es">
  <?php include '../headlogin.php'; ?>
  <body class="bg-[#03A9F4] text-gray-800">
    <div class="flex flex-col md:flex-row min-h-screen">
      <?php include '../asider.php'; ?>

      <div id="main-content" class="flex flex-col flex-1 min-h-screen transition-all duration-300 ease-in-out">

        <style>
          :root {
            --dominant-blue: #03A9F4;
            --dominant-blue-light: #4FC3F7;
            --dominant-blue-dark: #0288D1;
          }
          .carrito-card-hover {
            transition: transform 0.18s ease, box-shadow 0.18s ease;
          }
          .carrito-card-hover:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 22px rgba(15, 118, 178, 0.18);
          }
        </style>

        <main class="flex-grow p-3 sm:p-4 lg:p-6 bg-gray-50 overflow-hidden">
          <div class="bg-white shadow-lg rounded-3xl p-3 sm:p-4 lg:p-6 border border-sky-200 w-full">
            <div class="space-y-6">

              <!-- HEADER CARRITO -->
              <section class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div class="flex items-start gap-3">
                  <div class="w-10 h-10 rounded-full bg-gradient-to-br from-sky-500 to-sky-700 flex items-center justify-center text-white shadow-md">
                    <i class="fas fa-shopping-cart text-sm"></i>
                  </div>
                  <div>
                    <h1 class="text-lg md:text-xl font-semibold text-sky-800 flex items-center gap-2">
                      Carrito de compras
                      <span class="text-[11px] px-2 py-0.5 rounded-full bg-sky-50 text-sky-700 border border-sky-200">
                        Resumen de tus productos seleccionados
                      </span>
                    </h1>
                    <p class="text-xs text-gray-500 mt-1">
                      Revisa cantidades, elimina productos y continúa al pago cuando estés listo.
                    </p>
                  </div>
                </div>

                <div class="flex flex-wrap items-center gap-2">
                  <button id="btnVaciarCarrito"
                          type="button"
                          class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full border border-rose-200 bg-rose-50 text-rose-700 text-xs sm:text-sm hover:bg-rose-100 hover:border-rose-300 transition shadow-sm">
                    <i class="fas fa-trash-alt text-sm"></i>
                    Vaciar carrito
                  </button>
                </div>
              </section>

              <!-- CONTENIDO PRINCIPAL: ITEMS + RESUMEN -->
              <section class="grid grid-cols-1 lg:grid-cols-3 gap-5">

                <!-- LISTA DE ITEMS -->
                <div class="lg:col-span-2 space-y-3">
                  <!-- Estado vacío -->
                  <div id="carritoVacio" class="border border-dashed border-sky-200 rounded-2xl bg-sky-50/60 p-4 flex items-center justify-center">
                    <div class="flex flex-col items-center gap-2 text-sky-700 text-sm text-center">
                      <i class="fas fa-shopping-basket text-lg"></i>
                      <p class="max-w-xs">
                        Tu carrito está vacío por ahora. Agrega productos desde el catálogo para empezar.
                      </p>
                    </div>
                  </div>

                  <!-- Aquí pinta el JS -->
                  <div id="carritoItemsContainer" class="space-y-3"></div>
                </div>

                <!-- RESUMEN DEL CARRITO -->
                <aside class="carrito-card-hover border border-sky-200 bg-sky-50/70 rounded-2xl p-4 flex flex-col gap-4">
                  <div class="flex items-center justify-between gap-2">
                    <h2 class="text-sm font-semibold text-sky-800 flex items-center gap-2">
                      <i class="fas fa-receipt text-sky-500 text-sm"></i>
                      Resumen del pedido
                    </h2>
                  </div>

                  <div class="space-y-2 text-[13px] text-slate-700">
                    <div class="flex items-center justify-between">
                      <span>Subtotal</span>
                      <span id="lblCarritoSubtotal" class="font-semibold">S/ 0.00</span>
                    </div>

                    <div class="flex items-center justify-between text-xs text-slate-500">
                      <span>Envío estimado</span>
                      <span id="lblCarritoEnvio" class="font-medium text-slate-600">Por calcular</span>
                    </div>

                    <div class="border-t border-sky-100 my-2"></div>

                    <div class="flex items-center justify-between text-sm font-semibold text-slate-800">
                      <span>Total a pagar</span>
                      <span id="lblCarritoTotal" class="text-sky-700 text-base">S/ 0.00</span>
                    </div>
                  </div>

                  <div class="mt-2 space-y-2 text-[11px] text-slate-500">
                    <p class="flex items-start gap-2">
                      <i class="fas fa-info-circle mt-0.5"></i>
                      <span>
                        El costo de envío y los impuestos finales pueden variar según tu dirección de entrega y el método de pago.
                      </span>
                    </p>
                  </div>

                  <div class="mt-3 flex flex-col gap-2">
                    <button id="btnIrAPagar"
                            type="button"
                            class="w-full inline-flex items-center justify-center gap-2 px-4 py-2 rounded-full bg-gradient-to-r from-sky-600 to-sky-700 text-white text-sm font-medium shadow-md hover:from-sky-700 hover:to-sky-800 transition">
                      <i class="fas fa-lock text-sm"></i>
                      Ir a pagar
                    </button>

                    <a href="./producto.php"
                       class="w-full inline-flex items-center justify-center gap-2 px-4 py-2 rounded-full border border-sky-200 bg-white text-[12px] text-sky-700 hover:bg-sky-50 transition">
                      <i class="fas fa-arrow-left text-xs"></i>
                      Seguir comprando
                    </a>
                  </div>
                </aside>

              </section>

            </div>
          </div>
        </main>
         <?php include '../footerlogin.php'; ?>
      </div>
    </div>

    <script src="../js/logout.js?v=<?php echo time(); ?>"></script>
    <script src="../js/carrito.js?v=<?php echo time(); ?>"></script>
    <script src="../js/config.js?v=<?php echo time(); ?>"></script>
  </body>
</html>
