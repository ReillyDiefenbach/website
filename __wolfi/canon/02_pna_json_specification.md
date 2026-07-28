# PNA JSON Specification

## Technische Spezifikation für OpenClaw / Agenten / Datenmodell {#technische-spezifikation-für-openclaw-agenten-datenmodell}

### Version 1.0 -- Frozen Canon {#version-1.0-frozen-canon}

------------------------------------------------------------------------

# 1. Zweck dieses Dokuments {#zweck-dieses-dokuments}

Dieses Dokument beschreibt die verbindliche JSON-Struktur des PNA Frameworks.

Es dient als technische Referenz für:

- OpenClaw
- Subagents
- Modellgeneratoren
- FAQ-Generatoren
- Fragegeneratoren
- Mapping-Agenten
- Übersetzungsprozesse
- Report-Generatoren
- Frontend- und Backend-Implementierung

Ziel ist, dass alle Agenten dieselbe Struktur verstehen und keine zentralen Modellbestandteile unbeabsichtigt verändern.

------------------------------------------------------------------------

# 2. Grundprinzip {#grundprinzip}

Jedes PNA-Modell ist ein eigenständiger JSON-Eintrag mit numerischem Key.

Beispiel:

    {
      "1": {
        "name": "Think & Act Business",
        "family": "Thinking",
        "title": "Think & Act",
        "short": "thact_biz",
        "typ": "biz"
      }
    }

Der numerische Key ist die stabile Modell-ID.

------------------------------------------------------------------------

# 3. Pflichtfelder je Modell {#pflichtfelder-je-modell}

Jeder Modelleintrag MUSS folgende Felder enthalten:

    {
      "name": "",
      "family": "",
      "title": "",
      "short": "",
      "typ": "",
      "key": "",
      "desc": "",
      "answer": "",
      "axis": {},
      "orientation": {},
      "quadrants": {},
      "badge": "",
      "brand": ""
    }

------------------------------------------------------------------------

# 4. Top-Level-Felder {#top-level-felder}

## 4.1 name

Der konkrete formale Modellname.

name ist die lesbare Modellbezeichnung für Administration, Reports und interne Navigation.

## Schema
<family> Business
<family> Personal


## 4.2 family {#family}

Die wissenschaftliche Modellfamilie.

Beispiele:

    Thinking
    Nexus
    Communication
    Roles
    Motivation
    Identity
    Strategy

Die `family` ist keine Marketingbezeichnung.

------------------------------------------------------------------------

## 4.3 title {#title}

Der lesbare Marketingtitel des Modells.

Beispiele:

    Think & Act
    Bond & Change
    Connect & Influence
    Lead & Develop
    Plan & Reflect
    Identity & Purpose

Der `title` liefert zugleich die Grundlage für:

    axis.X.aka
    axis.Y.aka

Beispiel:

    Think & Act

wird zu:

    "axis": {
      "X": {
        "aka": "Think"
      },
      "Y": {
        "aka": "Act"
      }
    }

------------------------------------------------------------------------

## 4.4 short {#short}

Technischer, eindeutiger Modell-Key.

Regeln:

- lowercase
- eindeutig
- kurz
- keine Leerzeichen
- endet mit `_biz` oder `_pers`

Beispiele:

    thact_biz
    thact_pers
    comm_biz
    iden_pers
    stra_biz

------------------------------------------------------------------------

## 4.5 typ {#typ}

Kontext des Modells.

Erlaubte Werte:

    biz
    pers

Bedeutung:

    biz  = Business-Kontext
    pers = Personal-Kontext

------------------------------------------------------------------------

## 4.6 key {#key}

Kurzbeschreibung der Modellfunktion.

Das Feld `key` beschreibt in einem Satz, worauf das Modell abzielt.

Beispiel:

    Beschreibt die Balance zwischen konzeptionellem und linearem Denken sowie zwischen spontanem und analytischem Handeln als Arbeits- und Problemlösungsstil.

------------------------------------------------------------------------

## 4.7 desc {#desc}

Ausführliche Beschreibung des Modells.

`desc` erklärt:

- was das Modell beschreibt
- welche Achsen verwendet werden
- welche psychologische oder organisationale Logik dahinterliegt
- wofür das Modell eingesetzt werden kann

------------------------------------------------------------------------

## 4.8 answer {#answer}

Antwort auf die Kernfrage:

    Was beantwortet dieses Modell?

