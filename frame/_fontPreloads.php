<?php

$fontTheme = defined('FONT_THEME') ? FONT_THEME : 'gotham';

if ($fontTheme === 'montserrat'): ?>
    <link
        rel="preload"
        href="/<?= ASSETPATH ?>fonts/Montserrat/Montserrat-VariableFont_wght.ttf"
        as="font"
        type="font/ttf"
        crossorigin
    >
<?php else: ?>
    <link
        rel="preload"
        href="/<?= ASSETPATH ?>fonts/Gotham/GothamRounded-Book.woff2"
        as="font"
        type="font/woff2"
        crossorigin
    >
<?php endif; ?>
