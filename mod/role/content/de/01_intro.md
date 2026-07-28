# Role & Impact – Intro

Status: Entwurf für Human Review  
Kapitel-ID: `roles.intro`  
Ausgabesprache: Deutsch

## 1. Verbindliche Governance

Dieses Kapitel wird nach [`TEXT_GOVERNANCE.md`](../../../../__wolfi/governance/TEXT_GOVERNANCE.md) erstellt und geprüft. Für die Einleitung gelten insbesondere die Regeln `WCG-PLAIN-001` bis `WCG-PLAIN-006`.

Maßgebliche Quellen:

- `__wolfi/canon/`
- `__wolfi/governance/`
- `__wolfi/compact/roles/models.json`
- `__wolfi/compact/roles/roles_pers/models.json`
- `__wolfi/compact/roles/roles_biz/models.json`
- `__wolfi/compact/roles/roles_pers/freezed_doku.md`
- `__wolfi/compact/roles/roles_biz/freezed_doku.md`
- `_assets/_templates/_web_module_intro.html`

Das Kapitel darf die freigegebenen Achsen, Orientierungen, Quadrantenpositionen und Editionskontexte verständlich erzählen. Es darf daraus keine neue Mess-, Evidenz-, Diagnose-, Eignungs- oder Persönlichkeitsbehauptung ableiten.

## 2. Was der Artikel aussagen soll

### Zweck

Das Intro holt Leser bei einer vertrauten Erfahrung ab: In unterschiedlichen Situationen bringen Menschen sich unterschiedlich ein. Es erklärt anschließend, dass Role & Impact diesen Beitrag entlang zweier einfacher Fragen betrachtet.

### Zentrale Leserfrage

Wie bringe ich mich in einer Situation bevorzugt ein – eher über Menschen oder über die Sache beziehungsweise Aufgabe, eher flexibel oder eher strukturiert?

### Inhaltliche Leistung

Der Artikel soll:

1. mit konkreten Beispielen für unterschiedliche Rollenbeiträge beginnen;
2. die erste Achse in Personal und Business verständlich unterscheiden;
3. Flexibilität und Struktur als zweite Achse einführen;
4. den besonderen Betrachtungswinkel des Moduls erklären;
5. die vier Verbindungen beider Achsen ohne feste Typisierung zeigen;
6. Personal und Business als getrennte Kontexte derselben Grundarchitektur einordnen.

### Nicht Gegenstand dieses Kapitels

Das Intro erklärt noch nicht:

- die vollständige Ergebnislogik;
- einzelne Ergebnis-Keys;
- Rollenprofile im Detail;
- Core und Dark Facets;
- Messung, Berechnung oder PNA-Evidenzbildung;
- Eignung, Leistung oder Kompetenz.

## 3. Verbindliche Felder

Die Struktur folgt exakt `_assets/_templates/_web_module_intro.html`. Die Schlüssel werden nicht übersetzt oder umbenannt. Übersetzt werden ausschließlich ihre Werte.

| Feld | Funktion | Pflicht |
|---|---|---|
| `model.family` | technischer Familienwert und Bildpfad | ja |
| `model.title` | öffentlicher Modultitel | ja |
| `model.intro_desc` | einfacher Einstieg aus Lesersicht | ja |
| `model.intro_alt` | übersetzbarer Alternativtext | ja |
| `intro.1_h` | Überschrift zur ersten Achse | ja |
| `intro.1_p1` | Mensch und Sache beziehungsweise Aufgabe | ja |
| `intro.2_h` | Überschrift zur zweiten Achse | ja |
| `intro.2_p1` | Flexibilität und Struktur | ja |
| `intro.3_h` | Überschrift zum Modulblick | ja |
| `intro.3_p1` | unterscheidbarer Betrachtungswinkel | ja |
| `intro.4_h` | Überschrift zu den vier Verbindungen | ja |
| `intro.4_p1` | Wirkungsräume ohne Typisierung | ja |
| `intro.5_h` | Überschrift zu Personal und Business | ja |
| `intro.5_p1` | gemeinsame Architektur und getrennte Kontexte | ja |

## 4. Redaktioneller Ausgangstext

### Role & Impact

