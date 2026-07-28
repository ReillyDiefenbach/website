<?php

declare(strict_types=1);

/**
 * Erzeugt Rollen- und Wirkungsraum-SVGs aus den Quadrantenfiguren.
 *
 * - Gewinner: 100 %, schwarz, im Überlappungsbereich hinten
 * - Zweiter Quadrant: FIX 60 %, MODERATE 75 %, CLOSE 90 %, vorne
 * - horizontale Überlappung: ein Drittel der Breite der ersten Figur
 * - identische Ausgabegröße: viewBox 2000 × 1500
 */

$chessDirectory = __DIR__ . DIRECTORY_SEPARATOR . '_assets' . DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR . 'chess';
$outputDirectory = __DIR__ . DIRECTORY_SEPARATOR . '_assets' . DIRECTORY_SEPARATOR . 'symb';

if (!is_dir($outputDirectory) && !mkdir($outputDirectory, 0775, true) && !is_dir($outputDirectory)) {
    throw new RuntimeException("Ausgabeverzeichnis konnte nicht erstellt werden: {$outputDirectory}");
}

$roles = [];
for ($primary = 1; $primary <= 4; $primary++) {
    for ($secondary = 1; $secondary <= 4; $secondary++) {
        if ($primary === $secondary) {
            continue;
        }

        $roles["R{$primary}{$secondary}"] = [
            'primary' => "Q{$primary}",
            'secondary' => "Q{$secondary}",
        ];
    }
}

$strengths = [
    'FIX' => ['ratio' => 0.60, 'suffix' => '_f'],
    'MODERATE' => ['ratio' => 0.75, 'suffix' => '_m'],
    'CLOSE' => ['ratio' => 0.90, 'suffix' => '_c'],
];

$canvasWidth = 2000.0;
$canvasHeight = 1500.0;
$overlapRatio = 1 / 3;

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

$styleFigure = static function (DOMDocument $document, bool $secondary): void {
    $xpath = new DOMXPath($document);
    $graphicNodes = $xpath->query(
        '//*[local-name()="path" or local-name()="rect" or local-name()="circle" or '
        . 'local-name()="ellipse" or local-name()="polygon" or local-name()="polyline"]'
        . '[not(ancestor::*[local-name()="clipPath"])]'
    );

    if ($graphicNodes === false) {
        return;
    }

    foreach ($graphicNodes as $node) {
        if (!$node instanceof DOMElement) {
            continue;
        }

        $node->removeAttribute('style');
        $node->setAttribute('fill', $secondary ? '#aaa' : '#000');
        $node->setAttribute('fill-opacity', '1');

        if ($secondary) {
            $node->setAttribute('stroke', 'none');
            $node->setAttribute('stroke-width', '0');
            $node->removeAttribute('stroke-linejoin');
            $node->removeAttribute('vector-effect');
        } else {
            $node->setAttribute('stroke', 'none');
        }
    }
};

$prefixIds = static function (DOMDocument $document, string $prefix): void {
    $xpath = new DOMXPath($document);
    $nodes = $xpath->query('//*[@id]');
    $idMap = [];

    if ($nodes !== false) {
        foreach ($nodes as $node) {
            if (!$node instanceof DOMElement) {
                continue;
            }

            $oldId = $node->getAttribute('id');
            $newId = $prefix . '-' . $oldId;
            $idMap[$oldId] = $newId;
            $node->setAttribute('id', $newId);
        }
    }

    if ($idMap === []) {
        return;
    }

    $allElements = $xpath->query('//*');
    if ($allElements === false) {
        return;
    }

    foreach ($allElements as $element) {
        if (!$element instanceof DOMElement) {
            continue;
        }

        foreach (iterator_to_array($element->attributes) as $attribute) {
            $value = $attribute->value;
            foreach ($idMap as $oldId => $newId) {
                $value = str_replace("url(#{$oldId})", "url(#{$newId})", $value);
                if ($value === "#{$oldId}") {
                    $value = "#{$newId}";
                }
            }
            $attribute->value = $value;
        }
    }
};

$appendSvgContent = static function (
    DOMDocument $target,
    DOMElement $targetRoot,
    DOMDocument $source,
    string $id,
    string $transform
): void {
    $group = $target->createElement('g');
    $group->setAttribute('id', $id);
    $group->setAttribute('transform', $transform);

    foreach ($source->documentElement->childNodes as $child) {
        $group->appendChild($target->importNode($child, true));
    }

    $targetRoot->appendChild($group);
};

