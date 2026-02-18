# Columna "PEDIR CITA ITV" Añadida

**Fecha**: 17/02/2026
**Versión**: 2.2

---

## 📋 Cambio Implementado

Se ha añadido una nueva columna en la tabla de vehículos llamada **"PEDIR CITA ITV"**.

### ✅ Características

- **Ubicación**: Entre la columna "Estado" y "Acciones"
- **Contenido**: Logo de la ITV clickeable
- **Funcionalidad**: Al hacer clic, abre https://www.itvcita.com/Welcome.do en una nueva pestaña
- **Logo**: Se utiliza el archivo `ITV_logo.png` de la carpeta `public_html/build/assets/logos/`

---

## 📁 Archivo Modificado

### `resources/views/livewire/vehicle-list.blade.php`

**Cambios realizados**:

#### 1. Añadido header de columna
```blade
<th class="p-5 font-medium text-center">Pedir Cita ITV</th>
```

#### 2. Añadida celda con logo clickeable
```blade
<td class="p-5 text-center">
    <a href="https://www.itvcita.com/Welcome.do" target="_blank" class="inline-block hover:opacity-80 transition-opacity" title="Pedir cita ITV">
        <img src="{{ asset('build/assets/logos/ITV_logo.png') }}" alt="ITV Logo" class="h-8 w-auto mx-auto">
    </a>
</td>
```

#### 3. Actualizado colspan en estado vacío
```blade
<td colspan="6" class="p-12 text-center text-zinc-400">
```
(Cambiado de 5 a 6 para incluir la nueva columna)

---

## 🎨 Diseño Implementado

### Logo
- **Altura**: 32px (h-8 en Tailwind)
- **Ancho**: Automático (mantiene proporción)
- **Centrado**: Horizontal en la celda
- **Efecto hover**: Opacidad reducida al 80%
- **Transición**: Suave al pasar el ratón

### Link
- **URL**: https://www.itvcita.com/Welcome.do
- **Target**: `_blank` (abre en nueva pestaña)
- **Title**: "Pedir cita ITV" (tooltip al pasar el ratón)

---

## 📊 Estructura de la Tabla

Nueva estructura de columnas:

1. **Cliente** - Nombre y teléfono del propietario
2. **Vehículo** - Marca/modelo y matrícula
3. **ITV** - Fecha de vencimiento
4. **Estado** - Estado actual (Caducada, Urgente, Warning, Vigente)
5. **Pedir Cita ITV** ← ✨ **NUEVA COLUMNA**
6. **Acciones** - Botones de acción (Notificar, Nueva cita, Editar, Eliminar)

---

## 🚀 Despliegue

### Si tienes SFTP automático
✅ El archivo ya está subido automáticamente.

### Si NO tienes SFTP automático
Subir el archivo:
```
resources/views/livewire/vehicle-list.blade.php
```

### Limpiar caché
```bash
php artisan view:clear
php artisan cache:clear
```

O usar el archivo `clear-cache.php` creado anteriormente.

---

## ✅ Verificación

Después de desplegar:

1. Ve a la página de **Lista de Vehículos**
2. Verifica que aparece la columna **"PEDIR CITA ITV"** después de "Estado"
3. Verifica que el **logo de la ITV** se muestra correctamente
4. Haz **clic en el logo** y verifica que:
   - ✅ Se abre https://www.itvcita.com/Welcome.do
   - ✅ Se abre en una **nueva pestaña**
   - ✅ El logo tiene efecto hover (opacidad al pasar el ratón)

---

## 🖼️ Logo de la ITV

**Archivo**: `public_html/build/assets/logos/ITV_logo.png`
**Tamaño del archivo**: 122,900 bytes (~123 KB)
**Ruta en el código**: `{{ asset('build/assets/logos/ITV_logo.png') }}`

---

## 🔧 Personalización Futura

Si quieres cambiar alguna característica:

### Cambiar tamaño del logo
```blade
<img src="..." class="h-10 w-auto mx-auto">  <!-- h-10 = 40px -->
<img src="..." class="h-12 w-auto mx-auto">  <!-- h-12 = 48px -->
```

### Cambiar URL de destino
```blade
<a href="https://tu-nueva-url.com" ...>
```

### Añadir parámetros a la URL (por ejemplo, pasar datos del vehículo)
```blade
<a href="https://www.itvcita.com/Welcome.do?matricula={{ $vehicle->plate }}" ...>
```

---

## 📝 Notas Técnicas

- **Responsive**: El logo se adapta automáticamente al tamaño de pantalla
- **Accesibilidad**: Incluye atributo `alt` y `title` para screen readers
- **Performance**: Logo local (no requiere carga externa)
- **SEO**: Link con `target="_blank"` para no afectar la navegación del usuario

---

**Desarrollado por**: Claude Sonnet 4.5
**Fecha**: 17/02/2026
