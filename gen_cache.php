<?php
$providers = [];
$iterator = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator('vendor', RecursiveDirectoryIterator::SKIP_DOTS)
);
foreach ($iterator as $file) {
    if ($file->getExtension() !== 'php') continue;
    $content = file_get_contents($file->getPathname());
    if (preg_match('/class\s+(\w+ServiceProvider)\s+extends\s+ServiceProvider/', $content, $m)) {
        $relPath = substr($file->getPath(), strlen('vendor'));
        $ns = str_replace('/', '\\', $relPath);
        $parts = explode('\\', $ns);
        if (count($parts) >= 3 && $parts[0] === 'laravel' && $parts[1] === 'framework') {
            $providerClass = 'Illuminate\\' . implode('\\', array_slice($parts, 3)) . '\\' . $m[1];
            $providerClass = preg_replace('/\\\\src\\\\/', '\\\\', $providerClass);
            $providerClass = str_replace('\\src\\', '\\', $providerClass);
        } else {
            $packageName = $parts[0] . '/' . $parts[1];
            $providerClass = $packageName . '\\' . implode('\\', array_slice($parts, 2)) . '\\' . $m[1];
        }
        $providers[] = $providerClass;
    }
}
$eager = range(0, count($providers) - 1);
$content = '<?php return ' . var_export(['providers' => array_values(array_unique($providers)), 'eager' => $eager], true) . ';';
file_put_contents('bootstrap/cache/services.php', $content);
echo "Generated " . count($providers) . " providers\n";
