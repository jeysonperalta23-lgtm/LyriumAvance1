/**
 * SISTEMA DE ANIMACIONES DINÁMICAS DE BLOQUES
 * Controla las animaciones de entrada/salida de bloques
 */

// Configuración de animaciones disponibles
const ANIMACIONES_DISPONIBLES = {
    entrada: [
        'fadeInUp',
        'fadeInLeft',
        'fadeInRight',
        'zoomIn',
        'rotateIn',
        'flipInX',
        'flipInY',
        'bounceIn',
        'slideInDown'
    ],
    salida: [
        'fadeOut',
        'slideOutUp',
        'slideOutDown',
        'slideOutLeft',
        'slideOutRight',
        'zoomOut'
    ]
};

// Mapeo de animaciones a clases CSS
const CLASES_ANIMACION = {
    zoomIn: 'anim-zoom',
    rotateIn: 'anim-rotate',
    flipInX: 'anim-flip-x',
    flipInY: 'anim-flip-y',
    bounceIn: 'anim-bounce',
    slideInDown: 'anim-slide-down'
};

/**
 * Aplicar animación aleatoria a un bloque
 * @param {HTMLElement} bloque - Elemento del bloque
 * @param {string} tipo - 'entrada' o 'salida'
 */
function aplicarAnimacionAleatoria(bloque, tipo = 'entrada') {
    const animaciones = ANIMACIONES_DISPONIBLES[tipo];
    const animacionAleatoria = animaciones[Math.floor(Math.random() * animaciones.length)];

    const claseCSS = CLASES_ANIMACION[animacionAleatoria];
    if (claseCSS) {
        bloque.classList.add(claseCSS);
    }
}

/**
 * Sistema de rotación automática de bloques
 * Cambia el orden de los bloques cada X segundos
 */
class RotadorBloques {
    constructor(contenedor, intervalo = 10000, autoInicio = false) {
        this.contenedor = contenedor;
        this.intervalo = intervalo;
        this.bloques = [];
        this.intervalId = null;
        this.activo = false;
        this.plantilla = this.detectarPlantilla();

        if (autoInicio) {
            // Esperar a que carguen las animaciones iniciales
            setTimeout(() => this.iniciar(), 2000);
        }
    }

    detectarPlantilla() {
        const container = document.querySelector('.tienda-container');
        if (!container) return 1;
        if (container.classList.contains('layout-sidebar-right')) return 1;
        if (container.classList.contains('layout-sidebar-left')) return 2;
        if (container.classList.contains('layout-full-width')) return 3;
        return 1; // Por defecto
    }

    iniciar() {
        if (this.activo) return;

        // Filtrar bloques para NO mover el banner principal ni la info-card
        this.bloques = Array.from(this.contenedor.querySelectorAll('.tienda-bloque')).filter(b => {
            const tipo = b.getAttribute('data-tipo');
            const nombre = b.getAttribute('data-nombre');
            return tipo !== 'banner' && tipo !== 'info-card' && nombre !== 'Banner Principal';
        });

        if (this.bloques.length < 2) {
            console.log('ℹ️ Secciones protegidas: el banner permanecerá fijo.');
            return;
        }

        this.activo = true;
        this.intervalId = setInterval(() => this.rotar(), this.intervalo);
        console.log(`🔄 Rotador de bloques iniciado (Plantilla ${this.plantilla}, cada ${this.intervalo / 1000}s)`);
    }

    rotar() {
        if (this.bloques.length < 2) return;

        // Seleccionar dos bloques aleatorios
        const indice1 = Math.floor(Math.random() * this.bloques.length);
        let indice2 = Math.floor(Math.random() * this.bloques.length);
        while (indice2 === indice1) {
            indice2 = Math.floor(Math.random() * this.bloques.length);
        }

        const bloque1 = this.bloques[indice1];
        const bloque2 = this.bloques[indice2];

        // Agregar clase de reordenamiento
        bloque1.classList.add('reordenando');
        bloque2.classList.add('reordenando');

        // Intercambiar posiciones en el DOM
        setTimeout(() => {
            const parent = bloque1.parentNode;
            const siguiente1 = bloque1.nextSibling;
            const siguiente2 = bloque2.nextSibling;

            if (siguiente1 === bloque2) {
                parent.insertBefore(bloque2, bloque1);
            } else if (siguiente2 === bloque1) {
                parent.insertBefore(bloque1, bloque2);
            } else {
                parent.insertBefore(bloque1, siguiente2);
                parent.insertBefore(bloque2, siguiente1);
            }

            // Actualizar array de bloques
            [this.bloques[indice1], this.bloques[indice2]] = [this.bloques[indice2], this.bloques[indice1]];

            // Remover clase después de la animación
            setTimeout(() => {
                bloque1.classList.remove('reordenando');
                bloque2.classList.remove('reordenando');
            }, 500);
        }, 250);
    }

