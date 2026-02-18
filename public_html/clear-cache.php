<?php
/**
 * Script para limpiar cache de Laravel sin acceso SSH
 * Sube este archivo a public_html/ y accede via navegador
 * Ejemplo: https://tudominio.com/clear-cache.php
 *
 * IMPORTANTE: Elimina este archivo después de usarlo por seguridad
 */

// Prevenir acceso no autorizado (opcional - cambiar o eliminar)
$secret_key = 'taller2024'; // Cambia esto por seguridad
if (!isset($_GET['key']) || $_GET['key'] !== $secret_key) {
    die('Acceso denegado');
}

// Configurar paths
$basePath = dirname(__DIR__);
$bootstrapCache = $basePath . '/bootstrap/cache';

echo "<h1>Limpieza de Cache de Laravel</h1>";
echo "<pre>";

// Limpiar archivos de cache de bootstrap
$cacheFiles = [
    'config.php',
    'routes-v7.php',
    'packages.php',
    'services.php',
];

foreach ($cacheFiles as $file) {
    $filePath = $bootstrapCache . '/' . $file;
    if (file_exists($filePath)) {
        if (unlink($filePath)) {
            echo "✓ Eliminado: bootstrap/cache/$file\n";
        } else {
            echo "✗ Error al eliminar: bootstrap/cache/$file\n";
        }
    } else {
        echo "- No existe: bootstrap/cache/$file\n";
    }
}

// Limpiar cache de vistas
$viewsCache = $basePath . '/storage/framework/views';
if (is_dir($viewsCache)) {
    $files = glob($viewsCache . '/*');
    $count = 0;
    foreach ($files as $file) {
        if (is_file($file) && pathinfo($file, PATHINFO_EXTENSION) === 'php') {
            if (unlink($file)) {
                $count++;
            }
        }
    }
    echo "✓ Eliminados $count archivos de vistas compiladas\n";
}

// Limpiar cache de aplicación
$appCache = $basePath . '/storage/framework/cache/data';
if (is_dir($appCache)) {
    $files = glob($appCache . '/*/*');
    $count = 0;
    foreach ($files as $file) {
        if (is_file($file)) {
            if (unlink($file)) {
                $count++;
            }
        }
    }
    echo "✓ Eliminados $count archivos de cache de aplicación\n";
}

echo "\n<strong>✓ Cache limpiado correctamente</strong>\n";
echo "\nIMPORTANTE: Elimina este archivo (clear-cache.php) ahora mismo por seguridad.\n";
echo "</pre>";
