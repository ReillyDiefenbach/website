<?php

$pageLang = strtolower(str_replace('_', '-', trim((string)($_REQUEST['lang'] ?? $_COOKIE['lCode'] ?? 'de'))));
$pageLand = strtoupper(substr(trim((string)($_REQUEST['land'] ?? $_COOKIE['cCode'] ?? 'AT')), 0, 2));
$routePattern = '/^[a-zA-Z0-9_-]+(?:\/[a-zA-Z0-9_-]+)*$/';
$requestPath = trim((string)(parse_url((string)($_SERVER['REQUEST_URI'] ?? '/'), PHP_URL_PATH) ?? ''), '/');
$pathShowSite = str_starts_with($requestPath, 'show=')
    ? substr($requestPath, strlen('show='))
    : null;
$showRequested = array_key_exists('show', $_GET) || $pathShowSite !== null;
$showSite = trim((string)($_GET['show'] ?? $pathShowSite ?? ''), '/');
$requestedSite = trim((string)($_GET['site'] ?? ''), '/');

if (!preg_match('/^[a-z]{2,3}(?:-[a-z]{2})?$/i', $pageLang)) {
    $pageLang = 'de';
}

if (!preg_match('/^[A-Z]{2}$/', $pageLand)) {
    $pageLand = 'AT';
}

if ($showRequested && ($showSite === '' || !preg_match($routePattern, $showSite))) {
    http_response_code(400);
    exit('Invalid show route');
}

if ($requestedSite !== '' && !preg_match($routePattern, $requestedSite)) {
    $requestedSite = '';
}

$isShowMode = $showRequested;
$initialSite = $isShowMode ? $showSite : $requestedSite;
$isPathRoute = (
    ($isShowMode && str_starts_with($requestPath, 'show='))
    || (!$isShowMode && $initialSite !== '' && $requestPath === $initialSite)
);

if (!defined('LANG')) {
    define('LANG', substr($pageLang, 0, 2));
}

if (!defined('LAND')) {
    define('LAND', $pageLand);
}

$isAjaxRequest = strtolower((string)($_SERVER['HTTP_X_REQUESTED_WITH'] ?? '')) === 'xmlhttprequest';
$cdnVersion = '20260618';
$cdnOutname = 'carlvon';
$repositoryCdnBase = rtrim((string)(getenv('CARLVON_REPOSITORY_CDN') ?: 'https://cdn.jsdelivr.net/gh/carlvon/carlvon-cdn@main'), '/');
$hostName = strtolower((string)($_SERVER['HTTP_HOST'] ?? ''));
$useRepositoryCdn = !preg_match('/^(localhost|127\.0\.0\.1|\[::1\])(?::\d+)?$/', $hostName);
$scriptFile = $useRepositoryCdn ? $cdnOutname . '.min.js' : $cdnOutname . '.js';

if (!$isAjaxRequest) {
    try {
        require_once __DIR__ . '/__wolfi/_fileMaker/makeCDN.php';
        $cdnBuild = makeCDN_ifChanged();
        $cdnVersion = substr((string)($cdnBuild['hash'] ?? $cdnVersion), 0, 12);
    } catch (Throwable $exception) {
        $cssFile = __DIR__ . '/_cdn/' . $cdnOutname . '.min.css';
        $jsFile = __DIR__ . '/_cdn/' . $cdnOutname . '.min.js';
        $cdnVersion = (string) max(is_file($cssFile) ? filemtime($cssFile) : 0, is_file($jsFile) ? filemtime($jsFile) : 0, 20260618);
    }
}

function carlvon_cdn_url(string $path, string $version = ''): string
{
    global $repositoryCdnBase, $useRepositoryCdn;

    if (!$useRepositoryCdn) {
        return carlvon_local_url($path, $version);
    }

    $url = $repositoryCdnBase . '/' . ltrim($path, '/');

    return $version !== '' ? $url . '?v=' . rawurlencode($version) : $url;
}

function carlvon_local_url(string $path, string $version = ''): string
{
    $url = '/' . ltrim($path, '/');

    return $version !== '' ? $url . '?v=' . rawurlencode($version) : $url;
}

