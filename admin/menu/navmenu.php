<?php

declare(strict_types=1);

$menuLang = strtolower(str_replace('_', '-', (string)($_REQUEST['lang'] ?? (defined('LANG') ? LANG : 'de'))));
$menuLang = preg_match('/^[a-z]{2,3}(?:-[a-z]{2})?$/', $menuLang) ? substr($menuLang, 0, 2) : 'de';

$menuBase = __DIR__;

function carlvon_menu_read_json(string $path, array $fallback = []): array
{
    if (!is_file($path)) {
        return $fallback;
    }

    $json = json_decode((string)file_get_contents($path), true);

    return is_array($json) ? $json : $fallback;
}

function carlvon_menu_text(array $labels, string $lang, string $field, string $fallback = ''): string
{
    $key = $field === 'text' ? $lang : $lang . '_' . $field;

    if (array_key_exists($key, $labels)) {
        return (string)$labels[$key];
    }

    // Compatibility with the previous "overview_text" schema.
    $previousKey = $lang . '_' . $field;

    if (array_key_exists($previousKey, $labels)) {
        return (string)$labels[$previousKey];
    }

    $legacyKey = $lang . $field;

    if (array_key_exists($legacyKey, $labels)) {
        return (string)$labels[$legacyKey];
    }

    return $fallback;
}

function carlvon_menu_enrich_items(array $items, array $labels): array
{
    foreach ($items as &$item) {
        if (!is_array($item) || empty($item['lang'])) {
            continue;
        }

        $lang = (string)$item['lang'];
        $item['text'] = carlvon_menu_text($labels, $lang, 'text', $lang);
        $title = carlvon_menu_text($labels, $lang, 'title');
        $subtitle = carlvon_menu_text($labels, $lang, 'subtitle');

        if ($title !== '') {
            $item['title'] = $title;
        }

        if ($subtitle !== '') {
            $item['subtitle'] = $subtitle;
        }

        if (!empty($item['children']) && is_array($item['children'])) {
            $item['children'] = carlvon_menu_enrich_items($item['children'], $labels);
        }
    }
    unset($item);

    return $items;
}

function carlvon_menu_modules(array $modules): array
{
    $items = [];
    $fallbackImage = '_assets/media/mods/sm/_fallback.jpg';

    foreach ($modules as $key => $module) {
        if (!is_array($module)) {
            continue;
        }

        $short = (string)($module['short'] ?? $key);
        $image = '_assets/media/mods/sm/' . $short . '.jpg';

        if (!is_file(dirname(__DIR__, 2) . '/' . $image)) {
            $image = $fallbackImage;
        }

        $items[] = [
            'lang' => (string)$key,
            'text' => (string)($module['title'] ?? $key),
            'title' => (string)($module['title'] ?? $key),
            'subtitle' => (string)($module['subtitle'] ?? ''),
            'keyquest' => (string)($module['keyquest'] ?? ''),
            'axis' => (string)($module['axis'] ?? ''),
            'short' => $short,
            'badge' => (string)($module['badge'] ?? ''),
            'link' => 'mod/' . $short,
            'image' => $image,
            'image_fallback' => $fallbackImage,
        ];
    }

    return $items;
}

function carlvon_menu_attach_modules(array $items, array $modules): array
{
    foreach ($items as &$item) {
        if (!is_array($item)) {
            continue;
        }

        if (($item['lang'] ?? '') === 'modules') {
            $item['children'] = $modules;
            continue;
        }

        if (!empty($item['children']) && is_array($item['children'])) {
            $item['children'] = carlvon_menu_attach_modules($item['children'], $modules);
        }
    }
    unset($item);

    return $items;
}

