# carlvon.com – Einrichtung und Rechnerwechsel

## Voraussetzungen

- Windows mit PowerShell
- Git
- PHP 8.x; der aktuell gepruefte Entwicklungsrechner verwendet PHP 8.4.22
- MySQL oder MariaDB fuer die datenbankgestuetzten Funktionen
- Zugriff auf das spaetere private Git-Repository
- Separater, verschluesselter Zugriff auf Datenbankexport und Zugangsdaten

## Erstmalige Einrichtung auf einem neuen PC

Empfohlener identischer Projektpfad:

```powershell
New-Item -ItemType Directory -Force C:\projectFiles\carlvon-biz
Set-Location C:\projectFiles\carlvon-biz
git clone PRIVATE-REPOSITORY-URL httpdocs
Set-Location httpdocs
```

Lokale Konfiguration erstellen:

```powershell
Copy-Item __wolfi\_params\_SERVER.example.php __wolfi\_params\_SERVER.php
```

Danach nur in `_SERVER.php` die `CHANGE_ME`-Werte setzen oder die dokumentierten `CARLVON_*`-Umgebungsvariablen verwenden. Die echte `_SERVER.php` wird von Git ignoriert.

## Datenbank

Vor jedem Rechnerwechsel einen frischen Export der tatsaechlich verwendeten Datenbank erzeugen. Beispiel:

```powershell
mysqldump -h DB-HOST -u DB-BENUTZER -p DB-NAME > carlvon-database-YYYY-MM-DD.sql
```

Den Dump verschluesselt uebertragen und auf dem neuen Rechner importieren:

```powershell
mysql -h DB-HOST -u DB-BENUTZER -p DB-NAME < carlvon-database-YYYY-MM-DD.sql
```

SQL-Dateien nicht in das Git-Repository aufnehmen.

## Assets bauen

Im Projektstamm:

```powershell
C:\php\php.exe -r "require '__wolfi/_fileMaker/makeCDN.php'; makeCDN_ifChanged(true);"
```

## Lokale Website starten

```powershell
Set-Location C:\projectFiles\carlvon-biz\httpdocs
C:\php\php.exe -S localhost:8000 -t .
```

Danach `http://localhost:8000` im Browser aufrufen. Der Server muss nach normalen Datei- oder Build-Aenderungen nicht neu gestartet werden.

## Taeglicher Wechsel zwischen zwei PCs

Vor Arbeitsbeginn:

```powershell
git pull --ff-only
git status
```

Nach einer abgeschlossenen Arbeitseinheit:

```powershell
git status
git add --all
git commit -m "Kurze Beschreibung der Aenderung"
git push
```

Nicht gleichzeitig auf beiden Rechnern an denselben Dateien arbeiten. Erst auf PC A committen und pushen, dann auf PC B pullen.

## Was Git nicht uebertraegt

- `__wolfi/_params/_SERVER.php`
- Datenbankinhalte und SQL-Backups
- Recovery-Ordner
- lokale Editor-Einstellungen
- nicht gespeicherte Codex-Unterhaltungen

Der technische Arbeitskontext steht deshalb in `PROJECT_CONTEXT.md`. Bei einer neuen Codex-Aufgabe zuerst darum bitten, `PROJECT_CONTEXT.md` und `SETUP.md` zu lesen.

## Vollstaendige Sicherung

Vor dem ersten GitHub-Upload einmal den kompletten heutigen Ordner einschliesslich Recovery-Dateien, Datenbankexports und lokaler Konfiguration als verschluesseltes Archiv auf einem separaten Datentraeger sichern. Diese Sicherung nicht in den Projektordner oder ins Git-Repository legen.

