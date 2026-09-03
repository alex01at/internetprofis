<?php
/**
 * Catches a narrower, mechanical mistake: a literal backslash directly
 * before "$lang", i.e. "\$lang[...]" appearing in actual PHP code (not
 * inside a string or comment). This happened repeatedly when scripted
 * edits (e.g. a non-raw Python string) accidentally escaped the $ - the
 * backslash then shows up literally in the file and breaks the syntax
 * (or, inside a double-quoted string, prints a literal backslash).
 *
 * Usage: php tests/check-stray-backslashes.php
 * Exit code: 0 if clean, 1 if any occurrences were found.
 */

$root = dirname(__DIR__);
$skipDirs = ['.git', 'vendor', 'node_modules', 'GoogleAPI', 'Facebook', 'admin', 'tests'];

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

$hits = [];
foreach ($files as $file) {
    $lines = file($file);
    if ($lines === false) continue;
    foreach ($lines as $i => $line) {
        if (strpos($line, '\\$lang') !== false) {
            $relative = substr($file, strlen($root) + 1);
            $hits[] = "$relative:" . ($i + 1) . ": " . trim($line);
        }
    }
}

if (empty($hits)) {
    echo "No stray backslash-escaped \\\$lang found.\n";
    exit(0);
}

echo count($hits) . " occurrence(s) of a literal backslash before \$lang:\n\n";
foreach ($hits as $hit) {
    echo "  $hit\n";
}
exit(1);
