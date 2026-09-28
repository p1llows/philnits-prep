<?php
// Create bootstrap/cache/packages.php
$packages = [];
file_put_contents('bootstrap/cache/packages.php', '<?php return ' . var_export($packages, true) . ';');
echo "Generated packages.php\n";
