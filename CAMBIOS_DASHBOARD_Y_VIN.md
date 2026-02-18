# Cambios Implementados - Dashboard y Número de Bastidor

**Fecha**: 17/02/2026
**Versión**: 2.1

---

## 📋 Resumen de Cambios

Se han implementado 3 mejoras principales:

1. ✅ **Añadido título "Citas"** en el dashboard
2. ✅ **Cambiado "Atención Requerida" por "ITV Caducadas"**
3. ✅ **Añadido campo Número de Bastidor (VIN)** en vehículos

---

## 📁 Archivos Modificados

### 1. Dashboard - Título "Citas"

#### `resources/views/livewire/dashboard.blade.php`
**Cambio**: Añadido título y descripción antes de la sección de calendarios de citas.

```blade
{{-- Sección de Citas --}}
<div class="mb-4">
    <h3 class="text-2xl font-bold text-zinc-900">Citas</h3>
    <p class="text-zinc-500 mt-1">Gestión de citas del taller</p>
</div>
```

**Ubicación**: Línea ~67-71 (antes de los calendarios)

---

### 2. Cambio "Atención Requerida" → "ITV Caducadas"

#### `resources/views/livewire/dashboard.blade.php`
**Cambio**: Actualizado el título de la sección de vehículos con ITV caducada.

```blade
{{-- ITV Caducadas --}}
<div class="space-y-4">
    <h3 class="font-semibold text-lg flex items-center gap-2">
        <div class="w-2 h-2 rounded-full bg-red-500"></div>
        ITV Caducadas
    </h3>
```

**Ubicación**: Línea ~231-236

---

### 3. Añadido Número de Bastidor (VIN)

#### A. `app/Models/Vehicle.php`
**Cambio**: Añadido `'vin'` al array `$fillable`.

```php
protected $fillable = [
    'name',
    'phone',
    'car',
    'plate',
    'vin',        // ← NUEVO
    'itv_date',
    'notes',
];
```

#### B. `app/Livewire/VehicleForm.php`
**Cambios realizados**:

1. Añadida propiedad con validación:
```php
#[Validate('nullable|string|max:17')]
public string $vin = '';
```

2. Actualizado método `mount()` para cargar el VIN:
```php
'vin' => $vehicle->vin ?? '',
```

3. Actualizada validación en ambos métodos `save()` y `saveAndCreateAppointment()`:
```php
'vin' => 'nullable|string|max:17',
```

4. Añadido VIN al array de datos (convertido a mayúsculas):
```php
'vin' => strtoupper($this->vin),
```

#### C. `resources/views/livewire/vehicle-form.blade.php`
**Cambio**: Añadido campo de entrada para el VIN entre "Matrícula" y "Fecha ITV".

```blade
{{-- Número de Bastidor (VIN) --}}
<div class="space-y-2">
    <label for="vin" class="text-sm font-medium text-zinc-700">Número de Bastidor (VIN)</label>
    <input
        wire:model="vin"
        type="text"
        id="vin"
        maxlength="17"
        class="w-full p-3 bg-zinc-50 border border-zinc-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-zinc-900/10 outline-none transition-all font-mono uppercase @error('vin') border-red-500 @enderror"
        placeholder="Opcional - máx. 17 caracteres"
    />
    @error('vin')
        <p class="text-xs text-red-600">{{ $message }}</p>
    @enderror
</div>
```

**Características**:
- ✅ Campo opcional (nullable)
- ✅ Máximo 17 caracteres (estándar VIN)
- ✅ Convertido automáticamente a mayúsculas
- ✅ Fuente monoespaciada para mejor legibilidad
- ✅ Validación en tiempo real con Livewire

---

## 🗄️ Base de Datos - Añadir Columna VIN

### Opción 1: Ejecutar SQL en phpMyAdmin (SiteGround)

1. Acceder a **phpMyAdmin** en SiteGround
2. Seleccionar la base de datos del proyecto
3. Ir a pestaña **SQL**
4. Ejecutar el contenido del archivo `ADD_VIN_COLUMN.sql`:

```sql
ALTER TABLE `vehicles`
ADD COLUMN `vin` VARCHAR(17) NULL DEFAULT NULL AFTER `plate`;
```

