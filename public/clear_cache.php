<?php
$appRoot = '/home/laolycfc/bfol';

// Clear config cache
@unlink($appRoot . '/bootstrap/cache/config.php');
@unlink($appRoot . '/bootstrap/cache/routes-v7.php');
@unlink($appRoot . '/bootstrap/cache/packages.php');
@unlink($appRoot . '/bootstrap/cache/services.php');

// Read new APP_URL from .env to confirm
$appUrl = 'NOT FOUND';
foreach (file($appRoot . '/.env') as $line) {
    if (str_starts_with(trim($line), 'APP_URL=')) {
        $appUrl = trim(explode('=', $line, 2)[1]);
        break;
    }
}

echo "Config cache cleared!\n";
echo "APP_URL is now: " . $appUrl . "\n";
echo "Delete this file after use.";
