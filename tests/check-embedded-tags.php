<?php
/**
 * Catches the single most common bug from this project's translation work:
 * a literal `<?=` or `<?php` typed INSIDE an already-open PHP string
 * literal, e.g.:
 *
 *   echo "Hello <?= $name; ?>";      // never evaluates - prints literally
 *   $msg = 'Reason: <?= $why; ?>';   // same problem
 *
 * Once you're already inside a PHP string, another "<?=" is just text -
 * it does not open a new PHP tag. The fix is always string concatenation:
 *   echo "Hello ".$name;
 *
 * This is NOT always caught by `php -l`: if the surrounding string is
 * single-quoted (or the content still happens to form syntactically valid
 * PHP inside a double-quoted string), the file parses fine and just
 * silently renders the broken text - exactly the failure mode this check
 * is for. It uses PHP's own tokenizer, so it understands real PHP syntax
 * rather than guessing with regex.
 *
 * Usage: php tests/check-embedded-tags.php
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

$stringTokenTypes = [T_CONSTANT_ENCAPSED_STRING, T_ENCAPSED_AND_WHITESPACE];

$hits = [];
foreach ($files as $file) {
    $content = file_get_contents($file);
    if (strpos($content, '<?') === false) continue;

    $tokens = @token_get_all($content);
    if (!is_array($tokens)) continue;

    foreach ($tokens as $token) {
        if (!is_array($token)) continue;
        [$id, $text, $line] = $token;
        if (!in_array($id, $stringTokenTypes, true)) continue;
        if (strpos($text, '<?') === false) continue;

        $relative = substr($file, strlen($root) + 1);
        $snippet = trim(preg_replace('/\s+/', ' ', $text));
        if (strlen($snippet) > 90) {
            $snippet = substr($snippet, 0, 90) . '...';
        }
        $hits[] = "$relative:$line: $snippet";
    }
}

if (empty($hits)) {
    echo "No embedded <?= or <?php tags found inside string literals.\n";
    exit(0);
}

echo count($hits) . " string literal(s) containing a literal <?= or <?php tag - these will never be evaluated:\n\n";
foreach ($hits as $hit) {
    echo "  $hit\n";
}
exit(1);
