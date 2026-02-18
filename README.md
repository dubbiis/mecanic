# 🚗 Workshop CRM

Sistema de gestión de vehículos e ITVs para talleres mecánicos.

[![Laravel](https://img.shields.io/badge/Laravel-11-FF2D20?style=flat&logo=laravel)](https://laravel.com)
[![Livewire](https://img.shields.io/badge/Livewire-4.1.4-4E56A6?style=flat&logo=livewire)](https://livewire.laravel.com)
[![Tailwind CSS](https://img.shields.io/badge/Tailwind-3-38B2AC?style=flat&logo=tailwind-css)](https://tailwindcss.com)
[![PHP](https://img.shields.io/badge/PHP-8.4-777BB4?style=flat&logo=php)](https://php.net)

---

## 📸 Preview

- **Dashboard** con estadísticas en tiempo real
- **Lista de vehículos** con búsqueda y filtrado
- **Gestión completa** de entradas y salidas
- **Notificaciones WhatsApp** integradas
- **Diseño responsive** y moderno

---

## ✨ Características

- ✅ Dashboard con métricas en tiempo real
- ✅ CRUD completo de vehículos
- ✅ Seguimiento de vencimiento de ITVs
- ✅ Alertas automáticas por estado (expirado/urgente/advertencia)
- ✅ Búsqueda en tiempo real
- ✅ Notificaciones WhatsApp
- ✅ Sistema de autenticación completo
- ✅ Diseño responsive (mobile-first)
- ✅ Interfaz moderna con Alpine.js y Tailwind

---

## 🛠️ Stack Tecnológico

### Backend
- **Framework**: Laravel 11
- **Real-time**: Livewire 4.1.4
- **Auth**: Laravel Breeze
- **Database**: MySQL

### Frontend
- **CSS**: Tailwind CSS
- **JS**: Alpine.js
- **Build**: Vite
- **Fonts**: Poppins (Google Fonts)

---

## 📋 Requisitos

- PHP >= 8.4
- Composer >= 2.0
- Node.js >= 20.17
- MySQL >= 5.7
- npm o yarn

---

## 🚀 Instalación

### 1. Clonar el repositorio
```bash
cd c:\Users\Dubi\Documents\Proyectos automatizaciones\taller
cd workshop-crm
```

### 2. Instalar dependencias
```bash
composer install
npm install
```

### 3. Configurar entorno
```bash
cp .env.example .env
php artisan key:generate
```

### 4. Configurar base de datos
Edita `.env` con tus credenciales:
```env
DB_DATABASE=workshop_crm
DB_USERNAME=root
DB_PASSWORD=
```

### 5. Ejecutar migraciones
```bash
php artisan migrate
```

O importar el SQL completo:
```bash
# En phpMyAdmin o MySQL CLI
mysql -u root -p workshop_crm < database.sql
```

### 6. Compilar assets
```bash
# Desarrollo
npm run dev

# Producción
npm run build
```

### 7. Iniciar servidor
```bash
php artisan serve
```

Visita: http://localhost:8000

---

## 🔐 Credenciales por Defecto

- **Email**: admin@taller.com
- **Password**: password

⚠️ **Importante**: Cambia estas credenciales después del primer login.

---

## 📁 Estructura del Proyecto

```
workshop-crm/
├── app/
│   ├── Livewire/          # Componentes Livewire
│   │   ├── Dashboard.php
│   │   ├── VehicleList.php
│   │   └── VehicleForm.php
│   ├── Models/
│   │   └── Vehicle.php
│   └── Providers/
│       └── AppServiceProvider.php
├── resources/
│   ├── views/
│   │   ├── layouts/
│   │   ├── livewire/
│   │   └── components/
│   ├── css/
│   └── js/
├── public_html/           # ⚠️ Carpeta pública (NO public)
│   ├── build/             # Assets compilados
│   └── index.php
├── database/
│   └── migrations/
├── routes/
│   ├── web.php
│   └── auth.php
├── database.sql           # SQL para importación rápida
├── PROJECT_CONTEXT.md     # 📚 Documentación completa
├── QUICK_REFERENCE.md     # ⚡ Referencia rápida
└── README.md             # 👈 Estás aquí
```

---

## 📚 Documentación

- **[PROJECT_CONTEXT.md](PROJECT_CONTEXT.md)** - Documentación técnica completa
- **[QUICK_REFERENCE.md](QUICK_REFERENCE.md)** - Referencia rápida de comandos
- **[Laravel Docs](https://laravel.com/docs/11.x)** - Documentación oficial de Laravel
- **[Livewire Docs](https://livewire.laravel.com/docs)** - Documentación de Livewire

---

## 🎯 Uso Rápido

### Crear un vehículo
1. Login en el sistema
2. Click en "Registrar Entrada"
3. Completar formulario
4. Guardar

### Ver estadísticas
- El Dashboard muestra métricas actualizadas
- Vehículos con ITV expirada
- Vehículos con ITV próxima a vencer

### Enviar notificación WhatsApp
- Click en icono WhatsApp en cualquier vehículo
- Se abre Chatly.es con datos pre-rellenados

---

## 🚢 Despliegue en Producción

### Siteground (Hosting actual)

1. **Compilar assets**:
```bash
npm run build
```

2. **Subir archivos** vía FTP (excepto `node_modules` y `.git`)

3. **Importar base de datos** en phpMyAdmin:
   - Subir `database.sql`
   - Ejecutar importación

4. **Configurar `.env`** en el servidor

5. **Limpiar cache**:
   - Subir `public_html/clear-cache.php`
   - Visitar: `https://tu-dominio.com/clear-cache.php?key=taller2024`
   - **ELIMINAR** el archivo después

Ver [PROJECT_CONTEXT.md](PROJECT_CONTEXT.md) para instrucciones detalladas.

---

## 🔧 Comandos Útiles

```bash
# Limpiar cache
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# Ver logs
tail -f storage/logs/laravel.log

# Compilar assets
npm run dev       # desarrollo con watch
npm run build     # producción

# Tinker (consola)
php artisan tinker
```

---

## 🐛 Problemas Comunes

### Vite manifest not found
```bash
npm run build
php artisan config:clear
```

### No se guardan vehículos
1. Verificar base de datos importada
2. Revisar credenciales en `.env`
3. Ver logs: `storage/logs/laravel.log`

### Error de permisos
```bash
chmod -R 775 storage
chmod -R 775 bootstrap/cache
```

---

## 🎨 Personalización

### Colores
Edita `tailwind.config.js` para cambiar la paleta de colores.

Paleta actual: **Zinc**
- Background: `bg-zinc-50`
- Primario: `bg-zinc-900`
- Texto: `text-zinc-900`

### Fuentes
Edita `resources/views/layouts/app.blade.php`:
```html
<link href="https://fonts.googleapis.com/css2?family=TuFuente:wght@300;400;600&display=swap">
```

---

## 📊 Modelo de Datos

### Vehicle
```php
id              // PK
name            // Nombre propietario
phone           // Teléfono
car             // Marca y modelo
plate           // Matrícula (única)
itv_date        // Fecha vencimiento ITV
notes           // Notas adicionales
created_at
updated_at
```

### User
```php
id
name
email
password
created_at
updated_at
```

---

## 🧪 Testing

```bash
# Ejecutar tests
php artisan test

# Con coverage
php artisan test --coverage
```

⚠️ Tests pendientes de implementar.

---

## 📈 Roadmap

- [ ] Sistema de recordatorios automáticos
- [ ] Exportar datos a PDF/Excel
- [ ] Historial de ITVs anteriores
- [ ] Multi-usuario con roles
- [ ] API REST
- [ ] Tests automatizados
- [ ] CI/CD pipeline

---

## 🤝 Contribuir

1. Fork el proyecto
2. Crea una rama (`git checkout -b feature/amazing-feature`)
3. Commit tus cambios (`git commit -m 'Add amazing feature'`)
4. Push a la rama (`git push origin feature/amazing-feature`)
5. Abre un Pull Request

---

## 📝 Changelog

### v1.0.0 (2026-02-13)
- ✨ Release inicial
- ✅ CRUD de vehículos
- ✅ Dashboard con estadísticas
- ✅ Sistema de notificaciones
- ✅ Integración WhatsApp
- ✅ Autenticación completa

---

## 📄 Licencia

[DEFINIR LICENCIA]

---

## 👨‍💻 Autor

Desarrollado por [Tu Nombre]

---

## 🌐 Enlaces

- **Producción**: https://workshop-crm.desarrolloappsur.es
- **Documentación**: [PROJECT_CONTEXT.md](PROJECT_CONTEXT.md)
- **Soporte**: [Issues](https://github.com/tu-repo/issues)

---

## ⭐ Agradecimientos

- Laravel Team
- Livewire Team
- Tailwind Labs
- Alpine.js Team

---

<div align="center">

**Hecho con ❤️ para talleres mecánicos**

[⬆️ Volver arriba](#-workshop-crm)

</div>
