# Workshop CRM - Referencia Rápida

## 🚀 Quick Start

### Desarrollo Local
```bash
# Clonar/ubicar proyecto
cd c:\Users\Dubi\Documents\Proyectos automatizaciones\taller\workshop-crm

# Instalar dependencias
composer install
npm install

# Configurar .env
cp .env.example .env
php artisan key:generate

# Ejecutar migraciones
php artisan migrate

# Compilar assets
npm run dev  # desarrollo
npm run build  # producción

# Servir aplicación
php artisan serve
```

### Despliegue Rápido Siteground
```bash
# 1. Compilar assets
npm run build

# 2. Subir archivos por FTP (excepto node_modules, .git)

# 3. Importar database.sql en phpMyAdmin

# 4. Crear .env en servidor

# 5. Limpiar cache
https://workshop-crm.desarrolloappsur.es/clear-cache.php?key=taller2024

# 6. ELIMINAR clear-cache.php
```

---

## ⚠️ Configuraciones Críticas

### ❗ NUNCA cambiar esto
- Carpeta pública: `public_html` (NO `public`)
- No usar `wire:navigate` en enlaces
- No usar `navigate: true` en Livewire
- No usar `return` en métodos `void`

### AppServiceProvider.php
```php
public function register(): void
{
    $this->app->bind('path.public', function() {
        return base_path('public_html');
    });
    $this->app->usePublicPath(base_path('public_html'));
}
```

### vite.config.js
```javascript
export default defineConfig({
    plugins: [
        laravel({
            publicDirectory: 'public_html',
        }),
    ],
    build: {
        outDir: 'public_html/build',
    },
});
```

---

## 📁 Archivos Clave

| Archivo | Propósito |
|---------|-----------|
| `app/Livewire/Dashboard.php` | Página principal con estadísticas |
| `app/Livewire/VehicleList.php` | Lista de vehículos con búsqueda |
| `app/Livewire/VehicleForm.php` | Crear/editar vehículos |
| `app/Models/Vehicle.php` | Modelo con scopes y helpers |
| `app/Providers/AppServiceProvider.php` | Configuración public_html |
| `resources/views/layouts/app.blade.php` | Layout principal |
| `resources/views/components/notifications.blade.php` | Sistema notificaciones |
| `vite.config.js` | Configuración Vite |
| `database.sql` | SQL para importar |

---

## 🎨 Componentes Livewire

### Dashboard
```php
// app/Livewire/Dashboard.php
- Computed properties para stats
- sendNotification($vehicle) → WhatsApp
```

### VehicleList
```php
// app/Livewire/VehicleList.php
- $search → búsqueda en vivo
- delete($id) → eliminar vehículo
- sendNotification($vehicle) → WhatsApp
```

### VehicleForm
```php
// app/Livewire/VehicleForm.php
- mount($vehicleId = null) → cargar datos
- save() → crear/actualizar
- cancel() → volver a lista
```

---

## 🗄️ Modelo Vehicle

### Scopes
```php
Vehicle::expired()->get()        // ITVs expiradas
Vehicle::urgent()->get()         // ≤7 días
Vehicle::warning()->get()        // ≤30 días
Vehicle::valid()->get()          // >30 días
Vehicle::search('ABC')->get()    // Buscar
Vehicle::orderByItvDate()->get() // Ordenar
```

### Métodos
```php
$vehicle->daysUntilExpiration()  // int
$vehicle->isExpired()            // bool
$vehicle->isUrgent()             // bool
$vehicle->isWarning()            // bool
$vehicle->isValid()              // bool
$vehicle->getStatus()            // string
$vehicle->getFormattedItvDate()  // DD/MM/YYYY
```

---

## 🎯 Estados ITV

| Estado | Condición | Color | Badge |
|--------|-----------|-------|-------|
| Expirado | < 0 días | Rojo | `bg-red-50 text-red-700` |
| Urgente | ≤ 7 días | Amarillo | `bg-amber-50 text-amber-700` |
| Advertencia | ≤ 30 días | Naranja | `bg-orange-50 text-orange-700` |
| Válido | > 30 días | Verde | `bg-emerald-50 text-emerald-700` |

---

## 🔐 Credenciales

### Login
- **Email**: admin@taller.com
- **Password**: password

