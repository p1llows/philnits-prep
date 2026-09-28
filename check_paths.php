<?php
require __DIR__.'/vendor/autoload.php';
$app = require __DIR__.'/bootstrap/app.php';
Illuminate\Support\Facades\Facade::setFacadeApplication($app);

echo "Cached config path: " . $app->getCachedConfigPath() . "\n";
echo "Cached config exists: " . (file_exists($app->getCachedConfigPath()) ? 'yes' : 'no') . "\n";
echo "Cached config path value: " . $app->normalizeCachePath('APP_CONFIG_CACHE', 'cache/config.php') . "\n";

// Check what APP_CONFIG_CACHE is
echo "APP_CONFIG_CACHE: " . (getenv('APP_CONFIG_CACHE') ?: 'not set') . "\n";
echo "APP_SERVICES_CACHE: " . (getenv('APP_SERVICES_CACHE') ?: 'not set') . "\n";