function carlvon_cdn_fallback_attr(string $localPath, string $version = ''): string
{
    return "this.onerror=null;this.href='" . htmlspecialchars(carlvon_local_url($localPath, $version), ENT_QUOTES, 'UTF-8') . "'";
}

function carlvon_media_src(string $localPath): string
{
    return htmlspecialchars(carlvon_cdn_url($localPath), ENT_QUOTES, 'UTF-8');
}

function carlvon_media_fallback_attr(string $localPath): string
{
    return "this.onerror=null;this.src='" . htmlspecialchars(carlvon_local_url($localPath), ENT_QUOTES, 'UTF-8') . "'";
}
?>
<!doctype html>
<html
    lang="<?= htmlspecialchars($pageLang, ENT_QUOTES, 'UTF-8') ?>"
    land="<?= htmlspecialchars($pageLand, ENT_QUOTES, 'UTF-8') ?>"
    data-site="<?= htmlspecialchars($initialSite !== '' ? $initialSite : 'home', ENT_QUOTES, 'UTF-8') ?>"
    data-initial-site="<?= htmlspecialchars($initialSite, ENT_QUOTES, 'UTF-8') ?>"
    data-view-mode="<?= $isShowMode ? 'show' : 'full' ?>"
    data-route-style="<?= $isPathRoute ? 'path' : 'query' ?>"
    data-font="gotham"
>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <base href="/">
    <title>CarlVon</title>
    <?php require __DIR__ . '/frame/_favicons.php'; ?>
    <?php if (!$isShowMode): ?>
        <script>document.documentElement.classList.add('isLoading');</script>
    <?php endif; ?>
    <link rel="preload" href="/_assets/fonts/Gotham/GothamRounded Book.otf" as="font" type="font/otf" crossorigin>
    <link
        rel="stylesheet"
        href="<?= htmlspecialchars(carlvon_cdn_url('_cdn/' . $cdnOutname . '.typography.min.css', $cdnVersion), ENT_QUOTES, 'UTF-8') ?>"
        onerror="<?= carlvon_cdn_fallback_attr('_cdn/' . $cdnOutname . '.typography.min.css', $cdnVersion) ?>"
    >
    <link
        rel="stylesheet"
        href="<?= htmlspecialchars(carlvon_cdn_url('_cdn/' . $cdnOutname . '.min.css', $cdnVersion), ENT_QUOTES, 'UTF-8') ?>"
        onerror="<?= carlvon_cdn_fallback_attr('_cdn/' . $cdnOutname . '.min.css', $cdnVersion) ?>"
    >
</head>

<body>
    <?php if (!$isShowMode): ?>
        <?php require __DIR__ . '/frame/_preLoader.php'; ?>

        <div class="cookieConsent" role="dialog" aria-modal="true" aria-label="Cookie-Einstellungen">
            <div class="cookieConsent__panel" data-cookie-content></div>
        </div>

        <header class="siteHeader">
            <div class="siteHeader__brand">
                <div id="top-logo" data-link="home" aria-label="CarlVon Home">
                    <img src="/_assets/logos/carlvon_framed.png" />
                </div>
            </div>

            <?php require __DIR__ . '/admin/menu/navmenu.php'; ?>
        </header>
    <?php endif; ?>

    <main id="middle"></main>

    <?php if (!$isShowMode): ?>
        <div class="menuPageBlur" aria-hidden="true"></div>

        <button class="scrollTopButton" type="button" aria-label="Scroll to top"></button>

        <?php require __DIR__ . '/frame/_footer.php'; ?>
    <?php endif; ?>

    <script src="<?= htmlspecialchars(carlvon_cdn_url('_cdn/' . $scriptFile, $cdnVersion), ENT_QUOTES, 'UTF-8') ?>" onerror="this.onerror=null;this.src='<?= htmlspecialchars(carlvon_local_url('_cdn/' . $cdnOutname . '.js', $cdnVersion), ENT_QUOTES, 'UTF-8') ?>'"></script>
</body>
</html>
