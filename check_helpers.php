<?php
require __DIR__.'/vendor/autoload.php';
echo "env exists: " . (function_exists('env') ? 'yes' : 'no') . "\n";
echo "resource_path exists: " . (function_exists('resource_path') ? 'yes' : 'no') . "\n";
