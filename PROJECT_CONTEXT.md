# Workshop CRM - Contexto del Proyecto

## 📋 Descripción General

Sistema CRM para gestión de vehículos e ITVs de un taller mecánico. Migrado desde una aplicación vanilla PHP/JavaScript a Laravel 11 con Livewire, Alpine.js y Tailwind CSS.

### Aplicación Original
- **Archivo fuente**: `taller.php` (single-page application)
- **Tecnología**: Vanilla PHP + JavaScript + localStorage
- **Funcionalidades**: Dashboard con estadísticas, lista de vehículos, seguimiento de fechas ITV, integración WhatsApp

### Aplicación Nueva
- **Framework**: Laravel 11
- **Frontend**: Livewire 4.1.4 + Alpine.js + Tailwind CSS
- **Autenticación**: Laravel Breeze (Alpine stack)
- **Base de datos**: MySQL en Siteground
- **Hosting**: Siteground shared hosting (sin acceso SSH)

---

## 🛠️ Stack Tecnológico

### Backend
- PHP 8.4.18
- Laravel 11 (versión 12.51.0)
- Composer 2.9.5
- Livewire 4.1.4

### Frontend
- Alpine.js
- Tailwind CSS
- Vite 7.3.1 (bundler)
- Fuente: Poppins (Google Fonts)
- Paleta de colores: Zinc

### Base de Datos
- MySQL 5.7+
- Hosting: Siteground
- Gestión: phpMyAdmin

### Herramientas de Desarrollo
- Node.js 20.17.0 (advertencia: Vite recomienda 20.19+ o 22.12+)
- npm
- Laragon (entorno de desarrollo local)

---

## 📁 Estructura del Proyecto

```
workshop-crm/
├── app/
│   ├── Http/
│   │   └── Controllers/
│   │       └── Auth/
│   │           └── AuthenticatedSessionController.php
│   ├── Livewire/
│   │   ├── Dashboard.php          # Página principal con estadísticas
│   │   ├── VehicleList.php        # Lista y gestión de vehículos
│   │   └── VehicleForm.php        # Formulario crear/editar vehículo
│   ├── Models/
│   │   ├── User.php
│   │   └── Vehicle.php            # Modelo con scopes y helpers
│   └── Providers/
│       └── AppServiceProvider.php # Configuración de public_html
├── bootstrap/
│   └── app.php                    # Configuración de aplicación
├── config/
│   ├── app.php
│   ├── database.php
│   └── filesystems.php
├── database/
│   └── migrations/
│       └── xxxx_create_vehicles_table.php
├── public_html/                   # ⚠️ Carpeta pública (NO public)
│   ├── build/                     # Assets compilados por Vite
│   │   ├── .vite/
│   │   │   └── manifest.json
│   │   └── assets/
│   │       ├── app-OXIHS5rZ.css
│   │       └── app-CBbTb_k3.js
│   └── index.php                  # Entry point
├── resources/
│   ├── css/
│   │   └── app.css
│   ├── js/
│   │   ├── app.js
│   │   └── bootstrap.js
│   └── views/
│       ├── layouts/
│       │   ├── app.blade.php      # Layout principal
│       │   └── guest.blade.php    # Layout login/register
│       ├── livewire/
│       │   ├── dashboard.blade.php
│       │   ├── vehicle-list.blade.php
│       │   └── vehicle-form.blade.php
│       └── components/
│           └── notifications.blade.php  # Sistema de notificaciones Alpine.js
├── routes/
│   ├── web.php                    # Rutas principales
│   └── auth.php                   # Rutas de autenticación
├── storage/
│   ├── app/
│   ├── framework/
│   └── logs/
├── .env                           # Configuración local
├── database.sql                   # SQL completo para importar en Siteground
├── package.json
├── vite.config.js                 # ⚠️ Configurado para public_html
└── composer.json
```

---

## ⚙️ Configuraciones Importantes

### 1. Carpeta Pública: `public_html` (NO `public`)

**Motivo**: Siteground requiere que la carpeta pública se llame `public_html`.