Das Feld soll erklären, welche diagnostische oder reflexive Information das Modell liefert.

------------------------------------------------------------------------

## 4.9 badge {#badge}

Kurzer visueller Code.

Beispiele:

    THACT
    NEXUS
    COMM
    LDR
    IDNTY
    STRTG

Der Badge dient für:

- UI
- Cards
- Reports
- Social Media
- Kurzvisualisierung
- interne Modellnavigation

------------------------------------------------------------------------

## 4.10 brand {#brand}

Marken- oder Produktname der Modellfamilie.

Beispiele:

    ThinkLab
    BondShift
    ConnectFlow
    LeadDrive
    IdentityCore
    StrategyLab

Die Brand-Namen folgen einer kontrollierten Markenlogik.

------------------------------------------------------------------------

# 5. Axis-Struktur {#axis-struktur}

Jedes Modell besitzt exakt zwei Achsen:

    "axis": {
      "X": {},
      "Y": {}
    }

------------------------------------------------------------------------

## 5.1 axis.X {#axis.x}

Die horizontale Achse.

Schema:

    "X": {
      "label": "",
      "name": "",
      "aka": "",
      "range": "",
      "span": ""
    }

------------------------------------------------------------------------

## 5.2 axis.Y {#axis.y}

Die vertikale Achse.

Schema:

    "Y": {
      "label": "",
      "name": "",
      "aka": "",
      "range": "",
      "span": ""
    }

------------------------------------------------------------------------

# 6. Axis-Felder {#axis-felder}

## 6.1 label {#label}

Deutsche Achsenbezeichnung.

Beispiele:

    Denkachse
    Handlungsachse
    Motivationsachse
    Wirkungsachse
    Beziehungsachse

------------------------------------------------------------------------

## 6.2 name {#name-1}

Kurzname der Achsendomäne.

Beispiele:

    Denken
    Handeln
    Motivation
    Wirkung
    Identität
    Strategie

------------------------------------------------------------------------

## 6.3 aka {#aka}

Englischer Marketing-Alias der Achse.

Regel:

    axis.X.aka = erster Teil des title
    axis.Y.aka = zweiter Teil des title

Beispiele:

| title          | axis.X.aka | axis.Y.aka |
|----------------|------------|------------|
| Think & Act    | Think      | Act        |
| Bond & Change  | Bond       | Change     |
| Lead & Develop | Lead       | Develop    |
| Plan & Reflect | Plan       | Reflect    |

`aka` wird nicht übersetzt.

------------------------------------------------------------------------

## 6.4 range {#range}

Substantivische Benennung des Spannungsfeldes.

Beispiel:

    Konzeption vs. Linearität
    Sinn vs. Leistung
    Risiko vs. Sicherheit
    Authentizität vs. Status

------------------------------------------------------------------------

## 6.5 span {#span}

Adjektivische Benennung des Spannungsfeldes.

Beispiel:

    konzeptionell vs. linear
    sinnstiftend vs. leistungsstark
    risikofreudig vs. vorsorgend
    authentisch vs. statusbewusst

------------------------------------------------------------------------

# 7. Orientation-Struktur {#orientation-struktur}

Jedes Modell besitzt exakt vier Orientierungen:

    "orientation": {
      "W": {},
      "E": {},
      "N": {},
      "S": {}
    }

Bedeutung:

    W = West / linker Pol der X-Achse
    E = East / rechter Pol der X-Achse
    N = North / oberer Pol der Y-Achse
    S = South / unterer Pol der Y-Achse

------------------------------------------------------------------------

# 8. Orientation-Felder {#orientation-felder}

Jede Orientierung besitzt:

    {
      "label": "",
      "style": ""
    }

------------------------------------------------------------------------

## 8.1 label {#label-1}

Substantivischer Polbegriff.

Beispiele:

    Konzeption
    Linearität
    Spontanität
    Analyse
    Sinn
    Leistung
    Risiko
    Sicherheit

------------------------------------------------------------------------

## 8.2 style {#style}

Adjektivischer oder verhaltensnaher Stilbegriff.

Beispiele:

    konzeptionell
    linear
    spontan
    analytisch
    sinnstiftend
    leistungsstark
    risikofreudig
    vorsorgend

------------------------------------------------------------------------

# 9. Konsistenzregel für Achsen und Orientierungen {#konsistenzregel-für-achsen-und-orientierungen}

