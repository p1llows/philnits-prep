<?php
// Delete all cache files and regenerate them properly
$providers = [
    'Illuminate\Filesystem\FilesystemServiceProvider',
    'Illuminate\Auth\AuthServiceProvider',
    'Illuminate\Broadcasting\BroadcastServiceProvider',
    'Illuminate\Bus\BusServiceProvider',
    'Illuminate\Cache\CacheServiceProvider',
    'Illuminate\Cookie\CookieServiceProvider',
    'Illuminate\Database\DatabaseServiceProvider',
    'Illuminate\Encryption\EncryptionServiceProvider',
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
    'deferred' => [],
    'when' => [],
];

file_put_contents('bootstrap/cache/services.php', '<?php return ' . var_export($manifest, true) . ';');
file_put_contents('bootstrap/cache/packages.php', '<?php return [];');
echo "Cache files regenerated\n";
