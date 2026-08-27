<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// 1. Prepare writable storage directories in Vercel's ephemeral /tmp folder
$storagePath = '/tmp/storage';
$directories = [
    $storagePath . '/framework/views',
    $storagePath . '/framework/cache/data',
    $storagePath . '/framework/sessions',
    $storagePath . '/bootstrap/cache',
    $storagePath . '/logs',
];

foreach ($directories as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
}

// 2. Determine if the application is in maintenance mode...
if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

// 3. Register the Composer autoloader...
require __DIR__.'/../vendor/autoload.php';

// 4. Bootstrap Laravel...
/** @var Application $app */
$app = require_once __DIR__.'/../bootstrap/app.php';

// 5. Override storage path to write logs, cache, and compiled views to /tmp
$app->useStoragePath($storagePath);

// 6. Handle the incoming request...
$app->handleRequest(Request::capture());