    detener() {
        if (!this.activo) return;

        clearInterval(this.intervalId);
        this.activo = false;
        console.log('⏸️ Rotador de bloques detenido');
    }

    toggle() {
        if (this.activo) {
            this.detener();
        } else {
            this.iniciar();
        }
    }
}

/**
 * Aplicar efecto especial a un bloque
 * @param {HTMLElement} bloque - Elemento del bloque
 * @param {string} efecto - Nombre del efecto (shake, heartbeat, float, glow)
 * @param {number} duracion - Duración en ms (opcional)
 */
function aplicarEfecto(bloque, efecto, duracion = 2000) {
    bloque.classList.add(efecto);

    if (duracion > 0) {
        setTimeout(() => {
            bloque.classList.remove(efecto);
        }, duracion);
    }
}

/**
 * Animar salida de un bloque
 * @param {HTMLElement} bloque - Elemento del bloque
 * @param {string} direccion - 'arriba', 'abajo', 'izquierda', 'derecha', o 'fade'
 * @returns {Promise} - Promesa que se resuelve cuando termina la animación
 */
function animarSalida(bloque, direccion = 'fade') {
    return new Promise((resolve) => {
        const clases = {
            'arriba': 'saliendo-arriba',
            'abajo': 'saliendo-abajo',
            'izquierda': 'saliendo-izquierda',
            'derecha': 'saliendo-derecha',
            'fade': 'saliendo'
        };

        const clase = clases[direccion] || 'saliendo';
        bloque.classList.add(clase);

        // Esperar a que termine la animación
        setTimeout(() => {
            resolve();
        }, 500);
    });
}

/**
 * Inicializar sistema de animaciones
 */
function initAnimacionesBloques() {
    // Detectar plantilla actual
    const container = document.querySelector('.tienda-container');
    if (!container) return;

    const bloques = document.querySelectorAll('.tienda-bloque');
    console.log(`🎨 Sistema de animaciones iniciado - ${bloques.length} bloques detectados`);

    // Crear rotadores para cada sección que tenga bloques
    const rotadores = [];

    // Rotador para secciones principales (banner, info)
    const seccionesPrincipales = document.querySelector('.tienda-secciones-principales');
    if (seccionesPrincipales && seccionesPrincipales.querySelectorAll('.tienda-bloque').length >= 2) {
        const rotador1 = new RotadorBloques(seccionesPrincipales, 12000, true);
        rotadores.push(rotador1);
        console.log('✅ Rotador activado en secciones principales');
    }

    // Rotador para secciones secundarias (productos, banners)
    const seccionesSecundarias = document.querySelector('.tienda-secciones-secundarias');
    if (seccionesSecundarias && seccionesSecundarias.querySelectorAll('.tienda-bloque').length >= 2) {
        const rotador2 = new RotadorBloques(seccionesSecundarias, 15000, true);
        rotadores.push(rotador2);
        console.log('✅ Rotador activado en secciones secundarias');
    }

    // Rotador para secciones full (plantilla 3)
    const seccionesFull = document.querySelector('.tienda-secciones-secundarias-full');
    if (seccionesFull && seccionesFull.querySelectorAll('.tienda-bloque').length >= 2) {
        const rotador3 = new RotadorBloques(seccionesFull, 18000, true);
        rotadores.push(rotador3);
        console.log('✅ Rotador activado en secciones full');
    }

    // Guardar todos los rotadores globalmente
    window.rotadoresGlobales = rotadores;
    window.rotadorGlobal = rotadores[0]; // Mantener compatibilidad

    // Exponer funciones globalmente para uso en consola/debugging
    window.BloquesAnimaciones = {
        aplicarEfecto,
        animarSalida,
        aplicarAnimacionAleatoria,
        RotadorBloques,
        detenerTodos: () => rotadores.forEach(r => r.detener()),
        iniciarTodos: () => rotadores.forEach(r => r.iniciar())
    };

    console.log(`🔄 ${rotadores.length} rotadores activos`);
}

// Inicializar cuando el DOM esté listo
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initAnimacionesBloques);
} else {
    initAnimacionesBloques();
}

// Exportar para uso en otros módulos
if (typeof module !== 'undefined' && module.exports) {
    module.exports = {
        aplicarEfecto,
        animarSalida,
        aplicarAnimacionAleatoria,
        RotadorBloques
    };
}
