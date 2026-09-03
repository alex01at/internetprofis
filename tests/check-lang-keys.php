<?php
/**
 * Checks the $lang[] translation system for two classes of problems:
 *
 *  1. Keys referenced somewhere in the code (e.g. $lang['button']['close'])
 *     that don't exist in languages/english.php and/or languages/deutsch.php.
 *     These cause a PHP warning at runtime and print nothing where translated
 *     text should be.
 *
 *  2. Keys defined in one language file but missing from the other -
 *     i.e. a string that only has an English or only a German version.
 *
 * It also flags exact-duplicate key definitions within a single language
 * file (harmless in PHP - the last one wins - but usually a sign of
 * copy-paste drift worth cleaning up).
 *
 * Limitation: only catches static key references, e.g. $lang['a']['b'].
 * A dynamic reference like $lang[$variable] can't be checked this way and
 * is silently skipped.
 *
 * Usage: php tests/check-lang-keys.php
 * Exit code: 0 if clean, 1 if any undefined-key references were found.
 * (Missing-translation and duplicate-key findings are reported but don't
 * fail the run, since they're not going to break anything at runtime.)
 */

$root = dirname(__DIR__);

function loadLangArray($file) {
    $lang = [];
    $previousLevel = error_reporting(E_ERROR | E_PARSE);
    require $file;
    error_reporting($previousLevel);
    return $lang;
}

function flattenKeys($arr, $prefix = '') {
    $keys = [];
    foreach ($arr as $k => $v) {
        $path = $prefix === '' ? (string)$k : $prefix . '.' . $k;
        if (is_array($v)) {
            $keys = array_merge($keys, flattenKeys($v, $path));
        } else {
            $keys[] = $path;
        }
    }
    return $keys;
}

function findDuplicateAssignments($file) {
    $content = file_get_contents($file);
    $lines = explode("\n", $content);
    $seen = [];
    $duplicates = [];
    $pattern = '/^\$lang((?:\s*\[\s*(?:\'[^\']*\'|"[^"]*")\s*\])+)\s*=/';
    foreach ($lines as $i => $line) {
        if (preg_match($pattern, trim($line), $m)) {
            preg_match_all('/\[\s*[\'"]([^\'"]*)[\'"]\s*\]/', $m[1], $parts);
            $path = implode('.', $parts[1]);
            if ($path === '') continue;
            if (isset($seen[$path])) {
                $duplicates[] = [$path, $seen[$path], $i + 1];
            }
            $seen[$path] = $i + 1;
        }
    }
    return $duplicates;
}

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

// --- Load both language files ---

$enLang = loadLangArray($root . '/languages/english.php');
$deLang = loadLangArray($root . '/languages/deutsch.php');

$enKeys = array_flip(flattenKeys($enLang));
$deKeys = array_flip(flattenKeys($deLang));

// --- Missing-translation check (defined in one file, not the other) ---

$missingInDe = array_diff_key($enKeys, $deKeys);
$missingInEn = array_diff_key($deKeys, $enKeys);

// --- Duplicate-key check within each language file ---

$dupEn = findDuplicateAssignments($root . '/languages/english.php');
$dupDe = findDuplicateAssignments($root . '/languages/deutsch.php');

// --- Scan the codebase for $lang[...] references ---

$skipDirs = ['.git', 'vendor', 'node_modules', 'GoogleAPI', 'Facebook', 'admin', 'languages', 'tests'];
$files = [];
collectPhpFiles($root, $skipDirs, $files);
sort($files);

$referenced = [];
$chainPattern = '/\$lang((?:\s*\[\s*(?:\'[^\']*\'|"[^"]*")\s*\])+)/';

foreach ($files as $file) {
    $content = file_get_contents($file);
    if (!preg_match_all($chainPattern, $content, $matches)) continue;
    foreach ($matches[1] as $chain) {
        preg_match_all('/\[\s*[\'"]([^\'"]*)[\'"]\s*\]/', $chain, $parts);
        $path = implode('.', $parts[1]);
        if ($path === '') continue;
        $referenced[$path][] = substr($file, strlen($root) + 1);
    }
}

$undefinedInEn = [];
foreach ($referenced as $path => $usedIn) {
    if (!isset($enKeys[$path])) {
        $undefinedInEn[$path] = $usedIn;
    }
}

// --- Report ---

$exitCode = 0;

echo "Loaded " . count($enKeys) . " English keys, " . count($deKeys) . " German keys.\n";
echo "Found " . count($referenced) . " distinct \$lang[] key paths referenced across " . count($files) . " files.\n\n";

if (!empty($undefinedInEn)) {
    $exitCode = 1;
    echo count($undefinedInEn) . " referenced key(s) with NO definition in languages/english.php:\n";
    foreach ($undefinedInEn as $path => $usedIn) {
        echo "  \$lang['" . str_replace('.', "']['", $path) . "']\n";
        foreach (array_unique($usedIn) as $f) {
            echo "      used in $f\n";
        }
    }
    echo "\n";
}

if (!empty($missingInDe)) {
    echo count($missingInDe) . " key(s) defined in English but missing from German:\n";
    foreach (array_keys($missingInDe) as $path) {
        echo "  \$lang['" . str_replace('.', "']['", $path) . "']\n";
    }
    echo "\n";
}

if (!empty($missingInEn)) {
    echo count($missingInEn) . " key(s) defined in German but missing from English:\n";
    foreach (array_keys($missingInEn) as $path) {
        echo "  \$lang['" . str_replace('.', "']['", $path) . "']\n";
    }
    echo "\n";
}

if (!empty($dupEn)) {
    echo count($dupEn) . " duplicate key definition(s) in languages/english.php:\n";
    foreach ($dupEn as [$path, $firstLine, $secondLine]) {
        echo "  \$lang['" . str_replace('.', "']['", $path) . "'] defined at line $firstLine and again at line $secondLine\n";
    }
    echo "\n";
}

if (!empty($dupDe)) {
    echo count($dupDe) . " duplicate key definition(s) in languages/deutsch.php:\n";
    foreach ($dupDe as [$path, $firstLine, $secondLine]) {
        echo "  \$lang['" . str_replace('.', "']['", $path) . "'] defined at line $firstLine and again at line $secondLine\n";
    }
    echo "\n";
}

if ($exitCode === 0 && empty($missingInDe) && empty($missingInEn) && empty($dupEn) && empty($dupDe)) {
    echo "No issues found.\n";
}

exit($exitCode);
