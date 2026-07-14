<?php

$pageLang = strtolower(str_replace('_', '-', trim((string)($_REQUEST['lang'] ?? $_COOKIE['lCode'] ?? 'de'))));
$pageLand = strtoupper(substr(trim((string)($_REQUEST['land'] ?? $_COOKIE['cCode'] ?? 'AT')), 0, 2));

if (!preg_match('/^[a-z]{2,3}(?:-[a-z]{2})?$/i', $pageLang)) {
    $pageLang = 'de';
}

if (!preg_match('/^[A-Z]{2}$/', $pageLand)) {
    $pageLand = 'AT';
}

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
<html lang="<?= htmlspecialchars($pageLang, ENT_QUOTES, 'UTF-8') ?>" land="<?= htmlspecialchars($pageLand, ENT_QUOTES, 'UTF-8') ?>" data-site="home" data-font="gotham">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CarlVon</title>
    <script>document.documentElement.classList.add('isLoading');</script>
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
    <div class="wolf-preloader" role="status" aria-live="polite" aria-label="Seite wird geladen">
        <div class="wolf-preloader-content">
            <img class="wolf-preloader-image" src="<?= carlvon_media_src('_assets/media/img/vitruv.png') ?>" onerror="<?= carlvon_media_fallback_attr('_assets/media/img/vitruv.png') ?>" alt="" aria-hidden="true">
            <div class="wolf-preloader-slogan">
                Carl von <span aria-hidden="true"></span> Analytica
            </div>
            <div class="wolf-preloader-load" aria-hidden="true">
                <span class="wolf-preloader-progress"></span>
            </div>
            <div class="wolf-preloader-text">0%</div>
        </div>
    </div>

    <div class="cookieConsent" role="dialog" aria-modal="true" aria-label="Cookie-Einstellungen">
        <div class="cookieConsent__panel" data-cookie-content></div>
    </div>

    <header class="siteHeader">
        <div class="siteHeader__brand">
            <div id="top-icon" data-link="home" aria-label="Home">
                <svg xmlns="http://www.w3.org/2000/svg" width="1300" viewBox="0 0 974.88 1499.999933" height="2000" preserveAspectRatio="xMidYMid meet"><path fill="#000000" d="M 780.355469 1240.675781 L 194.226562 1240.675781 C 118.667969 1240.675781 57.414062 1301.9375 57.414062 1377.605469 L 57.414062 1445.085938 L 917.230469 1445.085938 L 917.230469 1377.605469 C 917.230469 1301.9375 855.914062 1240.675781 780.355469 1240.675781 "/><path fill="#000000" d="M 823.117188 319.242188 C 820.960938 308.683594 818.339844 298.371094 815.277344 288.375 C 781.480469 192.496094 674 158.816406 629.363281 148.726562 C 476.15625 120.828125 358.054688 75.464844 340.625 68.527344 C 340.625 68.527344 340.625 68.527344 340.5625 68.527344 C 339.59375 68.21875 338.625 69.246094 339.03125 70.214844 L 380.855469 172.40625 C 381.105469 173.0625 380.761719 173.84375 380.042969 174.09375 C 367.832031 177.9375 272.8125 209.617188 246.453125 274.878906 C 188.386719 418.652344 104.019531 438.738281 92.117188 440.894531 C 89.554688 441.363281 86.996094 442.113281 84.589844 443.207031 C 69.472656 450.113281 36.144531 478.636719 113.140625 585.667969 C 132.253906 615.722656 171.113281 626.125 202.691406 609.816406 C 233.550781 593.820312 230.769531 532.714844 293.460938 538.304688 C 437.707031 551.269531 465.128906 524.714844 497.769531 500.722656 C 498.644531 500.097656 499.925781 500.816406 499.769531 501.910156 C 496.394531 521.277344 471.84375 630.84375 330.785156 727.847656 C 218.121094 805.261719 133.285156 920.511719 198.285156 988.898438 C 230.832031 1023.140625 248.511719 1071.847656 251.011719 1078.8125 C 251.167969 1079.28125 251.636719 1079.59375 252.199219 1079.59375 L 743.5 1079.59375 C 744.53125 1079.59375 745.09375 1078.46875 744.53125 1077.6875 C 744.03125 1076.96875 743.5 1076.15625 742.78125 1075.1875 C 736.847656 1067.441406 731.660156 1059.035156 727.007812 1050.101562 C 714.605469 1028.484375 701.269531 999.460938 704.609375 979.558594 C 704.609375 979.464844 704.609375 979.308594 704.609375 979.152344 C 687.742188 876.054688 719.574219 740.96875 780.011719 638.683594 C 825.835938 561.113281 844.546875 431.148438 824.398438 325.738281 C 823.929688 323.738281 823.523438 321.585938 823.117188 319.242188 "/><path fill="#000000" d="M 785.480469 1109.429688 C 777.640625 1107.992188 770.578125 1107.117188 764.988281 1106.710938 C 763.238281 1106.648438 761.648438 1106.554688 760.210938 1106.554688 L 212.125 1106.554688 C 210.6875 1106.554688 209.09375 1106.648438 207.34375 1106.710938 C 201.753906 1107.117188 194.695312 1107.992188 186.855469 1109.429688 C 147.589844 1116.550781 87.871094 1135.921875 87.871094 1158.789062 C 87.871094 1181.566406 147.933594 1200.996094 187.105469 1208.121094 C 197.347656 1209.964844 206.125 1210.933594 212.125 1210.933594 L 760.210938 1210.933594 C 766.207031 1210.933594 774.984375 1209.964844 785.230469 1208.121094 C 824.398438 1200.996094 884.464844 1181.566406 884.464844 1158.789062 C 884.464844 1135.921875 824.742188 1116.550781 785.480469 1109.429688 "/></svg>
            </div>
            <div id="top-logo" data-link="home" aria-label="CarlVon Home">
                <svg id="carvon-svg" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 443.7 52.1"><g><path d="M36.7,52.1c-14.8,0-25.9-11.4-25.9-25.9v-0.1C10.8,11.7,21.6,0,37.1,0C46.6,0,52.3,3.2,57,7.8l-7.1,8.1c-3.9-3.5-7.8-5.7-12.9-5.7c-8.5,0-14.6,7.1-14.6,15.7v0.1c0,8.6,6,15.8,14.6,15.8c5.8,0,9.3-2.3,13.3-5.9l7.1,7.1C52.1,48.7,46.4,52.1,36.7,52.1z"/><path d="M114.5,51.3L109.9,40H88.6L84,51.3H72.6L94.3,0.5h10.2l21.6,50.8H114.5z M99.2,13.8l-6.7,16.3h13.4L99.2,13.8z"/><path d="M175.3,51.3l-10.8-16.1h-8.7v16.1h-11.1V0.9h23c11.9,0,19,6.3,19,16.6v0.1c0,8.1-4.4,13.2-10.8,15.6l12.3,18H175.3z M175.5,18.1c0-4.8-3.3-7.2-8.7-7.2h-11v14.5H167c5.4,0,8.5-2.9,8.5-7.1V18.1z"/><path d="M207.5,51.3V0.9h11.1v40.3h25.1v10.1H207.5z"/><path d="M280.4,51.6h-9.8L250.3,0.9h12.2l13.2,35.5l13.2-35.5h12L280.4,51.6z"/><path d="M340.6,52.1c-15.6,0-26.7-11.6-26.7-25.9v-0.1c0-14.3,11.3-26.1,26.9-26.1s26.7,11.6,26.7,25.9v0.1C367.5,40.4,356.2,52.1,340.6,52.1z M355.9,26.1c0-8.6-6.3-15.8-15.3-15.8s-15.1,7.1-15.1,15.7v0.1c0,8.6,6.3,15.8,15.3,15.8s15.1-7.1,15.1-15.7V26.1z"/><path d="M423.5,51.3l-24.4-32v32h-10.9V0.9h10.2l23.6,31v-31h10.9v50.4H423.5z"/></g></svg>
            </div>
        </div>

        <?php require __DIR__ . '/admin/menu/navmenu.php'; ?>
    </header>

    <main id="middle"></main>

    <div class="menuPageBlur" aria-hidden="true"></div>

    <button class="scrollTopButton" type="button" aria-label="Nach oben scrollen"></button>

    <footer class="siteFooter">© CarlVon</footer>

    <script src="<?= htmlspecialchars(carlvon_cdn_url('_cdn/' . $scriptFile, $cdnVersion), ENT_QUOTES, 'UTF-8') ?>" onerror="this.onerror=null;this.src='<?= htmlspecialchars(carlvon_local_url('_cdn/' . $cdnOutname . '.js', $cdnVersion), ENT_QUOTES, 'UTF-8') ?>'"></script>
</body>
</html>
