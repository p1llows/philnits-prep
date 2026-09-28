<?php
foreach (glob('config/*.php') as $f) {
    $r = require $f;
    echo basename($f) . ': ' . gettype($r) . "\n";
    if (gettype($r) !== 'array') {
        echo "  VALUE: " . print_r($r, true) . "\n";
    }
}
