<?php
// Search for env function definition
$files = glob('vendor/laravel/framework/src/Illuminate/**/*.php');
foreach ($files as $f) {
    $content = file_get_contents($f);
    if (strpos($content, 'function env(') !== false || strpos($content, 'function env (') !== false) {
        echo "Found in: $f\n";
    }
}
echo "Done.\n";
