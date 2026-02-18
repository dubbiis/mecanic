# Workshop CRM - Gu

ía de Instalación

## 📋 Tabla de Contenidos
1. [Requisitos](#requisitos)
2. [Instalación en Local](#instalación-en-local)
3. [Instalación en Siteground](#instalación-en-siteground)
4. [Configuración de Base de Datos](#configuración-de-base-de-datos)
5. [Primer Acceso](#primer-acceso)
6. [Resolución de Problemas](#resolución-de-problemas)

---

## 🔧 Requisitos

### Requisitos Locales
- PHP 8.4+ con extensiones:
  - OpenSSL
  - PDO
  - Mbstring
  - Tokenizer
  - XML
  - Ctype
  - JSON
  - BCMath
  - Fileinfo
  - cURL
  - Zip
- Composer 2.9+
- Node.js 20+ y NPM
- MySQL 5.7+ o MariaDB 10.3+

### Requisitos en Siteground
- Plan de hosting con soporte para Laravel
- Acceso a base de datos MySQL
- SSH access (recomendado)

---

## 💻 Instalación en Local

### 1. Instalar PHP y Composer

Ya has instalado:
- ✅ PHP 8.4.16 en `C:\laragon\bin\php\php-8.4.16`
- ✅ Composer 2.9.5 en `C:\laragon\bin\composer`

### 2. Instalar Dependencias

```bash
cd workshop-crm
composer install
npm install
```

### 3. Configurar Variables de Entorno

El archivo `.env` ya está configurado con:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=dbjubftnbggt5g
DB_USERNAME=uwwdaam9d6enq
DB_PASSWORD="HbD&{1CCHn1)"
```

### 4. Ejecutar Migraciones

**Opción A: Usando Laravel Migrations (recomendado para local)**
```bash
php artisan migrate
php artisan db:seed --class=AdminUserSeeder
```

**Opción B: Usando SQL directo**
```bash
# Importar el archivo database.sql
mysql -u uwwdaam9d6enq -p dbjubftnbggt5g < database.sql
```

### 5. Compilar Assets

```bash
npm run build
```

### 6. Iniciar Servidor de Desarrollo

```bash
php artisan serve
```

La aplicación estará disponible en: **http://localhost:8000**

---

## 🌐 Instalación en Siteground

### Paso 1: Subir Archivos

**Usando SFTP (recomendado)**
1. Configura tu cliente SFTP con las credenciales de Siteground
2. Sube todo el contenido de `workshop-crm/` a `public_html/` (o subdirectorio)

**Estructura en Siteground:**
```
public_html/
├── app/
├── bootstrap/
├── config/
├── database/
├── public/          ← Este será tu document root
├── resources/
├── routes/
├── storage/
├── vendor/
├── .env
└── ...
```

### Paso 2: Configurar Document Root

En Siteground, configura el **Document Root** para que apunte a:
```
/public_html/public
```

O si instalaste en un subdirectorio:
```
/public_html/taller/public
```

### Paso 3: Crear Base de Datos

1. Accede al **cPanel de Siteground**
2. Ve a **MySQL Database Wizard**
3. Ya tienes creada la base de datos:
   - **Nombre:** `dbjubftnbggt5g`
   - **Usuario:** `uwwdaam9d6enq`
   - **Contraseña:** `HbD&{1CCHn1)`

### Paso 4: Importar Estructura de Base de Datos

1. Accede a **phpMyAdmin** en Siteground
2. Selecciona la base de datos `dbjubftnbggt5g`
3. Ve a la pestaña **Importar**
4. Sube el archivo `database.sql`
5. Haz clic en **Continuar**

### Paso 5: Configurar `.env` en Siteground

Edita el archivo `.env` en Siteground con estos valores:

```env
APP_NAME="Workshop CRM"
APP_ENV=production
APP_KEY=base64:n04+c1m/Cyb7EOAACs5qUqYsHlaoKrNnvActBNJL9pE=
APP_DEBUG=false
APP_URL=https://tudominio.com

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=dbjubftnbggt5g
DB_USERNAME=uwwdaam9d6enq
DB_PASSWORD="HbD&{1CCHn1)"
```

⚠️ **Importante:**
- Cambia `APP_DEBUG=false` en producción
- Actualiza `APP_URL` con tu dominio real

### Paso 6: Configurar Permisos

Conecta por SSH y ejecuta:

```bash
cd /home/usuario/public_html
chmod -R 775 storage bootstrap/cache
chown -R usuario:usuario storage bootstrap/cache
```

### Paso 7: Generar Application Key (si es necesario)

```bash
php artisan key:generate
```

### Paso 8: Optimizar para Producción

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

## 🗄️ Configuración de Base de Datos

### Credenciales de Producción

```
Nombre BD: dbjubftnbggt5g
Usuario:   uwwdaam9d6enq
Contraseña: HbD&{1CCHn1)
Host:      localhost (en Siteground)
Puerto:    3306
```

### Tablas Creadas

El archivo `database.sql` crea automáticamente:
- ✅ `users` - Usuarios del sistema
- ✅ `password_reset_tokens` - Tokens de reset de contraseña
- ✅ `sessions` - Sesiones activas
- ✅ `cache` y `cache_locks` - Sistema de caché
- ✅ `jobs`, `job_batches`, `failed_jobs` - Cola de trabajos
- ✅ `vehicles` - Vehículos registrados

---

## 🔐 Primer Acceso

### Credenciales de Administrador

```
Email:    admin@taller.com
Password: password
```

⚠️ **IMPORTANTE:** Cambia la contraseña inmediatamente después del primer login en producción.

### Cambiar Contraseña

1. Inicia sesión con las credenciales de prueba
2. Ve a tu **Perfil** (icono de usuario en el sidebar)
3. Actualiza tu contraseña

---

## 🐛 Resolución de Problemas

### Error: "500 Internal Server Error"

**Solución:**
```bash
# Verifica permisos
chmod -R 775 storage bootstrap/cache

# Limpia caché
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

### Error: "No application encryption key has been specified"

**Solución:**
```bash
php artisan key:generate
```

### Error: "SQLSTATE[HY000] [1045] Access denied"

**Solución:**
- Verifica las credenciales en `.env`
- Asegúrate de que el usuario tenga permisos en la BD
- En Siteground, el host debe ser `localhost` no `127.0.0.1`

### Error: "The Mix manifest does not exist"

**Solución:**
```bash
npm run build
```

### Error: "Class 'Livewire\Component' not found"

**Solución:**
```bash
composer dump-autoload
php artisan clear-compiled
```

### Caché de Configuración en Producción

Si haces cambios en `.env`, recuerda limpiar la caché:
```bash
php artisan config:clear
php artisan config:cache
```

---

## 📂 Estructura del Proyecto

```
workshop-crm/
├── app/
│   ├── Livewire/
│   │   ├── Dashboard.php          # Componente del dashboard
│   │   ├── VehicleList.php        # Listado de vehículos
│   │   └── VehicleForm.php        # Formulario crear/editar
│   └── Models/
│       └── Vehicle.php             # Modelo de vehículo
├── database/
│   ├── migrations/                 # Migraciones de BD
│   ├── seeders/
│   │   └── AdminUserSeeder.php    # Seeder del admin
│   └── database.sql                # ⭐ SQL para importar
├── resources/
│   └── views/
│       ├── livewire/               # Vistas de componentes
│       ├── layouts/
│       │   └── app.blade.php       # Layout principal
│       └── components/
│           └── notifications.blade.php  # Sistema de notificaciones
└── .env                            # ⭐ Configuración de entorno
```

---

## 🚀 Comandos Útiles

```bash
# Limpiar caché
php artisan cache:clear
php artisan config:clear
php artisan view:clear
php artisan route:clear

# Optimizar para producción
php artisan optimize

# Ver rutas
php artisan route:list

# Ejecutar migraciones
php artisan migrate

# Revertir última migración
php artisan migrate:rollback

# Ver logs
tail -f storage/logs/laravel.log
```

---

## 📞 Soporte

Si encuentras problemas:
1. Revisa los logs en `storage/logs/laravel.log`
2. Verifica la configuración de `.env`
3. Asegúrate de que todos los permisos estén correctos
4. Consulta la documentación de Laravel: https://laravel.com/docs

---

## ✅ Checklist de Instalación

### Local
- [ ] PHP 8.4+ instalado
- [ ] Composer instalado
- [ ] Dependencias instaladas (`composer install`)
- [ ] NPM dependencies instaladas (`npm install`)
- [ ] Assets compilados (`npm run build`)
- [ ] Base de datos creada
- [ ] Migraciones ejecutadas
- [ ] Usuario admin creado
- [ ] Servidor iniciado (`php artisan serve`)

### Producción (Siteground)
- [ ] Archivos subidos vía SFTP
- [ ] Document root configurado a `/public`
- [ ] Base de datos importada (database.sql)
- [ ] `.env` configurado correctamente
- [ ] Permisos de storage configurados
- [ ] Caché optimizada
- [ ] Contraseña de admin cambiada
- [ ] APP_DEBUG=false en producción
- [ ] HTTPS configurado (SSL)

---

**¡Instalación Completada! 🎉**

Accede a tu aplicación y comienza a gestionar los vehículos de tu taller.
