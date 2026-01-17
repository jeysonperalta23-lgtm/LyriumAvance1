(function () {
    const tipo = document.getElementById('tipo_reclamo');
    if (!tipo) return; // sólo ejecutar si existe el formulario
    const help = document.getElementById('lrHelp');
    const detalle = document.getElementById('detalle_reclamo');
    function renderHelp() {
        const v = (tipo.value || '').toLowerCase();
        if (v === 'queja') {
            if (help) help.innerHTML = '<b>Queja:</b> Disconformidad frente a una mala atención del proveedor (sin relación directa con el producto/servicio).';
            if (detalle) detalle.placeholder = 'Describe la atención recibida, fecha, canal, persona que te atendió y lo ocurrido.';
        } else {
            if (help) help.innerHTML = '<b>Reclamo:</b> Disconformidad relacionada a un producto/servicio adquirido (calidad, entrega, garantía, etc.).';
            if (detalle) detalle.placeholder = 'Describe el problema con el producto/servicio, cuándo ocurrió y qué solución solicitas.';
        }
    }
    tipo.addEventListener('change', renderHelp);
    renderHelp();
})();
