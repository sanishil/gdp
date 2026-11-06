<?php

/**
 * Runs `php artisan optimize:clear` from the browser on hosts without SSH (e.g. InfinityFree).
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

/** @var Application $app */
$app = require_once __DIR__.'/../bootstrap/app.php';
$app->make(Kernel::class)->bootstrap();

Artisan::call('optimize:clear');

echo Artisan::output();
echo PHP_EOL.'Done.';
