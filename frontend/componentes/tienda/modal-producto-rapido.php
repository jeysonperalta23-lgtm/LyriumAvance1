<?php
/**
 * COMPONENTE: Modal Vista Rápida de Producto
 * Estilo compacto similar a marketplaces
 * Usando TailwindCSS
 */
?>

<!-- MODAL: VISTA RÁPIDA DE PRODUCTO -->
<div class="fixed inset-0 bg-black/50 z-[9999] flex items-center justify-center p-4 opacity-0 invisible transition-all duration-300" id="modalProductoOverlay">
  <div class="relative bg-white rounded max-w-[850px] w-full max-h-[90vh] overflow-hidden shadow-2xl transform translate-y-4 transition-transform duration-300" id="modalProducto">
    
    <!-- Botón cerrar -->
    <button class="absolute top-3 right-3 w-7 h-7 flex items-center justify-center z-10 text-gray-400 hover:text-rose-500 transition-colors" id="modalProductoClose" aria-label="Cerrar">
      <i class="ph ph-x text-xl"></i>
    </button>
    
    <div class="grid grid-cols-1 md:grid-cols-[380px_1fr] max-h-[90vh]">
      <!-- Columna izquierda: Galería -->
      <div class="p-5 bg-white flex flex-col gap-3 max-h-[320px] md:max-h-none border-b md:border-b-0 md:border-r border-gray-100">
        
        <!-- Imagen principal -->
        <div class="relative aspect-square bg-gray-50 rounded overflow-hidden flex items-center justify-center flex-1">
          <button class="absolute left-2 top-1/2 -translate-y-1/2 w-8 h-8 bg-white/90 border border-gray-200 rounded-full flex items-center justify-center hover:bg-white hover:border-gray-300 transition-all z-5" id="modalGaleriaPrev">
            <i class="ph ph-caret-left text-gray-500 text-sm"></i>
          </button>
          <img id="modalImagenPrincipal" class="max-w-full max-h-full object-contain" src="" alt="Producto">
          <button class="absolute right-2 top-1/2 -translate-y-1/2 w-8 h-8 bg-white/90 border border-gray-200 rounded-full flex items-center justify-center hover:bg-white hover:border-gray-300 transition-all z-5" id="modalGaleriaNext">
            <i class="ph ph-caret-right text-gray-500 text-sm"></i>
          </button>
        </div>
        
        <!-- Miniaturas -->
        <div class="flex gap-2 justify-center" id="modalThumbs">
          <!-- Se generan dinámicamente -->
        </div>
      </div>
      
      <!-- Columna derecha: Info -->
      <div class="p-5 overflow-y-auto max-h-[50vh] md:max-h-[90vh]">
        
        <!-- Categoría -->
        <span class="text-sm text-blue-500 mb-1 inline-block" id="modalCategoria">Categoría</span>
        
        <!-- Nombre -->
        <h2 class="text-lg font-semibold text-gray-800 leading-snug mb-3" id="modalNombre">Nombre del producto</h2>
        
        <!-- Rating y código -->
        <div class="flex items-center gap-3 mb-3 text-sm flex-wrap">
          <div class="flex items-center gap-0.5" id="modalStars">
            <i class="ph-fill ph-star text-amber-400"></i>
            <i class="ph-fill ph-star text-amber-400"></i>
            <i class="ph-fill ph-star text-amber-400"></i>
            <i class="ph-fill ph-star text-amber-400"></i>
            <i class="ph-fill ph-star-half text-amber-400"></i>
          </div>
          <span class="text-gray-500" id="modalValoraciones">0</span>
          <span class="text-gray-400" id="modalCodigo">Código: XXXX</span>
        </div>
        
        <!-- Precio y stock -->
        <div class="flex items-center justify-between mb-3">
          <div class="flex items-baseline gap-2">
            <span class="text-2xl font-bold text-gray-800" id="modalPrecio">S/ 0.00</span>
            <span class="text-sm text-gray-400 line-through" id="modalPrecioAnterior">S/ 0.00</span>
          </div>
          <span class="text-sm font-medium text-green-500" id="modalStock">En stock</span>
        </div>
        
        <!-- Descripción -->
        <p class="text-sm text-gray-500 leading-relaxed mb-4" id="modalDescripcion">
          Descripción del producto...
        </p>
        
        <!-- Cantidad y botón añadir -->
        <div class="flex gap-3 mb-4">
          <!-- Contador -->
          <div class="inline-flex items-center border border-gray-200 rounded h-10 bg-white">
            <button class="w-9 h-full flex items-center justify-center text-gray-400 hover:text-gray-600 hover:bg-gray-50 transition-colors" id="modalCantidadMenos">
              <i class="ph ph-minus text-xs"></i>
            </button>
            <input type="number" class="w-10 h-full border-x border-gray-200 text-center text-sm font-medium text-gray-800 bg-white focus:outline-none [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none" id="modalCantidad" value="1" min="1" max="99">
            <button class="w-9 h-full flex items-center justify-center text-gray-400 hover:text-gray-600 hover:bg-gray-50 transition-colors" id="modalCantidadMas">
              <i class="ph ph-plus text-xs"></i>
            </button>
          </div>
          
          <!-- Botón carrito -->
          <button class="flex-1 h-10 px-5 bg-blue-500 hover:bg-blue-600 text-white font-semibold text-sm rounded flex items-center justify-center gap-2 transition-colors" id="modalBtnCarrito">
            Añadir a la cesta
          </button>
        </div>
        
        <!-- Acciones secundarias -->
        <div class="flex gap-5 mb-4 pb-4 border-b border-gray-100">
          <button class="flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-800 transition-colors" id="modalBtnDeseos">
            <i class="ph ph-heart text-base"></i>
            Añadir a la lista de deseos
          </button>
          <button class="flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-800 transition-colors">
            <i class="ph ph-shuffle text-base"></i>
            Comparar
          </button>
        </div>
        
        <!-- Info de la tienda -->
        <div class="flex items-center gap-3 p-3 bg-gray-50 rounded mb-4">
          <div class="w-10 h-10 bg-blue-500 rounded flex items-center justify-center overflow-hidden" id="modalTiendaLogo">
            <span class="text-white font-bold">T</span>
          </div>
          <div class="flex-1">
            <span class="block text-xs text-gray-400">Almacenar:</span>
            <a href="#" class="text-sm font-semibold text-gray-800 hover:text-blue-500 transition-colors" id="modalTiendaNombre">Tienda</a>
          </div>
          <div class="flex items-center gap-0.5">
            <i class="ph-fill ph-star text-xs text-amber-400"></i>
            <i class="ph-fill ph-star text-xs text-amber-400"></i>
            <i class="ph-fill ph-star text-xs text-amber-400"></i>
            <i class="ph-fill ph-star text-xs text-amber-400"></i>
            <i class="ph ph-star text-xs text-amber-400"></i>
            <span class="text-xs text-gray-500 ml-1" id="modalTiendaReviews">0</span>
          </div>
        </div>
        
        <!-- Beneficios -->
        <div class="flex flex-col gap-2 mb-4">
          <div class="flex items-center gap-2 text-sm text-gray-500">
            <i class="ph ph-truck text-base text-green-500"></i>
            <span>Envío y devoluciones gratis para este artículo</span>
          </div>
          <div class="flex items-center gap-2 text-sm text-gray-500">
            <i class="ph ph-clock text-base text-green-500"></i>
            <span id="modalEntrega">Entrega en 3-5 días laborables</span>
          </div>
          <div class="flex items-center gap-2 text-sm text-gray-500">
            <i class="ph ph-shield-check text-base text-green-500"></i>
            <span>Garantía de devolución de dinero</span>
          </div>
        </div>
        
        <!-- Compartir -->
        <div class="pt-4 border-t border-gray-100">
          <span class="block text-sm text-gray-500 mb-2">Comparte este producto:</span>
          <div class="flex gap-2">
            <a href="#" class="w-8 h-8 rounded-full bg-[#1877f2] flex items-center justify-center text-white hover:scale-110 transition-transform">
              <i class="ph ph-facebook-logo text-sm"></i>
            </a>
            <a href="#" class="w-8 h-8 rounded-full bg-[#1da1f2] flex items-center justify-center text-white hover:scale-110 transition-transform">
              <i class="ph ph-twitter-logo text-sm"></i>
            </a>
            <a href="#" class="w-8 h-8 rounded-full bg-[#e60023] flex items-center justify-center text-white hover:scale-110 transition-transform">
              <i class="ph ph-pinterest-logo text-sm"></i>
            </a>
            <a href="#" class="w-8 h-8 rounded-full bg-[#0a66c2] flex items-center justify-center text-white hover:scale-110 transition-transform">
              <i class="ph ph-linkedin-logo text-sm"></i>
            </a>
            <a href="#" class="w-8 h-8 rounded-full bg-[#25d366] flex items-center justify-center text-white hover:scale-110 transition-transform">
              <i class="ph ph-whatsapp-logo text-sm"></i>
            </a>
            <a href="#" class="w-8 h-8 rounded-full bg-[#0088cc] flex items-center justify-center text-white hover:scale-110 transition-transform">
              <i class="ph ph-telegram-logo text-sm"></i>
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<style>
/* Solo estilos necesarios para el estado activo del modal */
#modalProductoOverlay.active {
  opacity: 1;
  visibility: visible;
}
#modalProductoOverlay.active #modalProducto {
  transform: translateY(0);
}
.modal-thumb {
  width: 55px;
  height: 55px;
  border: 2px solid transparent;
  border-radius: 4px;
  overflow: hidden;
  cursor: pointer;
  transition: border-color 0.2s ease;
}
.modal-thumb:hover {
  border-color: #cbd5e1;
}
.modal-thumb.active {
  border-color: #3b82f6;
}
.modal-thumb img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}
</style>

