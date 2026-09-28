<?php
// Run artisan through a wrapper that properly bootstraps
require __DIR__.'/vendor/autoload.php';

// Generate packages.php
$packages = [];
file_put_contents('bootstrap/cache/packages.php', '<?php return ' . var_export($packages, true) . ';');

// Generate build.php
$build = ['files' => []];
file_put_contents('bootstrap/cache/build.php', '<?php return ' . var_export($build, true) . ';');

// Create services.php properly
$providers = [
    'Illuminate\Auth\AuthServiceProvider',
    'Illuminate\Broadcasting\BroadcastServiceProvider',
    'Illuminate\Bus\BusServiceProvider',
    'Illuminate\Cache\CacheServiceProvider',
    'Illuminate\Cookie\CookieServiceProvider',
    'Illuminate\Database\DatabaseServiceProvider',
    'Illuminate\Encryption\EncryptionServiceProvider',
    'Illuminate\Filesystem\FilesystemServiceProvider',
    'Illuminate\Foundation\Providers\FoundationServiceProvider',
    'Illuminate\Hashing\HashServiceProvider',
    'Illuminate\Mail\MailServiceProvider',
    'Illuminate\Notifications\NotificationServiceProvider',
    'Illuminate\Pagination\PaginationServiceProvider',
    'Illuminate\Pipeline\PipelineServiceProvider',
    'Illuminate\Queue\QueueServiceProvider',
    'Illuminate\Routing\RoutingServiceProvider',
    'Illuminate\Session\SessionServiceProvider',
    'Illuminate\Translation\TranslationServiceProvider',
    'Illuminate\Validation\ValidationServiceProvider',
    'Illuminate\View\ViewServiceProvider',
];
$manifest = ['providers' => array_values(array_unique($providers)), 'eager' => range(0, count($providers) - 1)];
file_put_contents('bootstrap/cache/services.php', '<?php return ' . var_export($manifest, true) . ';');

// Now try to run artisan
$app = require __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$status = $kernel->handle(
    $input = new Symfony\Component\Console\Input\ArgvInput,
    new Symfony\Component\Console\Output\ConsoleOutput
);
$kernel->terminate($input, $status);
exit($status);
