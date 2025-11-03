<?php
// Simple cache clear script
echo "Clearing Laravel cache...\n";

// Remove cache files
$cachePaths = [
    'bootstrap/cache/packages.php',
    'bootstrap/cache/services.php',
    'bootstrap/cache/config.php',
    'storage/framework/cache/data/',
    'storage/framework/sessions/',
    'storage/framework/views/',
];

foreach ($cachePaths as $path) {
    if (file_exists($path)) {
        if (is_dir($path)) {
            array_map('unlink', glob("$path/*"));
            rmdir($path);
        } else {
            unlink($path);
        }
        echo "Removed: $path\n";
    }
}

echo "Cache cleared successfully!\n";
echo "Now run: php artisan serve\n";