### Base de Datos (Producción)
```env
DB_DATABASE=desar652_workshop_crm
DB_USERNAME=desar652_taller
DB_PASSWORD=[CONFIGURAR]
```

### URLs
- **App**: https://workshop-crm.desarrolloappsur.es
- **WhatsApp**: https://chatly.es

---

## 🐛 Errores Comunes

### "Vite manifest not found"
```bash
# 1. Verificar AppServiceProvider y vite.config.js
# 2. Recompilar
npm run build
# 3. Limpiar cache
php artisan config:clear
```

### "Alpine.navigate is not a function"
```php
// ❌ NO hacer:
wire:navigate
navigate: true

// ✅ Hacer:
// Enlaces normales
// Redirects sin navigate
```

### "A void method must not return a value"
```php
// ❌ NO hacer:
public function save(): void {
    return $this->redirect(...);
}

// ✅ Hacer:
public function save(): void {
    $this->redirect(...);
}
```

### No se guardan vehículos
1. ¿Base de datos importada? → phpMyAdmin
2. ¿Credenciales correctas? → .env
3. ¿Error en consola? → F12
4. ¿Error en logs? → storage/logs/laravel.log

---

## 📝 Comandos Útiles

```bash
# Limpiar cache
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# Ver logs
tail -f storage/logs/laravel.log

# Compilar assets
npm run dev    # desarrollo con watch
npm run build  # producción

# Tinker (consola interactiva)
php artisan tinker
> Vehicle::count()
> User::first()
```

---

## 📦 Estructura de Archivos para Subir

```
workshop-crm/
├── app/              ✅ Subir
├── bootstrap/        ✅ Subir
├── config/           ✅ Subir
├── database/         ✅ Subir (sin .sqlite)
├── public_html/      ✅ Subir (con build/)
├── resources/        ✅ Subir
├── routes/           ✅ Subir
├── storage/          ✅ Subir (vaciar logs/)
├── vendor/           ✅ Subir
├── .env              ⚠️  Crear manualmente en servidor
├── artisan           ✅ Subir
├── composer.json     ✅ Subir
├── composer.lock     ✅ Subir
├── database.sql      📋 Importar en phpMyAdmin
├── node_modules/     ❌ NO subir
├── .git/             ❌ NO subir
└── package.json      ℹ️  Opcional
```

---

## 🎨 Paleta de Colores

```css
/* Backgrounds */
bg-zinc-50       /* Fondo general */
bg-zinc-100      /* Hover, secundario */
bg-white         /* Tarjetas, sidebar */

/* Textos */
text-zinc-900    /* Principal */
text-zinc-700    /* Secundario */
text-zinc-500    /* Placeholder */
text-zinc-400    /* Deshabilitado */

/* Botones */
bg-zinc-900 text-white  /* Primario */
bg-zinc-100 text-zinc-700  /* Secundario */

/* Bordes */
border-zinc-100  /* Suave */
border-zinc-200  /* Visible */
```

---

## 🚨 Checklist de Despliegue

- [ ] Compilar assets: `npm run build`
- [ ] Verificar `public_html/build/` existe
- [ ] Subir archivos por FTP (excepto node_modules, .git)
- [ ] Crear `.env` en servidor
- [ ] Configurar credenciales DB en `.env`
- [ ] Importar `database.sql` en phpMyAdmin
- [ ] Verificar permisos: `chmod -R 775 storage bootstrap/cache`
- [ ] Subir y ejecutar `clear-cache.php`
- [ ] **ELIMINAR** `clear-cache.php` después de usar
- [ ] Probar login: admin@taller.com / password
- [ ] Probar crear vehículo
- [ ] Verificar notificaciones funcionan
- [ ] Cambiar password de admin

---

## 📞 Soporte

### Logs
```bash
# Laravel logs
storage/logs/laravel.log

# Consola del navegador
F12 → Console

# Errores PHP
Ver error_log en Siteground
```

### Debugging
```php
// En cualquier archivo .php
dd($variable);        // Dump and die
dump($variable);      // Dump y continuar
logger('mensaje');    // Log a storage/logs

// En Blade
@dump($variable)
@dd($variable)
```

---

**Versión**: Laravel 11
**Fecha**: 2026-02-13
**Estado**: ✅ Producción
