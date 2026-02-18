# Skill: Deploy Laravel en EasyPanel (con Dockerfile)

Aplica este skill completo cuando el usuario quiera subir un proyecto Laravel a EasyPanel (o cualquier servidor con Docker + reverse proxy HTTPS).

---

## 1. REPOSITORIO GITHUB

```bash
git init
git add .
git commit -m "Initial commit"
gh repo create NOMBRE_REPO --public --source=. --remote=origin --push
```

- Instalar GitHub CLI: `winget install --id GitHub.cli`
- Autenticarse: `gh auth login` → HTTPS → browser

---

## 2. PHP VERSION

**NUNCA uses Nixpacks para proyectos con PHP >= 8.4.** Nixpacks ignora `.php-version` y `nixpacks.toml`. Usa siempre **Dockerfile** propio.

---

## 3. DOCKERFILE (plantilla Laravel)

```dockerfile
FROM php:8.4-cli

RUN apt-get update && apt-get install -y \
    git curl zip unzip \
    libpng-dev libonig-dev libxml2-dev libzip-dev \
    && curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd zip

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app

COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-scripts

COPY package.json package-lock.json ./
RUN npm install

COPY . .

RUN composer dump-autoload --optimize
RUN npm run build
RUN chmod -R 775 storage bootstrap/cache

COPY start.sh /start.sh
RUN chmod +x /start.sh

EXPOSE 8080
CMD ["/start.sh"]
```

---

## 4. STARTUP SCRIPT (start.sh)

Genera el `.env` desde variables de entorno Docker y ejecuta migraciones automáticamente:

```bash
#!/bin/bash
set -e

cat > /app/.env <<EOF
APP_NAME=${APP_NAME:-MiApp}
APP_ENV=${APP_ENV:-production}
APP_KEY=${APP_KEY}
APP_DEBUG=${APP_DEBUG:-false}
APP_URL=${APP_URL:-http://localhost}

DB_CONNECTION=mysql
DB_HOST=${DB_HOST}
DB_PORT=${DB_PORT:-3306}
DB_DATABASE=${DB_DATABASE}
DB_USERNAME=${DB_USERNAME}
DB_PASSWORD=${DB_PASSWORD}

SESSION_DRIVER=file
CACHE_STORE=file
QUEUE_CONNECTION=sync
LOG_CHANNEL=stderr
EOF

chmod -R 775 /app/storage /app/bootstrap/cache

php artisan config:clear
php artisan cache:clear
php artisan migrate --force
php artisan db:seed --class=AdminUserSeeder --force 2>/dev/null || true

exec php -S 0.0.0.0:8080 -t /app/PUBLIC_DIR /app/PUBLIC_DIR/router.php
```

> Sustituye `PUBLIC_DIR` por `public` o `public_html` según el proyecto.

---

## 5. ROUTER PHP (para archivos estáticos)

Sin este archivo, JS y CSS se sirven como HTML y el navegador los rechaza.

```php
<?php
// public/router.php  (o public_html/router.php)
$file = __DIR__ . parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
if (is_file($file)) {
    return false;
}
require __DIR__ . '/index.php';
```

---

## 6. VITE CONFIG (manifest.json)

Vite 5+ guarda el manifest en `.vite/manifest.json` pero Laravel lo busca en `manifest.json`. Fix:

```js
// vite.config.js
build: {
    outDir: 'public/build',   // o public_html/build
    manifest: 'manifest.json', // ← CRÍTICO: no usar true
},
```

---

## 7. REVERSE PROXY HTTPS (EasyPanel / Traefik)

El contenedor recibe HTTP pero el usuario accede por HTTPS. Sin esto los assets se cargan por HTTP y el navegador los bloquea.

```php
// bootstrap/app.php
->withMiddleware(function (Middleware $middleware): void {
    $middleware->trustProxies(at: '*');
})
```

---

## 8. BASE DE DATOS EN EASYPANEL

- El usuario MySQL del servicio **solo tiene acceso a su base de datos por defecto**.
- `root` solo acepta conexiones desde `localhost`, no desde otros contenedores.
- **Solución**: usar la base de datos por defecto del servicio MySQL, o ejecutar `GRANT ALL PRIVILEGES ON db.* TO 'user'@'%';` como root desde phpMyAdmin/HeidiSQL.
- En MySQL 8+, `GRANT` ya **no acepta** `IDENTIFIED BY`. Separar en dos sentencias:
  ```sql
  GRANT ALL PRIVILEGES ON mecanic.* TO 'mysql'@'%';
  FLUSH PRIVILEGES;
  ```

---

## 9. VARIABLES DE ENTORNO EASYPANEL

```
APP_NAME=MiApp
APP_ENV=production
APP_KEY=base64:GENERADO_CON_php_artisan_key:generate_--show
APP_DEBUG=false
APP_URL=https://MI_DOMINIO.easypanel.host

DB_CONNECTION=mysql
DB_HOST=PROYECTO_bdd
DB_PORT=3306
DB_DATABASE=NOMBRE_BD
DB_USERNAME=mysql
DB_PASSWORD=CONTRASEÑA_COPIADA_SIN_ESCRIBIR_A_MANO

SESSION_DRIVER=file
CACHE_STORE=file
QUEUE_CONNECTION=sync
LOG_CHANNEL=stderr
```

> **IMPORTANTE**: copiar la contraseña con el icono 📋, nunca escribirla a mano.

---

## 10. SEEDERS: NUNCA USAR create(), USAR updateOrCreate()

```php
// MAL - falla si el usuario ya existe
User::create([...]);

// BIEN - idempotente, funciona en cada redeploy
User::updateOrCreate(
    ['email' => 'admin@taller.com'],
    [
        'name' => 'Admin',
        'email_verified_at' => now(),
        'password' => Hash::make('password'), // Laravel genera el hash correcto
    ]
);
```

> **NUNCA** hardcodear hashes bcrypt en SQL. Siempre dejar que `Hash::make()` los genere.

---

## 11. PUERTO EN EASYPANEL

En la sección **Dominios** de la app, configurar el puerto interno: **8080**

---

## 12. AUTO-DEPLOY (webhook)

1. EasyPanel → app → sección "Activación de implementación" → copiar URL
2. GitHub → repo → Settings → Webhooks → Add webhook
3. Payload URL: la URL de EasyPanel
4. Content type: `application/json`
5. Events: `Just the push event`

---

## 13. CHECKLIST DE DESPLIEGUE

- [ ] Dockerfile con PHP versión correcta
- [ ] start.sh genera .env y ejecuta migraciones
- [ ] router.php en el directorio público
- [ ] `manifest: 'manifest.json'` en vite.config.js
- [ ] `trustProxies(at: '*')` en bootstrap/app.php
- [ ] Variables de entorno configuradas en EasyPanel
- [ ] Puerto 8080 configurado en Dominios
- [ ] Webhook GitHub → EasyPanel configurado
- [ ] APP_DEBUG=false en producción
