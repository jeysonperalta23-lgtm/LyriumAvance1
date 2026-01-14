# Análisis: Estructura de Plantillas para Tienda Premium vs Básica

**Fecha:** 2026-01-09  
**Objetivo:** Revisar cómo se maneja la estructura de 3 plantillas para tienda premium y una sola para básica

---

## 📋 Resumen Ejecutivo

El sistema actualmente implementa **diferenciación entre planes Premium y Básico** a través de:

1. **3 Modelos de Layout para Premium** (estructuras visuales diferentes)
2. **3 Temas de Color para Premium** (paletas de colores distintas)
3. **1 Layout fijo para Básico** (sin opciones de personalización)

---

## 🏗️ Estructura de Layouts (Modelos Visuales)

### **Plan Premium: 3 Modelos de Layout**

Definidos en `tienda.php` (líneas 44-51):

```php
// Modelo de Layout (Solo Premium)
// 1: Sidebar Derecha, 2: Sidebar Izquierda, 3: Full Width (Sin sidebar lateral, solo banners)
$modelo_layout = ($plan === 'premium') ? 1 : 1; // Demo: cambiable a 1, 2 o 3

$layout_class = '';
if ($modelo_layout == 1) $layout_class = 'layout-sidebar-right';
if ($modelo_layout == 2) $layout_class = 'layout-sidebar-left';
if ($modelo_layout == 3) $layout_class = 'layout-full-width';
```

#### **Modelo 1: Sidebar Derecha** (`layout-sidebar-right`)
- **Estructura:** Contenido principal (izquierda) + Sidebar (derecha)
- **Grid CSS:** `grid-template-columns: 1fr 280px;`
- **Sidebar Premium:** Muestra banners publicitarios verticales con autoplay
- **Ubicación:** `tienda.css` líneas 252-254

#### **Modelo 2: Sidebar Izquierda** (`layout-sidebar-left`)
- **Estructura:** Sidebar (izquierda) + Contenido principal (derecha)
- **Grid CSS:** `grid-template-columns: 280px 1fr;`
- **Orden invertido** mediante grid-column
- **Ubicación:** `tienda.css` líneas 257-270

#### **Modelo 3: Full Width** (`layout-full-width`)
- **Estructura:** Sin sidebar lateral, contenido a ancho completo
- **Grid CSS:** `grid-template-columns: 1fr;`
- **Características especiales:**
  - Oculta el sidebar (`display: none`)
  - Agrega banners horizontales adicionales
  - Grid de productos más compacto (hasta 8 columnas)
  - Sección extra "Ofertas del día" (líneas 298-315 en `tienda.php`)
- **Ubicación:** `tienda.css` líneas 273-309

### **Plan Básico: 1 Layout Fijo**

- **Siempre usa:** `layout-sidebar-right` (Modelo 1)
- **Sidebar Básico:** Muestra información útil en lugar de banners:
  - Card de Envíos Nacionales
  - Card de Atención al Cliente
  - Card de Horarios
- **Sin opciones de personalización**

---

## 🎨 Temas de Color (Solo Premium)

Definidos en `tienda.php` (líneas 40-42) y `tienda.css` (líneas 32-66):

### **Tema 1: Ocean (Profundidad Azul)**
```css
.tema-ocean {
  --tienda-primary: #0891b2;
  --tienda-primary-dark: #164e63;
  --tienda-primary-light: #67e8f9;
  --tienda-bg-body: #f0fdfa;
  --tienda-header-bg: linear-gradient(135deg, #0891b2 0%, #0ea5e9 100%);
}
```

### **Tema 2: Dark (Elegancia Nocturna)**
```css
.tema-dark {
  --tienda-primary: #f59e0b; /* Acentos ámbar/dorado */
  --tienda-primary-dark: #b45309;
  --tienda-primary-light: #fcd34d;
  --tienda-bg-body: #0f172a;
  --tienda-bg-card: #1e293b;
  --tienda-text-main: #f8fafc;
  --tienda-text-muted: #94a3b8;
}
```

### **Tema 3: Minimal (Puro y Neutro)**
```css
.tema-minimal {
  --tienda-primary: #171717; /* Negro absoluto */
  --tienda-primary-dark: #000000;
  --tienda-primary-light: #444444;
  --tienda-bg-body: #ffffff;
  --tienda-border: #f3f4f6;
  --tienda-shadow: none;
}
```

### **Plan Básico: Sin Temas**
- Usa solo el tema por defecto (variables root en `tienda.css` líneas 7-20)
- No puede cambiar colores

---

## 📦 Componentes Clave

### **1. Banner Principal** (`tienda-banner.php`)
- **Premium:** Hasta 4 banners en carrusel
- **Básico:** Hasta 2 banners
- **Ubicación:** Líneas 11-13

### **2. Sidebar** (`tienda-sidebar.php`)
**Premium (líneas 40-94):**
- Banner vertical con autoplay (3 slides)
- Imágenes promocionales
- CTAs personalizados
- Indicadores de navegación

**Básico (líneas 96-160):**
- 3 Cards informativas:
  - Envíos Nacionales
  - Atención al Cliente
  - Horarios de Hoy

### **3. Grid de Productos** (`tienda-productos-grid.php`)
- **Premium:** Hasta 15 productos (línea 15)
- **Básico:** Hasta 10 productos (línea 15)

### **4. Tabs** (`tienda-tabs.php`)
- **Premium:** Formulario de contacto avanzado con CKEditor
- **Básico:** Formulario simple + mensaje de upgrade

### **5. Galería de Fotos**
- **Premium:** Hasta 30 fotos (línea 15 en `tienda-tabs.php`)
- **Básico:** Hasta 8 fotos

---

