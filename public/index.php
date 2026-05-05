<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Standard structure: vendor/ sits next to public/ (local dev, VPS)
// Shared hosting: public/ is inside public_html/, app lives in ../bfol/
$appRoot = is_dir(dirname(__DIR__) . '/vendor')
    ? dirname(__DIR__)
    : realpath(__DIR__ . '/../bfol');

if (file_exists($maintenance = $appRoot . '/storage/framework/maintenance.php')) {
    require $maintenance;
}

require $appRoot . '/vendor/autoload.php';

/** @var Application $app */
$app = require_once $appRoot . '/bootstrap/app.php';

$app->handleRequest(Request::capture());
