<?php
/**
 * TIENDA-INFO-CARD.PHP - Cards de información de tienda (fijas)
 * 
 * Muestra información del negocio en 3 cards horizontales:
 * - Sobre el negocio (categoría, rubros)
 * - Contacto (teléfono, correo)
 * - Horarios de atención
 * 
 * Variables esperadas:
 * @var array $tienda - Datos de la tienda
 * @var array $horarios - Horarios de atención
 */

// Validar datos requeridos
if (!isset($tienda) || !is_array($tienda)) {
    return;
}

// Día actual
$dias_semana = ['domingo', 'lunes', 'martes', 'miercoles', 'jueves', 'viernes', 'sabado'];
$dias_nombres = ['domingo' => 'Domingo', 'lunes' => 'Lunes', 'martes' => 'Martes', 'miercoles' => 'Miércoles', 'jueves' => 'Jueves', 'viernes' => 'Viernes', 'sabado' => 'Sábado'];
$dia_actual = $dias_semana[date('w')];
$horario_hoy = $horarios[$dia_actual] ?? null;
?>
<!-- INFO TIENDA - 3 Cards horizontales estilo garantías -->
<div class="bg-white rounded-xl border border-slate-200 mt-4 shadow-sm">
  <div class="grid grid-cols-1 md:grid-cols-3 divide-y md:divide-y-0 md:divide-x divide-slate-100">
    
    <!-- Card 1: Entregas a nivel nacional -->
    <div class="flex items-start gap-4 p-5">
      <div class="flex-shrink-0">
        <img 
          src="img/tienda/tienda-94.png" 
          alt="Entregas" 
          class="w-14 h-14 object-contain"
        >
      </div>
      <div class="flex-1 min-w-0">
        <h4 class="font-bold text-slate-800 text-sm mb-1">Entregas a nivel nacional</h4>
        <p class="text-slate-500 text-xs leading-relaxed mb-2">Envíos con las mejores empresas</p>
        <!-- Nombres de empresas de envío -->
        <div class="flex items-center gap-1.5 flex-wrap">
          <span class="inline-flex items-center px-2.5 py-1 bg-gradient-to-r from-orange-500 to-orange-600 text-white text-[10px] font-bold rounded-full shadow-sm">
            Shalom
          </span>
          <span class="inline-flex items-center px-2.5 py-1 bg-gradient-to-r from-red-500 to-red-600 text-white text-[10px] font-bold rounded-full shadow-sm">
            Olva
          </span>
          <span class="inline-flex items-center px-2.5 py-1 bg-gradient-to-r from-blue-500 to-blue-600 text-white text-[10px] font-bold rounded-full shadow-sm">
            Cruz del Sur
          </span>
        </div>
      </div>
    </div>
    
    <!-- Card 2: Contacto -->
    <div class="flex items-start gap-4 p-5">
      <div class="flex-shrink-0">
        <img 
          src="img/tienda/teléfono-94.png" 
          alt="Contacto" 
          class="w-14 h-14 object-contain"
        >
      </div>
      <div class="flex-1 min-w-0">
        <h4 class="font-bold text-slate-800 text-sm mb-1">Servicio al cliente 24/7</h4>
        <p class="text-slate-500 text-xs leading-relaxed mb-2">
<span>¿Quieres hablar con alguien? Elige
el chat o llámanos.</span>    
        </p>
       
      </div>
    </div>
    
    <!-- Card 3: Horarios -->
    <div class="flex items-start gap-4 p-5">
      <div class="flex-shrink-0 relative">
        <img 
          src="img/tienda/pasado-96.png" 
          alt="Horarios" 
          class="w-14 h-14 object-contain"
        >
        <!-- Indicador de estado -->
        <?php if ($tienda['abierto']): ?>
        <span class="absolute -top-0.5 -right-0.5 w-4 h-4 bg-emerald-500 rounded-full border-2 border-white flex items-center justify-center">
          <span class="w-1.5 h-1.5 bg-white rounded-full animate-pulse"></span>
        </span>
        <?php else: ?>
        <span class="absolute -top-0.5 -right-0.5 w-4 h-4 bg-red-500 rounded-full border-2 border-white"></span>
        <?php endif; ?>
      </div>
      <div class="flex-1 min-w-0">
        <div class="flex items-center gap-2 mb-1">
          <h4 class="font-bold text-slate-800 text-sm">Horarios</h4>
          <?php if ($tienda['abierto']): ?>
          <span class="text-emerald-600 text-xs font-medium">Abierto ahora</span>
          <?php else: ?>
          <span class="text-red-500 text-xs font-medium">Cerrado</span>
          <?php endif; ?>
        </div>
        <p class="text-slate-500 text-xs leading-relaxed mb-2">
          <?php echo $dias_nombres[$dia_actual]; ?>: 
          <?php if ($horario_hoy && !$horario_hoy['cerrado']): ?>
          <?php echo $horario_hoy['apertura']; ?> - <?php echo $horario_hoy['cierre']; ?>
          <?php else: ?>
          Cerrado
          <?php endif; ?>
        </p>
        <a href="#tab-sucursales" class="tienda-tab-link text-amber-600 hover:text-amber-700 text-xs font-semibold hover:underline">
          Ver horarios
        </a>
      </div>
    </div>
    
  </div>
</div>