## 🔄 Flujo de Aplicación de Plantillas

```
tienda.php
    ↓
1. Define $plan = 'premium' o 'basico' (línea 38)
    ↓
2. Define $tema_actual (línea 42)
    ↓
3. Define $modelo_layout (línea 46)
    ↓
4. Genera $layout_class (líneas 48-51)
    ↓
5. Aplica clase de tema en contenedor (línea 258)
    ↓
6. Aplica clase de layout en grid (línea 286)
    ↓
7. Renderiza componentes según plan
```

---

## ✅ Fortalezas del Sistema Actual

1. **Separación Clara:** Premium vs Básico bien diferenciados
2. **Modularidad:** Componentes reutilizables (banner, sidebar, tabs)
3. **CSS Variables:** Temas fácilmente intercambiables
4. **Responsive:** Layouts se adaptan a móvil
5. **Condicionales PHP:** Lógica de plan centralizada

---

## ⚠️ Áreas de Mejora Identificadas

### **1. Hardcoded Layout Selection**
```php
// Línea 46 en tienda.php
$modelo_layout = ($plan === 'premium') ? 1 : 1; // Siempre 1, no dinámico
```
**Problema:** El modelo de layout está hardcodeado, no se lee de BD.

**Solución Sugerida:**
```php
// Debería venir de la base de datos
$modelo_layout = ($plan === 'premium') ? ($tienda['layout_modelo'] ?? 1) : 1;
```

### **2. Hardcoded Theme Selection**
```php
// Línea 42 en tienda.php
$tema_actual = ($plan === 'premium') ? 'tema-minimal' : '';
```
**Problema:** El tema está hardcodeado a 'tema-minimal'.

**Solución Sugerida:**
```php
// Debería venir de la base de datos
$tema_actual = ($plan === 'premium') ? ($tienda['tema'] ?? '') : '';
```

### **3. Falta de Persistencia en BD**

**Campos necesarios en tabla `tiendas`:**
```sql
ALTER TABLE tiendas ADD COLUMN layout_modelo TINYINT DEFAULT 1 COMMENT '1=Sidebar Derecha, 2=Sidebar Izquierda, 3=Full Width';
ALTER TABLE tiendas ADD COLUMN tema VARCHAR(20) DEFAULT '' COMMENT 'tema-ocean, tema-dark, tema-minimal, o vacío';
```

### **4. Sin Interfaz de Configuración**

**Falta:** Panel de administración donde el usuario Premium pueda:
- Seleccionar entre los 3 modelos de layout
- Elegir entre los 3 temas de color
- Vista previa en tiempo real

### **5. Modelo 3 (Full Width) Parcialmente Implementado**

**Ubicación:** Líneas 298-315 en `tienda.php`

**Problema:** La sección "Ofertas del día" usa productos que no existen:
```php
$productosGrid = array_slice($productos, 8, 6); // Índice 8 puede no existir
```

**Solución:** Validar existencia de productos antes de renderizar.

---

## 📊 Comparativa: Premium vs Básico

| Característica | Premium | Básico |
|---|---|---|
| **Modelos de Layout** | 3 opciones | 1 fijo |
| **Temas de Color** | 3 opciones | Default |
| **Banners Carrusel** | 4 | 2 |
| **Sidebar** | Banners publicitarios | Info útil |
| **Productos en Grid** | 15 | 10 |
| **Fotos en Galería** | 30 | 8 |
| **Formulario Contacto** | CKEditor avanzado | Simple |
| **Secciones Extras** | Banners horizontales | - |

---

## 🎯 Recomendaciones

### **Corto Plazo (Urgente)**

1. **Crear campos en BD:**
   - `layout_modelo` (TINYINT)
   - `tema` (VARCHAR)

2. **Modificar `tienda.php`:**
   - Leer `$modelo_layout` de BD
   - Leer `$tema_actual` de BD

3. **Validar datos:**
   - Verificar que `$modelo_layout` esté entre 1-3
   - Verificar que `$tema_actual` sea válido

### **Mediano Plazo**

4. **Crear panel de configuración:**
   - Página: `panel/tienda-configuracion.php`
   - Formulario con:
     - Radio buttons para modelo de layout
     - Radio buttons para tema
     - Vista previa en iframe

5. **Endpoint API:**
   - `api/tienda-config.php?op=actualizar_layout`
   - `api/tienda-config.php?op=actualizar_tema`

### **Largo Plazo**

6. **Sistema de plantillas personalizables:**
   - Editor visual drag-and-drop
   - Más opciones de temas (5-10)
   - Temas custom con color picker

---

## 🐛 Bugs Potenciales

1. **Modelo 3 sin productos suficientes:**
   ```php
   // Línea 311 en tienda.php
   $productosGrid = array_slice($productos, 8, 6);
   ```
   Si hay menos de 8 productos, esta sección quedará vacía.

2. **Tema no se aplica a todos los componentes:**
   - Verificar que todos los componentes respeten `$scope_tema`
   - Algunos componentes pueden no tener la clase de tema

3. **Responsive en Modelo 3:**
   - Grid de 8 columnas puede ser demasiado en tablets
   - Revisar breakpoints en `tienda.css` líneas 293-309

---

## 📝 Conclusión

El sistema actual tiene una **base sólida** con:
- ✅ 3 modelos de layout bien definidos
- ✅ 3 temas de color implementados
- ✅ Diferenciación clara Premium/Básico

**Pero necesita:**
- ❌ Persistencia en base de datos
- ❌ Interfaz de configuración
- ❌ Validaciones robustas
- ❌ Corrección de bugs menores

**Prioridad:** Implementar persistencia en BD y panel de configuración para que los usuarios Premium puedan realmente elegir entre las 3 plantillas.
