<?php

$legalRoute = $adminRoute ?? $requestedSite ?? ($_REQUEST['site'] ?? '');
$legalRoute = trim((string)$legalRoute, '/');
$legalParts = explode('/', $legalRoute);
$requestedDoc = 'privacy';

foreach (['privacy', 'terms', 'coopol', 'disclaimer'] as $candidate) {
    if (in_array($candidate, $legalParts, true)) {
        $requestedDoc = $candidate;
        break;
    }
}

$documents = [
    'privacy' => 'Privacy Policy',
    'terms' => 'Terms & Conditions',
    'coopol' => 'Cookie Policy',
    'disclaimer' => 'Disclaimer',
];

$firm = json_decode((string)file_get_contents(__DIR__ . '/_firm.json'), true);
$firm = is_array($firm) ? $firm : [];

function legal_wrap(string $text, string $tag = 'span'): string
{
    $tags = explode(' ', $tag);

    return '<' . $tag . '>' . $text . '</' . $tags[0] . '>';
}

function legal_render_text(string $text, array $firm): string
{
    $firmUrl = (string)($firm['firmUrl'] ?? '');

    $replacements = [
        '[lastUpdated]' => legal_wrap((string)($firm['lastUpdated'] ?? '')),
        '[firmWeb]' => legal_wrap((string)($firm['firmWeb'] ?? '')),
        '[firmName]' => legal_wrap((string)($firm['firmName'] ?? '')),
        '[firmAddress]' => legal_wrap((string)($firm['firmAddress'] ?? '')),
        '[firmUrl]' => legal_wrap($firmUrl, 'a style="font-style:italic" href="' . htmlspecialchars($firmUrl, ENT_QUOTES, 'UTF-8') . '"'),
        '[firmCountry]' => legal_wrap((string)($firm['firmCountry'] ?? '')),
        '[firmEmail]' => legal_wrap((string)($firm['firmEmail'] ?? '')),
        '[firmContact]' => legal_wrap((string)($firm['firmContact'] ?? '')),
    ];

    return strtr($text, $replacements);
}

function legal_document_html(string $key, array $firm): string
{
    $file = __DIR__ . '/' . $key . '.txt';

    if (!is_file($file)) {
        return '';
    }

    return legal_render_text((string)file_get_contents($file), $firm);
}

?>


<section class="content topHead">
	<div>
		<p>CARLVON</p>
		<h1>Legal Documents</h1>
		<p>English Version Only</p>
	</div>
</section>

<section class="content legalPage" data-legal-page data-active-legal="<?= htmlspecialchars($requestedDoc, ENT_QUOTES, 'UTF-8') ?>">
    <div class="legalPage__layout">
        <aside class="legalPage__nav">
            <nav class="spyMenu legalTabs" aria-label="Legal documents">
                <?php foreach ($documents as $key => $label): ?>
                    <button
                        type="button"
                        class="<?= $key === $requestedDoc ? 'active' : '' ?>"
                        data-legal-tab="<?= htmlspecialchars($key, ENT_QUOTES, 'UTF-8') ?>"
                    >
                        <?= htmlspecialchars($label, ENT_QUOTES, 'UTF-8') ?>
                    </button>
                <?php endforeach; ?>
            </nav>
        </aside>

        <main class="legalPage__content">
            <div class="mobileNav legalMobileNav">
                <button class="mobileNav__toggle" type="button">
                    <span>Legal Documents</span>
                    <i></i>
                </button>

                <nav class="mobileNav__panel legalTabs" aria-label="Legal documents mobile">
                    <?php foreach ($documents as $key => $label): ?>
                        <button
                            type="button"
                            class="<?= $key === $requestedDoc ? 'active' : '' ?>"
                            data-legal-tab="<?= htmlspecialchars($key, ENT_QUOTES, 'UTF-8') ?>"
                        >
                            <?= htmlspecialchars($label, ENT_QUOTES, 'UTF-8') ?>
                        </button>
                    <?php endforeach; ?>
                </nav>
            </div>

            <?php foreach ($documents as $key => $label): ?>
                <article
                    class="legalDocument <?= $key === $requestedDoc ? 'active' : '' ?>"
                    data-legal-doc="<?= htmlspecialchars($key, ENT_QUOTES, 'UTF-8') ?>"
                    aria-label="<?= htmlspecialchars($label, ENT_QUOTES, 'UTF-8') ?>"
                >
                    <?= legal_document_html($key, $firm) ?>
                </article>
            <?php endforeach; ?>
        </main>
    </div>
</section>
