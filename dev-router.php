<?php
declare(strict_types=1);

/**
 * Router for PHP's built-in development server.
 * Start with: php -S localhost:8000 dev-router.php
 */
$requestPath = (string)(parse_url((string)($_SERVER['REQUEST_URI'] ?? '/'), PHP_URL_PATH) ?? '/');
$decodedPath = rawurldecode($requestPath);
$localPath = __DIR__ . str_replace('/', DIRECTORY_SEPARATOR, $decodedPath);

if ($decodedPath !== '/' && is_file($localPath)) {
    return false;
}

$route = trim($decodedPath, '/');

if (preg_match('#^show=([a-zA-Z0-9_-]+(?:/[a-zA-Z0-9_-]+)*)/?$#', $route, $matches)) {
    $_GET['show'] = $matches[1];
    $_REQUEST['show'] = $matches[1];
} elseif (preg_match('#^((?:mod|frame)/[a-zA-Z0-9_-]+(?:/[a-zA-Z0-9_-]+)*)/?$#', $route, $matches)) {
    $_GET['site'] = $matches[1];
    $_REQUEST['site'] = $matches[1];
}

require __DIR__ . '/index.php';
