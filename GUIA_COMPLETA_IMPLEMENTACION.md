# Guía Completa de Implementación - Sistema de Citas

## 📁 Archivos ya Creados (en workshop-crm)

### ✅ Backend - Listos para Subir a SiteGround
- `app/Models/Appointment.php` - Modelo de citas
- `app/Models/Vehicle.php` - Modelo actualizado con relaciones
- `database/migrations/2026_02_16_000000_create_appointments_table.php` - Migración
- `database/seeders/AppointmentSeeder.php` - Datos de prueba (opcional)

### ✅ SQL
- `SITEGROUND_SETUP.sql` - Script para phpMyAdmin

---

## 🚀 PASO 1: Crear Tabla en Base de Datos

### Opción A: phpMyAdmin (Recomendado)
1. Acceder a SiteGround Site Tools → MySQL → phpMyAdmin
2. Seleccionar tu base de datos
3. Clic en pestaña **SQL**
4. Copiar y pegar TODO el contenido de `SITEGROUND_SETUP.sql`
5. Clic en **Ejecutar**
6. Verificar que aparece la tabla `appointments` en el panel izquierdo

### Verificar Creación
```sql
DESCRIBE appointments;
SELECT * FROM appointments;
```

---

## 🚀 PASO 2: Subir Archivos por SFTP

Conectar a SiteGround por SFTP y subir:

```
Local → Remoto
workshop-crm/app/Models/Appointment.php → app/Models/Appointment.php
workshop-crm/app/Models/Vehicle.php → app/Models/Vehicle.php (SOBRESCRIBIR)
```

---

## 🚀 PASO 3: Modificar taller.php

**Archivo a modificar:** `C:\Users\Dubi\Documents\Proyectos automatizaciones\taller\taller.php`

### 3.1 Añadir Botón "Citas" en Sidebar

**Ubicación:** Después de la línea 50 (después del botón "Vehículos")

**INSERTAR:**
```html
            <button onclick="router.navigate('appointments')" id="nav-appointments" class="w-full flex items-center justify-between px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 group text-zinc-500 hover:bg-zinc-50 hover:text-zinc-900">
                <div class="flex items-center gap-3">
                    <i data-lucide="calendar" class="w-4 h-4"></i>
                    Citas
                </div>
            </button>
```

---

### 3.2 Cambiar Botones "Notificar" por Logo WhatsApp

**SVG de WhatsApp (completo):**
```html
<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
```

**SVG pequeño (w-4 h-4):**
```html
<svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
```

#### CAMBIO 1: Línea ~279 (Dashboard - Atención Requerida)

**BUSCAR:**
```html
<button onclick="app.sendWhatsApp(${c.id})" class="px-3 py-1.5 text-xs font-medium border border-zinc-200 rounded-lg hover:bg-zinc-50">Notificar</button>
```

**REEMPLAZAR POR:**
```html
<button onclick="app.sendWhatsApp(${c.id})" class="p-2 text-green-500 hover:text-white hover:bg-green-500 rounded-lg transition-all" title="WhatsApp">
    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
</button>
```

#### CAMBIO 2: Línea ~294 (Dashboard - Próximos 30 Días)

**BUSCAR:**
```html
<button onclick="app.sendWhatsApp(${c.id})" class="text-zinc-400 hover:text-zinc-900"><i data-lucide="message-square" class="w-4 h-4"></i></button>
```

**REEMPLAZAR POR:**
```html
<button onclick="app.sendWhatsApp(${c.id})" class="text-green-500 hover:text-white hover:bg-green-500 p-1.5 rounded-lg transition-all" title="WhatsApp">
    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
</button>
```

#### CAMBIO 3: Línea ~356 (Clients - Tabla de Vehículos)

**BUSCAR:**
```html
<button onclick="app.sendWhatsApp(${c.id})" class="p-2 text-zinc-400 hover:text-green-600 hover:bg-green-50 rounded-lg"><i data-lucide="message-square" class="w-4 h-4"></i></button>
```

**REEMPLAZAR POR:**
```html
<button onclick="app.sendWhatsApp(${c.id})" class="p-2 text-green-500 hover:text-white hover:bg-green-500 rounded-lg transition-all" title="WhatsApp">
    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
</button>
```

---

### 3.3 Actualizar JavaScript - Router

**Ubicación:** Línea ~212

**BUSCAR:**
```javascript
['dashboard', 'clients'].forEach(view => {
```

**REEMPLAZAR POR:**
```javascript
['dashboard', 'clients', 'appointments'].forEach(view => {
```

---

### 3.4 Actualizar renderCurrentView

**Ubicación:** Línea ~200

**BUSCAR:**
```javascript
renderCurrentView: () => {
    const container = document.getElementById('view-container');
    container.innerHTML = '';

    if (app.data.currentView === 'dashboard') container.innerHTML = views.dashboard();
    else if (app.data.currentView === 'clients') container.innerHTML = views.clients();
    else if (app.data.currentView === 'form') container.innerHTML = views.form();

    lucide.createIcons(); // Refrescar iconos
    app.updateNavStyles();
}
```

**REEMPLAZAR POR:**
```javascript
renderCurrentView: () => {
    const container = document.getElementById('view-container');
    container.innerHTML = '';

    if (app.data.currentView === 'dashboard') container.innerHTML = views.dashboard();
    else if (app.data.currentView === 'clients') container.innerHTML = views.clients();
    else if (app.data.currentView === 'appointments') container.innerHTML = views.appointments();
    else if (app.data.currentView === 'form') container.innerHTML = views.form();

    lucide.createIcons(); // Refrescar iconos
    app.updateNavStyles();
}
```

