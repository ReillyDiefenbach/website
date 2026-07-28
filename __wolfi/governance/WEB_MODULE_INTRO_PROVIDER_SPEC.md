# Provider-Spezifikation: Web Module Intro

Version: 1.0  
Status: verbindliche Übergabespezifikation  
Geltungsbereich: Erstellung, Prüfung und Einbindung eines öffentlichen Modul-Intros  
Ziel: identische Ergebnisse unabhängig vom eingesetzten KI-Provider

---

# 1. Zweck

Diese Spezifikation enthält den vollständigen Arbeitsvertrag für die Erstellung eines Modul-Intros. Ein externer KI-Provider darf keine fehlenden Regeln aus Erfahrung, Wahrscheinlichkeit oder bestehenden Webseiten ergänzen.

Die Spezifikation erzeugt keine neue Modellwahrheit. Sie regelt:

- welche Quellen gelesen werden müssen;
- welche Aussagen erlaubt sind;
- wie der Text aufgebaut und geschrieben wird;
- welche Dateien erzeugt werden;
- wie das JSON strukturiert ist;
- wie exakt 400 Wörter gezählt werden;
- wie das HTML aus dem zentralen Template entsteht;
- wie der neue Block vor die bestehende Seite gesetzt wird;
- wie Halluzinationen, Wiederholungen und technische Abweichungen geprüft werden.

---

# 2. Autorität und Quellenrangfolge

Der Provider muss vor der Textarbeit alle für den Auftrag genannten Dateien vollständig lesen.

Verbindliche Rangfolge:

1. `__wolfi/canon/`
2. `__wolfi/governance/01_structure.md` bis `06_output_rules.md`
3. `__wolfi/governance/TEXT_GOVERNANCE.md`
4. freigegebene Dateien unter `__wolfi/compact/{compact_module}/`
5. bestehende freigegebene Moduldokumentation
6. redaktionelle Ableitungen

Bei Widerspruch gewinnt immer die höherrangige Quelle. Ein Konflikt darf nicht durch Plausibilität oder sprachliche Glättung gelöst werden. Der Status lautet dann `BLOCKED`.

Pflichtquellen je Modul:

```text
__wolfi/compact/{compact_module}/models.json
__wolfi/compact/{compact_module}/{personal_model}/models.json
__wolfi/compact/{compact_module}/{business_model}/models.json
__wolfi/compact/{compact_module}/{personal_model}/freezed_doku.md
__wolfi/compact/{compact_module}/{business_model}/freezed_doku.md
```

Zusätzliche Compact-Dateien dürfen gelesen werden, wenn sie für eine Aussage erforderlich sind. Sie dürfen nicht verwendet werden, um eine Aussage zu erfinden, die in den maßgeblichen Quellen fehlt.

---

# 3. Auftragsparameter

Jeder Auftrag muss diese Werte ausdrücklich enthalten:

```yaml
compact_module: communication
personal_model: communication_pers
business_model: communication_biz
route_dir: comm
language: de
target_html: mod/comm/de.html
```

Bedeutung:

| Parameter | Bedeutung |
|---|---|
| `compact_module` | Verzeichnis unter `__wolfi/compact/` |
| `personal_model` | exaktes Personal-Unterverzeichnis |
| `business_model` | exaktes Business-Unterverzeichnis |
| `route_dir` | Modulverzeichnis unter `mod/` |
| `language` | Sprachcode der Ausgabe |
| `target_html` | bestehende Seite, vor die das Intro gesetzt wird |

Der Provider darf Verzeichnisnamen nicht aus sichtbaren Titeln ableiten. Fehlende Parameter werden aus den vorhandenen Dateien ermittelt oder als Blocker gemeldet.

---

# 4. Nicht veränderbare Strukturquelle

Das einzige zulässige Intro-Template ist:

[`_assets/_templates/_web_module_intro.html`](../../_assets/_templates/_web_module_intro.html)

Der Provider darf:

- Platzhalter durch validierte JSON-Werte ersetzen;
- HTML-Sonderzeichen korrekt maskieren;
- UTF-8 und echte Umlaute erhalten.

Der Provider darf nicht:

- Tags ergänzen, entfernen oder umordnen;
- Klassen, Attribute oder Attributwerte verändern;
- weitere Absätze erfinden;
- das Template lokal im Modul duplizieren;
- Runtime-Attribute wie `data-*-bound` oder `data-*-ready` in die Quelldatei schreiben.

Das aktuelle Template besitzt:

- einen sichtbaren Modultitel;
- einen einleitenden Absatz;
- fünf Zwischenüberschriften;
- fünf zugehörige Absätze.

Deshalb sind in Version 1.0 dieser Spezifikation nur `p1`-Felder erlaubt. `p2`-Felder dürfen erst verwendet werden, wenn das zentrale Template freigegeben erweitert wurde.

---

# 5. Verbindliche Inhaltsleistung

Das Intro beantwortet in dieser Reihenfolge:

1. Welche vertraute Situation holt den Leser beim Thema ab?
2. Welche erste freigegebene Achse oder Grundspannung betrachtet das Modul?
3. Welche zweite freigegebene Achse oder Grundspannung betrachtet das Modul?
4. Was macht die Verbindung dieser Fragen innerhalb des Dynamics Framework unterscheidbar?
5. Welche vier grundlegenden Verbindungen beziehungsweise Wirkungsräume entstehen, ohne die vollständige Ergebnislogik vorwegzunehmen?
6. Wie unterscheiden sich Personal und Business bei gemeinsamer Grundarchitektur?

Das Intro erklärt noch nicht:

- Axisprint, Drivecode, Impactspace, Roleprofile oder Facettenspektrum im Detail;
- einzelne Ergebnis-Keys;
- Core und Dark Facets;
- Berechnung, Mapping oder PNA-Evidenzbildung;
- Eignung, Leistung, Diagnose oder Zukunftsprognosen.

---

# 6. Schreibstil

Verbindliche Grundstimme:

- ruhig;
- direkt;
- menschlich;
- leicht verständlich;
- fachlich verantwortlich;
- Standardanrede `Du`.

## 6.1 Leser vor Modell

Der erste Absatz beginnt mit einer vertrauten Situation, nicht mit einer Definition des Modells.

Geeignet:

> Du möchtest etwas verändern, ohne zu verlieren, was sich bewährt hat.

Nicht geeignet:

> Das Modul beschreibt gleichzeitig wirksame Orientierungsdimensionen in einem konkreten Kontext.

## 6.2 Konkrete Beispiele

Alltagsbeispiele müssen:

- kurz und allgemein sein;
- einen vorhandenen Claim illustrieren;
- ohne erfundene Person oder Fallgeschichte auskommen;
- kein Wirkungsversprechen erzeugen.

## 6.3 Satz- und Absatzgestaltung

- Ein Satz trägt grundsätzlich einen Hauptgedanken.
- Ein Absatz führt höchstens einen neuen Modellbegriff ein.
- Fachbegriffe werden sofort einfach erklärt.
- Absätze beginnen nicht mechanisch mit demselben Subjekt.
- Die fünf Absätze besitzen unterschiedliche Funktionen.
- Personal und Business werden nicht durch bloßen Wortaustausch gespiegelt.

## 6.4 Abstraktionsprüfung

Diese Formulierungen lösen eine Überarbeitung aus, wenn eine einfachere Aussage möglich ist:

- Betrachtungsraum
- gleichzeitig wirksame Bewegungen
- konkreter Kontext
- kontextbezogene Modellperspektive
- prägt Orientierung
- navigiert zwischen
- multidimensional
- ganzheitlich
- tiefgreifend

Prüffrage: Würde ein Mensch diesen Satz in einem verständlichen persönlichen Gespräch so sagen?

---

# 7. Fachliche Grenzen

Verboten sind insbesondere:

- direkte Messbehauptungen über Achsen oder Quadranten;
- Gleichsetzung eines Ergebnisses mit der gesamten Persönlichkeit;
- Kompetenz-, Eignungs- oder Leistungsurteile;
- Diagnose- oder Therapieaussagen;
- Erfolgs- oder Zukunftsprognosen;
- PNA-Evidenz aus einem einzelnen Dynamics-Modul;
- erfundene Unterschiede zwischen Personal und Business;
- erfundene Zahlen, Ebenen, Rollen oder Facets;
- sichtbare interne Begriffe wie `Family` oder `OpenClaw`;
- interne Keys als Kundenbezeichnung;
- unbelegte Alleinstellungs- und Wissenschaftsbehauptungen.

Erlaubte Grenzen werden kurz dort formuliert, wo eine Fehlinterpretation entstehen könnte. Die Einleitung darf nicht wie eine juristische oder akademische Absicherung gelesen werden.

---

# 8. Verbindliche Wortzahl

Jede Sprachfassung besitzt exakt 400 Wörter Fließtext.

Gezählt werden:

```text
model.intro_desc
intro.1_p1
intro.2_p1
intro.3_p1
intro.4_p1
intro.5_p1
```

Nicht gezählt werden:

- Titel;
- Zwischenüberschriften;
- Alternativtext;
- technischer Familienwert;
- HTML-Attribute.

Verbindlicher regulärer Ausdruck:

```regex
[\p{L}\p{N}]+(?:[’'-][\p{L}\p{N}]+)*
```

Zählbeispiel in JavaScript:

```js
const WORD_RE = /[\p{L}\p{N}]+(?:[’'-][\p{L}\p{N}]+)*/gu;

const body = [
  data.model.intro_desc,
  data.intro["1_p1"],
  data.intro["2_p1"],
  data.intro["3_p1"],
  data.intro["4_p1"],
  data.intro["5_p1"]
].join(" ");

const wordCount = (body.match(WORD_RE) || []).length;

if (wordCount !== 400) {
  throw new Error(`Intro has ${wordCount} words; required: 400`);
}
```

Der Provider darf die Wortzahl nicht durch Füllsätze, Wiederholungen, unnötige Adjektive oder neue Claims erreichen.

---

# 9. Verbindliches Sprach-JSON

Schemaquelle:

[`web_module_intro.schema.json`](web_module_intro.schema.json)

Exakte Struktur:

```json
{
  "model": {
    "family": "technical-family-value",
    "title": "Public Module Title",
    "intro_desc": "Einleitender Fließtext",
    "intro_alt": "Übersetzbarer Alternativtext"
  },
  "intro": {
    "1_h": "Überschrift 1",
    "1_p1": "Absatz 1",
    "2_h": "Überschrift 2",
    "2_p1": "Absatz 2",
    "3_h": "Überschrift 3",
    "3_p1": "Absatz 3",
    "4_h": "Überschrift 4",
    "4_p1": "Absatz 4",
    "5_h": "Überschrift 5",
    "5_p1": "Absatz 5"
  }
}
```

Regeln:

- Keine zusätzlichen Schlüssel.
- Keine fehlenden Schlüssel.
- Keine HTML-Tags in Textwerten.
- Keine leeren Werte.
- `model.family` stammt aus `__wolfi/compact/{compact_module}/models.json` und wird kleingeschrieben.
- `model.title` entspricht exakt dem öffentlichen `title`.
- Alle Dateien werden als UTF-8 gespeichert.

---

# 10. Verbindliche Ausgaben

Für einen deutschen Auftrag werden angelegt:

```text
mod/{route_dir}/content/de/01_intro.md
mod/{route_dir}/content/de/01_intro.json
mod/{route_dir}/content/de/01_intro.review.md
mod/{route_dir}/test.html
```

Zusätzlich wird aktualisiert:

```text
mod/{route_dir}/de.html
```

## 10.1 Markdown

Die Markdown-Datei enthält:

1. Status und Kapitel-ID
2. Verweis auf Canon und Governance
3. exakte Quellenliste
4. Aussageziel und zentrale Leserfrage
5. erlaubte und nicht erlaubte Aussagen
6. verbindliche Feldzuordnung
7. redaktionellen Ausgangstext
8. Claim Ledger

## 10.2 JSON

Das JSON enthält ausschließlich die strukturierte Sprachfassung nach Abschnitt 9.

## 10.3 Reviewbericht

Der Reviewbericht dokumentiert:

```text
Dokument:
Kapitel:
Version:
Quellenstand:
Geänderte Claims:
Neue Claims:
Entfernte Claims:
Derived Claims:
Canon Findings:
Governance Findings:
Halluzinationsprüfung:
Architektur- und Messprüfung:
Redundanzprüfung:
Terminologieprüfung:
Sprach- und Natürlichkeitsprüfung:
Editionsprüfung:
Webprüfung:
Längenprüfung:
Offene Entscheidungen:
Finaler Status:
Human Approval:
```

Zulässiger Status vor menschlicher Freigabe:

```text
APPROVED FOR HUMAN REVIEW
```

## 10.4 test.html

`test.html` ist die vollständig aufgelöste Ausgabe des zentralen Templates.

Pflicht:

- kein verbleibender `{placeholder}`;
- exakt eine Intro-Section;
- exakt fünf `h3`;
- exakt sechs Fließtextabsätze;
- keine Runtime-Marker;
- UTF-8;
- HTML-Maskierung für `&`, `<`, `>` und Attribut-Anführungszeichen.

---

# 11. Bildpfad

Das Template erzeugt:

```text
/_assets/webs/{model.family}.jpg
```

Der Provider muss prüfen, ob die Datei existiert.

Wenn sie fehlt:

- keinen anderen Familienwert einsetzen;
- kein Bild kopieren oder erfinden;
- das Template nicht verändern;
- den fehlenden Pfad im Reviewbericht und Abschlussbericht nennen.

Ein fehlendes Bild blockiert nicht die Textprüfung, aber die visuelle Veröffentlichung.

---

# 12. Prepend-Regel

Die erzeugte `test.html` wird als erster Inhaltsblock vor `mod/{route_dir}/de.html` gesetzt.

Regeln:

1. Bestehende Inhalte bleiben vollständig und in ihrer Reihenfolge erhalten.
2. Beginnt die Seite noch mit einem Legacy-Intro, wird die neue Section davor gesetzt.
3. Beginnt die Seite bereits mit einem aus diesem Template erzeugten Block mit `data-src="/_assets/webs/..."`, wird dieser erste Block ersetzt.
4. Ein wiederholter Lauf darf kein weiteres generiertes Intro duplizieren.
5. Der erste Block in `de.html` muss nach dem Vorgang bytegleich beziehungsweise nach Zeilenenden normalisiert identisch mit `test.html` sein.
6. Die aktive Seite wird erst nach erfolgreicher JSON-, Wortzahl-, Template- und Halluzinationsprüfung verändert.

---

# 13. Übersetzungen

Die freigegebene deutsche JSON-Datei ist die inhaltliche Ausgangsbasis.

Ablauf:

```text
freigegebenes deutsches Markdown
→ validiertes de.json
→ fachtreue Übersetzung der JSON-Werte
→ validiertes Sprach-JSON
→ HTML aus demselben zentralen Template
```

Regeln:

- Es werden nur Werte übersetzt.
- Schlüssel und Struktur bleiben unverändert.
- Nicht aus gerendertem HTML übersetzen.
- Claims, Grenzen und Editionsunterschiede bleiben bedeutungsgleich.
- Jede Sprache erfüllt selbstständig die 400-Wörter-Regel.
- Fehlende oder zusätzliche Schlüssel blockieren die Ausgabe.
- Eine Übersetzung darf keinen neuen Claim ergänzen.

---

# 14. Verbindlicher Arbeitsablauf

## Pass 0 – Source Lock

- Modulidentität und Verzeichnisse sichern.
- Canon, Governance und Compact-Quellen auflisten.
- Widersprüche und fehlende Assets festhalten.
- Kapitelvertrag erstellen.

