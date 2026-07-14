<?php

function adminLanguage(): string
{
    $language = $_POST['lang'] ?? $_GET['lang'] ?? (defined('LANG') ? LANG : 'de');
    $language = str_replace('_', '-', trim((string) $language));

    return preg_match('/^[a-zA-Z]{2,3}(?:-[a-zA-Z]{2})?$/', $language)
        ? $language
        : 'de';
}

function adminEscape(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function adminReadJson(string $file): array
{
    if (!is_file($file)) {
        return [];
    }

    $data = json_decode((string) file_get_contents($file), true);

    return is_array($data) ? $data : [];
}

function adminLocalizedFile(string $directory, string $language): ?string
{
    $baseLanguage = strtolower(explode('-', $language)[0]);
    $candidates = array_unique([$language, $baseLanguage, 'de', 'en']);

    foreach ($candidates as $candidate) {
        $file = $directory . '/' . $candidate . '.html';

        if (is_file($file)) {
            return $file;
        }
    }

    return null;
}
