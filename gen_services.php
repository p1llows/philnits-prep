<?php
// Generate bootstrap/cache/services.php for Laravel
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
    'Illuminate\Redis\RedisServiceProvider',
];

$manifest = [
    'providers' => array_values(array_unique($providers)),
    'eager' => range(0, count($providers) - 1),
];

file_put_contents('bootstrap/cache/services.php', '<?php return ' . var_export($manifest, true) . ';');
echo "Generated services.php with " . count($providers) . " providers\n";