`axis.``X.range` muss aus den Labels von `orientation.W` und `orientation.E` gebildet werden.

Schema:

    axis.X.range = orientation.W.label + " vs. " + orientation.E.label

`axis.``X.span` muss aus den Styles von `orientation.W` und `orientation.E` gebildet werden.

Schema:

    axis.X.span = orientation.W.style + " vs. " + orientation.E.style

`axis.``Y.range` muss aus den Labels von `orientation.N` und `orientation.S` gebildet werden.

Schema:

    axis.Y.range = orientation.N.label + " vs. " + orientation.S.label

`axis.``Y.span` muss aus den Styles von `orientation.N` und `orientation.S` gebildet werden.

Schema:

    axis.Y.span = orientation.N.style + " vs. " + orientation.S.style

------------------------------------------------------------------------

# 10. Quadranten-Struktur {#quadranten-struktur}

Jedes Modell besitzt exakt vier Quadranten:

    "quadrants": {
      "Q1": {},
      "Q2": {},
      "Q3": {},
      "Q4": {}
    }

------------------------------------------------------------------------

# 11. Quadrantenlogik {#quadrantenlogik}

Die Quadranten sind räumlich fixiert.

    Q1 = WN = links oben
    Q2 = EN = rechts oben
    Q3 = ES = rechts unten
    Q4 = WS = links unten

Diese Logik darf nicht verändert werden.

------------------------------------------------------------------------

# 12. Universeller PNA-Kanon {#universeller-pna-kanon}

Die Quadranten repräsentieren universelle Meta-Archetypen:

| Quadrant | Position | Meta-Archetyp | Schachfigur |
|----------|----------|---------------|-------------|
| Q1       | WN       | Entdecker     | Springer    |
| Q2       | EN       | Gestalter     | Königin     |
| Q3       | ES       | Bewahrer      | Turm        |
| Q4       | WS       | Verstehender  | Läufer      |

Diese Meta-Archetypen bilden die Grundlage der Interpretation.

------------------------------------------------------------------------

# 13. Quadrantenfelder {#quadrantenfelder}

Jeder Quadrant besitzt folgende Felder:

    {
      "xy": "",
      "style": "",
      "desc": "",
      "strength": "",
      "deficit": "",
      "aka": "",
      "short": "",
      "role": ""
    }

------------------------------------------------------------------------

## 13.1 xy {#xy}

Technische Positionsangabe.

Erlaubte Werte:

    WN
    EN
    ES
    WS

Zuordnung:

    Q1 = WN
    Q2 = EN
    Q3 = ES
    Q4 = WS

------------------------------------------------------------------------

## 13.2 style {#style-1}

Adjektivischer Quadrantenstil.

Beispiele:

    visionär
    gestaltend
    bewahrend
    reflektierend
    entwickelnd
    stabilisierend

------------------------------------------------------------------------

## 13.3 desc {#desc-1}

Kurzbeschreibung des Quadranten.

Beschreibt:

- dominante Orientierung
- typisches Verhalten
- psychologische oder organisationale Funktion
- Beitrag des Quadranten

------------------------------------------------------------------------

## 13.4 strength {#strength}

Typische Stärken des Quadranten.

Beispiel:

    Innovativ, ideenreich, risikobereit

------------------------------------------------------------------------

## 13.5 deficit {#deficit}

Typische Übertreibungen, Schattenseiten oder Entwicklungsfelder.

Beispiel:

    Sprunghaft, chaotisch, realitätsfern

------------------------------------------------------------------------

## 13.6 aka {#aka-1}

Englischer Archetypenname.

Regeln:

- Englisch
- nicht übersetzen
- keine Artikel wie `The`
- archetypisch, nicht rein funktional

Beispiele:

    Visionary
    Explorer
    Builder
    Guardian
    Thinker
    Mentor
    Strategist
    Connector

## 13.7 short {#short-1}

Kurze deutsche Anzeigeform.

Beispiel:

    Der Visionär
    Der Entdecker
    Der Bewahrer
    Der Mentor

`short` darf Artikel enthalten, weil es für UI und Reporttext gedacht ist.

------------------------------------------------------------------------

## 13.8 role {#role}

Deutscher Rollenname ohne Artikel.

Beispiel:

    Visionär
    Entdecker
    Bewahrer
    Mentor

------------------------------------------------------------------------

