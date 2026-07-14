<?php

declare(strict_types=1);

/**
 * Builds public CDN assets from local SCSS and JS source files.
 *
 * Usage:
 *   require_once __DIR__ . '/__wolfi/_fileMaker/makeCDN.php';
 *   makeCDN_ifChanged();
 */

function makeCDN_ifChanged(bool $force = false): array
{
    $root = dirname(__DIR__, 2);
    $base = __DIR__;
    $cdn = $root . DIRECTORY_SEPARATOR . '_cdn';
    $manifestFile = $base . DIRECTORY_SEPARATOR . 'manifest.json';
    $outname = 'carlvon';

    $scssFiles = [
        $base . DIRECTORY_SEPARATOR . 'scss' . DIRECTORY_SEPARATOR . 'main.scss',
    ];

    $typographyFiles = [
        $base . DIRECTORY_SEPARATOR . 'scss' . DIRECTORY_SEPARATOR . 'typography.scss',
    ];

    $cssFiles = [
        $base . DIRECTORY_SEPARATOR . 'css' . DIRECTORY_SEPARATOR . 'essential.css',
        $base . DIRECTORY_SEPARATOR . 'scss' . DIRECTORY_SEPARATOR . 'sections.scss',
        $base . DIRECTORY_SEPARATOR . 'scss' . DIRECTORY_SEPARATOR . 'content-layouts.scss',
        $base . DIRECTORY_SEPARATOR . 'scss' . DIRECTORY_SEPARATOR . 'boxes.scss',
    ];

    $jsFiles = [
        $base . DIRECTORY_SEPARATOR . 'js' . DIRECTORY_SEPARATOR . 'main.js',
        $base . DIRECTORY_SEPARATOR . 'js' . DIRECTORY_SEPARATOR . 'site.js',
        $base . DIRECTORY_SEPARATOR . 'js' . DIRECTORY_SEPARATOR . 'nav_menu.js',
        $base . DIRECTORY_SEPARATOR . 'js' . DIRECTORY_SEPARATOR . 'sections.js',
        $base . DIRECTORY_SEPARATOR . 'js' . DIRECTORY_SEPARATOR . 'super_spy.js',
        $base . DIRECTORY_SEPARATOR . 'js' . DIRECTORY_SEPARATOR . 'switcher.js',
        $base . DIRECTORY_SEPARATOR . 'js' . DIRECTORY_SEPARATOR . 'factsheet.js',
    ];

    $sourceFiles = makeCDN_collectSources($typographyFiles, $base . DIRECTORY_SEPARATOR . 'scss');
    $sourceFiles = array_merge($sourceFiles, makeCDN_existingFiles($cssFiles));
    $sourceFiles = array_merge($sourceFiles, makeCDN_collectSources($scssFiles, $base . DIRECTORY_SEPARATOR . 'scss'));
    $sourceFiles = array_merge($sourceFiles, makeCDN_existingFiles($jsFiles));
    $hash = makeCDN_sourcesHash($sourceFiles);
    $manifest = makeCDN_readManifest($manifestFile);
    $expectedOutputs = [
        $cdn . DIRECTORY_SEPARATOR . $outname . '.typography.css',
        $cdn . DIRECTORY_SEPARATOR . $outname . '.typography.min.css',
        $cdn . DIRECTORY_SEPARATOR . $outname . '.css',
        $cdn . DIRECTORY_SEPARATOR . $outname . '.min.css',
        $cdn . DIRECTORY_SEPARATOR . $outname . '.js',
        $cdn . DIRECTORY_SEPARATOR . $outname . '.min.js',
    ];

    if (!$force && isset($manifest['hash']) && hash_equals((string) $manifest['hash'], $hash) && makeCDN_allFilesExist($expectedOutputs)) {
        return [
            'changed' => false,
            'hash' => $hash,
            'files' => [],
        ];
    }

    makeCDN_ensureDirectory($cdn);

    $variables = [];
    $js = makeCDN_joinJs($jsFiles);
    $jsMin = makeCDN_minifyJs($js);

    $outputs = [
        $cdn . DIRECTORY_SEPARATOR . $outname . '.js' => $js,
        $cdn . DIRECTORY_SEPARATOR . $outname . '.min.js' => $jsMin . PHP_EOL,
    ];

    $typographyCss = makeCDN_joinCompiledScss($typographyFiles, $variables);
    if (trim($typographyCss) !== '') {
        $outputs = [
            $cdn . DIRECTORY_SEPARATOR . $outname . '.typography.css' => $typographyCss,
            $cdn . DIRECTORY_SEPARATOR . $outname . '.typography.min.css' => makeCDN_minifyCss($typographyCss) . PHP_EOL,
        ] + $outputs;
    }

    $css = makeCDN_rootCss() . PHP_EOL . makeCDN_joinCss($cssFiles);
    if (is_file($scssFiles[0])) {
        $css .= PHP_EOL . trim(makeCDN_compileScssFile($scssFiles[0], $variables)) . PHP_EOL;
    }

    $css = makeCDN_normalizeCssClassTokens($css);

    if (trim($css) !== '') {
        $cssMin = makeCDN_minifyCss($css);

        $outputs = [
            $cdn . DIRECTORY_SEPARATOR . $outname . '.css' => $css,
            $cdn . DIRECTORY_SEPARATOR . $outname . '.min.css' => $cssMin . PHP_EOL,
        ] + $outputs;
    }

    foreach ($outputs as $file => $content) {
        file_put_contents($file, $content);
    }

    file_put_contents($manifestFile, json_encode([
        'hash' => $hash,
        'built_at' => date(DATE_ATOM),
        'sources' => array_map(static fn (string $file): string => makeCDN_relativePath($file, $root), $sourceFiles),
        'outputs' => array_map(static fn (string $file): string => makeCDN_relativePath($file, $root), array_keys($outputs)),
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . PHP_EOL);

    return [
        'changed' => true,
        'hash' => $hash,
        'files' => array_keys($outputs),
    ];
}

function makeCDN_collectSources(array $entryFiles, string $scssRoot): array
{
    $files = [];

    foreach (makeCDN_existingFiles($entryFiles) as $file) {
        $files[] = $file;
        $content = (string) file_get_contents($file);

        if (preg_match_all('/@(?:use|import)\s+[\'"]([^\'"]+)[\'"]\s*;/', $content, $matches)) {
            foreach ($matches[1] as $import) {
                $resolved = makeCDN_resolveScssImport($import, dirname($file), $scssRoot);
                if ($resolved !== null) {
                    $files = array_merge($files, makeCDN_collectSources([$resolved], $scssRoot));
                }
            }
        }
    }

    return array_values(array_unique($files));
}

function makeCDN_compileScssFile(string $file, array &$variables): string
{
    if (!is_file($file)) {
        return '';
    }

    $compiled = makeCDN_compileScssWithLibrary($file);
    if ($compiled !== null) {
        return $compiled;
    }

    $scssRoot = dirname($file);
    $content = makeCDN_expandScssImports($file, $scssRoot);

    [$content, $variables] = makeCDN_extractVariables($content, $variables);
    $content = makeCDN_applyVariables($content, $variables);

    return makeCDN_flattenNestedScss($content);
}

function makeCDN_compileScssWithLibrary(string $file): ?string
{
    $compilerFile = __DIR__ . DIRECTORY_SEPARATOR . 'program' . DIRECTORY_SEPARATOR . 'scss' . DIRECTORY_SEPARATOR . 'scss.inc.php';
    if (!is_file($compilerFile)) {
        return null;
    }

    $previousReporting = error_reporting();
    error_reporting($previousReporting & ~E_DEPRECATED & ~E_USER_DEPRECATED);

    try {
        require_once $compilerFile;

        if (!class_exists('ScssPhp\\ScssPhp\\Compiler')) {
            return null;
        }

        $compiler = new ScssPhp\ScssPhp\Compiler();

        if (method_exists($compiler, 'setImportPaths')) {
            $compiler->setImportPaths(dirname($file));
        }

        if (method_exists($compiler, 'setOutputStyle') && class_exists('ScssPhp\\ScssPhp\\OutputStyle')) {
            $compiler->setOutputStyle(ScssPhp\ScssPhp\OutputStyle::EXPANDED);
        }

        $result = $compiler->compileString((string) file_get_contents($file), $file);

        return method_exists($result, 'getCss') ? $result->getCss() : (string) $result;
    } catch (Throwable $exception) {
        return null;
    } finally {
        error_reporting($previousReporting);
    }
}

function makeCDN_expandScssImports(string $file, string $scssRoot, array $seen = []): string
{
    $realFile = realpath($file) ?: $file;
    if (isset($seen[$realFile]) || !is_file($file)) {
        return '';
    }

    $seen[$realFile] = true;
    $content = (string) file_get_contents($file);

    return preg_replace_callback('/@(?:use|import)\s+[\'"]([^\'"]+)[\'"]\s*;/', static function (array $match) use ($file, $scssRoot, $seen): string {
        $resolved = makeCDN_resolveScssImport($match[1], dirname($file), $scssRoot);

        return $resolved ? makeCDN_expandScssImports($resolved, $scssRoot, $seen) : '';
    }, $content) ?? '';
}

function makeCDN_extractVariables(string $scss, array $variables): array
{
    $scss = preg_replace_callback('/^\s*\$([a-zA-Z0-9_-]+)\s*:\s*([^;]+);\s*$/m', static function (array $match) use (&$variables): string {
        $variables[$match[1]] = trim($match[2]);

        return '';
    }, $scss) ?? $scss;

    return [$scss, $variables];
}

function makeCDN_applyVariables(string $scss, array $variables): string
{
    foreach ($variables as $name => $value) {
        $scss = str_replace('$' . $name, $value, $scss);
    }

    return $scss;
}

function makeCDN_flattenNestedScss(string $scss): string
{
    $scss = preg_replace('~/\*.*?\*/~s', '', $scss) ?? $scss;
    $scss = preg_replace('/^\s*\/\/.*$/m', '', $scss) ?? $scss;

    $lines = preg_split('/\R/', $scss) ?: [];
    $stack = [];
    $css = '';
    $pendingSelector = '';

    foreach ($lines as $line) {
        $line = trim($line);
        if ($line === '') {
            continue;
        }

        if ($line === '}') {
            array_pop($stack);
            continue;
        }

        if (str_ends_with($line, '}')) {
            $declaration = trim(substr($line, 0, -1));
            if ($declaration !== '' && str_contains($declaration, ':')) {
                $selector = end($stack);
                if ($selector) {
                    $css .= $selector . ' {' . makeCDN_normalizeDeclarations($declaration) . '}' . PHP_EOL;
                }
            }
            array_pop($stack);
            continue;
        }

        if (str_ends_with($line, '{')) {
            $selector = trim(substr($line, 0, -1));
            if ($pendingSelector !== '') {
                $selector = $pendingSelector . ' ' . $selector;
                $pendingSelector = '';
            }
            $parent = end($stack) ?: '';
            $stack[] = makeCDN_joinSelectors($parent, $selector);
            continue;
        }

        if (str_contains($line, ':') && str_ends_with($line, ';')) {
            $selector = end($stack);
            if ($selector) {
                $css .= $selector . ' {' . makeCDN_normalizeDeclarations($line) . '}' . PHP_EOL;
            }
            continue;
        }

        $pendingSelector .= ($pendingSelector === '' ? '' : ' ') . $line;
    }

    return $css;
}

function makeCDN_joinSelectors(string $parent, string $child): string
{
    $children = array_map('trim', explode(',', $child));
    if ($parent === '') {
        return implode(', ', $children);
    }

    $parents = array_map('trim', explode(',', $parent));
    $selectors = [];

    foreach ($parents as $parentSelector) {
        foreach ($children as $childSelector) {
            $selectors[] = str_contains($childSelector, '&')
                ? str_replace('&', $parentSelector, $childSelector)
                : $parentSelector . ' ' . $childSelector;
        }
    }

    return implode(', ', $selectors);
}

function makeCDN_normalizeDeclarations(string $block): string
{
    $declarations = array_filter(array_map('trim', explode(';', $block)));

    return ' ' . implode('; ', $declarations) . '; ';
}

function makeCDN_joinJs(array $files): string
{
    $parts = [];

    foreach (makeCDN_existingFiles($files) as $file) {
        $parts[] = '/* ' . basename($file) . ' */' . PHP_EOL . trim((string) file_get_contents($file));
    }

    return implode(PHP_EOL . PHP_EOL, $parts) . PHP_EOL;
}

function makeCDN_joinCss(array $files): string
{
    $parts = [];

    foreach (makeCDN_existingFiles($files) as $file) {
        $parts[] = '/* ' . basename($file) . ' */' . PHP_EOL . trim(makeCDN_normalizeCssClassTokens((string) file_get_contents($file)));
    }

    return implode(PHP_EOL . PHP_EOL, $parts) . PHP_EOL;
}

function makeCDN_joinCompiledScss(array $files, array &$variables): string
{
    $parts = [];

    foreach (makeCDN_existingFiles($files) as $file) {
        $compiled = makeCDN_compileScssFile($file, $variables);
        if (trim($compiled) !== '') {
            $parts[] = '/* ' . basename($file) . ' */' . PHP_EOL . trim($compiled);
        }
    }

    return implode(PHP_EOL . PHP_EOL, $parts) . PHP_EOL;
}

function makeCDN_normalizeCssClassTokens(string $css): string
{
    $replacements = [
        '.mobileNav__panel' => '.mobileNav > nav',
    ];

    $css = strtr($css, $replacements);
    $css = preg_replace('/\.contentBlock\s+h[1-6]\s*\{[^}]*\}/s', '', $css) ?? $css;
    $css = preg_replace('/\.contentBlock\s+p\s*\{[^}]*\}/s', '', $css) ?? $css;
    $css = preg_replace('/(^|(?<=[{}]))\s*[^{}@]*mobileNav[^{}]*\{[^{}]*\}/s', '', $css) ?? $css;
    $css = preg_replace('/,?\s*\.mobileNav[^,{]*/', '', $css) ?? $css;
    $css = preg_replace('/(^|(?<=[{}]))\s*[^{}@]*(?:spyMenu|spyGroup|spynav|spySection|spy-group)[^{}]*\{[^{}]*\}/s', '', $css) ?? $css;

    return $css;
}

function makeCDN_rootCss(): string
{
    return ':root {' . PHP_EOL
        . '    --m: 184, 175, 108;' . PHP_EOL
        . '    --main-color: rgba(var(--m), 1);' . PHP_EOL
        . '}';
}

function makeCDN_minifyCss(string $css): string
{
    $css = preg_replace('~/\*.*?\*/~s', '', $css) ?? $css;
    $css = preg_replace('/\s+/', ' ', $css) ?? $css;
    $css = preg_replace('/\s*([{}:;,>~])\s*/', '$1', $css) ?? $css;

    return trim($css);
}

function makeCDN_minifyJs(string $js): string
{
    $js = preg_replace('~/\*.*?\*/~s', '', $js) ?? $js;
    $js = preg_replace('/^\s*\/\/.*$/m', '', $js) ?? $js;
    $js = preg_replace('/\s+/', ' ', $js) ?? $js;
    $js = preg_replace('/\s*([{}();,:=+\-*\/<>])\s*/', '$1', $js) ?? $js;

    return trim($js);
}

function makeCDN_resolveScssImport(string $import, string $fromDirectory, string $scssRoot): ?string
{
    $import = str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $import);
    $candidates = [];

    foreach ([$fromDirectory, $scssRoot] as $base) {
        $path = $base . DIRECTORY_SEPARATOR . $import;
        $dir = dirname($path);
        $name = basename($path);

        $candidates[] = $path . '.scss';
        $candidates[] = $dir . DIRECTORY_SEPARATOR . '_' . $name . '.scss';
        $candidates[] = $path;
    }

    foreach ($candidates as $candidate) {
        if (is_file($candidate)) {
            return $candidate;
        }
    }

    return null;
}

function makeCDN_sourcesHash(array $files): string
{
    $context = '';

    foreach ($files as $file) {
        $context .= $file . ':' . filemtime($file) . ':' . hash_file('sha256', $file) . PHP_EOL;
    }

    return hash('sha256', $context);
}

function makeCDN_existingFiles(array $files): array
{
    return array_values(array_filter($files, static fn (string $file): bool => is_file($file)));
}

function makeCDN_allFilesExist(array $files): bool
{
    foreach ($files as $file) {
        if (!is_file($file)) {
            return false;
        }
    }

    return true;
}

function makeCDN_readManifest(string $file): array
{
    if (!is_file($file)) {
        return [];
    }

    $data = json_decode((string) file_get_contents($file), true);

    return is_array($data) ? $data : [];
}

function makeCDN_ensureDirectory(string $directory): void
{
    if (!is_dir($directory)) {
        mkdir($directory, 0775, true);
    }
}

function makeCDN_relativePath(string $file, string $root): string
{
    $file = str_replace('\\', '/', $file);
    $root = rtrim(str_replace('\\', '/', $root), '/') . '/';

    return str_starts_with($file, $root) ? substr($file, strlen($root)) : $file;
}
