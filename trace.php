<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
require __DIR__.'/vendor/autoload.php';

echo "Before require app.php\n";
$app = require __DIR__.'/bootstrap/app.php';
echo "After require app.php\n";
echo "App class: " . get_class($app) . "\n";
echo "Instance: " . (Illuminate\Support\Facades\Facade::getFacadeRoot() ? 'set' : 'NOT set') . "\n";
echo "App instance: " . (Illuminate\Support\Facades\Facade::getInstance() ? 'set' : 'NOT set') . "\n";

// Try to set the facade manually
Illuminate\Support\Facades\Facade::setFacadeApplication($app);
echo "After manual setFacadeApplication\n";
echo "Facade root: " . (Illuminate\Support\Facades\Facade::getFacadeRoot() ? 'set' : 'NOT set') . "\n";

// Try to make the kernel
try {
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
    echo "Kernel created: " . get_class($kernel) . "\n";
} catch (Exception $e) {
    echo "Kernel error: " . $e->getMessage() . "\n";
}
