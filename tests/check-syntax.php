<?php
/**
 * Runs `php -l` over every project PHP file and reports failures.
 * Excludes vendored third-party code (vendor/, GoogleAPI/, Facebook/, node_modules/).
 *
 * Usage: php tests/check-syntax.php
 * Exit code: 0 if all files parse, 1 if any file has a syntax error.
 */

$root = dirname(__DIR__);
$skipDirs = ['.git', 'vendor', 'node_modules', 'GoogleAPI', 'Facebook'];

function collectPhpFiles($dir, $skipDirs, &$files) {
    foreach (scandir($dir) as $entry) {
        if ($entry === '.' || $entry === '..') continue;
        $path = $dir . '/' . $entry;
        if (is_dir($path)) {
            if (in_array($entry, $skipDirs, true)) continue;
            collectPhpFiles($path, $skipDirs, $files);
        } elseif (substr($entry, -4) === '.php') {
            $files[] = $path;
        }
    }
}

$files = [];
collectPhpFiles($root, $skipDirs, $files);
sort($files);

$failures = [];
foreach ($files as $file) {
    $output = [];
    $exitCode = 0;
    exec('php -l ' . escapeshellarg($file) . ' 2>&1', $output, $exitCode);
    if ($exitCode !== 0) {
        $failures[$file] = implode("\n", $output);
    }
}

$total = count($files);
$failCount = count($failures);

echo "Checked $total PHP files.\n";

if ($failCount === 0) {
    echo "All files parse cleanly.\n";
    exit(0);
}

echo "\n$failCount file(s) with syntax errors:\n\n";
foreach ($failures as $file => $output) {
    $relative = substr($file, strlen($root) + 1);
    echo "=== $relative ===\n$output\n\n";
}
exit(1);