#### Archivos modificados:

**`app/Providers/AppServiceProvider.php`**:
```php
public function register(): void
{
    // Configurar public_html como carpeta pública
    $this->app->bind('path.public', function() {
        return base_path('public_html');
    });

    // Override del public_path para usar public_html
    $this->app->usePublicPath(base_path('public_html'));
}
```

**`vite.config.js`**:
```javascript
export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
            publicDirectory: 'public_html',  // ⚠️ Importante
        }),
    ],
    build: {
        outDir: 'public_html/build',         // ⚠️ Importante
        manifest: true,
    },
});
```

### 2. Base de Datos

**Tabla `vehicles`**:
```sql
CREATE TABLE `vehicles` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `car` varchar(255) NOT NULL,
  `plate` varchar(255) NOT NULL,
  `itv_date` date NOT NULL,
  `notes` text,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `vehicles_plate_unique` (`plate`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

**Tabla `users`**:
- Usuario por defecto: `admin@taller.com`
- Password: `password` (hash bcrypt incluido en database.sql)

**Credenciales de producción** (configurar en `.env`):
```env
DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=desar652_workshop_crm
DB_USERNAME=desar652_taller
DB_PASSWORD=[CONFIGURAR EN PRODUCCIÓN]
```

### 3. Navegación SPA Deshabilitada

**Motivo**: Alpine.js no tiene el plugin Navigate instalado y causaba errores.

- ❌ NO usar `wire:navigate` en enlaces
- ❌ NO usar `navigate: true` en redirects de Livewire
- ✅ Usar navegación normal de Laravel

---

## 🎨 Diseño y Estilos

### Colores (Paleta Zinc)
- Background: `bg-zinc-50`
- Texto principal: `text-zinc-900`
- Texto secundario: `text-zinc-500`, `text-zinc-400`
- Botones primarios: `bg-zinc-900 text-white`
- Bordes: `border-zinc-100`, `border-zinc-200`

### Fuente
- **Poppins** de Google Fonts
- Pesos: 300, 400, 500, 600, 700

### Componentes Visuales
- **Sidebar**: Barra lateral con navegación (desktop)
- **Notificaciones**: Sistema de toasts con Alpine.js
- **Modales**: Confirmación de eliminación con Alpine.js
- **Tarjetas**: Rounded-2xl con sombras sutiles
- **Estados ITV**:
  - 🔴 **Expirado**: `bg-red-50 text-red-700`
  - 🟡 **Urgente** (≤7 días): `bg-amber-50 text-amber-700`
  - 🟠 **Advertencia** (≤30 días): `bg-orange-50 text-orange-700`
  - 🟢 **Válido** (>30 días): `bg-emerald-50 text-emerald-700`

---

## 🔧 Funcionalidades Implementadas

### 1. Dashboard (`app/Livewire/Dashboard.php`)
- Estadísticas en tiempo real:
  - Total de vehículos
  - ITVs expiradas
  - ITVs próximas (≤30 días)
  - ITVs válidas
- Lista de vehículos expirados
- Lista de vehículos con advertencia
- Botón de notificación WhatsApp (https://chatly.es)

### 2. Lista de Vehículos (`app/Livewire/VehicleList.php`)
- Búsqueda en vivo (por nombre, matrícula o coche)
- Tabla con:
  - Propietario y teléfono
  - Vehículo y matrícula
  - Fecha ITV y días restantes
  - Estado (badge con color)
  - Acciones (editar, eliminar, WhatsApp)
- Ordenación por fecha ITV (más próximas primero)
- Modal de confirmación para eliminar
- Notificaciones toast al crear/actualizar/eliminar

### 3. Formulario de Vehículo (`app/Livewire/VehicleForm.php`)
- Crear nuevo vehículo
- Editar vehículo existente
- Validación en tiempo real:
  - Todos los campos requeridos
  - Matrícula única (excepto al editar el mismo)
  - Fecha ITV formato válido
- Auto-uppercase en matrícula
- Redirección tras guardar

### 4. Notificaciones WhatsApp
- URL: `https://chatly.es`
- Parámetros enviados:
  - `phone`: Teléfono del propietario
  - `name`: Nombre del propietario
  - `car`: Marca y modelo
  - `plate`: Matrícula
  - `itv_date`: Fecha ITV formateada

