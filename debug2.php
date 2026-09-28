<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
require __DIR__.'/vendor/autoload.php';
$app = require __DIR__.'/bootstrap/app.php';
$app->boot();
echo "App booted successfully\n";
echo "App class: " . get_class($app) . "\n";
