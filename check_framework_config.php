<?php
foreach (glob('vendor/laravel/framework/config/*.php') as $f) {
    try {
        $r = require $f;
        echo basename($f) . ': ' . gettype($r) . "\n";
    } catch (Throwable $e) {
        echo basename($f) . ': ERROR - ' . $e->getMessage() . "\n";
    }
}