### 5. Autenticación (Laravel Breeze)
- Login
- Register
- Password reset
- Profile management
- Logout

---

## 🐛 Problemas Resueltos

### 1. Vite Manifest Not Found
**Problema**: Laravel buscaba manifest en `/public/build/` en lugar de `/public_html/build/`

**Solución**:
- Configurar `usePublicPath()` en `AppServiceProvider`
- Configurar `publicDirectory` y `outDir` en `vite.config.js`

### 2. Alpine.navigate is not a function
**Problema**: Livewire intentaba usar navigate de Alpine que no estaba instalado

**Solución**:
- Eliminar `wire:navigate` de todos los enlaces en `layouts/app.blade.php`
- Eliminar `navigate: true` de redirects en `VehicleForm.php`

### 3. A void method must not return a value
**Problema**: PHP 8.4 es estricto con tipos de retorno `void`

**Solución**:
- Cambiar `return $this->redirect()` a `$this->redirect()`
- Los métodos `void` no deben usar `return` con valor

### 4. Duplicate Entry Error en SQL Import
**Problema**: Al importar database.sql, error de clave duplicada en users

**Solución**:
```sql
INSERT INTO `users` (...)
SELECT * FROM (SELECT ...) AS tmp
WHERE NOT EXISTS (
    SELECT email FROM `users` WHERE `email` = 'admin@taller.com'
) LIMIT 1;
```

---

## 📦 Despliegue en Siteground

### Requisitos Previos
1. Base de datos MySQL creada en Siteground
2. Acceso FTP o File Manager
3. phpMyAdmin disponible
4. Document root configurado a `public_html`

### Pasos de Despliegue

#### 1. Compilar Assets Localmente
```bash
cd workshop-crm
npm install
npm run build
```

Esto genera:
- `public_html/build/.vite/manifest.json`
- `public_html/build/assets/app-OXIHS5rZ.css`
- `public_html/build/assets/app-CBbTb_k3.js`

#### 2. Subir Archivos por FTP
Subir toda la carpeta `workshop-crm/` EXCEPTO:
- `node_modules/`
- `.git/`
- `storage/logs/*` (crear vacío)
- `.env` (crear manualmente en servidor)

#### 3. Configurar `.env` en Servidor
Crear archivo `.env` en la raíz con:
```env
APP_NAME="Workshop CRM"
APP_ENV=production
APP_KEY=base64:GENERAR_CON_php_artisan_key:generate
APP_DEBUG=false
APP_TIMEZONE=UTC
APP_URL=https://workshop-crm.desarrolloappsur.es

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=desar652_workshop_crm
DB_USERNAME=desar652_taller
DB_PASSWORD=[TU_PASSWORD]

SESSION_DRIVER=database
```

**Importante**: Generar `APP_KEY` ejecutando `php artisan key:generate` localmente y copiar.

#### 4. Importar Base de Datos
1. Ir a phpMyAdmin en Siteground
2. Seleccionar base de datos `desar652_workshop_crm`
3. Ir a pestaña "Importar"
4. Subir `database.sql`
5. Ejecutar importación

#### 5. Configurar Permisos
```bash
chmod 755 public_html
chmod -R 775 storage
chmod -R 775 bootstrap/cache
```

#### 6. Limpiar Cache
Opción A - Con SSH (si está disponible):
```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
```

Opción B - Sin SSH:
1. Subir `public_html/clear-cache.php`
2. Acceder a `https://workshop-crm.desarrolloappsur.es/clear-cache.php?key=taller2024`
3. **ELIMINAR** `clear-cache.php` inmediatamente después

#### 7. Verificar Funcionamiento
1. Acceder a `https://workshop-crm.desarrolloappsur.es/`
2. Login con: `admin@taller.com` / `password`
3. Probar crear un vehículo
4. Verificar que se guarde en la base de datos

