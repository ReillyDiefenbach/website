<?php

declare(strict_types=1);

/**
 * Splits the currently verified CSS into a frozen non-menu basis and the
 * complete header/menu stylesheet. It never changes the input file.
 */
function splitHeaderCss(string $input, string $essentialOutput, string $headerOutput): array
{
    $css = (string) file_get_contents($input);
    [$essential, $header] = splitHeaderCssBlocks($css);

    file_put_contents($essentialOutput, trim($essential) . PHP_EOL);
    file_put_contents($headerOutput, trim($header) . PHP_EOL);

    return [
        'input_bytes' => strlen($css),
        'essential_bytes' => strlen(trim($essential) . PHP_EOL),
        'header_bytes' => strlen(trim($header) . PHP_EOL),
    ];
}

function splitHeaderCssBlocks(string $css): array
{
    $essential = '';
    $header = '';
    $length = strlen($css);
    $offset = 0;

    while ($offset < $length) {
        $open = splitHeaderCssFindOpenBrace($css, $offset);

        if ($open === null) {
            $essential .= substr($css, $offset);
            break;
        }

        $close = splitHeaderCssFindClosingBrace($css, $open);
        if ($close === null) {
            throw new RuntimeException('Unclosed CSS block near byte ' . $open);
        }

        $prelude = substr($css, $offset, $open - $offset);
        $body = substr($css, $open + 1, $close - $open - 1);
        $trimmedPrelude = trim($prelude);

        if (preg_match('/^@(media|supports|container|layer)\b/i', $trimmedPrelude)) {
            [$essentialChildren, $headerChildren] = splitHeaderCssBlocks($body);

            if (trim($essentialChildren) !== '') {
                $essential .= $prelude . '{' . $essentialChildren . '}' . PHP_EOL;
            }
            if (trim($headerChildren) !== '') {
                $header .= $prelude . '{' . $headerChildren . '}' . PHP_EOL;
            }
        } else {
            $block = $prelude . '{' . $body . '}' . PHP_EOL;
            if (splitHeaderCssIsHeaderSelector($trimmedPrelude)) {
                $header .= $block;
            } else {
                $essential .= $block;
            }
        }

        $offset = $close + 1;
    }

    return [$essential, $header];
}

function splitHeaderCssIsHeaderSelector(string $selector): bool
{
    return (bool) preg_match(
        '/(?:siteHeader|siteNavigation|siteMenu|mainMenu|bigMenu|languageMenu|moduleList|moduleCard|standardMenu|menuBackdrop|menuPageBlur|mobileMenu|menuToggle|burger-btn|top-burger|top-icon|top-logo|headinfo|nav-menu|body\.(?:menu-open|big-menu-open))/i',
        $selector
    );
}

function splitHeaderCssFindOpenBrace(string $css, int $offset): ?int
{
    $length = strlen($css);
    $quote = null;
    $comment = false;

    for ($i = $offset; $i < $length; $i++) {
        $char = $css[$i];
        $next = $i + 1 < $length ? $css[$i + 1] : '';

        if ($comment) {
            if ($char === '*' && $next === '/') {
                $comment = false;
                $i++;
            }
            continue;
        }
        if ($quote !== null) {
            if ($char === '\\') {
                $i++;
            } elseif ($char === $quote) {
                $quote = null;
            }
            continue;
        }
        if ($char === '/' && $next === '*') {
            $comment = true;
            $i++;
        } elseif ($char === '"' || $char === "'") {
            $quote = $char;
        } elseif ($char === '{') {
            return $i;
        }
    }

    return null;
}

function splitHeaderCssFindClosingBrace(string $css, int $open): ?int
{
    $length = strlen($css);
    $depth = 0;
    $quote = null;
    $comment = false;

    for ($i = $open; $i < $length; $i++) {
        $char = $css[$i];
        $next = $i + 1 < $length ? $css[$i + 1] : '';

        if ($comment) {
            if ($char === '*' && $next === '/') {
                $comment = false;
                $i++;
            }
            continue;
        }
        if ($quote !== null) {
            if ($char === '\\') {
                $i++;
            } elseif ($char === $quote) {
                $quote = null;
            }
            continue;
        }
        if ($char === '/' && $next === '*') {
            $comment = true;
            $i++;
        } elseif ($char === '"' || $char === "'") {
            $quote = $char;
        } elseif ($char === '{') {
            $depth++;
        } elseif ($char === '}') {
            $depth--;
            if ($depth === 0) {
                return $i;
            }
        }
    }

    return null;
}

if (PHP_SAPI === 'cli' && realpath((string) ($_SERVER['SCRIPT_FILENAME'] ?? '')) === __FILE__) {
    $root = dirname(__DIR__, 2);
    if (!is_dir(__DIR__ . DIRECTORY_SEPARATOR . 'css')) {
        mkdir(__DIR__ . DIRECTORY_SEPARATOR . 'css', 0775, true);
    }
    $result = splitHeaderCss(
        $root . DIRECTORY_SEPARATOR . '_cdn' . DIRECTORY_SEPARATOR . 'carlvon.css',
        __DIR__ . DIRECTORY_SEPARATOR . 'css' . DIRECTORY_SEPARATOR . 'essential.css',
        __DIR__ . DIRECTORY_SEPARATOR . 'scss' . DIRECTORY_SEPARATOR . 'headermenu.scss'
    );
    echo json_encode($result, JSON_PRETTY_PRINT) . PHP_EOL;
}
