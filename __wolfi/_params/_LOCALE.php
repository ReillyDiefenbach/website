<?php

function initLocale(): array
{
    $allowedLang = ['de', 'en', 'it', 'fr', 'es', 'pt'];
    $default = ['lang' => 'en', 'land' => 'US'];

    $reqLang = $_REQUEST['lang'] ?? null;
    $reqLand = $_REQUEST['land'] ?? $_REQUEST['country'] ?? null;

    if ($reqLang || $reqLand) {
        $lang = normalizeLang($reqLang, $allowedLang) ?? $default['lang'];
        $land = normalizeLand($reqLand) ?? langToDefaultLand($lang);

        saveLocaleCookies($lang, $land);
        return ['lang' => $lang, 'land' => $land];
    }

    if (!empty($_COOKIE['lCode']) || !empty($_COOKIE['cCode'])) {
        $lang = normalizeLang($_COOKIE['lCode'] ?? null, $allowedLang) ?? $default['lang'];
        $land = normalizeLand($_COOKIE['cCode'] ?? null) ?? langToDefaultLand($lang);

        return ['lang' => $lang, 'land' => $land];
    }

    $browserLang = getBrowserLang($allowedLang);
    if ($browserLang) {
        $land = langToDefaultLand($browserLang);
        saveLocaleCookies($browserLang, $land);
        return ['lang' => $browserLang, 'land' => $land];
    }

    $geo = getGeoDataSafe();
    saveLocaleCookies($geo['lang'], $geo['land']);

    return $geo;
}

function saveLocaleCookies(string $lang, string $land): void
{
    $expires = time() + 60 * 60 * 24 * 365;

    setcookie('lCode', $lang, [
        'expires' => $expires,
        'path' => '/',
        'samesite' => 'Lax'
    ]);

    setcookie('cCode', $land, [
        'expires' => $expires,
        'path' => '/',
        'samesite' => 'Lax'
    ]);

    $_COOKIE['lCode'] = $lang;
    $_COOKIE['cCode'] = $land;
}

function normalizeLang($lang, array $allowed): ?string
{
    if (!$lang) return null;

    $lang = strtolower(substr(trim((string)$lang), 0, 2));

    return in_array($lang, $allowed, true) ? $lang : null;
}

function normalizeLand($land): ?string
{
    if (!$land) return null;

    $land = strtoupper(substr(trim((string)$land), 0, 2));

    return preg_match('/^[A-Z]{2}$/', $land) ? $land : null;
}

function getBrowserLang(array $allowed): ?string
{
    $header = $_SERVER['HTTP_ACCEPT_LANGUAGE'] ?? '';
    if (!$header) return null;

    foreach (explode(',', $header) as $part) {
        $lang = normalizeLang($part, $allowed);
        if ($lang) return $lang;
    }

    return null;
}

function getGeoDataSafe(): array
{
    $ip = getClientIP();
    $default = ['land' => 'US', 'lang' => 'en'];

    $json = @file_get_contents("https://ipwho.is/" . urlencode($ip));
    if (!$json) return $default;

    $data = json_decode($json, true);
    if (empty($data['success'])) return $default;

    $land = normalizeLand($data['country_code'] ?? null) ?? 'US';
    $lang = landToLang($land);

    return ['land' => $land, 'lang' => $lang];
}

function getClientIP(): string
{
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        return $_SERVER['HTTP_CLIENT_IP'];
    }

    if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        return trim(explode(',', $_SERVER['HTTP_X_FORWARDED_FOR'])[0]);
    }

    return $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
}

function landToLang(string $land): string
{
    $map = [
        'DE' => 'de', 'AT' => 'de', 'CH' => 'de',
        'IT' => 'it', 'SM' => 'it', 'VA' => 'it',
        'FR' => 'fr', 'BE' => 'fr', 'LU' => 'fr', 'MC' => 'fr',
        'ES' => 'es', 'MX' => 'es', 'AR' => 'es', 'BO' => 'es',
        'CL' => 'es', 'CO' => 'es', 'CR' => 'es', 'CU' => 'es',
        'DO' => 'es', 'EC' => 'es', 'GT' => 'es', 'HN' => 'es',
        'NI' => 'es', 'PA' => 'es', 'PE' => 'es', 'PR' => 'es',
        'PY' => 'es', 'SV' => 'es', 'UY' => 'es', 'VE' => 'es',
        'PT' => 'pt', 'BR' => 'pt', 'AO' => 'pt', 'MZ' => 'pt',
        'GW' => 'pt', 'TL' => 'pt', 'CV' => 'pt', 'ST' => 'pt',
        'GQ' => 'pt'
    ];

    return $map[$land] ?? 'en';
}

function langToDefaultLand(string $lang): string
{
    return [
        'de' => 'AT',
        'en' => 'US',
        'it' => 'IT',
        'fr' => 'FR',
        'es' => 'ES',
        'pt' => 'PT'
    ][$lang] ?? 'US';
}