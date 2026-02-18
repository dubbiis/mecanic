# Implementación Sistema de Citas - Workshop CRM

## 📋 Resumen

Este documento explica cómo implementar el sistema de citas en SiteGround.

## ✅ Archivos Creados

### Backend
- ✅ `app/Models/Appointment.php` - Modelo de citas
- ✅ `app/Models/Vehicle.php` - Actualizado con relaciones
- ✅ `database/migrations/2026_02_16_000000_create_appointments_table.php` - Migración
- ✅ `database/seeders/AppointmentSeeder.php` - Datos de prueba (opcional)
- ✅ `SITEGROUND_SETUP.sql` - Script SQL para ejecutar en phpMyAdmin

### Pendiente Modificar
- ⏳ `public_html/taller.php` - Añadir vista de citas y cambiar botones WhatsApp

---

## 🚀 Pasos de Implementación

### PASO 1: Base de Datos (phpMyAdmin)

1. Accede a **SiteGround Site Tools**
2. Ve a **MySQL → phpMyAdmin**
3. Selecciona tu base de datos
4. Clic en pestaña **SQL**
5. Copia todo el contenido de `SITEGROUND_SETUP.sql`
6. Pega en el área de texto
7. Clic en **Ejecutar** (Go)

**Verificar:** En el panel izquierdo debería aparecer la tabla `appointments`

---

### PASO 2: Subir Archivos por SFTP

Archivos a subir a SiteGround:

```
/app/Models/Appointment.php          ← NUEVO
/app/Models/Vehicle.php              ← ACTUALIZAR (sobrescribir)
/database/migrations/...             ← OPCIONAL (solo referencia)
```

---

### PASO 3: Modificar taller.php

#### 3.1 Añadir Botón "Citas" en Sidebar

**Ubicación:** Después del botón "Vehículos" (línea ~50)

```html
<button onclick="router.navigate('appointments')" id="nav-appointments"
    class="w-full flex items-center justify-between px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 group text-zinc-500 hover:bg-zinc-50 hover:text-zinc-900">
    <div class="flex items-center gap-3">
        <i data-lucide="calendar" class="w-4 h-4"></i>
        Citas
    </div>
</button>
```

#### 3.2 Cambiar Botones "Notificar" por Logo WhatsApp

**SVG del logo de WhatsApp:**
```html
<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
</svg>
```

**3 ubicaciones a cambiar:**

**A) Línea ~279 (Dashboard - Atención Requerida):**
```html
<!-- ANTES -->
<button onclick="app.sendWhatsApp(${c.id})" class="px-3 py-1.5 text-xs font-medium border border-zinc-200 rounded-lg hover:bg-zinc-50">Notificar</button>

<!-- DESPUÉS -->
<button onclick="app.sendWhatsApp(${c.id})" class="p-2 text-green-500 hover:text-white hover:bg-green-500 rounded-lg transition-all" title="WhatsApp">
    [PEGAR SVG AQUÍ]
</button>
```

**B) Línea ~294 (Dashboard - Próximos 30 Días):**
```html
<!-- ANTES -->
<button onclick="app.sendWhatsApp(${c.id})" class="text-zinc-400 hover:text-zinc-900"><i data-lucide="message-square" class="w-4 h-4"></i></button>

<!-- DESPUÉS -->
<button onclick="app.sendWhatsApp(${c.id})" class="text-green-500 hover:text-white hover:bg-green-500 p-1.5 rounded-lg transition-all" title="WhatsApp">
    [PEGAR SVG AQUÍ - usar w-4 h-4]
</button>
```

**C) Línea ~356 (Clients - Tabla):**
```html
<!-- ANTES -->
<button onclick="app.sendWhatsApp(${c.id})" class="p-2 text-zinc-400 hover:text-green-600 hover:bg-green-50 rounded-lg"><i data-lucide="message-square" class="w-4 h-4"></i></button>

<!-- DESPUÉS -->
<button onclick="app.sendWhatsApp(${c.id})" class="p-2 text-green-500 hover:text-white hover:bg-green-500 rounded-lg transition-all" title="WhatsApp">
    [PEGAR SVG AQUÍ - usar w-4 h-4]
</button>
```

