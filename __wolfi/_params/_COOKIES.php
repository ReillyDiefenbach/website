<?php

/* =========================================================
   COOKIE / USER / LANG / LAND BOOTSTRAP
   Voraussetzung:
   - encoder() ist bereits geladen
   - NATIONPATH ist bereits definiert
   ========================================================= */

$cookieExpire = time() + 60 * 60 * 24 * 365;

/* user */
$uCode = null;

if (!empty($_COOKIE['uCode'])) {
    $decodedUCode = encoder($_COOKIE['uCode']);

    if (is_numeric($decodedUCode)) {
        $uCode = (int)$decodedUCode;
    }
}

$un = !empty($_COOKIE['username']) ? $_COOKIE['username'] : null;
$pw = !empty($_COOKIE['password']) ? $_COOKIE['password'] : null;


/* lang / land */
$lCode = null;
$cCode = null;

if (!empty($_REQUEST['lang'])) {

    $lCode = strtolower(trim($_REQUEST['lang']));
    $cCode = !empty($_REQUEST['land'])
        ? strtoupper(trim($_REQUEST['land']))
        : strtoupper(trim($_COOKIE['cCode'] ?? 'US'));

    setcookie('lCode', $lCode, $cookieExpire, "/");
    setcookie('cCode', $cCode, $cookieExpire, "/");

    $_COOKIE['lCode'] = $lCode;
    $_COOKIE['cCode'] = $cCode;

} elseif (!empty($_COOKIE['lCode'])) {

    $lCode = strtolower(trim($_COOKIE['lCode']));
    $cCode = strtoupper(trim($_COOKIE['cCode'] ?? 'US'));

} else {

    require_once NATIONPATH . 'cookieSetter.php';

    $geo = getGeoData();

    $lCode = strtolower($geo['language'] ?? 'en');
    $cCode = strtoupper($geo['country'] ?? 'US');

    setcookie('lCode', $lCode, $cookieExpire, "/");
    setcookie('cCode', $cCode, $cookieExpire, "/");

    $_COOKIE['lCode'] = $lCode;
    $_COOKIE['cCode'] = $cCode;
}


/* final fallback */
if (empty($lCode)) {
    $lCode = 'en';
}

if (empty($cCode)) {
    $cCode = 'US';
}


/* ISO language codes: de, en, pcm, fr-HT */
$lCode = str_replace('_', '-', trim((string) $lCode));

if (!preg_match('/^[a-z]{2,3}(?:-[a-z]{2})?$/i', $lCode)) {
    $lCode = 'en';
}

$cCode = substr($cCode, 0, 2);


/* Konstanten nur definieren, falls noch nicht definiert */
if (!defined('LANG')) {
    define('LANG', $lCode);
}

if (!defined('LAND')) {
    define('LAND', $cCode);
}
