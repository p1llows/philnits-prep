<?php
// Load environment first
require __DIR__.'/vendor/autoload.php';
$app = require __DIR__.'/bootstrap/app.php';
Illuminate\Support\Facades\Facade::setFacadeApplication($app);

// Check each config file
$files = glob('config/*.php');
foreach ($files as $f) {
    $name = basename($f);
    try {
        // Simulate what LoadConfiguration does
        $base = [];
        $path = $f;
        $config = (fn () => require $path)();
        echo "$name: " . gettype($config) . "\n";
    } catch (Throwable $e) {
        echo "$name: ERROR - " . $e->getMessage() . "\n";
    }
}