# 14. Naming Rules {#naming-rules}

## 14.1 aka {#aka-2}

`aka` ist immer Englisch.

Nicht übersetzen.

Nicht mit Artikel.

Richtig:

    Visionary
    Guardian
    Connector
    Strategist

Falsch:

    The Visionary
    Der Guardian
    Visionärer Stil

------------------------------------------------------------------------

## 14.2 role {#role-1}

`role` ist immer Deutsch.

Ohne Artikel.

Richtig:

    Visionär
    Bewahrer
    Gestalter
    Mentor

Falsch:

    Der Visionär
    The Guardian
    visionär

------------------------------------------------------------------------

## 14.3 short {#short-2}

`short` ist die deutsche UI-Bezeichnung mit Artikel.

Richtig:

    Der Visionär
    Der Bewahrer
    Der Gestalter

------------------------------------------------------------------------

## 14.4 style {#style-2}

`style` ist immer adjektivisch oder verhaltensnah.

Richtig:

    visionär
    entwickelnd
    bewahrend
    reflektierend
    steuernd
    verbindend

Falsch:

    Visionär
    Der Entwickler
    Entwicklungsorientierung

------------------------------------------------------------------------

# 15. Brand-System {#brand-system}

Jede Modellfamilie besitzt einen Brand-Namen.

Erlaubte Brand-Endungen:

    Lab
    Core
    Flow
    Drive
    Shift

------------------------------------------------------------------------

## 15.1 Lab {#lab}

Bedeutung:

    Erkenntnis, Analyse, Forschung, Denken, Entwicklung von Modellen

Beispiele:

    ThinkLab
    CreateLab
    CogniLab
    StrategyLab

------------------------------------------------------------------------

## 15.2 Core {#core}

Bedeutung:

    Identität, Stabilität, Kern, Selbststeuerung, Werte

Beispiele:

    IdentityCore
    ValueCore
    BalanceCore
    OrgCore

------------------------------------------------------------------------

## 15.3 Flow {#flow}

Bedeutung:

    Beziehung, Kommunikation, Interaktion, Kultur, soziale Bewegung

Beispiele:

    ConnectFlow
    RelateFlow
    CultureFlow
    SpeakFlow

------------------------------------------------------------------------

## 15.4 Drive {#drive}

Bedeutung:

    Antrieb, Leistung, Führung, Aktivierung, Wirksamkeit

Beispiele:

    LeadDrive
    PerformDrive
    ActivateDrive
    EmpowerDrive

------------------------------------------------------------------------

## 15.5 Shift {#shift}

Bedeutung:

    Veränderung, Wandel, Anpassung, Perspektivwechsel, Konfliktlösung

Beispiele:

    BondShift
    ChangeShift
    ResolveShift
    FutureShift
    AdaptShift

------------------------------------------------------------------------

# 16. Badge-System {#badge-system}

Badges sind kurze visuelle Codes.

Regeln:

- uppercase
- eindeutig
- kurz
- visuell markant
- nicht zwingend vollständige Abkürzung

Beispiele:

    THACT
    NEXUS
    COMM
    ROLE
    MOTV
    LDR
    IDNTY
    STRTG

------------------------------------------------------------------------

# 17. Was Agenten verändern dürfen {#was-agenten-verändern-dürfen}

Agenten dürfen verändern oder ergänzen:

    desc
    answer
    quadrants.Q*.desc
    quadrants.Q*.strength
    quadrants.Q*.deficit
    FAQ-Texte
    Frageformulierungen
    Reporttexte
    Übersetzungen

------------------------------------------------------------------------

# 18. Was Agenten NICHT verändern dürfen {#was-agenten-nicht-verändern-dürfen}

Agenten dürfen NICHT verändern:

    numerische Modell-IDs
    short
    typ
    family
    title
    badge
    brand
    axis.X.aka
    axis.Y.aka
    axis.X.range
    axis.Y.range
    orientation.W/E/N/S
    quadrants.Q*.xy
    Quadrantenreihenfolge
    Meta-Archetypenlogik

Änderungen an diesen Feldern gelten als Strukturänderungen und benötigen explizite Freigabe.

------------------------------------------------------------------------

# 19. Validierungsregeln für Agenten {#validierungsregeln-für-agenten}

Vor jeder Ausgabe muss ein Agent prüfen:

