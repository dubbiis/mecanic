# Cambio de Notificaciones - Sistema Mejorado

**Fecha**: 17/02/2026
**Versión**: 2.3

---

## 📋 Cambio Implementado

Se ha modificado el comportamiento de los **botones de notificación** en todo el sistema.

### ❌ Comportamiento Anterior

- Al hacer clic en "Enviar notificación" → Redirigía a `https://chatly.es/login.php`
- Salía de la aplicación
- No había confirmación de envío

### ✅ Comportamiento Nuevo

- Al hacer clic en "Enviar notificación" → Muestra notificación verde **"Notificación enviada"**
- Permanece en la misma página
- Toast notification verde durante 5 segundos
- Animación suave de entrada/salida

---

## 📁 Archivos Modificados

### 1. `app/Livewire/VehicleList.php`

**Método actualizado**: `sendNotification()`

```php
/**
 * Send notification (simulated).
 */
public function sendNotification(int $vehicleId): void
{
    $this->dispatch('notify',
        message: 'Notificación enviada',
        type: 'success'
    );
}
```

**Cambio**:
- ❌ Antes: `$this->redirect('https://chatly.es');`
- ✅ Ahora: Dispara evento `notify` con mensaje y tipo

---

### 2. `app/Livewire/Dashboard.php`

**Método actualizado**: `sendNotification()`

```php
/**
 * Send notification (simulated).
 */
public function sendNotification(int $vehicleId): void
{
    $this->dispatch('notify',
        message: 'Notificación enviada',
        type: 'success'
    );
}
```

**Cambio**: Igual que VehicleList - dispara evento en lugar de redirigir

---

### 3. `resources/views/components/notifications.blade.php`

**Listener añadido**: Escucha eventos `notify`

```blade
@notify.window="add($event.detail.type || 'info', $event.detail.message || 'Notificación')"
```

**Funcionalidad**:
- Escucha eventos `notify` desde cualquier componente Livewire
- Extrae el `type` y `message` del evento
- Muestra la notificación con el estilo correspondiente

---

## 🎨 Tipos de Notificaciones Disponibles

El sistema ahora soporta 4 tipos de notificaciones:

| Tipo | Color | Icono | Uso |
|------|-------|-------|-----|
| `success` | 🟢 Verde | ✓ | Acciones exitosas |
| `error` | 🔴 Rojo | ✕ | Errores |
| `info` | 🔵 Azul | ℹ | Información |
| `warning` | 🟡 Amarillo | ⚠ | Advertencias |

---

## 🔧 Cómo Usar el Sistema de Notificaciones

### Desde un Componente Livewire

```php
// Notificación de éxito (verde)
$this->dispatch('notify',
    message: 'Operación completada',
    type: 'success'
);

// Notificación de error (rojo)
$this->dispatch('notify',
    message: 'Ha ocurrido un error',
    type: 'error'
);

// Notificación de información (azul)
$this->dispatch('notify',
    message: 'Información importante',
    type: 'info'
);

// Notificación de advertencia (amarillo)
$this->dispatch('notify',
    message: 'Cuidado con esto',
    type: 'warning'
);
```

---

## 🎯 Ubicaciones Afectadas

Los botones de notificación están en:

1. **Lista de Vehículos** (`/vehicles`)
   - Botón de notificación por cada vehículo
   - Icono de mensaje (chat)

2. **Dashboard** (`/dashboard`)
   - Botón "Notificar" en vehículos con ITV caducada
   - Botón "Notificar" en vehículos próximos a vencer

---

## ✅ Características de las Notificaciones

### Diseño
- ✅ **Posición**: Esquina superior derecha
- ✅ **Tamaño**: Mínimo 320px de ancho
- ✅ **Borde**: Color según tipo
- ✅ **Sombra**: Sombra suave
- ✅ **Bordes redondeados**: 12px (rounded-xl)

