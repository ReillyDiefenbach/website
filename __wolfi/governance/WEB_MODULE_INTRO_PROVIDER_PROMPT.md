# Kopierbarer Provider-Auftrag: Web Module Intro

Den folgenden Block vollständig an den ausführenden KI-Provider übergeben und nur die Werte unter `AUFTRAGSPARAMETER` ersetzen.

---

## AUFTRAGSPARAMETER

```yaml
compact_module: <EXAKTES COMPACT-VERZEICHNIS>
personal_model: <EXAKTES PERSONAL-UNTERVERZEICHNIS>
business_model: <EXAKTES BUSINESS-UNTERVERZEICHNIS>
route_dir: <EXAKTES MODULVERZEICHNIS UNTER mod/>
language: de
target_html: mod/<ROUTE_DIR>/de.html
```

## VERBINDLICHER ARBEITSAUFTRAG

Du arbeitest in einem bestehenden CarlVon-Repository.

Erstelle für das angegebene Modul ein öffentliches Web-Intro. Arbeite vollständig nach:

```text
__wolfi/governance/WEB_MODULE_INTRO_PROVIDER_SPEC.md
```

Lies vor jeder Änderung vollständig:

```text
__wolfi/canon/00_READ_FIRST.txt
__wolfi/canon/01_pna_manifest.md
__wolfi/canon/02_pna_json_specification.md
__wolfi/canon/03_pna_brand_architecture.md
__wolfi/canon/04_pna_agent_workflow_architecture.md
__wolfi/canon/05_pna_measurement_architecture.md
__wolfi/governance/01_structure.md
__wolfi/governance/02_views.md
__wolfi/governance/03_nomenclature.md
__wolfi/governance/04_style_bible.md
__wolfi/governance/05_compliance.md
__wolfi/governance/06_output_rules.md
__wolfi/governance/TEXT_GOVERNANCE.md
__wolfi/governance/WEB_MODULE_INTRO_PROVIDER_SPEC.md
__wolfi/governance/web_module_intro.schema.json
_assets/_templates/_web_module_intro.html
```

Lies anschließend vollständig die fünf modulspezifischen Pflichtquellen, die sich aus den Auftragsparametern ergeben.

Erzeuge:

```text
mod/{route_dir}/content/{language}/01_intro.md
mod/{route_dir}/content/{language}/01_intro.json
mod/{route_dir}/content/{language}/01_intro.review.md
mod/{route_dir}/test.html
```

Setze danach `test.html` regelkonform als ersten Block vor:

```text
{target_html}
```

Verbindliche Anforderungen:

1. Keine Halluzinationen und keine neuen Modellclaims.
2. Leser zuerst mit konkreten Situationen abholen.
3. Nicht akademisch, mechanisch oder werblich schreiben.
4. Standardanrede `Du`.
5. Zwei freigegebene Achsen beziehungsweise Grundspannungen verständlich erklären.
6. Den besonderen Betrachtungswinkel im Dynamics Framework erklären.
7. Vier grundlegende Verbindungen zeigen, ohne Ergebnisdetails vorwegzunehmen.
8. Personal und Business fachlich sauber trennen.
9. Keine Diagnose-, Eignungs-, Kompetenz-, Mess- oder PNA-Evidenzbehauptung.
10. Exakt 400 Wörter Fließtext nach `WCG-LENGTH-001`.
11. JSON exakt nach `web_module_intro.schema.json`.
12. HTML exakt aus `_web_module_intro.html`.
13. Keine zusätzlichen oder fehlenden JSON-Schlüssel.
14. Keine verbleibenden Platzhalter.
15. Keine Runtime-Attribute in den Quelldateien.
16. Bestehende Seiteninhalte nicht löschen oder umordnen.
17. Einen bereits generierten Introblock ersetzen, nicht duplizieren.
18. Fehlende Bilddateien nur melden; nicht ersetzen oder erfinden.
19. Alle Dateien als UTF-8 mit echten Umlauten speichern.
20. Status vor menschlicher Abnahme: `APPROVED FOR HUMAN REVIEW`.

Führe zum Abschluss mindestens diese Prüfungen aus:

```text
JSON_PARSE
JSON_SCHEMA
WORD_COUNT=400
MARKDOWN_JSON_MATCH
TEMPLATE_TEST_MATCH
TEST_PREPEND_MATCH
PLACEHOLDERS=0
H3_COUNT=5
P_COUNT=6
RUNTIME_MARKERS=0
UTF8_REPLACEMENT_CHARS=0
ASSET_EXISTS=true|false
```

Gib im Abschlussbericht nur an:

- welche Dateien erstellt oder verändert wurden;
- ob alle Prüfungen bestanden sind;
- die ermittelte Wortzahl;
- den Human-Review-Status;
- fehlende Assets oder andere offene Entscheidungen.

Wenn eine fachliche Quelle fehlt oder widersprüchlich ist, stoppe mit `BLOCKED`. Ergänze keine wahrscheinliche Lösung.