---

### 3.5 Añadir Vista de Citas

**Ubicación:** Después de `form: () => { ... }` (línea ~483)

**INSERTAR ANTES del cierre de `views`:**
```javascript
,

appointments: () => {
    return `
        <div class="space-y-6">
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="text-3xl font-bold tracking-tight text-zinc-900">Gestión de Citas</h2>
                    <p class="text-zinc-500 mt-1">Sistema de organización de citas del taller</p>
                </div>
                <button class="bg-zinc-900 text-white hover:bg-zinc-800 shadow-lg shadow-zinc-900/10 px-5 py-2.5 text-sm rounded-xl font-medium transition-all flex items-center gap-2">
                    <i data-lucide="plus" class="w-4 h-4"></i>
                    <span>Nueva Cita</span>
                </button>
            </div>

            <!-- Próximamente -->
            <div class="bg-white rounded-2xl border border-zinc-100 shadow-sm p-16 text-center">
                <div class="max-w-md mx-auto">
                    <div class="w-16 h-16 mx-auto mb-4 bg-zinc-100 rounded-full flex items-center justify-center">
                        <i data-lucide="calendar" class="w-8 h-8 text-zinc-400"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-zinc-900 mb-2">Sistema de Citas</h3>
                    <p class="text-zinc-500 mb-6">La base de datos está lista. La interfaz de gestión de citas se implementará próximamente.</p>
                    <div class="bg-zinc-50 rounded-lg p-4 text-left">
                        <p class="text-sm font-medium text-zinc-700 mb-2">Funcionalidades disponibles próximamente:</p>
                        <ul class="text-sm text-zinc-600 space-y-1">
                            <li class="flex items-center gap-2">
                                <i data-lucide="check" class="w-4 h-4 text-green-500"></i>
                                Calendario de citas
                            </li>
                            <li class="flex items-center gap-2">
                                <i data-lucide="check" class="w-4 h-4 text-green-500"></i>
                                Historial por vehículo
                            </li>
                            <li class="flex items-center gap-2">
                                <i data-lucide="check" class="w-4 h-4 text-green-500"></i>
                                Gestión de estados y costes
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    `;
}
```

---

## ✅ Checklist de Implementación

### Base de Datos
- [ ] Acceder a phpMyAdmin en SiteGround
- [ ] Ejecutar `SITEGROUND_SETUP.sql`
- [ ] Verificar que existe tabla `appointments`
- [ ] (Opcional) Ejecutar INSERT de datos de prueba

### SFTP
- [ ] Conectar a SiteGround
- [ ] Subir `app/Models/Appointment.php`
- [ ] Subir `app/Models/Vehicle.php` (actualizar)
- [ ] Verificar permisos (644)

### Frontend (taller.php)
- [ ] Añadir botón "Citas" en sidebar (después línea 50)
- [ ] Cambiar botón WhatsApp - Ubicación 1 (línea ~279)
- [ ] Cambiar botón WhatsApp - Ubicación 2 (línea ~294)
- [ ] Cambiar botón WhatsApp - Ubicación 3 (línea ~356)
- [ ] Actualizar array en updateNavStyles (línea ~212)
- [ ] Actualizar renderCurrentView (línea ~200)
- [ ] Añadir vista appointments (después línea ~483)

### Verificación
- [ ] Abrir sitio en navegador
- [ ] Ver botón "Citas" en sidebar
- [ ] Ver iconos verdes de WhatsApp
- [ ] Clic en "Citas" → ver mensaje de próximamente
- [ ] Verificar consola sin errores (F12)

---

## 🔍 Verificar que Funciona

### Base de Datos
```sql
-- Ver estructura
DESCRIBE appointments;

-- Contar registros
SELECT COUNT(*) FROM appointments;

-- Ver con datos de vehículos
SELECT a.*, v.name, v.car, v.plate
FROM appointments a
JOIN vehicles v ON a.vehicle_id = v.id;
```

### Frontend
1. Debe aparecer botón "Citas" con icono de calendario
2. Los 3 botones de WhatsApp deben ser verdes
3. Al hacer clic en "Citas" debe mostrar página de próximamente
4. No debe haber errores en consola

---

## 📊 Estructura de la Tabla appointments

```
id                   BIGINT         ID único
vehicle_id           BIGINT         Relación con vehículo
appointment_date     DATETIME       Fecha y hora
service_type         ENUM           Tipo de servicio
status               ENUM           Estado de la cita
description          TEXT           Descripción del trabajo
work_done            TEXT           Trabajo realizado
estimated_cost       DECIMAL(10,2)  Coste estimado
final_cost           DECIMAL(10,2)  Coste final
estimated_duration   INT            Duración en minutos
notes                TEXT           Notas
created_at           TIMESTAMP      Fecha creación
updated_at           TIMESTAMP      Fecha actualización
deleted_at           TIMESTAMP      Soft delete
```

---

## ⚠️ Notas Importantes

1. **Backup**: Haz copia de seguridad de la BD antes de ejecutar SQL
2. **Archivo taller.php**: Haz copia antes de modificar
3. **Líneas exactas**: Los números de línea son aproximados, busca por el contenido exacto
4. **SVG**: Copia el SVG completo en una sola línea
5. **Cache**: Limpia cache del navegador si no ves cambios (Ctrl+F5)

---

## 📞 Soporte

Si hay errores revisa:
- Logs de PHP en SiteGround (Site Tools > PHP > Error Log)
- Consola del navegador (F12 > Console)
- Que los archivos PHP se subieron correctamente
- Que la tabla appointments existe en phpMyAdmin
