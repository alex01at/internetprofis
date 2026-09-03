<?php
/**
 * Finds $lang[] keys whose English and German values are byte-identical -
 * usually a sign the German string was never actually translated, just
 * copied from English (or vice versa).
 *
 * Some identical values are correct on purpose: brand names (PayPal,
 * Bitcoin), country names that happen to be spelled the same in German
 * (Uganda, Nigeria), and common German loanwords (Chat, Status, Budget).
 * Those are listed in $allowlist below so they don't show up as noise.
 * If a new legitimately-identical value gets flagged, add its key path
 * to the allowlist rather than "fixing" it into a forced translation.
 *
 * Usage: php tests/check-identical-values.php
 * Exit code: 0 if clean (nothing new outside the allowlist), 1 otherwise.
 */

$root = dirname(__DIR__);

function loadLangArray($file) {
    $lang = [];
    $previousLevel = error_reporting(E_ERROR | E_PARSE);
    require $file;
    error_reporting($previousLevel);
    return $lang;
}

function flattenLang($arr, $prefix = '') {
    $out = [];
    foreach ($arr as $k => $v) {
        $path = $prefix === '' ? (string)$k : $prefix . '.' . $k;
        if (is_array($v)) {
            $out += flattenLang($v, $path);
        } else {
            $out[$path] = $v;
        }
    }
    return $out;
}

// Keys confirmed to be correctly identical in both languages: brand
// names, country names spelled the same in German, and common loanwords.
$allowlist = [
    'dusupay.bank',
    'dusupay.countries.UG',
    'dusupay.countries.BI',
    'dusupay.countries.GH',
    'dusupay.countries.NG',
    'dusupay.countries.SN',
    'sidebar.start_selling.tour',
    'proposals.video_heading',
    'packages.basic',
    'packages.standard',
    'label.phone_optional',
    'button.chat',
    'order_conversations.in_progress_prefix',
    'revenue.moneygram',
    'th.budget',
    'th.status',
    'th.minimum',
    'copyright_symbol',
    'order_details.status',
    'order_details.total',
];

$en = flattenLang(loadLangArray($root . '/languages/english.php'));
$de = flattenLang(loadLangArray($root . '/languages/deutsch.php'));

$flagged = [];
foreach ($en as $key => $value) {
    if (!isset($de[$key])) continue;
    if (trim((string)$value) === '') continue;
    if ($de[$key] !== $value) continue;
    if (in_array($key, $allowlist, true)) continue;
    $flagged[$key] = $value;
}

if (empty($flagged)) {
    echo "No unexpected identical English/German values.\n";
    exit(0);
}

echo count($flagged) . " key(s) with identical English and German text (likely untranslated):\n\n";
foreach ($flagged as $key => $value) {
    $display = strlen($value) > 80 ? substr($value, 0, 80) . '...' : $value;
    echo "  \$lang['" . str_replace('.', "']['", $key) . "'] = \"$display\"\n";
}
exit(1);
