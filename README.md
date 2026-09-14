# FoodFlow — Sistema de Pedidos para Restaurante

Proyecto en **CodeIgniter 3**, **MySQL**, **Bootstrap 5**, **JavaScript (AJAX + SweetAlert2)**, siguiendo el plan de 10 semanas: autenticación por roles, gestión de menú, mesas, toma de pedidos, panel de cocina, historial y reportes básicos.

## ✅ Este paquete ya viene completo

Incluye el **núcleo de CodeIgniter 3** (`system/`, `index.php`) junto con la aplicación **FoodFlow** (`application/`, `assets/`). No necesitas descargar nada más del framework: solo copiar la carpeta a tu servidor e importar la base de datos.

## 1. Requisitos

- XAMPP (PHP 7.4 recomendado, también funciona con 8.0/8.1)
- Navegador web

## 2. Instalación paso a paso

### Paso 1 — Copiar el proyecto
1. Copia toda esta carpeta dentro de `C:\xampp\htdocs\` (o tu carpeta de XAMPP) y renómbrala a `foodflow` si no se llama así.

Estructura esperada:
```
htdocs/foodflow/
├── application/      <- FoodFlow (controllers, models, views, config, core)
├── assets/           <- css/js de FoodFlow
├── system/           <- núcleo oficial de CodeIgniter 3
├── index.php
├── .htaccess
├── generar_hash.php
└── foodflow.sql
```

### Paso 2 — Base de datos
1. Abre **phpMyAdmin** (`http://localhost/phpmyadmin`).
2. Ve a la pestaña **Importar** → selecciona el archivo `foodflow.sql` de esta carpeta → **Continuar**.
   - Esto crea la base `foodflow`, todas las tablas y datos de ejemplo (usuarios, categorías, menú, mesas).

### Paso 3 — Configuración
1. Abre `application/config/database.php` y confirma usuario/clave de MySQL (por defecto XAMPP: usuario `root`, clave vacía).
2. Abre `application/config/config.php` y ajusta `base_url` si tu carpeta no se llama `foodflow`:
   ```php
   $config['base_url'] = 'http://localhost/foodflow/';
   ```

### Paso 4 — Probar
Abre `http://localhost/foodflow/` en el navegador. Deberías ver el login de **FoodFlow**.

## 3. Usuarios de prueba

| Usuario  | Contraseña | Rol     |
|----------|-----------|---------|
| admin    | 123456    | admin   |
| mesero   | 123456    | mesero  |
| cocina   | 123456    | cocina  |

Para crear más usuarios: usa `generar_hash.php` (ábrelo en el navegador, ej. `http://localhost/foodflow/generar_hash.php?pass=miclave`) para generar un hash, e insértalo manualmente en la tabla `usuarios` desde phpMyAdmin. **Elimina ese archivo cuando termines**, por seguridad.

## 4. Funcionalidades incluidas

- **Login con roles** (admin / mesero / cocina) y restricción de acceso por controlador.
- **Menú** (solo admin): CRUD completo de productos, categorías, disponibilidad.
- **Mesas**: CRUD, estado visual libre/ocupada con colores.
- **Toma de pedidos** (mesero): selección de productos por mesa, cálculo de total en tiempo real vía AJAX, sin recargar la página.
- **Cocina**: tablero de pedidos pendientes → en preparación → listo → entregado.
- **Cierre de pedidos**: marca como pagado y libera la mesa automáticamente.
- **Historial**: búsqueda por fecha y número de mesa.
- **Reportes** (admin): ingresos por rango de fechas, ventas por día, productos más vendidos.
- **UX**: SweetAlert2 para confirmaciones (eliminar producto, cerrar pedido), validaciones con CodeIgniter Form Validation, protección CSRF activada, consultas parametrizadas con Query Builder (previene SQL Injection) y `html_escape()` en las vistas (previene XSS).

## 5. Posibles mejoras (semana 10 y siguientes)

- CRUD de usuarios desde el panel admin (hoy se gestionan por SQL/phpMyAdmin).
- Exportar reportes a Excel/PDF.
- Notificaciones sonoras reales en cocina (WebSockets o Server-Sent Events en vez de recarga cada 20s).
- Subida de imágenes para los productos del menú.

## 6. Marca

El sistema está personalizado con el nombre **FoodFlow** en el login, la barra lateral y el color institucional (naranja `#E85D04`). Puedes cambiar el nombre buscando "FoodFlow" en `application/views/` y `README.md`.