<script>
/**
 * Módulo de Vista Rápida de Producto
 */
const ModalProducto = {
  overlay: null,
  modal: null,
  productoActual: null,
  imagenActual: 0,
  
  init() {
    this.overlay = document.getElementById('modalProductoOverlay');
    this.modal = document.getElementById('modalProducto');
    
    if (!this.overlay) return;
    
    // Cerrar modal
    document.getElementById('modalProductoClose')?.addEventListener('click', () => this.cerrar());
    this.overlay.addEventListener('click', (e) => {
      if (e.target === this.overlay) this.cerrar();
    });
    
    // Tecla Escape
    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape' && this.overlay.classList.contains('active')) {
        this.cerrar();
      }
    });
    
    // Cantidad
    document.getElementById('modalCantidadMenos')?.addEventListener('click', () => this.cambiarCantidad(-1));
    document.getElementById('modalCantidadMas')?.addEventListener('click', () => this.cambiarCantidad(1));
    
    // Navegación galería
    document.getElementById('modalGaleriaPrev')?.addEventListener('click', () => this.navegarGaleria(-1));
    document.getElementById('modalGaleriaNext')?.addEventListener('click', () => this.navegarGaleria(1));
    
    // Agregar al carrito
    document.getElementById('modalBtnCarrito')?.addEventListener('click', () => this.agregarCarrito());
    
    // Lista de deseos
    document.getElementById('modalBtnDeseos')?.addEventListener('click', () => this.toggleDeseos());
  },
  
  abrir(productoId) {
    const producto = this.obtenerProducto(productoId);
    this.productoActual = producto;
    this.imagenActual = 0;
    
    this.llenarModal(producto);
    this.overlay.classList.add('active');
    document.body.style.overflow = 'hidden';
  },
  
  cerrar() {
    this.overlay.classList.remove('active');
    document.body.style.overflow = '';
  },
  
  obtenerProducto(id) {
    const productosPage = window.tiendaProductos || [];
    const tiendaInfo = window.tiendaInfo || {};
    
    const productoEncontrado = productosPage.find(p => p.id === id);
    
    if (productoEncontrado) {
      return {
        id: productoEncontrado.id,
        nombre: productoEncontrado.nombre,
        precio: productoEncontrado.precio,
        precioAnterior: productoEncontrado.precio_anterior || null,
        categoria: tiendaInfo.categoria || 'General',
        codigo: `LY${String(productoEncontrado.id).padStart(4, '0')}`,
        descripcion: productoEncontrado.descripcion || 'Producto de alta calidad. Consulta más detalles en la página del producto.',
        valoraciones: productoEncontrado.valoraciones || Math.floor(Math.random() * 50) + 5,
        tienda: tiendaInfo.nombre || 'Tienda',
        tiendaLogo: tiendaInfo.logo || null,
        tiendaReviews: Math.floor(Math.random() * 100) + 10,
        imagenes: [productoEncontrado.imagen],
        stock: productoEncontrado.stock || Math.floor(Math.random() * 50) + 10,
        entrega: '3-5 días laborables'
      };
    }
    
    return {
      id: id,
      nombre: 'Producto no encontrado',
      precio: 0,
      precioAnterior: null,
      categoria: 'Sin categoría',
      codigo: 'N/A',
      descripcion: 'Este producto no está disponible.',
      valoraciones: 0,
      tienda: 'Tienda',
      tiendaLogo: null,
      tiendaReviews: 0,
      imagenes: ['https://via.placeholder.com/400x400?text=Sin+imagen'],
      stock: 0,
      entrega: 'No disponible'
    };
  },
  
  llenarModal(producto) {
    document.getElementById('modalCategoria').textContent = producto.categoria;
    document.getElementById('modalNombre').textContent = producto.nombre;
    document.getElementById('modalImagenPrincipal').src = producto.imagenes[0];
    
    // Miniaturas
    const thumbsContainer = document.getElementById('modalThumbs');
    thumbsContainer.innerHTML = producto.imagenes.map((img, i) => `
      <div class="modal-thumb ${i === 0 ? 'active' : ''}" onclick="ModalProducto.seleccionarImagen(${i})">
        <img src="${img}" alt="Miniatura ${i + 1}">
      </div>
    `).join('');
    
    document.getElementById('modalValoraciones').textContent = producto.valoraciones;
    document.getElementById('modalCodigo').textContent = `Código: ${producto.codigo}`;
    document.getElementById('modalPrecio').textContent = `S/ ${producto.precio.toFixed(2)}`;
    
    const precioAnterior = document.getElementById('modalPrecioAnterior');
    if (producto.precioAnterior) {
      precioAnterior.textContent = `S/ ${producto.precioAnterior.toFixed(2)}`;
      precioAnterior.style.display = 'inline';
    } else {
      precioAnterior.style.display = 'none';
    }
    
    const stockEl = document.getElementById('modalStock');
    if (producto.stock > 0) {
      stockEl.textContent = 'En stock';
      stockEl.className = 'text-sm font-medium text-green-500';
    } else {
      stockEl.textContent = 'Agotado';
      stockEl.className = 'text-sm font-medium text-red-500';
    }
    
    document.getElementById('modalDescripcion').textContent = producto.descripcion;
    document.getElementById('modalTiendaNombre').textContent = producto.tienda;
    
    const logoEl = document.getElementById('modalTiendaLogo');
    if (producto.tiendaLogo) {
      logoEl.innerHTML = `<img src="${producto.tiendaLogo}" class="w-full h-full object-cover" alt="${producto.tienda}">`;
    } else {
      logoEl.innerHTML = `<span class="text-white font-bold">${producto.tienda.charAt(0).toUpperCase()}</span>`;
    }
    
    document.getElementById('modalTiendaReviews').textContent = producto.tiendaReviews;
    document.getElementById('modalEntrega').textContent = `Entrega en ${producto.entrega}`;
    document.getElementById('modalCantidad').value = 1;
  },
  
  seleccionarImagen(index) {
    if (!this.productoActual) return;
    this.imagenActual = index;
    document.getElementById('modalImagenPrincipal').src = this.productoActual.imagenes[index];
    document.querySelectorAll('.modal-thumb').forEach((t, i) => {
      t.classList.toggle('active', i === index);
    });
  },
  
  navegarGaleria(direction) {
    if (!this.productoActual) return;
    const total = this.productoActual.imagenes.length;
    this.imagenActual = (this.imagenActual + direction + total) % total;
    this.seleccionarImagen(this.imagenActual);
  },
  
  cambiarCantidad(delta) {
    const input = document.getElementById('modalCantidad');
    let valor = parseInt(input.value) + delta;
    if (valor < 1) valor = 1;
    if (valor > 99) valor = 99;
    input.value = valor;
  },
  
  agregarCarrito() {
    const btn = document.getElementById('modalBtnCarrito');
    btn.innerHTML = '<i class="ph ph-check"></i> Agregado';
    btn.classList.remove('bg-blue-500', 'hover:bg-blue-600');
    btn.classList.add('bg-green-500');
    
    setTimeout(() => {
      btn.textContent = 'Añadir a la cesta';
      btn.classList.remove('bg-green-500');
      btn.classList.add('bg-blue-500', 'hover:bg-blue-600');
    }, 2000);
  },
  
  toggleDeseos() {
    const btn = document.getElementById('modalBtnDeseos');
    const icon = btn.querySelector('i');
    
    if (icon.classList.contains('ph-heart')) {
      icon.classList.remove('ph-heart');
      icon.classList.add('ph-heart-fill');
      btn.classList.add('text-rose-500');
    } else {
      icon.classList.remove('ph-heart-fill');
      icon.classList.add('ph-heart');
      btn.classList.remove('text-rose-500');
    }
  }
};

document.addEventListener('DOMContentLoaded', () => ModalProducto.init());

function vistaRapidaProducto(productoId) {
  ModalProducto.abrir(productoId);
}

if (typeof TiendaModule !== 'undefined') {
  TiendaModule.vistaRapida = vistaRapidaProducto;
} else {
  window.TiendaModule = { vistaRapida: vistaRapidaProducto };
}
</script>