1.  Sind alle Pflichtfelder vorhanden?
2.  Sind alle vier Orientierungen vorhanden?
3.  Sind alle vier Quadranten vorhanden?
4.  Stimmen Q1/Q2/Q3/Q4 mit WN/EN/ES/WS überein?
5.  Stimmen `axis.range` und `axis.span` mit `orientation` überein?
6.  Ist `aka` Englisch?
7.  Ist `role` Deutsch?
8.  Ist `style` adjektivisch?
9.  Bleibt der PNA-Kanon erhalten?
10. Wurden keine Freeze-Felder verändert?
11. Namensvalidierung

- `name` vorhanden
- `name` eindeutig
- bei `typ = biz`: `name = family + " Business"`
- bei `typ = pers`: `name = family + " Personal"`

Nicht prüfen:

- `name = title + " Business"`
- `name = title + " Personal"`

Stattdessen wird geprüft:

name vorhanden
name eindeutig
name endet auf Business oder Personal
typ passt zur Endung
family vorhanden
title vorhanden
short vorhanden

------------------------------------------------------------------------

# 20. Beispiel eines vollständigen Modells {#beispiel-eines-vollständigen-modells}

    {
      "1": {
        "name": "Think & Act Business",
        "family": "Thinking",
        "title": "Think & Act",
        "short": "thact_biz",
        "typ": "biz",
        "key": "Beschreibt die Balance zwischen konzeptionellem und linearem Denken sowie zwischen spontanem und analytischem Handeln als Arbeits- und Problemlösungsstil.",
        "desc": "...",
        "answer": "...",
        "axis": {
          "X": {
            "label": "Denkachse",
            "name": "Denken",
            "aka": "Think",
            "range": "Konzeption vs. Linearität",
            "span": "konzeptionell vs. linear"
          },
          "Y": {
            "label": "Handlungsachse",
            "name": "Handeln",
            "aka": "Act",
            "range": "Spontanität vs. Analyse",
            "span": "spontan vs. analytisch"
          }
        },
        "orientation": {
          "W": {
            "label": "Konzeption",
            "style": "konzeptionell"
          },
          "E": {
            "label": "Linearität",
            "style": "linear"
          },
          "N": {
            "label": "Spontanität",
            "style": "spontan"
          },
          "S": {
            "label": "Analyse",
            "style": "analytisch"
          }
        },
        "quadrants": {
          "Q1": {
            "xy": "WN",
            "style": "visionär",
            "desc": "...",
            "strength": "Innovativ, ideenreich, risikobereit",
            "deficit": "Sprunghaft, chaotisch, realitätsfern",
            "aka": "Visionary",
            "short": "Der Ideengeber",
            "role": "Visionär"
          },
          "Q2": {
            "xy": "EN",
            "style": "vorantreibend",
            "desc": "...",
            "strength": "Motivierend, pragmatisch, entscheidungsfreudig",
            "deficit": "Ungeduldig, detailarm, zu schnell im Abschluss",
            "aka": "Driver",
            "short": "Der Umsetzer",
            "role": "Antreiber"
          },
          "Q3": {
            "xy": "ES",
            "style": "umsetzend",
            "desc": "...",
            "strength": "Zuverlässig, exakt, verantwortungsvoll",
            "deficit": "Perfektionistisch, kontrollierend, unflexibel",
            "aka": "Implementer",
            "short": "Der Ausführer",
            "role": "Umsetzer"
          },
          "Q4": {
            "xy": "WS",
            "style": "verfeinernd",
            "desc": "...",
            "strength": "Qualitätsbewusst, reflektiert, vorsichtig",
            "deficit": "Zögerlich, überkritisch, bremsend",
            "aka": "Critic",
            "short": "Der Optimierer",
            "role": "Optimierer"
          }
        },
        "badge": "THACT",
        "brand": "ThinkLab"
      }
    }

------------------------------------------------------------------------

# 21. Zielzustand {#zielzustand}

Ein valides PNA-Modell ist dann erreicht, wenn:

- die technische JSON-Struktur vollständig ist
- die Achsenlogik konsistent ist
- die Polbegriffe korrekt verbunden sind
- die Quadrantenpositionen unverändert bleiben
- `aka`, `role`, `short` und `style` sauber getrennt sind
- Brand und Badge zur Family passen
- alle Texte die PNA-Grundlogik unterstützen

Diese Spezifikation ist verbindlich für alle weiteren Arbeitsschritte.
