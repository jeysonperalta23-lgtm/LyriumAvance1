<!-- FOOTER -->
<footer class="bg-sky-500 text-white mt-12">
  <div class="max-w-7xl mx-auto px-4 py-10 grid grid-cols-1 md:grid-cols-4 lg:grid-cols-5 gap-8 text-base">

    <div class="space-y-4">
      <div class="flex items-center gap-2">
        <img src="img/logo_lyrium_blanco_01-scaled.webp?v=<?php echo time(); ?>" alt="Lyrium" class="h-10">
      </div>
      <p class="text-sm text-sky-100">Biomarketplace de productos y servicios especializados.</p>
      <!-- Redes Sociales Lyrium -->
      <div class="flex items-center gap-4 mt-2">

        <a href="https://www.instagram.com/lyrium_biomarketplace/" target="_blank" class="social-icon-btn instagram"
          title="Síguenos en Instagram">
          <i class="ph-instagram-logo"></i>
        </a>


        <a href="https://facebook.com/lyriumperu" target="_blank" class="social-icon-btn facebook"
          title="Síguenos en Facebook">
          <i class="ph-facebook-logo"></i>
        </a>

        <!-- Ejemplo de Redirección TikTok -->
        <a href="https://tiktok.com/" target="_blank" class="social-icon-btn tiktok" title="Síguenos en TikTok">
          <i class="ph-tiktok-logo"></i>
        </a>

        <!-- Ejemplo de Redirección WhatsApp: Usamos wa.me para abrir el chat directamente -->
        <a href="https://wa.me/51937093420" target="_blank" class="social-icon-btn whatsapp"
          title="Escríbenos por WhatsApp">
          <i class="ph-whatsapp-logo"></i>
        </a>
      </div>
    </div>

    <div>
      <h3 class="font-semibold mb-3 text-base tracking-wide">CONTÁCTANOS</h3>
      <p class="flex items-center gap-2 text-sm mb-2"><i class="ph-phone-call text-lg"></i> +51 937 093 420</p>
      <p class="flex items-center gap-2 text-sm mb-2"><i class="ph-envelope-simple text-lg"></i>
        ventas@lyriumbiomarketplace.com</p>
    </div>

    <div>
      <h3 class="font-semibold mb-3 text-base tracking-wide">¿TE AYUDAMOS?</h3>
      <ul class="space-y-2 text-sm">
        <li><a href="preguntasfrecuentes.php" class="hover:underline">Preguntas frecuentes</a></li>
        <li><a href="politicasdeprivacidad.php" class="hover:underline">Políticas de privacidad</a></li>
        <li><a href="terminoscondiciones.php" class="hover:underline">Términos y condiciones</a></li>
        <li><a href="libroreclamaciones.php" class="hover:underline">Libro de reclamaciones</a></li>
      </ul>
    </div>

    <div>
      <h3 class="font-semibold mb-3 text-base tracking-wide">INFORMACIÓN</h3>
      <ul class="space-y-2 text-sm">
        <li><a href="nosotros.php" class="hover:underline">Nosotros</a></li>
        <li><a href="#" class="hover:underline">Tiendas registradas</a></li>
        <li><a href="#" class="hover:underline">Contáctanos</a></li>
      </ul>
    </div>

    <div>
      <h3 class="font-semibold mb-3 text-base tracking-wide">MÉTODOS DE PAGO</h3>
      <p class="text-sm mb-2">Aceptamos tarjetas:</p>
      <div class="flex flex-wrap gap-2 text-sm">
        <span class="px-2 py-1 rounded-full bg-sky-400/60">VISA</span>
        <span class="px-2 py-1 rounded-full bg-sky-400/60">MasterCard</span>
        <span class="px-2 py-1 rounded-full bg-sky-400/60">AmEx</span>
      </div>
      <p class="flex items-center gap-2 text-sm mt-3"><i class="ph-lock-key text-lg"></i> Tienda 100% segura</p>
    </div>
  </div>

  <div class="border-t border-white/30">
    <div class="max-w-7xl mx-auto px-4 py-4 text-center text-sm tracking-wide">
      © 2025 LYRIUM BIOMARKETPLACE y sus afiliados. Todos los derechos reservados.
    </div>
  </div>
</footer>

<!-- Botón WhatsApp flotante -->
<a href="#"
  class="fixed bottom-5 right-5 bg-green-500 hover:bg-green-600 text-white px-5 py-3 rounded-full shadow-lg flex items-center gap-2 text-base z-[100]">
  <i class="ph-whatsapp-logo text-2xl"></i> ¿Cómo puedo ayudarte?
</a>

<!-- MODAL PRODUCTO -->
<div id="modalProducto" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden items-center justify-center z-[999]">
  <div class="bg-white rounded-3xl w-[95%] max-w-3xl shadow-xl p-6 relative">
    <button type="button" onclick="cerrarModal()"
      class="absolute top-4 right-4 text-gray-500 hover:text-black text-3xl">
      <i class="ph-x"></i>
    </button>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <div class="relative">
        <img id="modalImg" src="" class="w-full rounded-xl shadow-md object-cover" alt="Elemento seleccionado">
      </div>

      <div class="space-y-3 text-base">
        <h2 id="modalTitulo" class="text-2xl font-semibold text-gray-800"></h2>
        <p id="modalPrecio" class="text-sky-600 font-bold text-xl"></p>
        <p id="modalDescripcion" class="text-gray-600 leading-relaxed"></p>

        <div class="mt-3">
          <label class="block text-sm text-gray-500 mb-1">Detalle:</label>
          <p class="text-sm text-gray-700">
            Información referencial del servicio, producto o campaña promocional seleccionada.
          </p>
        </div>

        <button type="button"
          class="mt-3 w-full bg-sky-500 hover:bg-sky-600 text-white rounded-xl py-3 font-semibold text-base shadow-md flex items-center justify-center gap-2">
          <i class="ph-shopping-cart-simple text-xl"></i>
          Más información
        </button>
      </div>
    </div>
  </div>
</div>

<!-- JS -->
<script src="js/buscar.js?v=<?php echo time(); ?>"></script>
<script src="js/index.js?v=<?php echo time(); ?>"></script>
</body>

</html>