$buildRoleSvg = static function (
    string $roleKey,
    string $primaryKey,
    string $secondaryKey,
    string $strength,
    float $secondaryRatio,
    string $targetPath
) use (
    $chessDirectory,
    $canvasWidth,
    $canvasHeight,
    $overlapRatio,
    $loadSvg,
    $readViewBox,
    $styleFigure,
    $prefixIds,
    $appendSvgContent
): void {
    $primary = $loadSvg($chessDirectory . DIRECTORY_SEPARATOR . $primaryKey . '.svg');
    $secondary = $loadSvg($chessDirectory . DIRECTORY_SEPARATOR . $secondaryKey . '.svg');

    [, , $primaryWidth, $primaryHeight] = $readViewBox($primary);
    [, , $secondaryWidth, $secondaryHeight] = $readViewBox($secondary);

    $styleFigure($primary, false);
    $styleFigure($secondary, true);
    $prefixIds($primary, strtolower($roleKey . '-primary'));
    $prefixIds($secondary, strtolower($roleKey . '-secondary'));

    $primaryScale = $canvasHeight / $primaryHeight;
    $secondaryScale = ($canvasHeight * $secondaryRatio) / $secondaryHeight;
    $renderedPrimaryWidth = $primaryWidth * $primaryScale;
    $renderedSecondaryWidth = $secondaryWidth * $secondaryScale;
    $overlap = $renderedPrimaryWidth * $overlapRatio;
    $contentWidth = $renderedPrimaryWidth + $renderedSecondaryWidth - $overlap;

    if ($contentWidth > $canvasWidth) {
        throw new RuntimeException("{$roleKey}_{$strength} überschreitet die feste Ausgabebreite.");
    }

    $primaryX = ($canvasWidth - $contentWidth) / 2;
    $primaryY = $canvasHeight - ($primaryHeight * $primaryScale);
    $secondaryX = $primaryX + $renderedPrimaryWidth - $overlap;
    $secondaryY = $canvasHeight - ($secondaryHeight * $secondaryScale);

    $output = new DOMDocument('1.0', 'UTF-8');
    $output->formatOutput = true;

    $root = $output->createElementNS('http://www.w3.org/2000/svg', 'svg');
    $root->setAttribute('viewBox', '0 0 2000 1500');
    $root->setAttribute('width', '2000');
    $root->setAttribute('height', '1500');
    $root->setAttribute('preserveAspectRatio', 'xMidYMid meet');
    $root->setAttribute('role', 'img');
    $root->setAttribute('aria-labelledby', 'role-title role-description');
    $output->appendChild($root);

    $title = $output->createElement('title', "{$roleKey} {$strength} – {$primaryKey} mit {$secondaryKey}");
    $title->setAttribute('id', 'role-title');
    $root->appendChild($title);

    $description = $output->createElement(
        'desc',
        sprintf(
            '%s als Gewinner in Schwarz; %s mit %.0f Prozent Größe in Grau.',
            $primaryKey,
            $secondaryKey,
            $secondaryRatio * 100
        )
    );
    $description->setAttribute('id', 'role-description');
    $root->appendChild($description);

    // Zuerst der Gewinner: Die zweite Figur wird danach gezeichnet und liegt vorne.
    $appendSvgContent(
        $output,
        $root,
        $primary,
        'primary-' . strtolower($primaryKey),
        sprintf('translate(%.3f %.3f) scale(%.5f)', $primaryX, $primaryY, $primaryScale)
    );
    $appendSvgContent(
        $output,
        $root,
        $secondary,
        'secondary-' . strtolower($secondaryKey),
        sprintf('translate(%.3f %.3f) scale(%.5f)', $secondaryX, $secondaryY, $secondaryScale)
    );

    if ($output->save($targetPath) === false) {
        throw new RuntimeException("SVG konnte nicht geschrieben werden: {$targetPath}");
    }
};

$buildBalancedSvg = static function (string $symbolKey, string $targetPath) use (
    $chessDirectory,
    $canvasWidth,
    $canvasHeight,
    $loadSvg,
    $readViewBox,
    $styleFigure,
    $prefixIds,
    $appendSvgContent
): void {
    $document = $loadSvg($chessDirectory . DIRECTORY_SEPARATOR . 'king.svg');
    [, , $width, $height] = $readViewBox($document);
    $scale = $canvasHeight / $height;
    $renderedWidth = $width * $scale;
    $x = ($canvasWidth - $renderedWidth) / 2;

    $styleFigure($document, false);
    $prefixIds($document, strtolower($symbolKey . '-king'));

    $output = new DOMDocument('1.0', 'UTF-8');
    $output->formatOutput = true;

    $root = $output->createElementNS('http://www.w3.org/2000/svg', 'svg');
    $root->setAttribute('viewBox', '0 0 2000 1500');
    $root->setAttribute('width', '2000');
    $root->setAttribute('height', '1500');
    $root->setAttribute('preserveAspectRatio', 'xMidYMid meet');
    $root->setAttribute('role', 'img');
    $root->setAttribute('aria-labelledby', 'role-title role-description');
    $output->appendChild($root);

    $titleText = $symbolKey === 'R0'
        ? 'R0 – ausgeglichenes Rollenprofil'
        : 'Q0 – ausgeglichener Wirkungsraum';
    $title = $output->createElement('title', $titleText);
    $title->setAttribute('id', 'role-title');
    $root->appendChild($title);

    $descriptionText = $symbolKey === 'R0'
        ? 'Der König steht für ein ausgeglichenes Rollenprofil ohne führenden Wirkungsraum.'
        : 'Der König steht für einen ausgeglichenen Wirkungsraum ohne Dominanz.';
    $description = $output->createElement('desc', $descriptionText);
    $description->setAttribute('id', 'role-description');
    $root->appendChild($description);

    $appendSvgContent(
        $output,
        $root,
        $document,
        'balanced-king',
        sprintf('translate(%.3f 0) scale(%.5f)', $x, $scale)
    );

    if ($output->save($targetPath) === false) {
        throw new RuntimeException("SVG konnte nicht geschrieben werden: {$targetPath}");
    }
};