5. Verificar con:
```sql
DESCRIBE vehicles;
```

### Opción 2: Ejecutar Migración (Si tienes SSH)

```bash
cd /ruta/al/proyecto
php artisan migrate
```

---

## 📤 Archivos a Subir por SFTP

Si tienes SFTP automático, los archivos ya están sincronizados. Si no:

```
app/Models/Vehicle.php
app/Livewire/VehicleForm.php
resources/views/livewire/dashboard.blade.php
resources/views/livewire/vehicle-form.blade.php
database/migrations/2026_02_17_120000_add_vin_to_vehicles_table.php (opcional)
```

---

## 🚀 Pasos de Despliegue

### 1. Actualizar Base de Datos
Ejecutar el script SQL `ADD_VIN_COLUMN.sql` en phpMyAdmin.

### 2. Subir Archivos (si no tienes SFTP automático)
Subir los archivos modificados listados arriba.

### 3. Limpiar Caché
Ejecutar en servidor o usar `clear-cache.php`:

```bash
php artisan view:clear
php artisan cache:clear
php artisan config:clear
```

### 4. Verificar Cambios
- ✅ Dashboard muestra título "Citas"
- ✅ Sección "ITV Caducadas" (en lugar de "Atención Requerida")
- ✅ Formulario de vehículos muestra campo "Número de Bastidor (VIN)"
- ✅ VIN se guarda correctamente en la base de datos

---

## ✅ Verificación Post-Despliegue

### Dashboard
- [ ] Ver título **"Citas"** encima de los calendarios
- [ ] Ver sección **"ITV Caducadas"** (antes "Atención Requerida")

### Formulario de Vehículos
- [ ] Campo **"Número de Bastidor (VIN)"** visible
- [ ] Campo acepta hasta 17 caracteres
- [ ] Texto se convierte automáticamente a mayúsculas
- [ ] Campo es opcional (se puede dejar vacío)

### Base de Datos
- [ ] Columna `vin` existe en tabla `vehicles`
- [ ] Tipo: VARCHAR(17)
- [ ] Permite NULL
- [ ] Ubicada después de la columna `plate`

---

## 📊 Estructura de la Columna VIN

| Campo | Tipo | Nulo | Predeterminado | Posición |
|-------|------|------|----------------|----------|
| `vin` | VARCHAR(17) | SÍ | NULL | Después de `plate` |

**Nota**: El VIN (Vehicle Identification Number) tiene un estándar internacional de 17 caracteres.

---

## 🔧 Validaciones Implementadas

### Frontend (Livewire)
```php
#[Validate('nullable|string|max:17')]
public string $vin = '';
```

### Backend (Validación en save)
```php
'vin' => 'nullable|string|max:17'
```

### Transformaciones
- El valor ingresado se convierte automáticamente a **MAYÚSCULAS**
- Se valida la longitud máxima de **17 caracteres**

---

## 🆘 Troubleshooting

### Error: "Unknown column 'vin' in 'field list'"
**Solución**: Ejecutar el script SQL `ADD_VIN_COLUMN.sql` en phpMyAdmin.

### El campo VIN no aparece en el formulario
**Solución**: Limpiar caché de vistas con `php artisan view:clear` o usar `clear-cache.php`.

### Error al guardar vehículo con VIN
**Solución**: Verificar que la columna `vin` existe en la tabla `vehicles` de la base de datos.

### VIN no se convierte a mayúsculas
**Solución**: Verificar que el archivo `VehicleForm.php` tiene `strtoupper($this->vin)` en el array `$data`.

---

## 📝 Notas Técnicas

- **VIN**: Número de Identificación del Vehículo (17 caracteres estándar)
- **Nullable**: Campo opcional, no es obligatorio rellenarlo
- **Mayúsculas**: Conversión automática para mantener consistencia
- **Compatibilidad**: Laravel 12, Livewire 4.1, MySQL 8.0+

---

## 🎯 Mejoras Futuras Sugeridas

- [ ] Validación de formato VIN (checksum)
- [ ] Decodificador de VIN para auto-completar marca/modelo
- [ ] Búsqueda de vehículos por VIN
- [ ] API de verificación de VIN

---

**Desarrollado por**: Claude Sonnet 4.5
**Fecha**: 17/02/2026
