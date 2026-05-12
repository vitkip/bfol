<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$news = \App\Models\News::find(16);
if (!$news) {
    echo "News 16 not found\n";
} else {
    try {
        $news->delete();
        echo "Successfully deleted News 16\n";
    } catch (\Throwable $e) {
        echo "Error deleting: " . $e->getMessage() . "\n";
    }
}
