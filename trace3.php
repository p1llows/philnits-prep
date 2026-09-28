<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
require __DIR__.'/vendor/autoload.php';

echo "App loaded\n";
$app = require __DIR__.'/bootstrap/app.php';
echo "App bootstrapped\n";

// Check if facade is set
$facadeApp = Illuminate\Support\Facades\Facade::getInstance();
echo "Facade instance: " . ($facadeApp ? get_class($facadeApp) : 'null') . "\n";

// Try to access the 'files' binding
try {
    $files = $app->make('files');
    echo "Files service: " . get_class($files) . "\n";
} catch (Exception $e) {
    echo "Files error: " . $e->getMessage() . "\n";
}

// Try to make the kernel
try {
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
    echo "Kernel: " . get_class($kernel) . "\n";
} catch (Exception $e) {
    echo "Kernel error: " . $e->getMessage() . "\n";
}
