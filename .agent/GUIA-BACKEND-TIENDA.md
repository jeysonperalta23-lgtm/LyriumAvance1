   # 🎯 GUÍA DE IMPLEMENTACIÓN BACKEND - SISTEMA DE TIENDAS

**Fecha:** 2026-01-09  
**Propósito:** Guía paso a paso para conectar el frontend de tiendas con el backend

---

## 📋 ÍNDICE

1. [Estructura de Base de Datos](#estructura-de-base-de-datos)
2. [Archivos Backend a Crear](#archivos-backend-a-crear)
3. [Endpoints API Necesarios](#endpoints-api-necesarios)
4. [Flujo de Datos](#flujo-de-datos)
5. [Checklist de Implementación](#checklist-de-implementación)

---

## 🗄️ ESTRUCTURA DE BASE DE DATOS

### **Tabla: `tiendas`**

```sql
CREATE TABLE IF NOT EXISTS `tiendas` (
  `IdTienda` INT AUTO_INCREMENT PRIMARY KEY,
  `IdUsuario` INT NOT NULL COMMENT 'Dueño de la tienda',
  `Nombre` VARCHAR(100) NOT NULL,
  `Slug` VARCHAR(120) UNIQUE NOT NULL COMMENT 'URL amigable: /tienda.php?slug=nombre-tienda',
  `Descripcion` TEXT,
  `Logo` VARCHAR(255),
  `Cover` VARCHAR(255) COMMENT 'Imagen de portada',
  `Plan` ENUM('basico', 'premium') DEFAULT 'basico',
  `Categoria` VARCHAR(50),
  `Telefono` VARCHAR(20),
  `Correo` VARCHAR(100),
  `Direccion` VARCHAR(255),
  `Actividad` VARCHAR(255) COMMENT 'Actividad empresarial',
  `Estado` ENUM('activo', 'inactivo', 'suspendido') DEFAULT 'activo',
  
  -- NUEVOS CAMPOS PARA PERSONALIZACIÓN PREMIUM
  `LayoutModelo` TINYINT DEFAULT 1 COMMENT '1=Sidebar Derecha, 2=Sidebar Izquierda, 3=Full Width',
  `Tema` VARCHAR(20) DEFAULT '' COMMENT 'tema-ocean, tema-dark, tema-minimal, o vacío para default',
  
  `FechaCreacion` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `FechaActualizacion` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  
  FOREIGN KEY (`IdUsuario`) REFERENCES `usuarios`(`IdUsuario`) ON DELETE CASCADE,
  INDEX idx_slug (`Slug`),
  INDEX idx_plan (`Plan`),
  INDEX idx_estado (`Estado`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

### **Tabla: `tienda_banners`**

```sql
CREATE TABLE IF NOT EXISTS `tienda_banners` (
  `IdBanner` INT AUTO_INCREMENT PRIMARY KEY,
  `IdTienda` INT NOT NULL,
  `Tipo` ENUM('principal', 'sidebar', 'horizontal') DEFAULT 'principal',
  `Url` VARCHAR(255) NOT NULL COMMENT 'URL de la imagen',
  `Titulo` VARCHAR(100),
  `Orden` TINYINT DEFAULT 0,
  `Estado` ENUM('activo', 'inactivo') DEFAULT 'activo',
  `FechaCreacion` DATETIME DEFAULT CURRENT_TIMESTAMP,
  
  FOREIGN KEY (`IdTienda`) REFERENCES `tiendas`(`IdTienda`) ON DELETE CASCADE,
  INDEX idx_tienda_tipo (`IdTienda`, `Tipo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

### **Tabla: `tienda_redes_sociales`**

```sql
CREATE TABLE IF NOT EXISTS `tienda_redes_sociales` (
  `IdRed` INT AUTO_INCREMENT PRIMARY KEY,
  `IdTienda` INT NOT NULL,
  `Plataforma` ENUM('instagram', 'facebook', 'whatsapp', 'tiktok', 'youtube', 'twitter', 'linkedin', 'pinterest', 'telegram', 'web') NOT NULL,
  `Url` VARCHAR(255) NOT NULL,
  `Orden` TINYINT DEFAULT 0,
  
  FOREIGN KEY (`IdTienda`) REFERENCES `tiendas`(`IdTienda`) ON DELETE CASCADE,
  UNIQUE KEY unique_tienda_plataforma (`IdTienda`, `Plataforma`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

### **Tabla: `tienda_horarios`**

```sql
CREATE TABLE IF NOT EXISTS `tienda_horarios` (
  `IdHorario` INT AUTO_INCREMENT PRIMARY KEY,
  `IdTienda` INT NOT NULL,
  `DiaSemana` ENUM('lunes', 'martes', 'miercoles', 'jueves', 'viernes', 'sabado', 'domingo') NOT NULL,
  `HoraApertura` TIME,
  `HoraCierre` TIME,
  `Cerrado` BOOLEAN DEFAULT FALSE,
  
  FOREIGN KEY (`IdTienda`) REFERENCES `tiendas`(`IdTienda`) ON DELETE CASCADE,
  UNIQUE KEY unique_tienda_dia (`IdTienda`, `DiaSemana`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

### **Tabla: `tienda_sucursales`**

```sql
CREATE TABLE IF NOT EXISTS `tienda_sucursales` (
  `IdSucursal` INT AUTO_INCREMENT PRIMARY KEY,
  `IdTienda` INT NOT NULL,
  `Nombre` VARCHAR(100) NOT NULL,
  `Direccion` VARCHAR(255),
  `Ciudad` VARCHAR(100),
  `Telefono` VARCHAR(20),
  `HorarioApertura` TIME,
  `HorarioCierre` TIME,
  `GoogleMapsUrl` VARCHAR(500),
  `EsPrincipal` BOOLEAN DEFAULT FALSE,
  `Orden` TINYINT DEFAULT 0,
  
  FOREIGN KEY (`IdTienda`) REFERENCES `tiendas`(`IdTienda`) ON DELETE CASCADE,
  INDEX idx_tienda (`IdTienda`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

### **Tabla: `tienda_fotos`**

```sql
CREATE TABLE IF NOT EXISTS `tienda_fotos` (
  `IdFoto` INT AUTO_INCREMENT PRIMARY KEY,
  `IdTienda` INT NOT NULL,
  `Url` VARCHAR(255) NOT NULL,
  `Titulo` VARCHAR(100),
  `Orden` TINYINT DEFAULT 0,
  `FechaSubida` DATETIME DEFAULT CURRENT_TIMESTAMP,
  
  FOREIGN KEY (`IdTienda`) REFERENCES `tiendas`(`IdTienda`) ON DELETE CASCADE,
  INDEX idx_tienda (`IdTienda`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

### **Tabla: `tienda_opiniones`**

```sql
CREATE TABLE IF NOT EXISTS `tienda_opiniones` (
  `IdOpinion` INT AUTO_INCREMENT PRIMARY KEY,
  `IdTienda` INT NOT NULL,
  `IdUsuario` INT COMMENT 'Usuario que opina (puede ser NULL si es anónimo)',
  `Autor` VARCHAR(100) NOT NULL,
  `Rating` TINYINT NOT NULL CHECK (Rating BETWEEN 1 AND 5),
  `Comentario` TEXT NOT NULL,
  `VotosUtil` INT DEFAULT 0,
  `VotosNoUtil` INT DEFAULT 0,
  `Estado` ENUM('pendiente', 'aprobado', 'rechazado') DEFAULT 'pendiente',
  `FechaCreacion` DATETIME DEFAULT CURRENT_TIMESTAMP,
  
  FOREIGN KEY (`IdTienda`) REFERENCES `tiendas`(`IdTienda`) ON DELETE CASCADE,
  FOREIGN KEY (`IdUsuario`) REFERENCES `usuarios`(`IdUsuario`) ON DELETE SET NULL,
  INDEX idx_tienda_estado (`IdTienda`, `Estado`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

### **Tabla: `tienda_terminos`**

```sql
CREATE TABLE IF NOT EXISTS `tienda_terminos` (
  `IdTermino` INT AUTO_INCREMENT PRIMARY KEY,
  `IdTienda` INT NOT NULL,
  `Tipo` ENUM('envio', 'devolucion', 'privacidad') NOT NULL,
  `Contenido` TEXT NOT NULL,
  `FechaActualizacion` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  
  FOREIGN KEY (`IdTienda`) REFERENCES `tiendas`(`IdTienda`) ON DELETE CASCADE,
  UNIQUE KEY unique_tienda_tipo (`IdTienda`, `Tipo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

### **Tabla: `tienda_archivos_terminos`**

```sql
CREATE TABLE IF NOT EXISTS `tienda_archivos_terminos` (
  `IdArchivo` INT AUTO_INCREMENT PRIMARY KEY,
  `IdTienda` INT NOT NULL,
  `Nombre` VARCHAR(150) NOT NULL,
  `Url` VARCHAR(255) NOT NULL,
  `Orden` TINYINT DEFAULT 0,
  
  FOREIGN KEY (`IdTienda`) REFERENCES `tiendas`(`IdTienda`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

### **Tabla: `tienda_rubros`**

```sql
CREATE TABLE IF NOT EXISTS `tienda_rubros` (
  `IdRubro` INT AUTO_INCREMENT PRIMARY KEY,
  `IdTienda` INT NOT NULL,
  `Nombre` VARCHAR(50) NOT NULL,
  
  FOREIGN KEY (`IdTienda`) REFERENCES `tiendas`(`IdTienda`) ON DELETE CASCADE,
  INDEX idx_tienda (`IdTienda`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

### **Tabla: `tienda_seguidores`**

```sql
CREATE TABLE IF NOT EXISTS `tienda_seguidores` (
  `IdSeguidor` INT AUTO_INCREMENT PRIMARY KEY,
  `IdTienda` INT NOT NULL,
  `IdUsuario` INT NOT NULL,
  `FechaSeguimiento` DATETIME DEFAULT CURRENT_TIMESTAMP,
  
  FOREIGN KEY (`IdTienda`) REFERENCES `tiendas`(`IdTienda`) ON DELETE CASCADE,
  FOREIGN KEY (`IdUsuario`) REFERENCES `usuarios`(`IdUsuario`) ON DELETE CASCADE,
  UNIQUE KEY unique_seguimiento (`IdTienda`, `IdUsuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

### **Tabla: `tienda_estadisticas`**

```sql
CREATE TABLE IF NOT EXISTS `tienda_estadisticas` (
  `IdEstadistica` INT AUTO_INCREMENT PRIMARY KEY,
  `IdTienda` INT NOT NULL UNIQUE,
  `ValoracionPositiva` DECIMAL(4,1) DEFAULT 0.0 COMMENT 'Porcentaje 0-100',
  `TotalSeguidores` INT DEFAULT 0,
  `VendidosUltimos180Dias` INT DEFAULT 0,
  `CompradoresHabituales` INT DEFAULT 0,
  `FechaApertura` DATE,
  `UltimaActualizacion` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  
  FOREIGN KEY (`IdTienda`) REFERENCES `tiendas`(`IdTienda`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

---

## 📁 ARCHIVOS BACKEND A CREAR

### **1. Modelo: `models/Tienda.php`**

**Ubicación:** `c:\xampp\htdocs\lyrium\models\Tienda.php`

**Métodos necesarios:**

```php
class Tienda {
    // CRUD Básico
    public function obtener_por_slug($slug);
    public function obtener_por_id($id_tienda);
    public function crear($datos);
    public function actualizar($id_tienda, $datos);
    public function eliminar($id_tienda);
    
    // Personalización (Premium)
    public function actualizar_layout($id_tienda, $modelo);
    public function actualizar_tema($id_tienda, $tema);
    
    // Banners
    public function obtener_banners($id_tienda, $tipo = null);
    public function agregar_banner($id_tienda, $datos);
    public function eliminar_banner($id_banner);
    
    // Redes Sociales
    public function obtener_redes_sociales($id_tienda);
    public function actualizar_redes_sociales($id_tienda, $redes);
    
    // Horarios
    public function obtener_horarios($id_tienda);
    public function actualizar_horarios($id_tienda, $horarios);
    
    // Sucursales
    public function obtener_sucursales($id_tienda);
    public function agregar_sucursal($id_tienda, $datos);
    public function eliminar_sucursal($id_sucursal);
    
    // Fotos
    public function obtener_fotos($id_tienda);
    public function agregar_foto($id_tienda, $url, $titulo);
    public function eliminar_foto($id_foto);
    
    // Opiniones
    public function obtener_opiniones($id_tienda, $estado = 'aprobado');
    public function agregar_opinion($id_tienda, $datos);
    public function votar_opinion($id_opinion, $tipo); // 'util' o 'no_util'
    
    // Términos
    public function obtener_terminos($id_tienda);
    public function actualizar_termino($id_tienda, $tipo, $contenido);
    public function obtener_archivos_terminos($id_tienda);
    
    // Estadísticas
    public function obtener_estadisticas($id_tienda);
    public function actualizar_estadisticas($id_tienda, $datos);
    
    // Seguidores
    public function seguir_tienda($id_tienda, $id_usuario);
    public function dejar_seguir_tienda($id_tienda, $id_usuario);
    public function esta_siguiendo($id_tienda, $id_usuario);
    
    // Validaciones
    public function validar_plan_premium($id_tienda);
    public function validar_limite_banners($id_tienda, $tipo);
    public function validar_limite_fotos($id_tienda);
}
```

### **2. Controlador: `controller/TiendaController.php`**

**Ubicación:** `c:\xampp\htdocs\lyrium\controller\TiendaController.php`

**Métodos necesarios:**

```php
class TiendaController {
    public function obtener_datos_tienda($slug);
    public function actualizar_configuracion($id_tienda, $datos);
    public function subir_banner($id_tienda, $archivo);
    public function guardar_opinion($id_tienda, $datos);
    public function enviar_mensaje_contacto($id_tienda, $datos);
}
```

### **3. API: `api/tienda.php`**

**Ubicación:** `c:\xampp\htdocs\lyrium\api\tienda.php`

**Operaciones necesarias:**

```php
switch($_GET['op']) {
    case 'obtener_datos':
        // GET: Obtener todos los datos de una tienda por slug
        break;
    
    case 'actualizar_layout':
        // POST: Actualizar modelo de layout (solo Premium)
        break;
    
    case 'actualizar_tema':
        // POST: Actualizar tema de color (solo Premium)
        break;
    
    case 'seguir':
        // POST: Seguir una tienda
        break;
    
    case 'dejar_seguir':
        // POST: Dejar de seguir una tienda
        break;
    
    case 'votar_opinion':
        // POST: Votar opinión como útil/no útil
        break;
    
    case 'agregar_opinion':
        // POST: Agregar nueva opinión
        break;
    
    case 'contactar':
        // POST: Enviar mensaje de contacto
        break;
}
```

---

## 🔄 FLUJO DE DATOS

### **Frontend → Backend**

```
tienda.php (Frontend)
    ↓
1. Recibe parámetro: ?slug=nombre-tienda
    ↓
2. Llama a: TiendaController::obtener_datos_tienda($slug)
    ↓
3. Controlador llama a: Tienda::obtener_por_slug($slug)
    ↓
4. Modelo consulta BD y retorna:
   - Datos básicos de tienda
   - Banners
   - Redes sociales
   - Horarios
   - Sucursales
   - Fotos
   - Opiniones
   - Términos
   - Estadísticas
    ↓
5. Frontend renderiza con los datos reales
```

### **Configuración Premium → Backend**

```
Panel de Configuración (Frontend)
    ↓
1. Usuario selecciona Layout Modelo 2
    ↓
2. AJAX POST a: api/tienda.php?op=actualizar_layout
   Datos: { id_tienda: 1, modelo: 2 }
    ↓
3. API valida:
   - Usuario autenticado
   - Es dueño de la tienda
   - Tiene plan Premium
   - Modelo válido (1-3)
    ↓
4. Llama a: Tienda::actualizar_layout($id_tienda, $modelo)
    ↓
5. Actualiza BD: UPDATE tiendas SET LayoutModelo = 2 WHERE IdTienda = 1
    ↓
6. Retorna: { success: true, mensaje: "Layout actualizado" }
    ↓
7. Frontend recarga vista previa
```

---

## ✅ CHECKLIST DE IMPLEMENTACIÓN

### **Fase 1: Base de Datos** ⏱️ 1-2 horas

- [ ] Ejecutar script SQL para crear todas las tablas
- [ ] Insertar datos de prueba para 2-3 tiendas
- [ ] Verificar relaciones y constraints

### **Fase 2: Modelo** ⏱️ 3-4 horas

- [ ] Crear `models/Tienda.php`
- [ ] Implementar método `obtener_por_slug()`
- [ ] Implementar métodos de banners
- [ ] Implementar métodos de redes sociales
- [ ] Implementar métodos de horarios
- [ ] Implementar métodos de sucursales
- [ ] Implementar métodos de fotos
- [ ] Implementar métodos de opiniones
- [ ] Implementar métodos de términos
- [ ] Implementar métodos de estadísticas
- [ ] Implementar métodos de configuración Premium

### **Fase 3: Controlador** ⏱️ 2-3 horas

- [ ] Crear `controller/TiendaController.php`
- [ ] Implementar `obtener_datos_tienda()`
- [ ] Implementar validaciones de plan
- [ ] Implementar manejo de errores

### **Fase 4: API** ⏱️ 2-3 horas

- [ ] Crear `api/tienda.php`
- [ ] Implementar endpoint `obtener_datos`
- [ ] Implementar endpoint `actualizar_layout`
- [ ] Implementar endpoint `actualizar_tema`
- [ ] Implementar endpoint `seguir`
- [ ] Implementar endpoint `votar_opinion`
- [ ] Implementar endpoint `contactar`

### **Fase 5: Integración Frontend** ⏱️ 1-2 horas

- [ ] Modificar `tienda.php` para usar datos reales
- [ ] Eliminar datos mock
- [ ] Probar con diferentes slugs
- [ ] Verificar que los 3 layouts funcionen
- [ ] Verificar que los 3 temas funcionen

### **Fase 6: Panel de Configuración** ⏱️ 4-5 horas

- [ ] Crear `panel/tienda-configuracion.php`
- [ ] Implementar selector de layout
- [ ] Implementar selector de tema
- [ ] Implementar vista previa en iframe
- [ ] Conectar con API

### **Fase 7: Testing** ⏱️ 2-3 horas

- [ ] Probar plan Básico vs Premium
- [ ] Probar cambio de layout
- [ ] Probar cambio de tema
- [ ] Probar límites (banners, fotos)
- [ ] Probar responsive
- [ ] Corregir bugs

---

## 🎯 PRIORIDADES

### **Crítico (Hacer primero):**
1. Crear tablas en BD
2. Implementar `Tienda::obtener_por_slug()`
3. Modificar `tienda.php` para usar datos reales

### **Importante (Hacer después):**
4. Implementar configuración de layout y tema
5. Crear panel de configuración
6. Implementar límites por plan

### **Opcional (Hacer al final):**
7. Sistema de opiniones
8. Sistema de seguidores
9. Estadísticas avanzadas

---

## 📝 NOTAS IMPORTANTES

1. **Validación de Plan:** Siempre verificar que el usuario tenga plan Premium antes de permitir:
   - Cambiar layout
   - Cambiar tema
   - Subir más de 2 banners
   - Subir más de 8 fotos

2. **Seguridad:** Validar que el usuario autenticado sea el dueño de la tienda antes de permitir modificaciones

3. **Límites:** Implementar límites estrictos según el plan:
   ```php
   $limites = [
       'basico' => [
           'banners_principales' => 2,
           'fotos' => 8,
           'productos_grid' => 15,  // 5 columnas × 3 filas
           'layouts' => 1,
           'temas' => 0
       ],
       'premium' => [
           'banners_principales' => 4,
           'banners_sidebar' => 3,
           'fotos' => 30,
           'productos_grid' => 25,  // 5 columnas × 5 filas
           'layouts' => 3,
           'temas' => 3
       ]
   ];
   ```

4. **Caché:** Considerar implementar caché para datos de tienda que no cambian frecuentemente

5. **Slug único:** Validar que el slug sea único al crear/actualizar tienda

---

## 🚀 TIEMPO ESTIMADO TOTAL

- **Mínimo viable:** 10-12 horas
- **Completo:** 18-22 horas
- **Con testing exhaustivo:** 25-30 horas

---

**Última actualización:** 2026-01-09
