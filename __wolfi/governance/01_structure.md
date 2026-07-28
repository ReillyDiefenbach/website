# CarlVon Marken- und Produktarchitektur

## Ebene 1 – Dachmarke

CarlVon

Die Dachmarke umfasst sämtliche Produkte, Dienstleistungen, Forschungsaktivitäten und Geschäftsfelder.

Beispiele:

* Interna
* Research
* Coaching Suite
* Enterprise Edition
* Recruiting Suite
* Academy
* Certification
* Analytics
* weitere zukünftige Produktlinien

---

## Ebene 2 – Geschäftsfelder, Suites und Editions

Unterhalb der Dachmarke befinden sich eigenständige Produkt- und Leistungsbereiche.

Beispiele:

```text
CarlVon
├── Interna
├── Research
├── Coaching Suite
├── Enterprise Edition
├── Recruiting Suite
├── Academy
└── Certification
```

Diese Bereiche definieren Zielgruppen, Anwendungsfälle und Leistungsangebote.

---

## Ebene 3 – Dynamics Framework

Das Dynamics Framework bildet die gemeinsame analytische Grundlage aller CarlVon-Produkte, Geschäftsfelder, Editions.

Es definiert:

* Modellarchitektur
* Messlogik
* Achsenlogik
* Orientierungslogik
* Auswertungslogik
* Vergleichslogik

Alle diagnostischen Verfahren innerhalb von CarlVon basieren auf dieser gemeinsamen Architektur.

Das Dynamics Framework ist damit kein Produkt, sondern die methodische Kernplattform des gesamten Systems.

```text
CarlVon
└── Dynamics Framework
```

---

## Ebene 4 – Module

Module beschreiben einzelne Betrachtungsperspektiven innerhalb des Dynamics Framework.

Jedes Modul beantwortet eine spezifische Fragestellung.

Beispiele:

```text
Dynamics Framework
├── Think & Act
├── Bond & Change
├── Connect & Influence
├── Lead & Develop
├── Values & Decisions
├── Learn & Apply
├── Future & Direction
└── ...
```

Die heutigen Family-Modelle entsprechen dieser Ebene.

Der Begriff „Family“ wird primär intern verwendet.

Nach außen wird von Modulen gesprochen.

---

## Ebene 5 – Modelle

Innerhalb eines Moduls können mehrere Modelle existieren.

Die Modelle verwenden dieselbe Grundarchitektur, passen Sprache, Kontext und Interpretation jedoch an unterschiedliche Anwendungsbereiche an.

Beispiel:

```text
Think & Act
├── Business
└── Personal
```

Später möglich:

```text
Think & Act
├── Personal
├── Business
├── Leadership
├── Team
├── Recruiting
└── Relationship
```

Ein Modell stellt die konkrete diagnostische Ausprägung eines Moduls dar.

---

## Zusammenfassung

```text
CarlVon
│
├── Interna
├── Research
├── Coaching Suite
├── Enterprise Edition
├── Recruiting Suite
└── ...

└── Dynamics Framework
    │
    ├── Think & Act
    │   ├── Business
    │   └── Personal
    │
    ├── Bond & Change
    │   ├── Business
    │   └── Personal
    │
    ├── Connect & Influence
    │   ├── Business
    │   └── Personal
    │
    └── ...
```

**Marke → Suite/Edition/Abteilung → Framework → Modul → Modell**
