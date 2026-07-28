<?php

declare(strict_types=1);

/**
 * Erzeugt alle geordneten Prioritätenkombinationen in drei Stärken.
 *
 * - Gewinner: volles Symbol, 100 %, Originalfarbe, hinten
 * - Runner-up: 60/75/90 %, vorne
 * - Runner-up-Füllung: volles Symbol mit 0.4 Deckkraft
 * - Runner-up-Kontur: jeweilige _out.svg, deckend in Originalfarbe
 * - Überlappung: ein Drittel der Gewinnerbreite
 */

$sourceDirectory = __DIR__ . DIRECTORY_SEPARATOR . '_assets' . DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR . 'cards';
$outputDirectory = __DIR__ . DIRECTORY_SEPARATOR . '_assets' . DIRECTORY_SEPARATOR . 'symb';
$priorityKeys = ['W', 'N', 'E', 'S'];
$variants = [
    'f' => ['label' => 'FIX', 'ratio' => 0.60],
    'm' => ['label' => 'MODERATE', 'ratio' => 0.75],
    'c' => ['label' => 'CLOSE', 'ratio' => 0.90],
];
$canvasWidth = 26.0;
$canvasHeight = 16.0;
$overlap = 16.0 / 3.0;

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

$readViewBox = static function (DOMDocument $document): array {
    $viewBox = preg_split('/\s+/', trim($document->documentElement->getAttribute('viewBox')));
    if ($viewBox === false || count($viewBox) !== 4) {
        throw new RuntimeException('Das SVG besitzt keine gültige viewBox.');
    }

    return array_map('floatval', $viewBox);
};

$appendContent = static function (
    DOMDocument $target,
    DOMElement $root,
    DOMDocument $source,
    string $id,
    string $transform,
    ?float $opacity = null
): void {
    $group = $target->createElement('g');
    $group->setAttribute('id', $id);
    $group->setAttribute('transform', $transform);

    foreach (['fill', 'fill-opacity', 'stroke', 'stroke-width'] as $attributeName) {
        if ($source->documentElement->hasAttribute($attributeName)) {
            $group->setAttribute($attributeName, $source->documentElement->getAttribute($attributeName));
        }
    }

    if ($opacity !== null) {
        $group->setAttribute('opacity', rtrim(rtrim(sprintf('%.2f', $opacity), '0'), '.'));
    }

    foreach ($source->documentElement->childNodes as $child) {
        $group->appendChild($target->importNode($child, true));
    }

    $root->appendChild($group);
};

$written = [];

foreach ($priorityKeys as $winnerKey) {
    foreach ($priorityKeys as $runnerUpKey) {
        if ($winnerKey === $runnerUpKey) {
            continue;
        }

        $winner = $loadSvg($sourceDirectory . DIRECTORY_SEPARATOR . $winnerKey . '.svg');
        $runnerUpFill = $loadSvg($sourceDirectory . DIRECTORY_SEPARATOR . $runnerUpKey . '.svg');
        $runnerUpOutline = $loadSvg($sourceDirectory . DIRECTORY_SEPARATOR . $runnerUpKey . '_out.svg');
        [, , $winnerWidth, $winnerHeight] = $readViewBox($winner);
        [, , $runnerWidth, $runnerHeight] = $readViewBox($runnerUpFill);

        if ($winnerWidth !== 16.0 || $winnerHeight !== 16.0 || $runnerWidth !== 16.0 || $runnerHeight !== 16.0) {
            throw new RuntimeException("{$winnerKey}{$runnerUpKey}: Erwartet werden 16 × 16 große Quellsymbole.");
        }

        foreach ($variants as $suffix => $variant) {
            $scale = $variant['ratio'];
            $runnerRenderedWidth = $runnerWidth * $scale;
            $runnerRenderedHeight = $runnerHeight * $scale;
            $contentWidth = $winnerWidth + $runnerRenderedWidth - $overlap;
            $winnerX = ($canvasWidth - $contentWidth) / 2.0;
            $runnerX = $winnerX + $winnerWidth - $overlap;
            $runnerY = $canvasHeight - $runnerRenderedHeight;
            $winnerTransform = sprintf('translate(%.3f 0)', $winnerX);
            $runnerTransform = sprintf('translate(%.3f %.3f) scale(%.3f)', $runnerX, $runnerY, $scale);
            $combinationKey = $winnerKey . $runnerUpKey . '_' . $suffix;

            $output = new DOMDocument('1.0', 'UTF-8');
            $output->formatOutput = true;

            $root = $output->createElementNS('http://www.w3.org/2000/svg', 'svg');
            $root->setAttribute('viewBox', '0 0 26 16');
            $root->setAttribute('width', '26');
            $root->setAttribute('height', '16');
            $root->setAttribute('preserveAspectRatio', 'xMidYMid meet');
            $root->setAttribute('role', 'img');
            $root->setAttribute('aria-labelledby', 'priority-title priority-description');
            $output->appendChild($root);

            $title = $output->createElement('title', "Prioritätenkombination {$combinationKey}");
            $title->setAttribute('id', 'priority-title');
            $root->appendChild($title);

            $description = $output->createElement(
                'desc',
                sprintf(
                    '%s gewinnt; %s ist mit %.0f Prozent der Runner-up.',
                    $winnerKey,
                    $runnerUpKey,
                    $scale * 100
                )
            );
            $description->setAttribute('id', 'priority-description');
            $root->appendChild($description);

            $appendContent($output, $root, $winner, 'priority-winner-' . strtolower($winnerKey), $winnerTransform);
            $appendContent($output, $root, $runnerUpFill, 'priority-runner-fill-' . strtolower($runnerUpKey), $runnerTransform, 0.4);
            $appendContent($output, $root, $runnerUpOutline, 'priority-runner-outline-' . strtolower($runnerUpKey), $runnerTransform);

            $targetPath = $outputDirectory . DIRECTORY_SEPARATOR . $combinationKey . '.svg';
            if ($output->save($targetPath) === false) {
                throw new RuntimeException("SVG konnte nicht geschrieben werden: {$targetPath}");
            }
            $written[] = basename($targetPath);
        }

        $oldPath = $outputDirectory . DIRECTORY_SEPARATOR . $winnerKey . $runnerUpKey . '.svg';
        if (is_file($oldPath) && !unlink($oldPath)) {
            throw new RuntimeException("Alte Prioritäten-SVG konnte nicht entfernt werden: {$oldPath}");
        }
    }
}

printf("%d Prioritäten-SVGs geschrieben: %s\n", count($written), implode(', ', $written));

