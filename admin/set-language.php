<?php

$language = str_replace('_', '-', trim((string) ($_POST['lang'] ?? $_GET['lang'] ?? 'de')));
$direction = strtolower(trim((string) ($_POST['dir'] ?? $_GET['dir'] ?? 'ltr')));

if (!preg_match('/^[a-z]{2,3}(?:-[a-z]{2})?$/i', $language)) {
    $language = 'de';
}

if (!in_array($direction, ['ltr', 'rtl'], true)) {
    $direction = 'ltr';
}

$expires = time() + 60 * 60 * 24 * 365;

setcookie('lCode', $language, [
    'expires' => $expires,
    'path' => '/',
    'samesite' => 'Lax',
]);

setcookie('lDirection', $direction, [
    'expires' => $expires,
    'path' => '/',
    'samesite' => 'Lax',
]);

if (empty($_COOKIE['cCode'])) {
    setcookie('cCode', 'US', [
        'expires' => $expires,
        'path' => '/',
        'samesite' => 'Lax',
    ]);
}

if (($_POST['ajax'] ?? '') === '1') {
    header('Content-Type: application/json; charset=UTF-8');
    echo json_encode([
        'ok' => true,
        'language' => $language,
        'direction' => $direction,
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

header('Content-Type: text/html; charset=UTF-8');
?>
<!doctype html>
<html lang="<?= htmlspecialchars($language, ENT_QUOTES, 'UTF-8') ?>" dir="<?= $direction ?>">
<head>
    <meta charset="utf-8">
    <meta name="robots" content="noindex">
    <title>Sprache wird geladen</title>
</head>
<body>
    <script>
        sessionStorage.setItem('initialSite', 'admin/languages');
        location.replace('/');
    </script>
    <noscript>
        <a href="/">Weiter</a>
    </noscript>
</body>
</html>
