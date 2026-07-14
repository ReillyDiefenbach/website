<?php

$directory = __DIR__; // Aktuelles Verzeichnis
$folders = scandir($directory); // Liste aller Dateien und Ordner im Verzeichnis
$result = []; // Array, das das Ergebnis speichert

foreach ($folders as $folder) {
    // Überprüfe, ob es sich um einen Ordner handelt und ignoriere '.' und '..'
    if ($folder === '.' || $folder === '..' || !is_dir($directory . '/' . $folder)) {
        continue;
    }

    // Verwende glob, um alle PNG-Dateien im Unterordner zu finden
    $pngFiles = glob($directory . '/' . $folder . '/*.png');

    foreach ($pngFiles as $file) {
        $newName = str_replace(' ', '_', basename($file));
        // Überprüfe, ob der Dateiname geändert werden muss
        if ($newName !== basename($file)) {
            // Benenne die Datei um, wenn nötig
            rename($file, $directory . '/' . $folder . '/' . $newName);
        }
        // Füge den neuen Dateinamen zum Ergebnisarray hinzu
        $result[$folder][] = $newName;
    }
}

// Konvertiere das Ergebnis in JSON
$json = json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

// Speichere das JSON in einer Datei
file_put_contents($directory . '/avatare.json', $json);

echo "JSON-Datei wurde erstellt, alle Dateien umbenannt.\n";
