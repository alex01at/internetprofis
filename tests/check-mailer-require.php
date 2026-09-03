<?php
/**
 * Catches the exact bug that caused a live 500 on widerruf.php: calling
 * send_mail() in a file whose include chain never actually loads
 * functions/mailer.php (where it's defined). includes/db.php does NOT
 * pull it in automatically - every entry point that wants to send mail
 * has to require it itself, directly or through something like
 * functions/email.php.
 *
 * This does real (if bounded) static/require-graph resolution rather
 * than a same-file grep, since the require is often one or two files
 * up the include chain (e.g. order_details.php -> functions/email.php
 * -> functions/mailer.php). It only follows require/include calls with
 * a literal string path - a dynamic include (a variable, a function
 * call) can't be resolved this way and is silently skipped, which can
 * hide a real problem. Treat a clean run as reassuring, not a guarantee.
 *
 * Usage: php tests/check-mailer-require.php
 * Exit code: 0 if every send_mail() caller's chain reaches
 * functions/mailer.php, 1 otherwise.
 */

$root = dirname(__DIR__);
$skipDirs = ['.git', 'vendor', 'node_modules', 'GoogleAPI', 'Facebook', 'admin', 'tests'];
$mailerFile = $root . '/functions/mailer.php';

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

// Extract literal-string require/include targets, resolved to absolute paths.
// Handles the codebase's common "$dir/..." pattern, where $dir is always
// the project root (set once in includes/db.php) - everything else with
// a variable in the path is unresolvable and gets skipped.
function extractIncludes($file, $root) {
    $content = @file_get_contents($file);
    if ($content === false) return [];
    $fileDir = dirname($file);
    $targets = [];
    if (preg_match_all('/\b(?:require|include)(?:_once)?\s*\(?\s*[\'"]([^\'"]+)[\'"]/', $content, $matches)) {
        foreach ($matches[1] as $rel) {
            if (strpos($rel, '$dir/') === 0 || strpos($rel, '$dir\\') === 0) {
                $resolved = realpath($root . '/' . substr($rel, 5));
            } elseif (strpos($rel, '$') !== false) {
                continue; // genuinely dynamic, can't resolve statically
            } else {
                $resolved = realpath($fileDir . '/' . $rel);
            }
            if ($resolved !== false) {
                $targets[] = $resolved;
            }
        }
    }
    return $targets;
}

function chainReachesMailer($file, $mailerFile, $root, &$visited, $depth = 0) {
    if ($file === $mailerFile) return true;
    if ($depth > 6) return false;
    if (isset($visited[$file])) return false;
    $visited[$file] = true;
    foreach (extractIncludes($file, $root) as $target) {
        if (chainReachesMailer($target, $mailerFile, $root, $visited, $depth + 1)) {
            return true;
        }
    }
    return false;
}

// Fragments that are only ever include()'d into a page that already
// requires functions/mailer.php (directly or via functions/email.php)
// before reaching them - verified manually, since that "parent already
// loaded it" relationship isn't something this script's forward-only
// chain walk can see for itself.
$allowlist = [
    'emails/orderEmail.php', // included via orderIncludes/order/*.php, all included from order.php which requires mailer.php directly
    'orderIncludes/modals/deliverOrderRevisionRequestModal.php', // included from order_details.php, which requires functions/email.php (which requires mailer.php)
];

$files = [];
collectPhpFiles($root, $skipDirs, $files);
sort($files);

$flagged = [];
foreach ($files as $file) {
    if ($file === $mailerFile) continue;
    $relative = substr($file, strlen($root) + 1);
    if (in_array($relative, $allowlist, true)) continue;
    $content = file_get_contents($file);
    if (!preg_match('/\bsend_mail\s*\(/', $content)) continue;

    $visited = [];
    if (!chainReachesMailer($file, $mailerFile, $root, $visited)) {
        $flagged[] = $relative;
    }
}

if (empty($flagged)) {
    echo "Every send_mail() caller's include chain reaches functions/mailer.php.\n";
    exit(0);
}

echo count($flagged) . " file(s) call send_mail() but their include chain never reaches functions/mailer.php:\n\n";
foreach ($flagged as $f) {
    echo "  $f\n";
}
echo "\nAdd require_once(\"functions/mailer.php\") (adjust the relative path as needed), or verify this manually if the include is dynamic.\n";
exit(1);
