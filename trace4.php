<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
require __DIR__.'/vendor/autoload.php';

$app = require __DIR__.'/bootstrap/app.php';
echo "App created\n";

// Check if facade::$app is set via reflection
$ref = new ReflectionClass(Illuminate\Support\Facades\Facade::class);
$prop = $ref->getProperty('app');
$prop->setAccessible(true);
$facadeApp = $prop->getValue();
echo "Facade \$app: " . ($facadeApp ? get_class($facadeApp) : 'NULL') . "\n";

// Check if 'files' binding works
try {
    $files = $app->make('files');
    echo "Files: " . get_class($files) . "\n";
} catch (Exception $e) {
    echo "Files error: " . $e->getMessage() . "\n";
}

// Try Artisan facade specifically
try {
    Artisan::version();
    echo "Artisan works!\n";
} catch (Exception $e) {
    echo "Artisan error: " . $e->getMessage() . "\n";
}
