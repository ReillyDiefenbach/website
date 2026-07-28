<?php

$moduleDirectory = $root . '/mod/' . $moduleShort;
$moduleContentFile = null;

foreach ($languages as $language) {
    foreach (['html', 'php'] as $extension) {
        $candidate = $moduleDirectory . '/' . $language . '.' . $extension;

        if (is_file($candidate)) {
            $moduleContentFile = $candidate;
            break 2;
        }
    }
}

if ($moduleContentFile === null) {
    http_response_code(404);
    echo '<section class="content"><h1>Modul nicht gefunden</h1></section>';
    return;
}

$moduleData = [];

foreach ($languages as $language) {
    $menuFile = $root . '/admin/menu/modules/' . $language . '.json';

    if (!is_file($menuFile)) {
        continue;
    }

    $menuModules = json_decode((string)file_get_contents($menuFile), true);

    if (!is_array($menuModules)) {
        continue;
    }

    foreach ($menuModules as $key => $candidate) {
        if (!is_array($candidate)) {
            continue;
        }

        if ((string)($candidate['short'] ?? $key) === $moduleShort) {
            $moduleData = $candidate;
            break 2;
        }
    }
}

$moduleTitle = (string)($moduleData['title'] ?? ucfirst($moduleShort));
$moduleBadge = (string)($moduleData['badge'] ?? strtoupper($moduleShort));
$moduleClaim = (string)($moduleData['subtitle'] ?? '');
$moduleTitleParts = preg_split('/\s*&\s*/u', $moduleTitle, 2);
$moduleTitleFirst = (string)($moduleTitleParts[0] ?? $moduleTitle);
$moduleTitleSecond = (string)($moduleTitleParts[1] ?? '');
$moduleVideoFallbackSrc = '/_assets/media/mods/video/_fallback.mp4';
$moduleVideoSrc = '/_assets/media/mods/video/' . $moduleShort . '.mp4';

if (!is_file($root . $moduleVideoSrc)) {
    $moduleVideoSrc = $moduleVideoFallbackSrc;
}
$escape = static fn (string $value): string => htmlspecialchars(
    $value,
    ENT_QUOTES | ENT_SUBSTITUTE,
    'UTF-8'
);

?>
<div
    class="scrollHeroPage topSection"
    data-module="<?= $escape($moduleShort) ?>"
    data-src="<?= $escape($moduleVideoSrc) ?>"
    data-fallback-src="<?= $escape($moduleVideoFallbackSrc) ?>"
>
    <div class="welcomeBox">
        <p class="modBadge"><?= $escape($moduleBadge) ?></p>
        <h1><?= $escape($moduleTitle) ?></h1>
        <?php if ($moduleClaim !== ''): ?>
            <p class="modClaim"><?= $escape($moduleClaim) ?></p>
        <?php endif; ?>
    </div>
    <div class="moduleStickyLabel" aria-hidden="true"><?= $escape($moduleTitle) ?></div>
</div>
<section class="modHead">
    <p class="modLogo">carlvon</p>
    <h1>
        <span class="Axis_x"><?= $escape($moduleTitleFirst) ?></span>
        <?php if ($moduleTitleSecond !== ''): ?>
            <span class="Axis_and"> &amp; </span>
            <span class="Axis_y"><?= $escape($moduleTitleSecond) ?></span>
        <?php endif; ?>
    </h1>
    <p class="modTypes">
        <span class="modPersonal">personal</span>
        <span aria-hidden="true">•</span>
        <span class="modPersonal">Business</span>
    </p>
</section>
<?php

if (str_ends_with($moduleContentFile, '.php')) {
    require $moduleContentFile;
} else {
    readfile($moduleContentFile);
}
