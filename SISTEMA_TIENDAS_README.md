# 🏪 Sistema de Tiendas - Lyrium Marketplace

## 📋 Descripción
Sistema completo de gestión de tiendas independientes para el marketplace Lyrium. Cada tienda tiene su propio panel de administración y credenciales de acceso.

---

## 🗂️ Estructura de Carpetas

```
lyrium/
├── database/
│   └── migrations/
│       └── 001_crear_sistema_tiendas.sql    # Script SQL principal
│
├── backend/
│   ├── admin/
│   │   └── tiendas/                         # CRUD de tiendas (Panel Admin)
│   │       ├── crear.php                    # Crear nueva tienda
│   │       ├── editar.php                   # Editar tienda
│   │       ├── listar.php                   # Lista de tiendas
│   │       ├── aprobar.php                  # Aprobar/rechazar tiendas
│   │       └── estadisticas.php             # Estadísticas generales
│   │
│   └── panel-tienda/                        # Backend del Panel de Tienda
│       ├── api/
│       │   ├── auth.php                     # Login/logout de tienda
│       │   ├── productos.php                # CRUD de productos
│       │   ├── pedidos.php                  # Gestión de pedidos
│       │   ├── estadisticas.php             # Estadísticas de la tienda
│       │   ├── configuracion.php            # Configuración de tienda
│       │   ├── categorias.php               # Categorías de productos
│       │   ├── galeria.php                  # Galería de fotos
│       │   └── cuentas_bancarias.php        # Cuentas bancarias
│       │
│       ├── middleware/
│       │   ├── auth_tienda.php              # Verificar sesión de tienda
│       │   └── verificar_plan.php           # Verificar límites del plan
│       │
│       └── controllers/
│           ├── ProductoController.php       # Lógica de productos
│           ├── PedidoController.php         # Lógica de pedidos
│           └── ConfiguracionController.php  # Lógica de configuración
│
├── frontend/
│   ├── registrar-tienda/                    # Registro público de tiendas
│   │   ├── index.php                        # Formulario de registro
│   │   ├── exito.php                        # Página de confirmación
│   │   └── assets/
│   │       ├── css/
│   │       │   └── registro.css
│   │       └── js/
│   │           └── registro.js
│   │
│   ├── panel-tienda/                        # Panel de administración de tienda
│   │   ├── index.php                        # Dashboard principal
│   │   ├── login.php                        # Login de tienda
│   │   ├── logout.php                       # Cerrar sesión
│   │   │
│   │   ├── productos/
│   │   │   ├── index.php                    # Lista de productos
│   │   │   ├── crear.php                    # Crear producto
│   │   │   ├── editar.php                   # Editar producto
│   │   │   └── categorias.php               # Gestionar categorías
│   │   │
│   │   ├── pedidos/
│   │   │   ├── index.php                    # Lista de pedidos
│   │   │   ├── detalle.php                  # Detalle de pedido
│   │   │   └── procesar.php                 # Cambiar estado
│   │   │
│   │   ├── configuracion/
│   │   │   ├── tienda.php                   # Datos de la tienda
│   │   │   ├── cuentas-bancarias.php        # Cuentas bancarias
│   │   │   ├── horarios.php                 # Horarios de atención
│   │   │   ├── galeria.php                  # Galería de fotos
│   │   │   ├── personalizacion.php          # Temas y colores (Premium)
│   │   │   └── plan.php                     # Información del plan
│   │   │
│   │   ├── estadisticas/
│   │   │   ├── index.php                    # Dashboard de estadísticas
│   │   │   ├── ventas.php                   # Reportes de ventas
│   │   │   └── productos.php                # Productos más vendidos
│   │   │
│   │   ├── componentes/
│   │   │   ├── header.php                   # Header del panel
│   │   │   ├── sidebar.php                  # Menú lateral
│   │   │   ├── footer.php                   # Footer
│   │   │   └── modales.php                  # Modales reutilizables
│   │   │
│   │   └── assets/
│   │       ├── css/
│   │       │   ├── panel.css                # Estilos del panel
│   │       │   └── dashboard.css            # Estilos del dashboard
│   │       └── js/
│   │           ├── panel.js                 # Scripts generales
│   │           ├── productos.js             # Scripts de productos
│   │           └── pedidos.js               # Scripts de pedidos
│   │
│   └── tienda.php                           # Vista pública de tienda (ya existe)
```

---

## 🗄️ Tablas de Base de Datos

### Tablas Principales:
1. **`tiendas`** - Información principal de cada tienda
2. **`tiendas_cuentas_bancarias`** - Cuentas bancarias para pagos
3. **`tiendas_categorias`** - Categorías de productos de la tienda
4. **`tiendas_horarios`** - Horarios de atención
5. **`tiendas_galeria`** - Galería de fotos de la tienda
6. **`tiendas_sucursales`** - Sucursales físicas
7. **`tiendas_tokens`** - Tokens de autenticación

