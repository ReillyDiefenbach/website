<?php

declare(strict_types=1);

/**
 * Erzeugt die vollständige Liste der kanonisch auflösbaren Ergebnisfälle.
 * Schwellenwerte gehören bewusst nicht in diese Datei; sie werden zur Laufzeit
 * durch die Berechnungslogik bestimmt.
 */

$rows = [];

$add = static function (
    string $level,
    string $key,
    string $baseKey,
    string $winner,
    string $runnerUp,
    string $strength,
    string $balancedKeys,
    string $roleAllowed,
    string $meaning
) use (&$rows): void {
    $rows[] = [
        $level,
        $key,
        $baseKey,
        $winner,
        $runnerUp,
        $strength,
        $balancedKeys,
        $roleAllowed,
        $meaning,
    ];
};

// 1. Achsenanalyse / Orientierung: 7 Fälle
foreach ([['X', 'Y'], ['Y', 'X']] as [$winner, $runnerUp]) {
    $add('orientation', "{$winner}_CLEAR", $winner, $winner, $runnerUp, 'CLEAR', '', '',
        "{$winner} gewinnt klar gegenüber {$runnerUp}.");
    $add('orientation', "{$winner}_MODERATE", $winner, $winner, $runnerUp, 'MODERATE', '', '',
        "{$winner} gewinnt erkennbar, aber nicht klar, gegenüber {$runnerUp}.");
    $add('orientation', "{$winner}_CLOSE_{$runnerUp}", $winner, $winner, $runnerUp, 'CLOSE', '', '',
        "{$winner} liegt knapp vor {$runnerUp}; beide Pole prägen die Orientierung.");
}
$add('orientation', 'XY_BALANCED', 'XY', 'X = Y', '', 'BALANCED', 'X,Y', '',
    'X und Y sind ausgeglichen; kein Achsenpol dominiert.');

// 2. Richtungsanalyse / Prioritäten: 7 Fälle je Gegenpolpaar, insgesamt 14.
foreach ([['W', 'E', 'West', 'Ost'], ['N', 'S', 'Nord', 'Süd']] as [$a, $b, $aLabel, $bLabel]) {
    foreach ([[$a, $b, $aLabel, $bLabel], [$b, $a, $bLabel, $aLabel]] as [$winner, $runnerUp, $winnerLabel, $runnerLabel]) {
        $add('priorities', "{$winner}_CLEAR", $winner, $winner, $runnerUp, 'CLEAR', '', '',
            "{$winnerLabel} gewinnt klar gegenüber {$runnerLabel}.");
        $add('priorities', "{$winner}_MODERATE", $winner, $winner, $runnerUp, 'MODERATE', '', '',
            "{$winnerLabel} gewinnt erkennbar, aber nicht klar, gegenüber {$runnerLabel}.");
        $add('priorities', "{$winner}_CLOSE_{$runnerUp}", $winner, $winner, $runnerUp, 'CLOSE', '', '',
            "{$winnerLabel} liegt knapp vor {$runnerLabel}; beide Richtungen bleiben wirksam.");
    }

    $balancedKey = $a . $b;
    $add('priorities', "{$balancedKey}_BALANCED", $balancedKey, "{$a} = {$b}", '', 'BALANCED', "{$a},{$b}", '',
        "{$aLabel} und {$bLabel} sind ausgeglichen; keine Richtung dominiert.");
}

// 3. Quadrantenanalyse / Wirkungsräume: 31 Fälle.
for ($winner = 1; $winner <= 4; $winner++) {
    $winnerKey = "Q{$winner}";
    $add('impact_space', "{$winnerKey}_CLEAR", $winnerKey, $winnerKey, '', 'CLEAR', '', 'true',
        "Wirkungsraum {$winnerKey} dominiert klar.");
    $add('impact_space', "{$winnerKey}_MODERATE", $winnerKey, $winnerKey, '', 'MODERATE', '', 'true',
        "Wirkungsraum {$winnerKey} dominiert erkennbar, aber nicht klar.");

    for ($runnerUp = 1; $runnerUp <= 4; $runnerUp++) {
        if ($runnerUp === $winner) {
            continue;
        }
        $runnerKey = "Q{$runnerUp}";
        $add('impact_space', "{$winnerKey}_CLOSE_{$runnerKey}", $winnerKey, $winnerKey, $runnerKey, 'CLOSE', '', 'true',
            "Wirkungsraum {$winnerKey} liegt knapp vor {$runnerKey}; die Reihenfolge bleibt eindeutig.");
    }
}

