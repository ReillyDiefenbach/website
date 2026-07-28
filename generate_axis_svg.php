<?php

declare(strict_types=1);

/**
 * Erzeugt die sieben Ergebnisoptionen der Achsenanalyse.
 * Der Gewinner bleibt bei 100 %, der unterlegene Pol wird grau und abhängig
 * von FIX, MODERATE oder CLOSE mit 60, 75 oder 90 % dargestellt.
 */

$sourceDirectory = __DIR__ . DIRECTORY_SEPARATOR . '_assets' . DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR . 'axis';
$outputDirectory = __DIR__ . DIRECTORY_SEPARATOR . '_assets' . DIRECTORY_SEPARATOR . 'symb' . DIRECTORY_SEPARATOR . 'axis';
$variants = [
    'f' => ['label' => 'FIX', 'ratio' => 0.60],
    'm' => ['label' => 'MODERATE', 'ratio' => 0.75],
    'c' => ['label' => 'CLOSE', 'ratio' => 0.90],
];

if (!is_dir($outputDirectory) && !mkdir($outputDirectory, 0775, true) && !is_dir($outputDirectory)) {
    throw new RuntimeException("Ausgabeverzeichnis konnte nicht erstellt werden: {$outputDirectory}");
}

$loadSvg = static function (string $path): DOMDocument {
    $document = new DOMDocument('1.0', 'UTF-8');
    $document->preserveWhiteSpace = false;

    if (!$document->load($path, LIBXML_NOBLANKS)) {
        throw new RuntimeException("SVG konnte nicht geladen werden: {$path}");
    }

    return $document;
};

$appendContent = static function (
    DOMDocument $target,
    DOMElement $root,
    DOMDocument $source,
    string $id,
    string $fill,
    float $scale = 1.0
): void {
    $group = $target->createElement('g');
    $group->setAttribute('id', $id);
    $group->setAttribute('fill', $fill);

    if ($scale !== 1.0) {
        $offset = 8.0 * (1.0 - $scale);
        $group->setAttribute('transform', sprintf('translate(%.3f %.3f) scale(%.3f)', $offset, $offset, $scale));
    }

    foreach ($source->documentElement->childNodes as $child) {
        $group->appendChild($target->importNode($child, true));
    }

    $root->appendChild($group);
};

$createOutput = static function (string $titleText, string $descriptionText): array {
    $output = new DOMDocument('1.0', 'UTF-8');
    $output->formatOutput = true;

    $root = $output->createElementNS('http://www.w3.org/2000/svg', 'svg');
    $root->setAttribute('viewBox', '0 0 16 16');
    $root->setAttribute('width', '16');
    $root->setAttribute('height', '16');
    $root->setAttribute('preserveAspectRatio', 'xMidYMid meet');
    $root->setAttribute('role', 'img');
    $root->setAttribute('aria-labelledby', 'axis-title axis-description');
    $output->appendChild($root);

    $title = $output->createElement('title', $titleText);
    $title->setAttribute('id', 'axis-title');
    $root->appendChild($title);

    $description = $output->createElement('desc', $descriptionText);
    $description->setAttribute('id', 'axis-description');
    $root->appendChild($description);

    return [$output, $root];
};

$sources = [
    'X' => $loadSvg($sourceDirectory . DIRECTORY_SEPARATOR . 'X.svg'),
    'Y' => $loadSvg($sourceDirectory . DIRECTORY_SEPARATOR . 'Y.svg'),
];
$fills = [
    'X' => $sources['X']->documentElement->getAttribute('fill') ?: '#AA0000',
    'Y' => $sources['Y']->documentElement->getAttribute('fill') ?: '#000000',
];
$written = [];

foreach ([['winner' => 'X', 'runner' => 'Y'], ['winner' => 'Y', 'runner' => 'X']] as $result) {
    foreach ($variants as $suffix => $variant) {
        $winner = $result['winner'];
        $runner = $result['runner'];
        $key = $winner . '_' . $suffix;
        [$output, $root] = $createOutput(
            "Achsenresultat {$key}",
            sprintf('%s gewinnt; %s wirkt mit %.0f Prozent mit.', $winner, $runner, $variant['ratio'] * 100)
        );

        $appendContent($output, $root, $sources[$runner], 'axis-runner-' . strtolower($runner), '#aaa', $variant['ratio']);
        $appendContent($output, $root, $sources[$winner], 'axis-winner-' . strtolower($winner), $fills[$winner]);

        $targetPath = $outputDirectory . DIRECTORY_SEPARATOR . $key . '.svg';
        if ($output->save($targetPath) === false) {
            throw new RuntimeException("SVG konnte nicht geschrieben werden: {$targetPath}");
        }
        $written[] = basename($targetPath);
    }
}

[$balancedOutput, $balancedRoot] = $createOutput(
    'Achsenresultat XY',
    'X und Y sind gleich stark und werden gleich groß dargestellt.'
);
$appendContent($balancedOutput, $balancedRoot, $sources['X'], 'axis-balanced-x', $fills['X']);
$appendContent($balancedOutput, $balancedRoot, $sources['Y'], 'axis-balanced-y', $fills['Y']);

$balancedPath = $outputDirectory . DIRECTORY_SEPARATOR . 'XY.svg';
if ($balancedOutput->save($balancedPath) === false) {
    throw new RuntimeException("SVG konnte nicht geschrieben werden: {$balancedPath}");
}
$written[] = basename($balancedPath);

printf("%d Achsen-SVGs geschrieben: %s\n", count($written), implode(', ', $written));

