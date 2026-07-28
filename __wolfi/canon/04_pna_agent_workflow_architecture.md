<a id="pna-agent-workflow-architecture"></a>

# PNA Agent & Workflow Architecture

<a id="X278159aa2a5c7d48a31ab55a51cba156a502b67"></a>

## Agentensystem, Entwicklungsprozess und Governance

<a id="version-1.0-frozen-canon"></a>

### Version 1\.0 – Frozen Canon

<a id="zweck-dieses-dokuments"></a>

# 1\. Zweck dieses Dokuments

Dieses Dokument beschreibt die organisatorische und technische Zusammenarbeit aller Agenten innerhalb des PNA\-Ökosystems\.

Es definiert:

- Zuständigkeiten
- Verantwortlichkeiten
- Freigabeprozesse
- Datenflüsse
- Entwicklungsabläufe

Ziel ist ein reproduzierbarer und skalierbarer Entwicklungsprozess\.

<a id="grundprinzip"></a>

# 2\. Grundprinzip

Das PNA\-System wird nicht von einem einzelnen Agenten gepflegt\.

Stattdessen existiert ein spezialisiertes Multi\-Agent\-System\.

Jeder Agent besitzt:

- klar definierte Aufgaben
- klar definierte Eingaben
- klar definierte Ausgaben
- keinen direkten Einfluss auf den PNA\-Kanon

<a id="governance-ebenen"></a>

# 3\. Governance\-Ebenen

Das System besteht aus vier Ebenen\.

Level 1  
PNA Canon  
  
Level 2  
Model Development  
  
Level 3  
Content Generation  
  
Level 4  
Reporting & Delivery

<a id="level-1-pna-canon"></a>

# 4\. Level 1 – PNA Canon

Dies ist die höchste Ebene\.

Referenzdokumente:

- PNA Manifest
- PNA JSON Specification
- PNA Brand Architecture

Diese Dokumente sind die einzige Wahrheit\.

<a id="veränderungsregel"></a>

## Veränderungsregel

Kein Agent darf diese Dokumente verändern\.

Änderungen erfolgen ausschließlich durch:

Human Approval Required

<a id="level-2-model-development"></a>

# 5\. Level 2 – Model Development

Auf dieser Ebene entstehen die Modelle\.

<a id="model-architect"></a>

## Model Architect

Verantwortung:

- Familien entwickeln
- Achsen definieren
- Polbegriffe definieren
- Quadrantenlogik prüfen

Input:

Manifest  
JSON Specification

Output:

Model JSON

<a id="model-reviewer"></a>

## Model Reviewer

Verantwortung:

Prüfung von:

- Achsenkonsistenz
- Archetypenlogik
- Polbegriffen
- Quadrantenlogik
- Naming Rules

Output:

Approved  
Needs Revision

<a id="scientific-reviewer"></a>

## Scientific Reviewer

Verantwortung:

Prüfung:

- psychologische Plausibilität
- theoretische Anschlussfähigkeit
- wissenschaftliche Belastbarkeit
- Überschneidungen zu bestehenden Modellen

Output:

Scientific Review

<a id="level-3-content-generation"></a>

# 6\. Level 3 – Content Generation

Auf dieser Ebene entstehen Inhalte\.

<a id="faq-agent"></a>

## FAQ Agent

Verantwortung:

Erzeugt standardisierte FAQs\.

Beispiel:

Wozu dient das Modell?  
Welche Achsen werden verwendet?  
Welche Stärken besitzt es?  
Welche Grenzen besitzt es?

Output:

faq\.json

<a id="family-documentation-agent"></a>

## Family Documentation Agent

Verantwortung:

Erzeugt Family\-Dokumentationen\.

Output:

Family Documentation

<a id="question-design-agent"></a>

## Question Design Agent

Verantwortung:

Erzeugt Frageblöcke\.

Input:

Model JSON

Output:

question\_blocks\.json

<a id="regeln-für-frageblöcke"></a>

# 7\. Regeln für Frageblöcke

Jedes Modell erzeugt:

24 Blöcke

Jeder Block enthält:

Titel  
4 Aussagen

<a id="zuordnung"></a>

## Zuordnung

Aussage A

orientation\.W

Aussage B

orientation\.E

Aussage C

orientation\.N

Aussage D

orientation\.S

Diese Regel darf nicht verletzt werden\.

<a id="validation-agent"></a>

## Validation Agent

Prüft:

- W/E/N/S\-Zuordnung
- sprachliche Qualität
- Wiederholungen
- Verständlichkeit

<a id="level-4-trait-mapping"></a>

# 8\. Level 4 – Trait Mapping

Hier entsteht das eigentliche Messmodell\.

<a id="trait-mapping-agent"></a>

## Trait Mapping Agent

Verantwortung:

Zuordnung von Fragen zu:

Micro Traits  
Macro Traits  
Facets

Output:

mapping\.json

<a id="evidence-agent"></a>

## Evidence Agent

Verantwortung:

Gewichtung der Zusammenhänge\.

Output:

evidence\.json

Beispiel:

\{  
  "MT\_AN": \[  
    \["T\_G\_1",0\.96\],  
    \["T\_G\_3",0\.78\]  
  \]  
\}

<a id="calculation-agent"></a>

## Calculation Agent

Verantwortung:

Berechnungslogik\.

Input:

Antworten  
Mappings  
Gewichtungen

Output:

Scores  
Rankings  
Profile

<a id="reporting-layer"></a>

# 9\. Reporting Layer

<a id="report-agent"></a>

## Report Agent

Erzeugt:

- Kurzreports
- Detailreports
- Teamreports
- Entwicklungsberichte

Input:

Scores  
Profile

Output:

pdf  
json

<a id="visualization-agent"></a>

## Visualization Agent

Verantwortung:

- Achsengrafiken
- Radar Charts
- Quadrantenkarten
- Archetypenkarten

<a id="translation-agent"></a>

## Translation Agent

Verantwortung:

Mehrsprachigkeit\.

Input:

de\.json

Output:

en\.json

<a id="marketing-layer"></a>

# 10\. Marketing Layer

<a id="brand-agent"></a>

## Brand Agent

Verantwortung:

- Badge\-System
- Brand\-System
- Namenslogik

Darf NICHT:

- Modelle verändern
- Achsen verändern

<a id="social-media-agent"></a>

## Social Media Agent

Verantwortung:

Erzeugt:

- Reels
- Posts
- Storyboards
- Videoideen

Input:

Archetypen  
Families  
Reports

<a id="openclaw-integration"></a>

# 11\. OpenClaw Integration

OpenClaw fungiert als Orchestrator\.

OpenClaw besitzt keine eigene Wahrheit\.

OpenClaw liest:

Manifest  
JSON Specification  
Brand Architecture  
Agent Architecture

und delegiert Aufgaben\.

<a id="agentenhierarchie"></a>

# 12\. Agentenhierarchie

OpenClaw  
│  
├── Model Architect  
├── Model Reviewer  
├── Scientific Reviewer  
│  
├── FAQ Agent  
├── Documentation Agent  
├── Question Agent  
├── Validation Agent  
│  
├── Trait Mapping Agent  
├── Evidence Agent  
├── Calculation Agent  
│  
├── Report Agent  
├── Visualization Agent  
├── Translation Agent  
│  
├── Brand Agent  
└── Social Media Agent

<a id="freigabeprozess"></a>

# 13\. Freigabeprozess

Neues Modell:

Architect  
↓  
Reviewer  
↓  
Scientific Review  
↓  
Human Approval  
↓  
Freeze

Neue Fragen:

Question Agent  
↓  
Validation Agent  
↓  
Human Approval  
↓  
Release

Neues Mapping:

Mapping Agent  
↓  
Evidence Agent  
↓  
Human Approval  
↓  
Release

<a id="human-in-the-loop"></a>

# 14\. Human\-in\-the\-Loop

Bestimmte Entscheidungen dürfen niemals automatisiert werden\.

Dazu gehören:

- Änderungen am PNA\-Kanon
- Änderungen an Archetypen
- Änderungen an Achsen
- Änderungen an Familien
- Änderungen an Brands
- Änderungen an JSON\-Strukturen

Diese Entscheidungen erfordern immer menschliche Freigabe\.

<a id="zielarchitektur"></a>

# 15\. Zielarchitektur

Langfristig soll das PNA\-System ermöglichen:

- neue Modelle entwickeln
- Fragen generieren
- Mappings erzeugen
- Reports erstellen
- Inhalte publizieren

ohne die Integrität des Kanons zu gefährden\.

Der Kanon bleibt stabil\.

Die Inhalte bleiben flexibel\.

Damit entsteht ein skalierbares Persönlichkeits\- und Entwicklungsökosystem auf Basis eines zentralen, unveränderbaren PNA\-Kerns\.
