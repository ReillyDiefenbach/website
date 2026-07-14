# carlvon.com – Projektkontext

Stand: 14. Juli 2026

Dieses Dokument ist die zentrale Orientierung fuer Menschen und Codex-Aufgaben. Vor groesseren Aenderungen zuerst den realen Projektstand und danach dieses Dokument lesen.

## Projektziel

Dieses Repository enthaelt den PHP-Webauftritt von carlvon.com mit dateibasierten Inhaltsseiten, gemeinsamem Frame, zentraler Navigation, SCSS-/JavaScript-Quellen und erzeugten CDN-Dateien.

## Wichtige Einstiegspunkte

- `index.php`: Hauptseite, CDN-Auswahl, gemeinsamer Frame und initiales Routing.
- `sites/`: normale Inhaltsseiten. Das Muster ist `sites/<route>/<seite>/<sprache>.html`.
- `home/`: Bestandteile der Startseite.
- `mod/`: Modulseiten.
- `admin/`: Navigation, Controller und funktionale Bereiche.
- `frame/`: gemeinsam verwendete PHP-Fragmente.
- `__wolfi/_fileMaker/scss/`: aktive SCSS-Quellen.
- `__wolfi/_fileMaker/js/`: aktive JavaScript-Quellen.
- `_cdn/`: erzeugte CSS- und JavaScript-Bundles.
- `_assets/`: Bilder, Schriften, Videos und weitere Medien.

## Asset-Build

Die Quelle des Builds ist `__wolfi/_fileMaker/makeCDN.php`. Der Build erzeugt:

- `_cdn/carlvon.css`
- `_cdn/carlvon.min.css`
- `_cdn/carlvon.typography.css`
- `_cdn/carlvon.typography.min.css`
- `_cdn/carlvon.js`
- `_cdn/carlvon.min.js`

Manueller Build aus dem Projektstamm:

```powershell
C:\php\php.exe -r "require '__wolfi/_fileMaker/makeCDN.php'; makeCDN_ifChanged(true);"
```

`index.php` ruft den Build bei lokalen normalen Seitenaufrufen zusaetzlich bei Bedarf auf. Lokal werden die Dateien aus `_cdn/` verwendet. Auf einem nicht lokalen Host ist derzeit standardmaessig das Repository-CDN `carlvon/carlvon-cdn` eingestellt; es kann mit `CARLVON_REPOSITORY_CDN` ueberschrieben werden.

## Typografie und Layout

- Zentrale Typografiequelle: `__wolfi/_fileMaker/scss/typography.scss`.
- Grundschrift: Gotham Rounded; Montserrat ist ueber `data-font="montserrat"` oder `.font-montserrat` waehlbar.
- Universelle Textklassen: `.text-left`, `.text-center`, `.text-right`, `.text-block`, `.text-nowrap`.
- Wiederverwendbare Layout-Helfer befinden sich in den SCSS-Dateien, unter anderem `.columnize-2`, `.columnize-3` und `section.col-2`.

## Inhaltsrouting

Beispiele:

- Route `overview/test` laedt `sites/overview/test/de.html` oder `en.html`.
- Route `about/contact` laedt `sites/about/contact/de.html` oder `en.html`.

Alte Inhalte nicht kommentarlos loeschen. Bei Umstrukturierungen als klar benannte Legacy- oder Recovery-Kopie erhalten und separat sichern.

## Private Konfiguration und Datenbank

`__wolfi/_params/_SERVER.php` ist lokal und wird von Git ignoriert. Die Datei enthaelt Datenbank- und Mailzugangsdaten. Als Ausgangspunkt dient `__wolfi/_params/_SERVER.example.php`.

SQL-Exporte unter `__wolfi/_fileMaker/database/backup/` werden ebenfalls ignoriert. Sie muessen verschluesselt und getrennt vom Git-Repository gesichert werden.

## Arbeitsregeln

1. Vor einer Aenderung die betroffenen Quelldateien lesen.
2. Generierte `_cdn`-Dateien nicht direkt als alleinige Quelle bearbeiten.
3. Nach SCSS-/JS-Aenderungen den Build ausfuehren.
4. Lokal mindestens Startseite und betroffene Route pruefen.
5. Vor einem Rechnerwechsel committen und pushen; am anderen Rechner vor Arbeitsbeginn pullen.
6. Passwoerter, Datenbankexports und persoenliche Daten niemals committen.

