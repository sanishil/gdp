<?php

/**
 * Clears Laravel caches from the browser on hosts without SSH (e.g. InfinityFree).
 *
 * Runs: config:clear, cache:clear, route:clear, view:clear, optimize:clear
 *
 * Usage: https://your-domain.com/clear-cache.php?token=YOUR_SECRET
 * The token must match CACHE_CLEAR_TOKEN in the .env file.
 */

use Dotenv\Dotenv;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Artisan;

require __DIR__.'/../vendor/autoload.php';

header('Content-Type: text/plain; charset=utf-8');

$environmentFile = __DIR__.'/../.env';
$environmentValues = file_exists($environmentFile) ? Dotenv::parse(file_get_contents($environmentFile)) : [];
$expectedToken = (string) ($environmentValues['CACHE_CLEAR_TOKEN'] ?? '');
$providedToken = (string) ($_GET['token'] ?? '');

if ($expectedToken === '' || ! hash_equals($expectedToken, $providedToken)) {
    http_response_code(403);
    exit('Forbidden');
}

/**
 * Remove cached bootstrap files first, so a stale cached config/route
 * cannot stop the application from booting.
 */
$bootstrapCacheFiles = glob(__DIR__.'/../bootstrap/cache/*.php') ?: [];

foreach ($bootstrapCacheFiles as $bootstrapCacheFile) {
    if (@unlink($bootstrapCacheFile)) {
        echo 'Deleted bootstrap/cache/'.basename($bootstrapCacheFile).PHP_EOL;
    }
}

/** @var Application $app */
$app = require_once __DIR__.'/../bootstrap/app.php';
$app->make(Kernel::class)->bootstrap();

$commands = ['config:clear', 'cache:clear', 'route:clear', 'view:clear', 'optimize:clear'];

foreach ($commands as $command) {
    echo PHP_EOL.'> php artisan '.$command.PHP_EOL;

    try {
        Artisan::call($command);
        echo trim(Artisan::output()).PHP_EOL;
    } catch (Throwable $exception) {
        echo 'Failed: '.$exception->getMessage().PHP_EOL;
    }
}

echo PHP_EOL.'Done.';