---

## 🔐 Credenciales

### Usuario Admin
- **Email**: admin@taller.com
- **Password**: password
- **Cambiar** después del primer login

### URLs
- **Producción**: https://workshop-crm.desarrolloappsur.es
- **WhatsApp**: https://chatly.es
- **Hosting**: Siteground

### Base de Datos
- **Database**: desar652_workshop_crm
- **Usuario**: desar652_taller
- **Host**: localhost

---

## 📝 Modelo de Datos

### Vehicle Model (`app/Models/Vehicle.php`)

#### Campos
- `id`: bigint UNSIGNED (PK, auto_increment)
- `name`: string (nombre del propietario)
- `phone`: string (teléfono)
- `car`: string (marca y modelo)
- `plate`: string (matrícula, UNIQUE)
- `itv_date`: date (fecha vencimiento ITV)
- `notes`: text (nullable, notas adicionales)
- `created_at`: timestamp
- `updated_at`: timestamp

#### Métodos Útiles
```php
// Cálculos de días
$vehicle->daysUntilExpiration()  // int: días hasta vencimiento (negativo si expiró)

// Verificaciones booleanas
$vehicle->isExpired()    // bool: ITV expirada
$vehicle->isUrgent()     // bool: expira en ≤7 días o expirada
$vehicle->isWarning()    // bool: expira en ≤30 días
$vehicle->isValid()      // bool: expira en >30 días

// Estado como string
$vehicle->getStatus()    // string: 'expired'|'urgent'|'warning'|'valid'

// Formato
$vehicle->getFormattedItvDate()  // string: DD/MM/YYYY
```

#### Scopes
```php
Vehicle::expired()->get()           // ITVs expiradas
Vehicle::urgent()->get()            // ITVs urgentes (≤7 días)
Vehicle::warning()->get()           // ITVs advertencia (≤30 días)
Vehicle::valid()->get()             // ITVs válidas (>30 días)
Vehicle::search('ABC123')->get()    // Buscar por nombre/matrícula/coche
Vehicle::orderByItvDate()->get()    // Ordenar por fecha (más próximas primero)
```

---

## 🚀 Comandos Útiles

### Desarrollo Local
```bash
# Instalar dependencias
composer install
npm install

# Compilar assets (desarrollo)
npm run dev

# Compilar assets (producción)
npm run build

# Ejecutar migraciones
php artisan migrate

# Crear usuario admin
php artisan tinker
> User::create(['name' => 'Admin', 'email' => 'admin@taller.com', 'password' => bcrypt('password')]);

# Limpiar cache
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# Ver logs
tail -f storage/logs/laravel.log
```

### Testing
```bash
# Crear vehículo de prueba
php artisan tinker
> Vehicle::create(['name' => 'Juan Pérez', 'phone' => '600123456', 'car' => 'Seat Ibiza', 'plate' => 'ABC1234', 'itv_date' => '2026-03-15', 'notes' => 'Prueba']);
```

---

## 📊 Estado Actual del Proyecto

### ✅ Completado
- [x] Migración de diseño desde taller.php
- [x] Configuración de Laravel 11 + Livewire
- [x] Sistema de autenticación (Breeze)
- [x] CRUD completo de vehículos
- [x] Dashboard con estadísticas
- [x] Sistema de notificaciones
- [x] Integración WhatsApp
- [x] Búsqueda en tiempo real
- [x] Configuración para Siteground (public_html)
- [x] Compilación de assets con Vite
- [x] Base de datos SQL lista para importar
- [x] Responsividad mobile

### 🔄 Pendiente / Mejoras Futuras
- [ ] Actualizar Node.js a versión 20.19+ o 22.12+
- [ ] Implementar filtros avanzados en lista de vehículos
- [ ] Agregar paginación (actualmente muestra todos)
- [ ] Sistema de recordatorios automáticos por email
- [ ] Exportar lista de vehículos a PDF/Excel
- [ ] Historial de ITVs anteriores
- [ ] Multi-usuario con roles (admin, mecánico, recepcionista)
- [ ] API REST para integración externa
- [ ] Tests automatizados (PHPUnit, Pest)
- [ ] CI/CD pipeline
- [ ] Logs de auditoría

