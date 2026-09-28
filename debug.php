<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
echo "App class: " . get_class($app) . "\n";
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
echo "Kernel class: " . get_class($kernel) . "\n";
$status = $kernel->handle(
    $input = new Symfony\Component\Console\Input\ArgvInput,
    new Symfony\Component\Console\Output\ConsoleOutput
);
exit($status);
