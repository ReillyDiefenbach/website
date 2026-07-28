# Style Bible

## Zweck

Die Style Bible definiert die verbindlichen Gestaltungsregeln für alle Dokumente.

Sie gilt für:

- Markdown
- DOCX
- PDF
- Managementberichte
- Organisationsdokumente
- Governance-Dokumente

---

## Grundschrift

font-family: Arial
font-size: 10 pt
line-height: 1.15

---

## Überschriften

### H1

font-size: 14 pt
font-weight: bold
numbering: 1.
spacing-before: 12 pt
spacing-after: 6 pt

### H2

font-size: 13 pt
font-weight: bold
numbering: 1.1.
indent-left: 7 pt
spacing-before: 10 pt
spacing-after: 5 pt

### H3

font-size: 12 pt
font-weight: bold
numbering: 1.1.1.
indent-left: 14 pt
spacing-before: 8 pt
spacing-after: 4 pt

---

## Fließtext

font-size: 10 pt
font-weight: regular
spacing-after: 4 pt

---

## Sprache und Zeichen

Deutschsprachige Benutzer-, Management- und Download-Dokumente verwenden echte Umlaute.

Transliteration wie ae, oe, ue oder ss ist nur in technischen Identifikatoren, Dateinamen, Slugs oder Systemkontexten zulässig.

Richtig:

- Führung
- Qualität
- Prioritäten
- Größe

Falsch in Benutzer-, Management- und Download-Dokumenten:

- Fuehrung
- Qualitaet
- Prioritaeten
- Groesse

Alle Standard-Outputs müssen durchgehend UTF-8 verwenden.

---

## Modul- und Modellschreibweise

Sichtbare Modulnamen verwenden den öffentlichen `title`, nicht interne technische Bezeichnungen.

Beispiel:

Richtig:

- Think & Act
- Think & Act _Personal_
- Think & Act _Business_

Falsch in sichtbaren Benutzer-, Management- und Download-Dokumenten:

- Thinking
- thinking
- Thinking Personal
- Thinking Business

In HTML werden Modellvarianten mit Kontextmarker im Superscript gesetzt.

Verbindliches Web-Markup:

```html
<span class="family">Think & Act <sup>Personal</sup></span>
```

und:

```html
<span class="family">Think & Act <sup>Business</sup></span>
```

Der Inhalt der Klasse `family` muss den öffentlichen Titel enthalten. Der Kontextmarker steht in `sup`.

---

## Listen

font-size: 10 pt
indent-left: 12 pt
spacing-after: 3 pt

---

## Tabellen

font-size: 9 pt
header-font-weight: bold
cell-padding: 4 pt
table-width: max 90 %
word-wrap: true
repeat-header-row: true
avoid-row-page-break: true
visible-cell-borders: false
header-background: light-gray
header-bottom-border: 1 pt solid black

### Tabellenkopf

font-weight: bold
vertical-align: top
background: light-gray
bottom-border: 1 pt solid black

Die gesamte Kopfzeile wird grau hinterlegt.

Nur die Kopfzeile darf grau hinterlegt sein.

### Tabellenzellen

vertical-align: top
word-wrap: true
background: none
border: none

Datenzellen haben keine sichtbaren Zellrahmen.

Tabellen verwenden keine Rasterlinien.

### Breite

Tabellen dürfen nicht über den Satzspiegel hinausragen.

Bei breiten Tabellen:

- Text umbrechen
- Spalten kürzen
- mehrere kleinere Tabellen verwenden
- keine horizontale Überbreite erzeugen

---

## Titelseite

Titelseite verwendet DOCX-Template.

Keine technischen Pfade.

Keine Runtime-Kommandos.

Keine Hash-Informationen.

---

## Inhaltsverzeichnis

Nach der Titelseite.

Automatisch oder aus Kapitelstruktur erzeugt.

---

## Kopf- und Fußzeilen

Kopf- und Fußzeilen verwenden die im jeweiligen DOCX-Template definierten Variablen.

Standardvariablen:

- `{title}`
- `{subtitle}`
- `{document_type}`
- `{author}`
- `{date}`
- `{version}`
- `{classification}`
- `{page}`
- `{pages}`

Die Variablen müssen vor DOCX- und PDF-Ausgabe ersetzt werden.

Platzhalter dürfen im finalen Benutzer-, Management- oder Download-Dokument nicht sichtbar bleiben.

Header und Footer dürfen nicht aus einer separaten CSS-Regel frei erfunden werden.

Wenn HTML als Zwischenformat verwendet wird, müssen Header- und Footer-Daten mit den DOCX-Template-Variablen übereinstimmen.

---

## Technische Anhänge

Technische Quellen, Pfade, Digest-Prüfungen und Runtime-Kommandos dürfen nur in einen technischen Anhang, wenn ausdrücklich verlangt.
