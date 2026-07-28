<?php

$req = $_POST['req'] ?? '';
$requestedSite = trim((string) ($_POST['site'] ?? ''), '/');

if ($req !== 'site') {
    http_response_code(400);
    exit('Invalid request');
}

if (
    $requestedSite === ''
    || !preg_match('/^[a-zA-Z0-9_-]+(?:\/[a-zA-Z0-9_-]+)*$/', $requestedSite)
) {
    http_response_code(400);
    exit('Invalid site');
}

$requestedLanguage = str_replace('_', '-', (string) ($_POST['lang'] ?? (defined('LANG') ? LANG : 'de')));

if (!preg_match('/^[a-z]{2,3}(?:-[a-z]{2})?$/i', $requestedLanguage)) {
    $requestedLanguage = 'de';
}

$baseLanguage = strtolower(explode('-', $requestedLanguage)[0]);
$languages = array_values(array_unique([
    $requestedLanguage,
    $baseLanguage,
    'de',
    'en',
]));

$root = dirname(__DIR__, 2);
$programRoot = __DIR__;
$sitesRoot = $root . '/sites';
$routeAliases = [$requestedSite];

if (strpos($requestedSite, 'site/') === 0) {
    $routeAliases[] = substr($requestedSite, 5);
}

// Public menu routes use "calculations"; existing content remains under
// sites/frame/calculation so current files and internal references stay intact.
if (
    $requestedSite === 'frame/calculations'
    || strpos($requestedSite, 'frame/calculations/') === 0
) {
    $routeAliases[] = 'frame/calculation'
        . substr($requestedSite, strlen('frame/calculations'));
}

$routeAliases = array_values(array_unique(array_filter($routeAliases)));
$paths = [];

function backbone_read_json_file(string $path): ?array
{
    if (!is_file($path)) {
        return null;
    }

    $content = (string)file_get_contents($path);
    $content = preg_replace('/^\xEF\xBB\xBF/', '', $content);
    $data = json_decode($content, true);

    return is_array($data) ? $data : null;
}

function backbone_template_value(array $data, string $path): mixed
{
    $current = $data;

    foreach (explode('.', $path) as $segment) {
        $segment = trim($segment);

        if ($segment === '' || !is_array($current) || !array_key_exists($segment, $current)) {
            return null;
        }

        $current = $current[$segment];
    }

    return $current;
}

function backbone_template_escape(mixed $value): string
{
    if (is_array($value) || is_object($value)) {
        return '';
    }

    return htmlspecialchars((string)$value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function backbone_render_template(string $template, array $data): string
{
    $template = preg_replace_callback(
        '/{{#each\s+([a-zA-Z0-9_.-]+)}}(.*?){{\/each}}/s',
        static function (array $matches) use ($data): string {
            $items = backbone_template_value($data, $matches[1]);

            if (!is_array($items)) {
                return '';
            }

            $output = '';

            foreach ($items as $item) {
                $scope = is_array($item) ? $item : ['value' => $item];
                $output .= backbone_render_template($matches[2], array_replace($data, $scope));
            }

            return $output;
        },
        $template
    );

    return preg_replace_callback(
        '/{{\s*([a-zA-Z0-9_.-]+)\s*}}/',
        static fn (array $matches): string => backbone_template_escape(backbone_template_value($data, $matches[1])),
        $template
    );
}

// All module pages share one hero wrapper. Language-specific files in
// mod/{short}/ only contain the content that follows the hero.
if (preg_match('#^mod/([a-zA-Z0-9_-]+)$#', $requestedSite, $moduleRoute)) {
    $moduleShort = $moduleRoute[1];
    require $root . '/mod/_wrapper.php';
    exit;
}

foreach ($routeAliases as $route) {
    foreach ([$root, $sitesRoot, $programRoot] as $searchRoot) {
        $routeRoot = $searchRoot . '/' . $route;
        $templateFile = $routeRoot . '/template.html';

        if (!is_file($templateFile)) {
            continue;
        }

        foreach ($languages as $language) {
            $data = backbone_read_json_file($routeRoot . '/' . $language . '.json');

            if ($data === null) {
                continue;
            }

            echo backbone_render_template((string)file_get_contents($templateFile), $data);
            exit;
        }
    }
}

foreach ($routeAliases as $route) {
    foreach ([$root, $sitesRoot, $programRoot] as $searchRoot) {
        foreach ($languages as $language) {
            $paths[] = $searchRoot . '/' . $route . '/' . $language . '.php';
            $paths[] = $searchRoot . '/' . $route . '/' . $language . '.html';
        }

        $paths[] = $searchRoot . '/' . $route . '.php';
        $paths[] = $searchRoot . '/' . $route . '/_index.php';
        $paths[] = $searchRoot . '/' . $route . '/index.php';
    }
}

/*
|--------------------------------------------------------------------------
| Admin-Controller mit Unterrouten
|--------------------------------------------------------------------------
|
| admin/legal/privacy wird beispielsweise von admin/legal/_index.php
| verarbeitet. $adminRoute bleibt für den Controller verfügbar.
|
*/

if (strpos($requestedSite, 'admin/') === 0) {
    $adminRoute = substr($requestedSite, 6);
    $adminSection = explode('/', $adminRoute)[0] ?? '';

    if ($adminSection !== '') {
        array_unshift($paths, $root . '/admin/' . $adminSection . '/_index.php');
    }
}

if (strpos($requestedSite, 'about/legal') === 0) {
    $adminRoute = 'legal' . substr($requestedSite, strlen('about/legal'));
    array_unshift($paths, $root . '/admin/legal/_index.php');
}

foreach (array_values(array_unique($paths)) as $file) {
    if (!is_file($file)) {
        continue;
    }

    if (str_ends_with($file, '.html')) {
        readfile($file);
    } else {
        require $file;
    }

    exit;
}

http_response_code(404);

$requestedPage = $requestedSite . '/' . $requestedLanguage;
$safeRequestedPage = htmlspecialchars($requestedPage, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');

echo '<section class="headinfo headinfo--fullscreen" aria-label="Seite nicht gefunden">'
    . '<div>404</div>'
    . '<div>Die Seite ' . $safeRequestedPage . ' konnte nicht gefunden werden.</div>'
    . '</section>';
