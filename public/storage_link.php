<?php
$laravelStorage = '/home/csfangko/public_html/csfa/storage/app/public';
$storageLink    = '/home/csfangko/public_html/storage';

echo "Laravel storage : " . $laravelStorage . "\n";
echo "Symlink target  : " . $storageLink . "\n";
echo "storage/app/pub exists: " . (is_dir($laravelStorage) ? 'YES' : 'NO') . "\n";
echo "public/storage status : " . (is_link($storageLink) ? 'SYMLINK -> ' . readlink($storageLink) : (is_dir($storageLink) ? 'DIRECTORY' : 'NOT EXISTS')) . "\n\n";

if (!is_dir($laravelStorage)) {
    echo "ERROR: storage/app/public not found.\n"; exit;
}
if (is_link($storageLink)) {
    echo "Already a symlink — done!\n"; exit;
}
if (!is_dir($storageLink)) {
    symlink($laravelStorage, $storageLink)
        ? print("Symlink created! Delete this file.\n")
        : print("Symlink creation failed.\n");
    exit;
}

$items  = array_diff(scandir($storageLink), ['.', '..', '.gitignore']);
$moved  = 0;
$errors = [];
foreach ($items as $item) {
    $src  = $storageLink . '/' . $item;
    $dest = $laravelStorage . '/' . $item;
    if (!file_exists($dest)) {
        @rename($src, $dest) ? $moved++ : ($errors[] = $item);
    } else {
        is_dir($src) ? rmdirRecursive($src) : @unlink($src);
        $moved++;
    }
}
@unlink($storageLink . '/.gitignore');

if (!empty($errors)) { echo "Errors: " . implode(', ', $errors) . "\n"; exit; }

$remaining = array_diff(scandir($storageLink), ['.', '..']);
if (!empty($remaining)) { echo "Still not empty: " . implode(', ', $remaining) . "\n"; exit; }

if (rmdir($storageLink)) {
    symlink($laravelStorage, $storageLink)
        ? print("SUCCESS: symlink created! Delete this file.\n")
        : print("rmdir OK but symlink failed.\n");
} else {
    echo "rmdir failed.\n";
}

function rmdirRecursive(string $dir): void {
    foreach (array_diff(scandir($dir), ['.', '..']) as $item) {
        $p = $dir . '/' . $item;
        is_dir($p) ? rmdirRecursive($p) : unlink($p);
    }
    rmdir($dir);
}