$buildImpactSvg = static function (string $quadrantKey, string $targetPath) use (
    $chessDirectory,
    $canvasWidth,
    $canvasHeight,
    $loadSvg,
    $readViewBox,
    $styleFigure,
    $prefixIds,
    $appendSvgContent
): void {
    $document = $loadSvg($chessDirectory . DIRECTORY_SEPARATOR . $quadrantKey . '.svg');
    [, , $width, $height] = $readViewBox($document);
    $scale = $canvasHeight / $height;
    $renderedWidth = $width * $scale;
    $x = ($canvasWidth - $renderedWidth) / 2;

    $styleFigure($document, false);
    $prefixIds($document, 'impact-' . strtolower($quadrantKey));

    $output = new DOMDocument('1.0', 'UTF-8');
    $output->formatOutput = true;

    $root = $output->createElementNS('http://www.w3.org/2000/svg', 'svg');
    $root->setAttribute('viewBox', '0 0 2000 1500');
    $root->setAttribute('width', '2000');
    $root->setAttribute('height', '1500');
    $root->setAttribute('preserveAspectRatio', 'xMidYMid meet');
    $root->setAttribute('role', 'img');
    $root->setAttribute('aria-labelledby', 'impact-title impact-description');
    $output->appendChild($root);

    $title = $output->createElement('title', "Wirkungsraum {$quadrantKey}");
    $title->setAttribute('id', 'impact-title');
    $root->appendChild($title);

    $description = $output->createElement('desc', "Symbolische Darstellung des Wirkungsraums {$quadrantKey}.");
    $description->setAttribute('id', 'impact-description');
    $root->appendChild($description);

    $appendSvgContent(
        $output,
        $root,
        $document,
        'impact-' . strtolower($quadrantKey),
        sprintf('translate(%.3f 0) scale(%.5f)', $x, $scale)
    );

    if ($output->save($targetPath) === false) {
        throw new RuntimeException("SVG konnte nicht geschrieben werden: {$targetPath}");
    }
};

$written = [];

foreach ($roles as $roleKey => $role) {
    foreach ($strengths as $strength => $variant) {
        $targetPath = $outputDirectory . DIRECTORY_SEPARATOR . $roleKey . $variant['suffix'] . '.svg';
        $buildRoleSvg($roleKey, $role['primary'], $role['secondary'], $strength, $variant['ratio'], $targetPath);
        $written[] = basename($targetPath);
    }

    foreach (["{$roleKey}.svg", "{$roleKey}_f.svg", "{$roleKey}_m.svg", "{$roleKey}_c.svg", "{$roleKey}_CLEAR.svg", "{$roleKey}_MODERATE.svg", "{$roleKey}_CLOSE.svg"] as $oldName) {
        $oldPath = $chessDirectory . DIRECTORY_SEPARATOR . $oldName;
        if (is_file($oldPath) && !unlink($oldPath)) {
            throw new RuntimeException("Alte SVG-Variante konnte nicht entfernt werden: {$oldPath}");
        }
    }
}

$r0Path = $outputDirectory . DIRECTORY_SEPARATOR . 'R0.svg';
$buildBalancedSvg('R0', $r0Path);
$written[] = basename($r0Path);

foreach ([
    $outputDirectory . DIRECTORY_SEPARATOR . 'R1234.svg',
    $chessDirectory . DIRECTORY_SEPARATOR . 'R1234.svg',
] as $oldBalancedPath) {
    if (is_file($oldBalancedPath) && !unlink($oldBalancedPath)) {
        throw new RuntimeException("Alte Balance-SVG konnte nicht entfernt werden: {$oldBalancedPath}");
    }
}

for ($quadrant = 1; $quadrant <= 4; $quadrant++) {
    $quadrantKey = "Q{$quadrant}";
    $impactPath = $outputDirectory . DIRECTORY_SEPARATOR . $quadrantKey . '.svg';
    $buildImpactSvg($quadrantKey, $impactPath);
    $written[] = basename($impactPath);
}

$q0Path = $outputDirectory . DIRECTORY_SEPARATOR . 'Q0.svg';
$buildBalancedSvg('Q0', $q0Path);
$written[] = basename($q0Path);

printf("%d Symbol-SVGs geschrieben: %s\n", count($written), implode(', ', $written));
