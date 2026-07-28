# Output Rules

## Grundsatz

Arbeitsdateien und Download-Dateien werden getrennt gespeichert.

Markdown-Dateien sind Arbeits- und Quelldokumente.

DOCX- und PDF-Dateien sind Benutzer-, Management- und Download-Dokumente.

---

## Speicherorte

Verwende ausschließlich die in `path.json` definierten Ausgabe- und Downloadpfade.

Wenn `path.json` eigene Pfade für Output und Download definiert, gelten diese Pfade.

---

## Markdown

Markdown-Dateien werden im konfigurierten Output-Bereich gespeichert.

Beispiel:

```text
/outputfiles/organization/handbook.md
```

---

## DOCX

DOCX-Dateien werden im konfigurierten Download-Bereich gespeichert.

Beispiel:

```text
/download/organization/handbook.docx
```

---

## PDF

PDF-Dateien werden im konfigurierten Download-Bereich gespeichert.

Beispiel:

```text
/download/organization/handbook.pdf
```

---

## Dokumentations-Outputs

Für standardisierte Dokumentations-Outputs gilt ein datiertes technisches Dateischema mit `docu_*`-Präfix:

```text
docu_<bereich>_<scope>_DDMMJJ
```

Beispiele für den 24. Juni 2026:

```text
docu_organization_240626.md
docu_tasks_canon_freeze_240626.pdf
docu_canon_gesamtcanon_240626.pdf
docu_family_think_act_240626.pdf
docu_model_think_act_personal_240626.pdf
```

Vor dem Schreiben einer neuen Organisationshandbuch-Ausgabe müssen alte `docu_organization_*`-Artefakte im Organisations-Output und Organisations-Download gelöscht werden.

Die Cleanup-Regel gilt ausschließlich für Organisationshandbuch-Artefakte.

Nicht löschen:

* Mitarbeiterlisten
* Rollenhandbücher
* Nachfolgepläne
* Blind-Spot-Reports
* Organisationsreviews
* Templates
* technische Quellen
---

## DOCX- und PDF-Tabellenstandard

Tabellen müssen managementtauglich und druckbar formatiert sein.

### Grundformat

- Schriftgröße in Tabellen: 9 pt
- Kopfzeile: fett
- gesamte Kopfzeile: hellgrau hinterlegt
- untere Linie der Kopfzeile: schwarz
- Zellinnenabstand: 4–6 pt
- Zeilenumbruch in Zellen: aktiviert
- Tabellenbreite: maximal 90 % der nutzbaren Seitenbreite
- Tabellen dürfen nicht über den Seitenrand hinausragen
- Tabellen sollen Seitenumbrüche innerhalb einzelner Zeilen vermeiden, soweit technisch möglich
- keine sichtbaren Zellrahmen
- keine Tabellenrasterlinien
- keine graue Hinterlegung außerhalb der Kopfzeile

### Spaltenbreiten

Wenn keine spezifische Spaltenbreite definiert ist:

- erste Spalte: 20–25 %
- mittlere Spalten: gleichmäßig verteilt
- letzte Spalte: flexibel

Bei breiten Tabellen:

- Inhalte kürzen oder umbrechen
- keine horizontale Überbreite erzeugen
- lieber mehrere kleinere Tabellen als eine zu breite Tabelle

### Lange Inhalte

Bei langen Texten in Tabellenzellen:

- automatische Zeilenumbrüche verwenden
- keine endlosen Einzeiler erzeugen
- maximal 2–4 kurze Sätze pro Zelle
- längere Erläuterungen außerhalb der Tabelle platzieren

### PDF-Regel

Die PDF-Version muss die Tabellenbreiten aus dem DOCX übernehmen.

Wenn eine Tabelle im PDF abgeschnitten würde, muss sie vor dem Export angepasst werden.

---

## Standard-Rendering

Standard-Outputs verwenden die verbindliche CSS- und Render-Regel aus:

```text
knowledge/templates/01_standard_output_rendering.md
```

Wenn HTML als Zwischenformat verwendet wird, darf der Exporter keine eigenen CSS-Regeln erfinden.

Die maschinenlesbare Standard-CSS liegt unter:

```text
templates/css/standard_output.css
```

DOCX- und PDF-Ausgabe müssen Header- und Footer-Variablen aus dem jeweiligen DOCX-Template übernehmen oder äquivalent setzen.

Platzhalter wie `{title}`, `{author}`, `{date}` oder `{version}` dürfen im finalen Dokument nicht sichtbar bleiben.

---

## Sprache und Zeichensatz

Deutschsprachige Benutzer-, Management- und Download-Dokumente verwenden echte Umlaute.

Transliteration wie ae, oe, ue oder ss ist nur in technischen Identifikatoren, Dateinamen, Slugs oder Systemkontexten zulässig.

Alle Standard-Outputs verwenden UTF-8.

---

## Download-Link

Wenn eine DOCX- oder PDF-Datei erzeugt wurde, muss am Ende ein Download-Link ausgegeben werden.

Wenn die Laufzeitumgebung klickbare Links unterstützt, muss der Link klickbar ausgegeben werden.

---

## Abschlussmeldung

Die Abschlussmeldung enthält:

* erzeugte Markdown-Datei
* erzeugte DOCX-Datei, falls vorhanden
* erzeugte PDF-Datei, falls vorhanden
* Download-Link zur PDF-Datei, falls vorhanden
* Download-Link zur DOCX-Datei, falls vorhanden

---

## Keine technischen Interna im Hauptdokument

Technische Informationen gehören nicht in das Hauptdokument:

* Task-Datei
* interne Pfade
* `path.json`
* `knowledge_index`
* Runtime-Kommandos
* Digest-Status
* Hash-Prüfungen
* OpenClaw als interne Infrastrukturbezeichnung

Solche Informationen dürfen nur in einem technischen Anhang erscheinen, wenn der Nutzer oder der Task dies ausdrücklich verlangt.
