<?php

require_once dirname(__DIR__) . '/_helpers.php';

$language = adminLanguage();
$baseLanguage = strtolower(explode('-', $language)[0]);
$nationsPath = dirname(__DIR__, 2) . '/_assets/nations';
$continentNames = adminReadJson($nationsPath . '/contnames.json');
$languageNames = adminReadJson($nationsPath . '/langnames.json');
$ui = $continentNames['_ui'][$language]
    ?? $continentNames['_ui'][$baseLanguage]
    ?? $continentNames['_ui']['de']
    ?? ['title' => 'Sprachen', 'subtitle' => 'Sprache und Region auswählen'];
$translatedContinents = $continentNames[$language]
    ?? $continentNames[$baseLanguage]
    ?? $continentNames['de']
    ?? [];
$groups = [
    ['source' => 'Europa', 'translation' => 'eu'],
    ['source' => 'Asien', 'translation' => 'as'],
    ['source' => 'Afrika', 'translation' => 'af'],
    ['source' => 'Amerika', 'translation' => 'am'],
    ['source' => 'Nahost', 'translation' => 'no'],
];
?>

<section class="content topHead">
	<div>
		<p>CARLVON</p>
		<h1><?= adminEscape((string) $ui['title']) ?></h1>
		<p><?= adminEscape((string) $ui['subtitle']) ?></p>
	</div>
</section>

<section class="languagesPage" data-languages-page>
    <div class="languagesPage__inner">
        <div class="languageGrid">
            <?php foreach ($groups as $group): ?>
                <?php $languages = $languageNames[$group['source']] ?? []; ?>
                <section class="languageGroup">
                    <h3 class="languageGroup__title">
                        <?= adminEscape((string) ($translatedContinents[$group['translation']] ?? $group['source'])) ?>
                    </h3>
                    <ul class="languageGroup__list">
                        <?php foreach ($languages as $entry): ?>
                            <?php
                            $iso = (string) ($entry['iso'] ?? '');
                            $direction = (string) ($entry['richtung'] ?? 'ltr');
                            $name = (string) ($entry['name_native'] ?? $entry['name_de'] ?? $iso);
                            ?>
                            <li>
                                <button
                                    type="button"
                                    data-language="<?= adminEscape($iso) ?>"
                                    data-direction="<?= adminEscape($direction) ?>"
                                    onclick="window.CarlvonLanguage(this.dataset.language, this.dataset.direction)"
                                    class="languageChoice<?= strcasecmp($iso, $language) === 0 ? ' current-language' : '' ?>"
                                ><?= adminEscape($name) ?></button>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </section>
            <?php endforeach; ?>
        </div>
    </div>
</section>