In einer Gruppe übernimmst du oft eine Rolle, ohne lange darüber nachzudenken. Vielleicht bringst du Menschen zusammen, kümmerst dich um eine Aufgabe, reagierst spontan oder sorgst für einen klaren Ablauf. Role & Impact hilft dir zu verstehen, wie du dich in unterschiedlichen Situationen bevorzugt einbringst.

#### Menschen im Blick oder die Sache?

Manchmal achtest du zuerst auf die Menschen: Wer braucht Unterstützung, wie gelingt Zusammenarbeit, was stärkt den Zusammenhalt? In anderen Momenten steht im Vordergrund, was getan oder gelöst werden muss. Personal nennt diese zweite Richtung Sache, Business spricht von Aufgabe. Gemeint ist jeweils die Frage, worauf du deine Aufmerksamkeit zuerst richtest.

#### Flexibel reagieren oder Struktur schaffen?

Ein Plan ändert sich, und du findest schnell einen neuen Weg. Oder du sorgst dafür, dass Absprachen halten und nichts Wichtiges verloren geht. Role & Impact beschreibt diese beiden Richtungen als Flexibilität und Struktur. Beide können hilfreich sein – je nachdem, was gerade gebraucht wird.

#### Wie du deinen Beitrag gestaltest

Role & Impact fragt nicht nur, worauf du achtest. Das Modul schaut auch darauf, wie du deinen Beitrag gestaltest: offen und anpassungsfähig oder geordnet und verlässlich. Genau diese Verbindung macht seinen Blick innerhalb des Dynamics Framework aus.

#### Vier mögliche Rollenbeiträge

Aus beiden Fragen entstehen vier Wirkungsräume. Du kannst Menschen flexibel zusammenbringen, eine Sache oder Aufgabe beweglich voranbringen, verlässlich für Umsetzung sorgen oder mit Struktur den Zusammenhalt stärken. Diese Rollenbeiträge beschreiben eine mögliche Orientierung. Sie legen weder deine Persönlichkeit noch deine Fähigkeiten fest.

#### Privat und beruflich kann es anders sein

Die Personal Edition betrachtet deinen Alltag, deine Beziehungen und persönliche Verpflichtungen. In der Business Edition geht es um Teams, Aufgaben und Zusammenarbeit in Organisationen. Die Grundstruktur bleibt gleich, doch die Situation verändert den Blick. Deshalb kannst du dich im privaten Leben anders einbringen als im Beruf.

## 5. Feldzuordnung

| Textteil | JSON-Feld |
|---|---|
| Titel | `model.title` |
| Einstiegsabsatz | `model.intro_desc` |
| Menschen im Blick oder die Sache? | `intro.1_h`, `intro.1_p1` |
| Flexibel reagieren oder Struktur schaffen? | `intro.2_h`, `intro.2_p1` |
| Wie du deinen Beitrag gestaltest | `intro.3_h`, `intro.3_p1` |
| Vier mögliche Rollenbeiträge | `intro.4_h`, `intro.4_p1` |
| Privat und beruflich kann es anders sein | `intro.5_h`, `intro.5_p1` |

## 6. Claim Ledger

| Claim-ID | Aussage | Typ | Quelle | Status |
|---|---|---|---|---|
| `ROL-I-001` | Personal betrachtet Mensch und Sache. | Struktur | `roles_pers/models.json`, Feld `axis.X` | Supported |
| `ROL-I-002` | Business betrachtet Mensch und Aufgabe. | Struktur | `roles_biz/models.json`, Feld `axis.X` | Supported |
| `ROL-I-003` | Beide Editionen betrachten Flexibilität und Struktur. | Struktur | beide `models.json`, Feld `axis.Y` | Supported |
| `ROL-I-004` | Aus beiden Achsen entstehen vier Wirkungsräume. | Struktur | beide `models.json`, Feld `quadrants` | Supported |
| `ROL-I-005` | Die Rollenbeiträge sind keine festen Persönlichkeitstypen, Fähigkeiten oder Eignungsurteile. | Grenze | beide `freezed_doku.md`, Einführung und Grenzen | Paraphrase |
| `ROL-I-006` | Personal und Business nutzen dieselbe Grundarchitektur in unterschiedlichen Kontexten. | Struktur | beide `freezed_doku.md`, „Zusammenhang mit der Family“ | Paraphrase |

