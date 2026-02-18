<?php
/**
 * Script para limpiar caché de Laravel
 * Subir a la raíz del proyecto y acceder por navegador
 * Ejemplo: https://tudominio.com/clear-cache.php
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';

echo "<h1>Limpiando caché de Laravel...</h1>";

try {
    // Limpiar caché de vistas
    echo "<p>🔄 Limpiando vistas...</p>";
    $app->make('Illuminate\Contracts\Console\Kernel')->call('view:clear');
    echo "<p>✅ Vistas limpiadas</p>";

    // Limpiar caché de configuración
    echo "<p>🔄 Limpiando configuración...</p>";
    $app->make('Illuminate\Contracts\Console\Kernel')->call('config:clear');
    echo "<p>✅ Configuración limpiada</p>";

    // Limpiar caché general
    echo "<p>🔄 Limpiando caché general...</p>";
    $app->make('Illuminate\Contracts\Console\Kernel')->call('cache:clear');
    echo "<p>✅ Caché general limpiada</p>";

    // Limpiar rutas
    echo "<p>🔄 Limpiando rutas...</p>";
    $app->make('Illuminate\Contracts\Console\Kernel')->call('route:clear');
    echo "<p>✅ Rutas limpiadas</p>";

    echo "<hr>";
    echo "<h2 style='color: green;'>✅ ¡Caché limpiada correctamente!</h2>";
    echo "<p><strong>Ahora:</strong></p>";
    echo "<ol>";
    echo "<li>Presiona <strong>Ctrl + F5</strong> en tu navegador</li>";
    echo "<li>Recarga la página de citas</li>";
    echo "<li>Borra este archivo por seguridad</li>";
    echo "</ol>";

} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Error: " . $e->getMessage() . "</p>";
}
