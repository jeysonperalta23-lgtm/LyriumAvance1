/**
 * Libro de Reclamaciones - Interactive Help Text
 * Actualiza dinámicamente el texto de ayuda según el tipo de reclamo seleccionado
 */

(function () {
    'use strict';

    // Esperar a que el DOM esté completamente cargado
    function initReclamacionesForm() {
        const tipoSelect = document.getElementById('tipo_reclamo');
        const helpBox = document.getElementById('lrHelp');
        const detalleTextarea = document.getElementById('detalle_reclamo');

        if (!tipoSelect || !helpBox || !detalleTextarea) {
            console.warn('Lyrium: Elementos del formulario de reclamaciones no encontrados');
            return;
        }

        /**
         * Actualiza el texto de ayuda y el placeholder según el tipo de reclamo
         */
        function renderHelp() {
            const tipoValue = (tipoSelect.value || '').toLowerCase();

            if (tipoValue === 'queja') {
                // Configuración para QUEJA
                helpBox.innerHTML = '<b>Queja:</b> Disconformidad frente a una mala atención del proveedor (sin relación directa con el producto/servicio).';
                detalleTextarea.placeholder = 'Describe la atención recibida, fecha, canal, persona que te atendió y lo ocurrido.';
            } else {
                // Configuración para RECLAMO (default)
                helpBox.innerHTML = '<b>Reclamo:</b> Disconformidad relacionada a un producto/servicio adquirido (calidad, entrega, garantía, etc.).';
                detalleTextarea.placeholder = 'Describe el problema con el producto/servicio, cuándo ocurrió y qué solución solicitas.';
            }
        }

        // Escuchar cambios en el select
        tipoSelect.addEventListener('change', renderHelp);

        // Renderizar el estado inicial
        renderHelp();
    }

    // Inicializar cuando el DOM esté listo
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initReclamacionesForm);
    } else {
        initReclamacionesForm();
    }
})();