---

## 🆘 Troubleshooting

### Error: "Vite manifest not found"
**Causa**: Laravel busca manifest en ubicación incorrecta

**Solución**:
1. Verificar `app/Providers/AppServiceProvider.php` tiene `usePublicPath()`
2. Verificar `vite.config.js` tiene `publicDirectory` y `outDir` correctos
3. Recompilar assets: `npm run build`
4. Limpiar cache (ver sección de despliegue)

### Error: "Alpine.navigate is not a function"
**Causa**: Uso de `wire:navigate` sin plugin instalado

**Solución**:
1. NO usar `wire:navigate` en enlaces
2. NO usar `navigate: true` en Livewire redirects
3. Verificar que `resources/views/layouts/app.blade.php` no tiene `wire:navigate`

### Error: "SQLSTATE[HY000] [2002] Connection refused"
**Causa**: Credenciales de base de datos incorrectas

**Solución**:
1. Verificar `.env` tiene credenciales correctas
2. Verificar que base de datos existe en Siteground
3. Probar conexión desde phpMyAdmin

### Error: "The stream or file could not be opened"
**Causa**: Permisos incorrectos en storage/

**Solución**:
```bash
chmod -R 775 storage
chmod -R 775 bootstrap/cache
```

### No se guardan vehículos
**Causas posibles**:
1. Base de datos no importada → Importar `database.sql`
2. Tabla `vehicles` no existe → Verificar en phpMyAdmin
3. Error de validación → Ver consola del navegador (F12)
4. Error de servidor → Ver logs en `storage/logs/laravel.log`

---

## 📞 Contacto y URLs

### Producción
- **URL**: https://workshop-crm.desarrolloappsur.es
- **Hosting**: Siteground
- **Panel**: https://siteground.com/

### Repositorio
- **Local**: `c:\Users\Dubi\Documents\Proyectos automatizaciones\taller\workshop-crm\`
- **Git**: [CONFIGURAR SI SE USA]

### Servicios Externos
- **WhatsApp**: https://chatly.es

---

## 🎯 Próximos Pasos Sugeridos

1. **Seguridad**:
   - Cambiar password de admin
   - Habilitar HTTPS (SSL)
   - Configurar CORS si es necesario
   - Revisar permisos de archivos

2. **Optimización**:
   - Implementar cache de queries
   - Optimizar imágenes (si se añaden)
   - Configurar CDN para assets estáticos
   - Minificar y comprimir assets

3. **Funcionalidades**:
   - Sistema de recordatorios automáticos
   - Exportación de datos
   - Estadísticas avanzadas
   - Historial de cambios

4. **Mantenimiento**:
   - Backups automáticos de base de datos
   - Monitoreo de errores (Sentry, Bugsnag)
   - Actualización de dependencias
   - Tests automatizados

---

## 📚 Recursos y Documentación

### Laravel
- [Laravel 11 Docs](https://laravel.com/docs/11.x)
- [Livewire 4 Docs](https://livewire.laravel.com/docs)
- [Laravel Breeze](https://laravel.com/docs/11.x/starter-kits#laravel-breeze)

### Frontend
- [Alpine.js](https://alpinejs.dev/)
- [Tailwind CSS](https://tailwindcss.com/docs)
- [Vite](https://vitejs.dev/)

### Hosting
- [Siteground Laravel Hosting](https://www.siteground.com/tutorials/laravel/)
- [Deploying Laravel to Shared Hosting](https://laracasts.com/discuss/channels/general-discussion/deploying-laravel-to-shared-hosting)

---

## 📄 Licencia

[DEFINIR LICENCIA DEL PROYECTO]

---

**Última actualización**: 2026-02-13
**Versión Laravel**: 11 (12.51.0)
**Versión PHP**: 8.4.18
**Estado**: ✅ Funcionando en producción