## Pass 1 – Claim Extraction

- Achsen, Pole, Quadranten und Editionskontexte extrahieren.
- Grenzen und verbotene Ableitungen erfassen.
- Claim Ledger erstellen.
- Noch keinen Webtext schreiben.

## Pass 2 – Content Architecture

- Jedem der sechs Fließtextfelder genau eine Aufgabe zuweisen.
- Wiederholungen und vorweggenommene Detaildokumentation verhindern.

## Pass 3 – Narrative Draft

- Leser mit einer konkreten Situation abholen.
- Modellbegriffe schrittweise einführen.
- Personal und Business natürlich unterscheiden.

## Pass 4 – Halluzinationsprüfung

Jede fachliche Aussage wird gegen das Claim Ledger geprüft.

Fail bei:

- fehlender Quelle;
- erfundener Kausalität;
- Messbehauptung;
- Kompetenz- oder Persönlichkeitsschluss;
- erfundenem Editionsunterschied;
- zusätzlicher Ebene, Rolle oder Zahl.

## Pass 5 – Plain-Language Review

- abstrakte Sprache entfernen;
- Beispiele konkretisieren;
- Satzlast reduzieren;
- Fachbegriffe dosieren;
- mechanische Wiederholungen entfernen.

Dieser Pass darf keine neuen Claims erzeugen.

## Pass 6 – Längenprüfung

- Wortzahl nach Abschnitt 8 berechnen.
- Nur redaktionell sinnvoll kürzen oder ergänzen.
- Danach Halluzinations- und Redundanzprüfung wiederholen.

## Pass 7 – JSON und Template

- JSON gegen das Schema prüfen.
- Template-Platzhalter ersetzen.
- HTML korrekt maskieren.
- `test.html` erzeugen.

## Pass 8 – Prepend und technische Prüfung

- vorhandenen generierten Introblock ersetzen oder neuen Block vorschalten;
- Identität von `test.html` und erster Section prüfen;
- Platzhalter, leere Elemente und Runtime-Marker prüfen;
- UTF-8 und Bildpfad prüfen.

## Pass 9 – Review und Handoff

- Reviewbericht abschließen;
- offene Entscheidungen nennen;
- Status `APPROVED FOR HUMAN REVIEW` setzen;
- keine Veröffentlichung ohne menschliche Freigabe.

---

# 15. Definition of Done

Ein Auftrag ist vollständig, wenn:

- alle Pflichtquellen gelesen wurden;
- ein Kapitelvertrag vorliegt;
- jeder Claim Supported oder Paraphrase ist;
- keine Derived-, Unverifiable- oder Conflict-Claims unentschieden bleiben;
- das Intro lesernah und nicht akademisch beginnt;
- alle sechs Fließtextfelder zusammen exakt 400 Wörter besitzen;
- das JSON dem Schema entspricht;
- `test.html` exakt aus dem zentralen Template entstanden ist;
- keine Platzhalter oder Runtime-Marker verbleiben;
- `test.html` identisch als erster Block der Zielseite steht;
- der Alt-Text vorhanden ist;
- der Bildpfad geprüft wurde;
- ein Reviewbericht vorliegt;
- der Status und alle offenen Punkte klar genannt sind.

---

# 16. Abbruchbedingungen

Der Provider stoppt mit `BLOCKED`, wenn:

- die Modulidentität nicht eindeutig ist;
- Personal- und Business-Quellen widersprechen;
- Canon und Compact-Daten kollidieren;
- der öffentliche Titel nicht eindeutig ist;
- eine zentrale Aussage nur erfunden oder als `Derived` ergänzt werden könnte;
- das zentrale Template fehlt oder verändert werden müsste;
- die Zielseite nicht eindeutig bestimmt werden kann.

Ein fehlendes Modulbild wird als offener Asset-Fund dokumentiert, blockiert aber nicht die Erstellung der Text-, JSON- und Testartefakte.