#### 3.3 Actualizar JavaScript

**En `updateNavStyles` (línea ~212):**
```javascript
// ANTES
['dashboard', 'clients'].forEach(view => {

// DESPUÉS
['dashboard', 'clients', 'appointments'].forEach(view => {
```

**En `renderCurrentView` (línea ~200):**
```javascript
renderCurrentView: () => {
    const container = document.getElementById('view-container');
    container.innerHTML = '';

    if (app.data.currentView === 'dashboard') container.innerHTML = views.dashboard();
    else if (app.data.currentView === 'clients') container.innerHTML = views.clients();
    else if (app.data.currentView === 'appointments') container.innerHTML = views.appointments(); // ← AÑADIR
    else if (app.data.currentView === 'form') container.innerHTML = views.form();

    lucide.createIcons();
    app.updateNavStyles();
}
```

**Añadir vista appointments después de `form` (línea ~483):**

```javascript
appointments: () => {
    return `
        <div class="space-y-6">
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="text-3xl font-bold tracking-tight text-zinc-900">Gestión de Citas</h2>
                    <p class="text-zinc-500 mt-1">Próximamente: sistema completo de citas</p>
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-zinc-100 shadow-sm p-12 text-center">
                <i data-lucide="calendar" class="w-16 h-16 mx-auto text-zinc-300 mb-4"></i>
                <h3 class="text-xl font-semibold text-zinc-900 mb-2">Sistema de Citas</h3>
                <p class="text-zinc-500">La funcionalidad de citas estará disponible próximamente.</p>
            </div>
        </div>
    `;
}
```

---

## 📊 Estructura de la Tabla `appointments`

```
id                  - ID único
vehicle_id          - Relación con vehículos
appointment_date    - Fecha y hora
service_type        - revision, reparacion, itv, diagnostico, mantenimiento, otro
status              - pendiente, confirmada, en_proceso, completada, cancelada, no_presentado
description         - Trabajo a realizar
work_done           - Trabajo realizado
estimated_cost      - Coste estimado (€)
final_cost          - Coste final (€)
estimated_duration  - Duración (minutos)
notes               - Notas adicionales
created_at          - Fecha creación
updated_at          - Fecha actualización
deleted_at          - Soft delete
```

---

## 🔍 Verificación

### Base de Datos
```sql
-- Ver estructura
DESCRIBE appointments;

-- Ver todas las citas
SELECT
    a.id,
    a.appointment_date,
    a.service_type,
    a.status,
    v.name,
    v.car,
    v.plate
FROM appointments a
INNER JOIN vehicles v ON a.vehicle_id = v.id
ORDER BY a.appointment_date DESC;
```

### Frontend
1. Acceder al sitio
2. Verificar que aparece botón "Citas" en sidebar
3. Verificar que los iconos de WhatsApp son verdes
4. Clic en "Citas" debería mostrar mensaje de "próximamente"

---

## ✅ Checklist

- [ ] Ejecutar `SITEGROUND_SETUP.sql` en phpMyAdmin
- [ ] Verificar tabla `appointments` creada
- [ ] Subir `Appointment.php` por SFTP
- [ ] Subir `Vehicle.php` actualizado por SFTP
- [ ] Modificar `taller.php` - Añadir botón Citas
- [ ] Modificar `taller.php` - Cambiar 3 botones WhatsApp
- [ ] Modificar `taller.php` - Actualizar JavaScript
- [ ] Probar en navegador
- [ ] Verificar consola sin errores

---

## 📞 Soporte

Si hay errores:
- Revisar logs PHP en SiteGround (Site Tools > PHP > Error Log)
- Revisar consola del navegador (F12)
- Verificar que la tabla existe en phpMyAdmin
- Comprobar permisos de archivos PHP (644)