for ($a = 1; $a <= 4; $a++) {
    for ($b = $a + 1; $b <= 4; $b++) {
        $balancedKeys = "Q{$a},Q{$b}";
        $add('impact_space', "Q{$a}_Q{$b}_BALANCED", 'Q0', "Q{$a} = Q{$b}", '', 'BALANCED', $balancedKeys, 'false',
            "Die Wirkungsräume Q{$a} und Q{$b} sind ausgeglichen; kein Rollenprofil wird gebildet.");
    }
}

for ($a = 1; $a <= 2; $a++) {
    for ($b = $a + 1; $b <= 3; $b++) {
        for ($c = $b + 1; $c <= 4; $c++) {
            $balancedKeys = "Q{$a},Q{$b},Q{$c}";
            $add('impact_space', "Q{$a}_Q{$b}_Q{$c}_BALANCED", 'Q0', "Q{$a} = Q{$b} = Q{$c}", '', 'BALANCED', $balancedKeys, 'false',
                "Die Wirkungsräume Q{$a}, Q{$b} und Q{$c} sind ausgeglichen; Q0 beschreibt die fehlende Dominanz.");
        }
    }
}

$add('impact_space', 'Q0_BALANCED', 'Q0', 'keine Dominanz', '', 'BALANCED', 'Q1,Q2,Q3,Q4', 'false',
    'Drei oder mehr Wirkungsräume sind ausgeglichen beziehungsweise keiner dominiert; Q0 ist das Ergebnis.');

// 4. Rollenprofil: 12 geordnete Quadrantenpaare × 3 Stärken plus Sperrfall.
for ($primary = 1; $primary <= 4; $primary++) {
    for ($secondary = 1; $secondary <= 4; $secondary++) {
        if ($primary === $secondary) {
            continue;
        }

        $roleKey = "R{$primary}{$secondary}";
        $primaryKey = "Q{$primary}";
        $secondaryKey = "Q{$secondary}";

        $add('role_profile', "{$roleKey}_CLEAR", $roleKey, $primaryKey, $secondaryKey, 'CLEAR', '', 'true',
            "Rollenprofil {$roleKey}: {$primaryKey} ist der klar stärkste und {$secondaryKey} der zweitstärkste Wirkungsraum.");
        $add('role_profile', "{$roleKey}_MODERATE", $roleKey, $primaryKey, $secondaryKey, 'MODERATE', '', 'true',
            "Rollenprofil {$roleKey}: {$primaryKey} führt erkennbar vor dem zweitstärksten Wirkungsraum {$secondaryKey}.");
        $add('role_profile', "{$roleKey}_CLOSE", $roleKey, $primaryKey, $secondaryKey, 'CLOSE', '', 'true',
            "Rollenprofil {$roleKey}: {$primaryKey} liegt knapp vor {$secondaryKey}; die Rangfolge ist dennoch eindeutig.");
    }
}

$add('role_profile', 'ROLE_SUPPRESSED_BY_BALANCE', '', 'kein Rollenprofil', '', 'BALANCED', '', 'false',
    'Bei echter Balance wird kein Rollenprofil erzeugt; der ausgeglichene Wirkungsraum bleibt das Ergebnis.');

$target = __DIR__ . DIRECTORY_SEPARATOR . 'model_result_cases.tsv';
$handle = fopen($target, 'wb');
if ($handle === false) {
    throw new RuntimeException("TSV kann nicht geschrieben werden: {$target}");
}

// UTF-8-BOM, damit auch Excel und ältere Windows-Programme Umlaute erkennen.
fwrite($handle, "\xEF\xBB\xBF");

fputcsv($handle, [
    'level',
    'key',
    'base_key',
    'winner',
    'runner_up',
    'dominance_strength',
    'balanced_keys',
    'role_allowed',
    'meaning',
], "\t", '"', '\\');

foreach ($rows as $row) {
    fputcsv($handle, $row, "\t", '"', '\\');
}

fclose($handle);

