<?php

require_once dirname(__DIR__) . '/_helpers.php';

$language = adminLanguage();
$contentFile = adminLocalizedFile(__DIR__ . '/content', $language);

if ($contentFile === null) {
    http_response_code(404);
    exit('Cookie content not found');
}

readfile($contentFile);