### Animaciones
- ✅ **Entrada**: Desliza desde la derecha + fade in (300ms)
- ✅ **Salida**: Fade out (200ms)
- ✅ **Duración**: Auto-cierre a los 5 segundos
- ✅ **Botón cerrar**: X para cerrar manualmente

### Funcionalidad
- ✅ **Múltiples notificaciones**: Se apilan verticalmente
- ✅ **Auto-cierre**: Desaparecen automáticamente
- ✅ **Cierre manual**: Botón X en cada notificación
- ✅ **No bloquean**: No requieren interacción

---

## 🚀 Despliegue

### Si tienes SFTP automático
✅ Los archivos ya están sincronizados automáticamente.

### Si NO tienes SFTP automático
Subir estos archivos:

```
app/Livewire/VehicleList.php
app/Livewire/Dashboard.php
resources/views/components/notifications.blade.php
```

### Limpiar caché
```bash
php artisan view:clear
php artisan cache:clear
```

O usar `clear-cache.php` creado anteriormente.

---

## 🧪 Verificación

### Probar en Lista de Vehículos

1. Ve a **Vehículos** (`/vehicles`)
2. Haz clic en el **icono de mensaje** (botón de notificación) de cualquier vehículo
3. Deberías ver:
   - ✅ Notificación verde en esquina superior derecha
   - ✅ Texto: "Notificación enviada"
   - ✅ Icono de check verde
   - ✅ Se mantiene en la misma página
   - ✅ Desaparece después de 5 segundos

### Probar en Dashboard

1. Ve a **Dashboard** (`/dashboard`)
2. En la sección "ITV Caducadas" o "Próximos 30 Días"
3. Haz clic en **"Notificar"**
4. Verifica lo mismo que en la lista de vehículos

---

## 🎨 Personalización Futura

### Cambiar mensaje
```php
$this->dispatch('notify',
    message: 'Tu mensaje personalizado aquí',
    type: 'success'
);
```

### Cambiar duración (en notifications.blade.php)
```javascript
// Línea 8: Cambiar 5000 (5 segundos) a otro valor
setTimeout(() => this.remove(id), 5000); // ← cambiar aquí
```

### Añadir sonido (opcional)
```javascript
add(type, message) {
    const id = Date.now();
    this.notifications.push({ id, type, message });

    // Reproducir sonido
    const audio = new Audio('/sounds/notification.mp3');
    audio.play();

    setTimeout(() => this.remove(id), 5000);
}
```

---

## 🆕 Mejoras Futuras Sugeridas

- [ ] Integración real con API de notificaciones (Email, SMS, WhatsApp)
- [ ] Historial de notificaciones enviadas
- [ ] Plantillas de mensajes personalizables
- [ ] Programación de notificaciones
- [ ] Estadísticas de notificaciones enviadas
- [ ] Confirmación de lectura

---

## 📝 Notas Técnicas

- **Alpine.js**: Sistema de notificaciones reactivo
- **Livewire Events**: Comunicación entre componentes
- **Auto-cierre**: 5 segundos por defecto
- **Stack de notificaciones**: Máximo ilimitado (se apilan)
- **Responsive**: Se adapta a móviles
- **No requiere JavaScript adicional**: Todo integrado con Alpine.js

---

## 🔍 Detalles de Implementación

### Flujo de Datos

1. **Usuario** hace clic en botón "Notificar"
2. **Livewire** ejecuta método `sendNotification()`
3. **Método** dispara evento `notify` con datos
4. **Componente notifications** escucha el evento
5. **Alpine.js** añade notificación al array
6. **UI** muestra notificación con animación
7. **Timer** elimina notificación después de 5s

### Estructura del Evento

```php
// PHP (Livewire)
$this->dispatch('notify', [
    'message' => 'Texto de la notificación',
    'type' => 'success' // success, error, info, warning
]);
```

```javascript
// JavaScript (Alpine.js)
@notify.window="add($event.detail.type, $event.detail.message)"
```

---

**Desarrollado por**: Claude Sonnet 4.5
**Fecha**: 17/02/2026
