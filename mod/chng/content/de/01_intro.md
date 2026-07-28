# Change & Adapt – Intro

Status: Entwurf für Human Review  
Kapitel-ID: `change.intro`  
Ausgabesprache: Deutsch  
Verbindlicher Umfang: 400 Wörter Fließtext

## 1. Verbindliche Governance

Dieses Kapitel folgt [`TEXT_GOVERNANCE.md`](../../../../__wolfi/governance/TEXT_GOVERNANCE.md), insbesondere `WCG-PLAIN-001` bis `WCG-PLAIN-006` und `WCG-LENGTH-001`.

Maßgebliche Inhaltsquellen:

- `__wolfi/compact/change/models.json`
- `__wolfi/compact/change/change_pers/models.json`
- `__wolfi/compact/change/change_biz/models.json`
- `__wolfi/compact/change/change_pers/freezed_doku.md`
- `__wolfi/compact/change/change_biz/freezed_doku.md`

Strukturquelle:

- `_assets/_templates/_web_module_intro.html`

## 2. Was der Artikel aussagen soll

Change & Adapt macht verständlich, wie Menschen mit Veränderung umgehen. Das Intro führt von bekannten Situationen zu zwei Modellfragen: Wie wird Veränderung gesteuert, und welche Richtung erhält dabei Vorrang? Personal unterscheidet Selbstbestimmung und Kontrolle sowie Entwicklung und Stabilität. Business unterscheidet Autonomie und Steuerung sowie Transformation und Stabilität.

Das Intro erklärt den Betrachtungswinkel, die vier grundlegenden Verbindungen und die Trennung der Editionen. Es erklärt noch keine Ergebnis-Keys, Rollenprofile, Facets, Messung oder PNA-Evidenz.

## 3. Verbindliche Felder

| Feld | Inhaltsfunktion |
|---|---|
| `model.family` | technischer Familienwert |
| `model.title` | öffentlicher Modultitel |
| `model.intro_desc` | alltagsnaher Einstieg |
| `model.intro_alt` | übersetzbarer Alternativtext |
| `intro.1_h`, `intro.1_p1` | Steuerung von Veränderung |
| `intro.2_h`, `intro.2_p1` | Richtung von Veränderung |
| `intro.3_h`, `intro.3_p1` | unterscheidbarer Modulblick |
| `intro.4_h`, `intro.4_p1` | vier Wirkungsräume |
| `intro.5_h`, `intro.5_p1` | Personal und Business |

## 4. Redaktioneller Ausgangstext

### Change & Adapt

Veränderung kann sich gut anfühlen, wenn du sie selbst anstößt. Sie kann verunsichern, wenn sie plötzlich kommt oder andere über den Weg entscheiden. Manchmal möchtest du sofort ausprobieren, was möglich ist. Manchmal brauchst du erst einen Plan, Ruhe oder etwas Verlässliches. Change & Adapt hilft dir ganz konkret zu verstehen, wie du mit solchen Situationen umgehst und was dir dabei Orientierung gibt.

#### Selbst entscheiden oder stärker absichern?

Wenn sich etwas ändert, stellt sich oft zuerst die Frage: Möchtest du deinen Weg selbst bestimmen oder brauchst du mehr Kontrolle und Absicherung? Selbstbestimmung kann bedeuten, eigene Ideen zu testen und aus Erfahrung zu lernen. Kontrolle kann helfen, Risiken zu begrenzen, Schritte zu planen und den Überblick zu behalten. Im Business heißt dieses Spannungsfeld Autonomie und Steuerung. Dort geht es darum, wie viel Freiraum und wie viel gemeinsame Koordination ein Veränderungsprozess braucht.

#### Neues entwickeln oder Stabilität schützen?

Eine zweite Frage betrifft die Richtung: Soll etwas bewusst weiterentwickelt werden, oder ist es wichtiger, Stabilität zu sichern? Entwicklung und Transformation öffnen neue Möglichkeiten. Stabilität schützt, was funktioniert, und gibt Menschen einen verlässlichen Rahmen. Im Alltag zeigt sich das etwa bei neuen Gewohnheiten, Entscheidungen oder Lebensphasen. Im Unternehmen kann es um neue Abläufe, Verantwortlichkeiten oder Arbeitsweisen gehen. Wandel wird verständlicher, wenn beide Bedürfnisse Platz haben.

#### Wie Veränderung Richtung bekommt

Change & Adapt verbindet diese beiden Fragen. Das unterscheidet den Blick des Moduls innerhalb des Dynamics Framework. Es betrachtet nicht nur, ob du Veränderung begrüßt oder vorsichtig auf sie reagierst. Es fragt auch, wie du sie steuerst und welche Richtung du dabei bevorzugst. So kann derselbe Wunsch nach Entwicklung sehr unterschiedlich aussehen: als freies Ausprobieren, als geplante Gestaltung, als verlässliche Absicherung oder als sorgfältige Einordnung neuer Erfahrungen.

#### Vier mögliche Wege durch den Wandel

Aus dem Zusammenspiel entstehen vier Wirkungsräume. Einer öffnet neue Möglichkeiten und erkundet unbekannte Wege. Ein anderer bringt Veränderung gezielt voran. Ein dritter schützt Kontinuität und sorgt für Sicherheit. Der vierte prüft Erfahrungen und verbindet Neues mit dem, was weiterhin trägt. Diese Rollenlesarten sind keine Bewertung. Sie beschreiben eine mögliche Orientierung in einer bestimmten Situation und sagen nicht, wie anpassungsfähig, mutig oder leistungsfähig ein Mensch grundsätzlich ist.

#### Veränderung im Leben und im Unternehmen

Die Personal Edition richtet den Blick auf private Veränderungen, Unsicherheit und persönliche Entwicklung. Die Business Edition betrachtet Wandel in Teams und Organisationen. Ihre Begriffe unterscheiden sich dort, wo der Kontext andere Fragen stellt, die Grundstruktur bleibt jedoch verbunden. Du kannst privat mehr Sicherheit suchen und beruflich gern neue Wege testen. Das ist kein Widerspruch. Es zeigt, dass Veränderung nur zusammen mit Verantwortung, Umfeld und aktueller Lebenslage sinnvoll verstanden werden kann.

## 5. Claim Ledger

| Claim-ID | Aussage | Quelle | Status |
|---|---|---|---|
| `CHG-I-001` | Personal unterscheidet Selbstbestimmung und Kontrolle. | `change_pers/models.json`, `axis.X` | Supported |
| `CHG-I-002` | Business unterscheidet Autonomie und Steuerung. | `change_biz/models.json`, `axis.X` | Supported |
| `CHG-I-003` | Personal unterscheidet Entwicklung und Stabilität. | `change_pers/models.json`, `axis.Y` | Supported |
| `CHG-I-004` | Business unterscheidet Transformation und Stabilität. | `change_biz/models.json`, `axis.Y` | Supported |
| `CHG-I-005` | Aus beiden Achsen entstehen vier Wirkungsräume. | beide `models.json`, `quadrants` | Supported |
| `CHG-I-006` | Rollenlesarten sind keine Kompetenz- oder Persönlichkeitsurteile. | beide `freezed_doku.md` | Paraphrase |