### Tablas Modificadas:
- **`productos`** - Agregado campo `tienda_id`
- **`pedidos`** - Agregado campo `tienda_id`

---

## 🔐 Flujos de Autenticación

### 1. Registro de Nueva Tienda (Cliente)
```
URL: /frontend/registrar-tienda/index.php

Flujo:
1. Cliente llena formulario de registro
2. Se crea tienda con estado "pendiente"
3. Email de confirmación enviado
4. Admin debe aprobar la tienda
5. Tienda recibe email de activación
6. Puede acceder al panel
```

### 2. Login de Tienda
```
URL: /frontend/panel-tienda/login.php

Credenciales:
- Email: email@tienda.com
- Password: ********

Sesión:
$_SESSION['tienda_id'] = X
$_SESSION['tienda_nombre'] = "Nombre Tienda"
$_SESSION['tienda_plan'] = "premium"
```

### 3. Login de Admin
```
URL: /backend/admin/login.php (existente)

Puede gestionar todas las tiendas desde:
/backend/admin/tiendas/
```

---

## 🎯 Planes y Límites

### Plan Básico (S/ 29/mes)
- ✅ Hasta 50 productos
- ✅ 3 imágenes por producto
- ✅ 10 categorías
- ✅ Layout estándar
- ✅ 1 cuenta bancaria

### Plan Premium (S/ 79/mes)
- ✅ Productos ilimitados
- ✅ 10 imágenes por producto
- ✅ Categorías ilimitadas
- ✅ Personalización de tema y colores
- ✅ 3 layouts disponibles
- ✅ Múltiples cuentas bancarias
- ✅ Estadísticas avanzadas

---

## 🚀 Instalación

### Paso 1: Ejecutar Script SQL
```sql
-- Desde phpMyAdmin o MySQL CLI
source database/migrations/001_crear_sistema_tiendas.sql;
```

### Paso 2: Configurar Permisos
```bash
# Dar permisos de escritura a carpetas de uploads
chmod 755 frontend/panel-tienda/uploads
chmod 755 frontend/registrar-tienda/uploads
```

### Paso 3: Configurar Variables de Entorno
```php
// config/tiendas.php
define('TIENDA_UPLOAD_DIR', __DIR__ . '/../uploads/tiendas/');
define('TIENDA_MAX_FILE_SIZE', 5 * 1024 * 1024); // 5MB
```

---

## 📝 Próximos Pasos

### Fase 1: Registro y Autenticación (Semana 1)
- [ ] Crear formulario de registro público
- [ ] Implementar login de tienda
- [ ] Crear middleware de autenticación
- [ ] CRUD de tiendas en panel admin

### Fase 2: Dashboard y Productos (Semana 2)
- [ ] Dashboard con métricas
- [ ] CRUD completo de productos
- [ ] Subida de imágenes
- [ ] Gestión de categorías

### Fase 3: Pedidos y Configuración (Semana 3)
- [ ] Lista de pedidos
- [ ] Cambio de estados
- [ ] Configuración de tienda
- [ ] Cuentas bancarias

### Fase 4: Estadísticas y Premium (Semana 4)
- [ ] Reportes de ventas
- [ ] Gráficos de estadísticas
- [ ] Personalización de tema (Premium)
- [ ] Límites por plan

---

## 🔧 APIs Disponibles

### Backend Panel Tienda
```
POST   /backend/panel-tienda/api/auth.php?action=login
POST   /backend/panel-tienda/api/auth.php?action=logout
GET    /backend/panel-tienda/api/productos.php
POST   /backend/panel-tienda/api/productos.php
PUT    /backend/panel-tienda/api/productos.php
DELETE /backend/panel-tienda/api/productos.php
GET    /backend/panel-tienda/api/pedidos.php
PUT    /backend/panel-tienda/api/pedidos.php?action=cambiar_estado
GET    /backend/panel-tienda/api/estadisticas.php
```

---

## 👥 Roles y Permisos

### Administrador (Lyrium)
- ✅ Ver todas las tiendas
- ✅ Crear/editar/eliminar tiendas
- ✅ Aprobar/rechazar tiendas
- ✅ Asignar planes
- ✅ Ver todas las estadísticas

### Tienda
- ✅ Ver solo SUS productos
- ✅ Ver solo SUS pedidos
- ✅ Editar SU configuración
- ✅ Ver SUS estadísticas
- ❌ No puede ver otras tiendas

---

## 📞 Soporte

Para dudas o problemas:
- Email: soporte@lyrium.com
- Documentación: /docs/tiendas/

---

**Versión:** 1.0  
**Fecha:** 2026-01-09  
**Autor:** Lyrium Development Team
