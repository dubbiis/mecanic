<?php
/**
 * Script para limpiar caché de Laravel
 * Colocar en: public/clear-cache.php
 * Acceder: https://tudominio.com/clear-cache.php
 */

// Subir un nivel para llegar a la raíz del proyecto
require __DIR__.'/../vendor/autoload.php';

$app = require_once __DIR__.'/../bootstrap/app.php';

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Limpiar Caché - Laravel</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background: #f5f5f5;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #333;
            border-bottom: 3px solid #10b981;
            padding-bottom: 10px;
        }
        .step {
            background: #f9fafb;
            padding: 15px;
            margin: 10px 0;
            border-left: 4px solid #3b82f6;
            border-radius: 4px;
        }
        .success {
            color: #10b981;
            font-weight: bold;
        }
        .error {
            color: #ef4444;
            font-weight: bold;
        }
        .warning {
            background: #fef3c7;
            padding: 15px;
            border-left: 4px solid #f59e0b;
            margin: 20px 0;
            border-radius: 4px;
        }
        ol {
            line-height: 2;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🧹 Limpieza de Caché de Laravel</h1>

        <?php
        try {
            echo '<div class="step">🔄 Limpiando caché de vistas...</div>';
            \Illuminate\Support\Facades\Artisan::call('view:clear');
            echo '<div class="step success">✅ Caché de vistas limpiada</div>';

            echo '<div class="step">🔄 Limpiando caché de configuración...</div>';
            \Illuminate\Support\Facades\Artisan::call('config:clear');
            echo '<div class="step success">✅ Caché de configuración limpiada</div>';

            echo '<div class="step">🔄 Limpiando caché general...</div>';
            \Illuminate\Support\Facades\Artisan::call('cache:clear');
            echo '<div class="step success">✅ Caché general limpiada</div>';

            echo '<div class="step">🔄 Limpiando rutas compiladas...</div>';
            \Illuminate\Support\Facades\Artisan::call('route:clear');
            echo '<div class="step success">✅ Rutas limpiadas</div>';

            echo '<hr style="margin: 30px 0;">';
            echo '<h2 style="color: #10b981;">✅ ¡Caché limpiada correctamente!</h2>';

            echo '<div class="warning">';
            echo '<h3>🎯 Próximos pasos:</h3>';
            echo '<ol>';
            echo '<li>Presiona <strong>Ctrl + Shift + R</strong> para recargar el navegador sin caché</li>';
            echo '<li>Ve a la página de <strong>Calendario de Citas</strong></li>';
            echo '<li>Haz clic en <strong>"Registro Completo"</strong></li>';
            echo '<li>Ahora deberías ver los márgenes mejorados y el calendario funcionando</li>';
            echo '<li><strong>IMPORTANTE:</strong> Borra este archivo (<code>public/clear-cache.php</code>) del servidor por seguridad</li>';
            echo '</ol>';
            echo '</div>';

        } catch (Exception $e) {
            echo '<div class="step error">❌ Error: ' . htmlspecialchars($e->getMessage()) . '</div>';
            echo '<div class="warning">';
            echo '<p><strong>Si ves este error, intenta:</strong></p>';
            echo '<ol>';
            echo '<li>Verificar que el archivo está en <code>public/clear-cache.php</code></li>';
            echo '<li>Verificar permisos del archivo (644)</li>';
            echo '<li>Contactar con soporte de SiteGround para ejecutar comandos artisan</li>';
            echo '</ol>';
            echo '</div>';
        }
        ?>
    </div>
</body>
</html>