$baseRows = [
    ['orientation', 'X', 'X', 'Die X-Achse überwiegt gegenüber der Y-Achse.', 'X_CLEAR', 'X_MODERATE', 'X_CLOSE_Y', 'Y'],
    ['orientation', 'Y', 'Y', 'Die Y-Achse überwiegt gegenüber der X-Achse.', 'Y_CLEAR', 'Y_MODERATE', 'Y_CLOSE_X', 'X'],
    ['orientation', 'XY', 'X = Y', 'Die X- und Y-Achse sind ausgeglichen.', '', '', '', ''],

    ['priorities', 'W', 'W', 'West überwiegt gegenüber Ost.', 'W_CLEAR', 'W_MODERATE', 'W_CLOSE_E', 'E'],
    ['priorities', 'E', 'E', 'Ost überwiegt gegenüber West.', 'E_CLEAR', 'E_MODERATE', 'E_CLOSE_W', 'W'],
    ['priorities', 'WE', 'W = E', 'West und Ost sind ausgeglichen.', '', '', '', ''],
    ['priorities', 'N', 'N', 'Nord überwiegt gegenüber Süd.', 'N_CLEAR', 'N_MODERATE', 'N_CLOSE_S', 'S'],
    ['priorities', 'S', 'S', 'Süd überwiegt gegenüber Nord.', 'S_CLEAR', 'S_MODERATE', 'S_CLOSE_N', 'N'],
    ['priorities', 'NS', 'N = S', 'Nord und Süd sind ausgeglichen.', '', '', '', ''],

    ['impact_space', 'Q0', 'keine Dominanz', 'Kein Wirkungsraum dominiert.', '', '', '', ''],
    ['impact_space', 'Q1', 'Q1', 'Wirkungsraum Q1 dominiert.', 'Q1_CLEAR', 'Q1_MODERATE', 'Q1_CLOSE_Q2,Q1_CLOSE_Q3,Q1_CLOSE_Q4', 'Q2,Q3,Q4'],
    ['impact_space', 'Q2', 'Q2', 'Wirkungsraum Q2 dominiert.', 'Q2_CLEAR', 'Q2_MODERATE', 'Q2_CLOSE_Q1,Q2_CLOSE_Q3,Q2_CLOSE_Q4', 'Q1,Q3,Q4'],
    ['impact_space', 'Q3', 'Q3', 'Wirkungsraum Q3 dominiert.', 'Q3_CLEAR', 'Q3_MODERATE', 'Q3_CLOSE_Q1,Q3_CLOSE_Q2,Q3_CLOSE_Q4', 'Q1,Q2,Q4'],
    ['impact_space', 'Q4', 'Q4', 'Wirkungsraum Q4 dominiert.', 'Q4_CLEAR', 'Q4_MODERATE', 'Q4_CLOSE_Q1,Q4_CLOSE_Q2,Q4_CLOSE_Q3', 'Q1,Q2,Q3'],
];

for ($primary = 1; $primary <= 4; $primary++) {
    for ($secondary = 1; $secondary <= 4; $secondary++) {
        if ($primary === $secondary) {
            continue;
        }

        $roleKey = "R{$primary}{$secondary}";
        $primaryKey = "Q{$primary}";
        $secondaryKey = "Q{$secondary}";
        $baseRows[] = [
            'role_profile',
            $roleKey,
            "{$primaryKey} > {$secondaryKey}",
            "{$primaryKey} ist der stärkste und {$secondaryKey} der zweitstärkste Wirkungsraum.",
            "{$roleKey}_CLEAR",
            "{$roleKey}_MODERATE",
            "{$roleKey}_CLOSE",
            $secondaryKey,
        ];
    }
}

$baseTarget = __DIR__ . DIRECTORY_SEPARATOR . 'model_result_options_26.tsv';
$baseHandle = fopen($baseTarget, 'wb');
if ($baseHandle === false) {
    throw new RuntimeException("TSV kann nicht geschrieben werden: {$baseTarget}");
}

fwrite($baseHandle, "\xEF\xBB\xBF");
fputcsv($baseHandle, [
    'level',
    'key',
    'winner',
    'meaning',
    'clear',
    'moderate',
    'close',
    'close_mit',
], "\t", '"', '\\');

foreach ($baseRows as $baseRow) {
    fputcsv($baseHandle, $baseRow, "\t", '"', '\\');
}

fclose($baseHandle);

printf(
    "%d Ergebnisfälle nach %s und %d Basisoptionen nach %s geschrieben.\n",
    count($rows),
    $target,
    count($baseRows),
    $baseTarget
);
