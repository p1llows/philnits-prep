<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
require __DIR__.'/vendor/autoload.php';

echo "Before require app.php\n";
$app = require __DIR__.'/bootstrap/app.php';
echo "After require app.php\n";
echo "App class: " . get_class($app) . "\n";
echo "App singleton instance set: " . ($app->getInstance('app') ? 'yes' : 'no') . "\n";

// Check if Container::getInstance() returns the app
echo "Container instance: " . (Illuminate\Container\Container::getInstance() ? 'yes' : 'no') . "\n";

// Check if facade application is set via the app
echo "App has instance 'app': " . ($app->getInstance('app') ? 'yes' : 'no') . "\n";