function carlvon_menu_escape(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

function carlvon_menu_media_src(string $localPath): string
{
    return function_exists('carlvon_media_src')
        ? carlvon_media_src($localPath)
        : carlvon_menu_escape('/' . ltrim($localPath, '/'));
}

function carlvon_menu_media_fallback_attr(string $localPath): string
{
    return function_exists('carlvon_media_fallback_attr')
        ? carlvon_media_fallback_attr($localPath)
        : '';
}

function carlvon_menu_item_url_attrs(array $item): string
{
    if (!empty($item['href'])) {
        return ' href="' . carlvon_menu_escape((string)$item['href']) . '" target="_blank" rel="noopener" data-external-link="1"';
    }

    if (!empty($item['link'])) {
        return ' href="#" data-link="' . carlvon_menu_escape((string)$item['link']) . '"';
    }

    return ' href="#"';
}

function carlvon_menu_render_modules(array $item): void
{
    $children = is_array($item['children'] ?? null) ? $item['children'] : [];
    ?>
    <div class="bigMenu bigMenu--modules">
        <button class="bigMenu__close" type="button" aria-label="Menü schließen">
            <span aria-hidden="true"></span>
        </button>
        <div class="bigMenu__inner">
            <div class="bigMenu__heading headinfo">
                <div><?= carlvon_menu_escape((string)($item['title'] ?? $item['text'] ?? '')) ?></div>
                <div><?= carlvon_menu_escape((string)($item['subtitle'] ?? '')) ?></div>
            </div>

            <ul class="moduleList">
                <?php foreach ($children as $module): ?>
                    <?php
                    $moduleImage = ltrim((string)($module['image'] ?? '_assets/media/mods/sm/_fallback.jpg'), '/');
                    $moduleImageFallback = ltrim((string)($module['image_fallback'] ?? '_assets/media/mods/sm/_fallback.jpg'), '/');
                    ?>
                    <li class="moduleList__item">
                        <a class="moduleCard"<?= carlvon_menu_item_url_attrs($module) ?>>
                            <span class="moduleCard__imageWrap">
                                <img
                                    class="moduleCard__image"
                                    src="<?= carlvon_menu_media_src($moduleImage) ?>"
                                    onerror="<?= carlvon_menu_media_fallback_attr($moduleImageFallback) ?>"
                                    alt=""
                                    loading="lazy"
                                >
                            </span>
                            <span class="moduleCard__copy">
                                <strong class="moduleCard__title"><?= carlvon_menu_escape((string)($module['title'] ?? $module['text'] ?? '')) ?></strong>
                                <span class="moduleCard__subtitle"><?= carlvon_menu_escape((string)($module['subtitle'] ?? '')) ?></span>
                            </span>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
    <?php
}

function carlvon_menu_render_standard_children(array $item): void
{
    $children = is_array($item['children'] ?? null) ? $item['children'] : [];
    $useTwoColumns = count($children) >= 6;
    $menuImage = ltrim((string)($item['image'] ?? '_assets/img/vitruv.png'), '/');
    ?>
    <div class="bigMenu bigMenu--standard">
        <button class="bigMenu__close" type="button" aria-label="Menü schließen">
            <span aria-hidden="true"></span>
        </button>
        <div class="bigMenu__inner">
            <div class="standardMenuPanel<?= $useTwoColumns ? ' standardMenuPanel--two-columns' : ' standardMenuPanel--one-column' ?>">
                <div class="bigMenu__heading headinfo">
                    <div><?= carlvon_menu_escape((string)($item['title'] ?? $item['text'] ?? '')) ?></div>
                    <div><?= carlvon_menu_escape((string)($item['subtitle'] ?? '')) ?></div>
                </div>

                <ul class="standardMenuList">
                    <?php foreach ($children as $child): ?>
                        <li class="standardMenuList__item<?= !empty($child['children']) && is_array($child['children']) ? ' standardMenuList__item--open' : '' ?>">
                            <?php if (!empty($child['children']) && is_array($child['children'])): ?>
                                <div class="standardMenuLink standardMenuLink--group">
                                    <span class="standardMenuLink__title"><?= carlvon_menu_escape((string)($child['text'] ?? '')) ?></span>
                                    <span class="standardMenuLink__children">
                                        <?php foreach ($child['children'] as $grandChild): ?>
                                            <a class="standardMenuLink__child"<?= carlvon_menu_item_url_attrs($grandChild) ?>>
                                                <?= carlvon_menu_escape((string)($grandChild['text'] ?? '')) ?>
                                            </a>
                                        <?php endforeach; ?>
                                    </span>
                                </div>
                            <?php else: ?>
                                <a class="standardMenuLink"<?= carlvon_menu_item_url_attrs($child) ?>>
                                    <span class="standardMenuLink__title"><?= carlvon_menu_escape((string)($child['text'] ?? '')) ?></span>
                                </a>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>

                <div class="standardMenuMedia" aria-hidden="true">
                    <img src="<?= carlvon_menu_media_src($menuImage) ?>" onerror="<?= carlvon_menu_media_fallback_attr($menuImage) ?>" alt="" loading="lazy">
                </div>
            </div>
        </div>
    </div>
    <?php
}

function carlvon_menu_render_item(array $item): void
{
    $hasChildren = !empty($item['children']) && is_array($item['children']);
    $isModules = ($item['lang'] ?? '') === 'modules';
    $itemClass = $hasChildren ? ' class="mainMenu__item mainMenu__item--big"' : '';
    ?>
    <li<?= $itemClass ?>>
        <?php if ($hasChildren): ?>
            <button class="mainMenu__trigger" type="button" aria-expanded="false">
                <?= carlvon_menu_escape((string)($item['text'] ?? '')) ?>
            </button>

            <?php
            if ($isModules) {
                carlvon_menu_render_modules($item);
            } else {
                carlvon_menu_render_standard_children($item);
            }
            ?>
        <?php else: ?>
            <a<?= carlvon_menu_item_url_attrs($item) ?>><?= carlvon_menu_escape((string)($item['text'] ?? '')) ?></a>
        <?php endif; ?>
    </li>
    <?php
}

$standardMenu = carlvon_menu_read_json($menuBase . '/standard.json');
$labels = carlvon_menu_read_json($menuBase . '/standards/' . $menuLang . '.json');

if (!$labels && $menuLang !== 'de') {
    $labels = carlvon_menu_read_json($menuBase . '/standards/de.json');
}

$modules = carlvon_menu_read_json($menuBase . '/modules/' . $menuLang . '.json');

if (!$modules && $menuLang !== 'de') {
    $modules = carlvon_menu_read_json($menuBase . '/modules/de.json');
}

$menuItems = carlvon_menu_attach_modules(
    carlvon_menu_enrich_items($standardMenu, $labels),
    carlvon_menu_modules($modules)
);

$loginItems = array_values(array_filter(
    $menuItems,
    static fn (array $item): bool => ($item['lang'] ?? '') === 'login'
));
$menuItems = array_values(array_filter(
    $menuItems,
    static fn (array $item): bool => ($item['lang'] ?? '') !== 'login'
));
$languageText = carlvon_menu_text($labels, 'language', 'text', 'Languages');
?>

<div class="siteNavigation">
    <div id="top-burger">
        <button class="menuToggle burger-btn" type="button" aria-expanded="false" aria-controls="primary-menu" aria-label="Menü öffnen">
            <span aria-hidden="true"></span>
        </button>
    </div>

    <nav class="siteMenu" id="primary-menu" aria-label="Hauptnavigation">
        <ul class="mainMenu">
            <?php foreach ($menuItems as $menuItem): ?>
                <?php carlvon_menu_render_item($menuItem); ?>
            <?php endforeach; ?>
            <li class="mainMenu__item mainMenu__item--languages">
                <a class="languageMenu__link" href="/admin/languages/" data-link="admin/languages" aria-label="Sprache auswählen">
                    <svg class="languageMenu__globe" aria-hidden="true" viewBox="0 0 24 24" focusable="false">
                        <circle cx="12" cy="12" r="9"></circle>
                        <path d="M3 12h18M12 3c2.4 2.5 3.7 5.5 3.7 9S14.4 18.5 12 21M12 3C9.6 5.5 8.3 8.5 8.3 12s1.3 6.5 3.7 9"></path>
                    </svg>
                    <span class="languageMenu__code"><?= carlvon_menu_escape(strtoupper($menuLang)) ?></span>
                </a>
            </li>
            <?php foreach ($loginItems as $loginItem): ?>
                <?php carlvon_menu_render_item($loginItem); ?>
            <?php endforeach; ?>
        </ul>
    </nav>

    <button class="menuBackdrop" type="button" aria-label="Menü schließen"></button>
</div>
