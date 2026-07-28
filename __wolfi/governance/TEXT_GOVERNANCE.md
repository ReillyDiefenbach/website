# Webcontent Governance

## TEXT_GOVERNANCE

Version: 1.2  
Status: verbindliche Arbeitsgrundlage  
Geltungsbereich: öffentlich sichtbare deutschsprachige Webinhalte von CarlVon  
Freigabestatus: Human Approval Required für Veröffentlichung und Regeländerungen

---

# 1. Zweck

Diese Datei definiert verbindlich, wie Webtexte für CarlVon, PNA, das Dynamics Framework, Module, Modelle, Factsheets und Resultsheets erstellt, geprüft und ausgeliefert werden.

Sie regelt:

- Quellenbindung und Canontreue
- sichtbare Nomenklatur
- Erzählstil und Tonalität
- Kapitelstruktur
- Wiederholungsvermeidung
- Umgang mit Personal- und Business-Modellen
- wissenschaftliche und methodische Begrenzung
- verbotene und kontrollierte Formulierungen
- Halluzinationsprüfung
- mehrstufige Qualitätssicherung
- Freigabe und Erweiterung dieser Governance

Diese Datei erzeugt keine neue Modellwahrheit. Sie ordnet ausschließlich die sprachliche und redaktionelle Auslieferung bereits freigegebener Inhalte.

---

# 2. Autorität und Konfliktregel

## 2.1 Rangfolge

Für Webcontent gilt folgende verbindliche Rangfolge:

1. `__wolfi/canon/`
2. bestehende Dateien in `__wolfi/governance/`
3. freigegebene Modul- und Modellartefakte
4. freigegebene Website- und Dokumentationstexte
5. redaktionelle Ableitungen

## 2.2 Vorrang

**WCG-AUTH-001**  
Der Canon besitzt ausnahmslos Vorrang vor dieser Datei.

**WCG-AUTH-002**  
Diese Datei darf bestehende Governance nicht ersetzen, umdeuten oder abschwächen.

**WCG-AUTH-003**  
Bei einem Konflikt wird keine freie redaktionelle Entscheidung getroffen. Die betroffene Aussage wird gesperrt und als Governance-Frage dokumentiert.

**WCG-AUTH-004**  
Eine sprachlich attraktive Formulierung ist unzulässig, wenn sie fachlich über die freigegebene Quelle hinausgeht.

**WCG-AUTH-005**  
Widersprüche zwischen Quellen werden nicht durch Plausibilität, Mehrheitsentscheidung oder stillschweigende Auswahl gelöst.

## 2.3 Nicht-Gegenstand

Diese Datei darf nicht verändern:

- Modell-IDs
- `model_key`
- `family`
- `title`
- `badge`
- `brand`
- Achsen
- Orientierungen
- Quadrantenpositionen
- Meta-Archetypen
- Berechnungslogik
- Messarchitektur
- Micro-Mappings
- PNA-Evidenzlogik

Änderungen an diesen Bereichen sind keine Textarbeit und folgen den bestehenden Canon- und Human-Approval-Regeln.

---

# 3. Geltungsbereich

Diese Governance gilt für:

- Modulstartseiten
- Modellseiten
- Personal- und Business-Seiten
- Einleitungen
- Keyfacts
- FAQ und Know-how-Bereiche
- Editionsvergleiche
- ausführliche Dokumentationen
- Factsheets
- Resultsheets
- Calls to Action
- sichtbare Hilfetexte
- Meta-Texte und Teaser
- öffentlich sichtbare Tabellen und Bildbeschreibungen

Sie gilt nicht als Ersatz für:

- wissenschaftliche Publikationsstandards
- technische JSON-Spezifikationen
- interne Agentendokumentation
- Berechnungsdokumentation
- rechtliche Texte
- Datenschutz- oder Vertragsdokumente

Für diese Bereiche gelten deren eigene Regelwerke zusätzlich.

---

# 4. Grundsätze der Webauslieferung

**WCG-BASE-001 – Canontreue**  
Jede fachliche Aussage muss auf eine freigegebene Quelle zurückführbar sein.

**WCG-BASE-002 – Keine Halluzination**  
Fehlende Informationen dürfen weder ergänzt noch wahrscheinlich gemacht noch aus sprachlicher Symmetrie erfunden werden.

**WCG-BASE-003 – Natürliche Sprache**  
Der Text muss wie eine zusammenhängende menschliche Erzählung wirken. Er darf nicht wie eine Feldliste, Promptantwort oder automatisch kombinierte Textschablone klingen.

**WCG-BASE-004 – Einmalige Funktion**  
Jeder Abschnitt besitzt eine eindeutige Aufgabe. Eine vollständig erklärte Aussage wird an anderer Stelle nicht erneut vollständig erklärt.

**WCG-BASE-005 – Verständlichkeit**  
Die wissenschaftliche und methodische Begrenzung bleibt erhalten, wird aber in einer für Website-Nutzer verständlichen Sprache vermittelt.

**WCG-BASE-006 – Präzision vor Wirkung**  
Werbliche Wirkung darf niemals durch Übertreibung, Vereinfachung oder unbelegte Zuspitzung erzeugt werden.

**WCG-BASE-007 – Mensch vor Etikett**  
Ein Ergebnis beschreibt eine testbezogene Perspektive oder Orientierung. Es definiert nicht die gesamte Person.

**WCG-BASE-008 – Kontexttreue**  
Personal, Business und spätere Modellkontexte verwenden dieselbe Grundarchitektur. Unterschiede dürfen nur aus freigegebenen Sprach-, Rollen-, Interpretations- und Anwendungskontexten entstehen.

---

# 5. Verbindliche sichtbare Nomenklatur

## 5.1 Organisation und Framework

Verbindlich:

- CarlVon
- PNA
- Persona Natura Authentica
- Dynamics Framework
- PNA Dynamics

Nicht zulässig:

- carlvon
- Carl von
- Dynamics framework
- PNA framework
- dynamisches Framework

## 5.2 Module und Modelle

**WCG-NAME-001**  
Nach außen wird von einem **Modul** gesprochen.

**WCG-NAME-002**  
`Family` ist ein interner und technischer Begriff und darf nicht in öffentlich sichtbarem Webcontent erscheinen.

**WCG-NAME-003**  
Sichtbare Modulnamen verwenden ausschließlich den öffentlichen `title`.

Richtig:

- Think & Act
- Think & Act _Personal_
- Think & Act _Business_

Falsch:

- Thinking
- Thinking Personal
- Thinking Business
- thinking_pers
- thinking_biz

**WCG-NAME-004**  
In HTML wird der Kontextmarker gemäß bestehender Governance als `sup` am sichtbaren Titel geführt.

**WCG-NAME-005**  
Technische Keys dürfen nur in ausdrücklich gekennzeichneten technischen Spezifikationen, Factsheet-Metadaten oder internen Ansichten erscheinen.

## 5.3 Nicht sichtbare Infrastruktur

**WCG-NAME-006**  
OpenClaw darf nicht in öffentlich sichtbaren Webseiten, Reports, Downloads oder Kundenunterlagen erscheinen.

---

# 6. Methodische und fachliche Begrenzung

## 6.1 Dynamics-Auswertung und PNA-Evidenzbasis

**WCG-METH-001**  
Die Dynamics-Auswertung und das Micro-Mapping sind zwei eigenständige Auswertungspfade.

**WCG-METH-002**  
Die PNA entsteht nicht aus:

- Orientierung
- Prioritäten
- Wirkungsraum
- Rollenprofil
- Facets
- Darkfacets

**WCG-METH-003**  
Die PNA-Evidenzbasis ist der kumulative Micro-Pool einer Person.

**WCG-METH-004**  
Macro- und Meta-Views sind Darstellungs- und Organisationssichten. Sie erzeugen keine zusätzliche PNA-Evidenz.

## 6.2 Familymodelle beziehungsweise Module

**WCG-METH-005**  
Module und ihre Modelle werden primär als Reflexions-, Kommunikations-, Dynamics- und testbezogene Auswertungsmodelle beschrieben.

**WCG-METH-006**  
Sie dürfen nicht automatisch als primäre psychometrische Skalen oder diagnostische Instrumente bezeichnet werden.

**WCG-METH-007**  
Webtexte dürfen nicht behaupten, dass Achsen oder Quadranten direkt beobachtet oder direkt gemessen werden.

**WCG-METH-008**  
Die Familyachsen dürfen nicht ohne freigegebene Grundlage als empirisch belegte bipolare Messskalen dargestellt werden.

**WCG-METH-009**  
Messung, Evidenz, Interpretation und Kommunikation müssen sprachlich unterscheidbar bleiben.

## 6.3 Ergebnisse

**WCG-METH-010**  
Ein Ergebnis darf nicht als vollständiges Persönlichkeitsbild, Diagnose, Eignungsurteil oder Zukunftsprognose dargestellt werden.

**WCG-METH-011**  
Balance ist keine schwache, fehlende oder unklare Ausprägung.

**WCG-METH-012**  
Core und Dark Facets beziehen sich auf den jeweiligen Einzeltest und bilden keine testübergreifende PNA-Evidenzbasis.

**WCG-METH-013**  
Dark Facets sind mögliche Überdehnungen einer testbezogenen Qualität und keine Defekte einer Person.

**WCG-METH-014**  
Stärken und Schwächen dürfen nur als typische, kontextabhängige Ausdrucksformen beschrieben werden. Sie sind keine garantierten Kompetenzen oder Defizite.

---

# 7. Erzählstil

## 7.1 Erzählprinzip

„Wie ein Roman“ bedeutet für CarlVon:

- ein erkennbarer gedanklicher Verlauf
- natürliche Übergänge
- ein Spannungsbogen vom Erleben zur Einordnung
- wechselnde, aber kontrollierte Satzrhythmen
- konkrete Bedeutung für den Leser
- keine fiktionalen Tatsachen
- keine erfundenen Personen, Fälle oder Forschungsergebnisse

Der Text darf erzählerisch sein, aber nicht fiktional werden.

## 7.2 Stimme

**WCG-VOICE-001**  
Die Grundstimme ist ruhig, präzise, menschlich, zugänglich und fachlich verantwortlich.

**WCG-VOICE-002**  
Die Standardanrede ist „Du“.

**WCG-VOICE-003**  
Der Leser wird begleitet, nicht belehrt, bewertet oder psychologisch festgelegt.

**WCG-VOICE-004**  
Der Text darf Nähe erzeugen, aber keine emotionale Manipulation verwenden.

**WCG-VOICE-005**  
Die Sprache bleibt konkret. Abstrakte Begriffe werden nur verwendet, wenn sie für die fachliche Aussage notwendig sind.

## 7.3 Absatzgestaltung

**WCG-PARA-001**  
Jeder Absatz trägt einen zentralen Gedanken.

**WCG-PARA-002**  
Ein Absatz muss eine erkennbare Funktion erfüllen:

- Beobachtung
- Einordnung
- Zusammenhang
- Bedeutung
- Differenzierung
- Grenze
- Übergang

**WCG-PARA-003**  
Absätze werden nicht nach einem wiederkehrenden Textschema gebaut.

**WCG-PARA-004**  
Mehrere aufeinanderfolgende Absätze dürfen nicht mit derselben Satzform oder demselben Subjekt beginnen.

**WCG-PARA-005**  
Kurze und längere Sätze dürfen sich abwechseln. Künstlich gleich lange Sätze sind zu vermeiden.

**WCG-PARA-006**  
Aufzählungen werden nur verwendet, wenn Übersicht wichtiger ist als Erzählfluss.

## 7.4 Übergänge

**WCG-FLOW-001**  
Jeder Abschnitt muss aus dem vorherigen Gedanken hervorgehen oder seinen Perspektivwechsel nachvollziehbar ankündigen.

**WCG-FLOW-002**  
Übergänge dürfen nicht ausschließlich aus mechanischen Signalwörtern bestehen.

Zu vermeiden:

- „Darüber hinaus“
- „Des Weiteren“
- „Zusammenfassend lässt sich sagen“
- „Ein weiterer wichtiger Aspekt“
- „Nicht zuletzt“

Diese Formulierungen sind nicht absolut verboten, lösen aber eine Stilprüfung aus und müssen funktional begründet sein.

## 7.5 Natürlichkeit

**WCG-NAT-001**  
Der Modulname darf nicht als mechanisches Subjekt in aufeinanderfolgenden Absätzen wiederholt werden.

**WCG-NAT-002**  
Die direkte Anrede darf nicht in jedem Absatz neu angesetzt werden.

**WCG-NAT-003**  
Konstruktionen nach dem Muster „nicht X, sondern Y“ dürfen nicht als wiederkehrendes Stilmittel eingesetzt werden.

**WCG-NAT-004**  
Künstliche Dreiergruppen, symmetrische Gegensatzpaare und rhetorische Fragen dürfen nur verwendet werden, wenn sie den Inhalt tatsächlich klären.

**WCG-NAT-005**  
Ein Text darf nicht so wirken, als seien Felder aus einer Datenstruktur lediglich zu vollständigen Sätzen erweitert worden.

## 7.6 Leserführung und einfache Sprache

**WCG-PLAIN-001 – Leser vor Modell**  
Eine Einleitung beginnt mit einer vertrauten Situation, einer konkreten Beobachtung oder einer einfachen Frage aus dem Leben des Lesers. Die abstrakte Beschreibung des Modells folgt erst danach.

Begründung:  
Website-Nutzer sollen zuerst erkennen, warum ein Thema für sie relevant ist. Sie dürfen kein Modellwissen benötigen, um den ersten Absatz zu verstehen.

Geltungsbereich:  
Alle Einleitungen, Teaser und ersten Absätze öffentlich sichtbarer Modul- und Modellseiten.

Beispiel:

- zugänglich: „Du möchtest selbst entscheiden, aber niemanden vor den Kopf stoßen.“
- zu abstrakt für einen Einstieg: „Das Modul betrachtet gleichzeitig wirksame Bewegungen in einem konkreten Kontext.“

Freigabestatus: verbindlich; Human Approval durch direkten Governance-Auftrag erteilt  
Datum: 26. Juli 2026

**WCG-PLAIN-002 – Beispiele vor Abstraktion**  
Wo eine freigegebene Aussage durch eine alltägliche Situation verständlicher wird, ist ein kurzes, erkennbar allgemeines Beispiel zu bevorzugen. Ein Beispiel illustriert den Claim; es darf keinen neuen Claim, keine Fallgeschichte und kein Wirkungsversprechen erzeugen.

Begründung:  
Konkrete Situationen erleichtern den Zugang, ohne die fachliche Aussage zu verändern.

Geltungsbereich:  
Erzählung des Moduls, Keyfacts-Einleitungen, Know-how und Editionsvergleich.

Beispiel:  
„Du willst etwas verändern, ohne zu verlieren, was sich bewährt hat.“

Freigabestatus: verbindlich; Human Approval durch direkten Governance-Auftrag erteilt  
Datum: 26. Juli 2026

**WCG-PLAIN-003 – Ein Gedanke pro Satz**  
In Einleitungen tragen Sätze grundsätzlich einen Hauptgedanken. Mehrere Definitionen, Einschränkungen oder Gegensatzpaare werden nicht in einen einzigen Satz gepackt.

Begründung:  
Fachliche Genauigkeit entsteht nicht durch Satzlänge. Kurze gedankliche Schritte verbessern Verständlichkeit und Lesefluss.

Geltungsbereich:  
Einleitungen und allgemeinverständliche Webtexte.

Freigabestatus: verbindlich; Human Approval durch direkten Governance-Auftrag erteilt  
Datum: 26. Juli 2026

**WCG-PLAIN-004 – Fachbegriffe dosieren**  
Ein Absatz führt höchstens einen neuen Modell- oder Systembegriff ein. Der Begriff wird unmittelbar in einfacher Sprache erklärt oder durch ein konkretes Beispiel verständlich gemacht.

Begründung:  
Leser dürfen nicht mehrere unbekannte Begriffe gleichzeitig entschlüsseln müssen.

Geltungsbereich:  
Einleitungen, Keyfacts und Know-how-Bereiche. Technische Spezifikationen, Factsheets und Resultsheets dürfen dichter formuliert sein.

Freigabestatus: verbindlich; Human Approval durch direkten Governance-Auftrag erteilt  
Datum: 26. Juli 2026

**WCG-PLAIN-005 – Methodische Grenzen richtig platzieren**  
Eine Einleitung muss notwendige Grenzen sichtbar machen, darf aber nicht mit Diagnose-, Mess-, Evidenz- und Typisierungsabgrenzungen überladen werden. Zuerst wird der Nutzen des Betrachtungswinkels verständlich. Die fachliche Grenze folgt kurz an der Stelle, an der eine Fehlinterpretation entstehen könnte.

Begründung:  
Governance soll Verständlichkeit absichern und nicht den Lesefluss durch vorgezogene Fachverteidigung blockieren.

Geltungsbereich:  
Modul- und Modelleinleitungen.

Freigabestatus: verbindlich; Human Approval durch direkten Governance-Auftrag erteilt  
Datum: 26. Juli 2026

**WCG-PLAIN-006 – Abstraktionsprüfung**  
Formulierungen wie „Betrachtungsraum“, „gleichzeitig wirksame Bewegungen“, „konkreter Kontext“, „kontextbezogene Modellperspektive“ oder „prägt Orientierung“ lösen in einer Einleitung eine Verständlichkeitsprüfung aus. Sie sind nur zulässig, wenn eine einfachere Formulierung den fachlichen Inhalt nicht gleichwertig ausdrücken kann.

Prüffragen:

1. Versteht ein Leser ohne Modellwissen den Satz beim ersten Lesen?
2. Kann der Satz mit einem gebräuchlichen Verb und einem konkreten Beispiel einfacher werden?
3. Spricht der Text über eine erlebbare Situation oder nur über das Modell selbst?
4. Würde der Satz in einem persönlichen Gespräch natürlich klingen?

Geltungsbereich:  
Einleitungen und erzählerische Webtexte.

Freigabestatus: verbindlich; Human Approval durch direkten Governance-Auftrag erteilt  
Datum: 26. Juli 2026

## 7.7 Verbindliche Länge eines Modul-Intros

**WCG-LENGTH-001 – 400 Wörter Fließtext**  
Ein vollständiges Modul-Intro umfasst in jeder Sprachfassung exakt 400 Wörter Fließtext.

Gezählt werden:

- `model.intro_desc`
- alle vorhandenen Felder nach dem Muster `intro.*_p*`

Nicht gezählt werden:

- `model.title`
- `model.family`
- `model.intro_alt`
- Überschriftenfelder nach dem Muster `intro.*_h`
- HTML-Attribute und technische Metadaten

Zählweise:

- Als Wort gilt eine zusammenhängende Folge aus Buchstaben oder Ziffern.
- Zusammensetzungen mit Bindestrich oder Apostroph gelten als ein Wort.
- Ein alleinstehendes `&` gilt nicht als Wort.
- HTML wird vor der Zählung entfernt.
- Die Prüfung erfolgt auf dem Sprach-JSON vor der HTML-Erzeugung.

Begründung:  
Die feste Länge schafft für alle Module einen vergleichbaren redaktionellen Raum. Sie verhindert sowohl zu knappe Teaser als auch ausufernde Fachdokumentation im Einstieg.

Geltungsbereich:  
Alle öffentlichen Modul-Intros und alle daraus erzeugten Sprachfassungen.

Freigabestatus: verbindlich; Human Approval durch direkten Governance-Auftrag erteilt  
Datum: 26. Juli 2026

---

# 8. Verbindliche Inhaltsdramaturgie einer Modulseite

Die Standardreihenfolge lautet:

1. Erzählung des Moduls
2. Keyfacts
3. Fragen über das Modul
4. kurzer Editionsvergleich
5. ausführliche Dokumentation der Editionen
6. Factsheets
7. Resultsheets

Abweichungen benötigen eine dokumentierte redaktionelle Begründung.

## 8.1 Erzählung des Moduls

Die Erzählung beantwortet:

- Welchen Betrachtungswinkel öffnet das Modul?
- Welche menschliche oder organisationale Spannung wird verständlich?
- Welche freigegebenen Dimensionen strukturieren diese Perspektive?
- Was macht das Modul innerhalb des Dynamics Framework unterscheidbar?
- Wie fügt sich die Perspektive in das Gesamtbild ein?

Die Erzählung darf noch keine vollständige Ergebnislogik vorwegnehmen.

## 8.2 Keyfacts

Keyfacts:

- verdichten bereits eingeführte Aussagen
- führen keine neue Fachbehauptung ein
- enthalten einen Gedanken pro Punkt
- sind schnell erfassbar
- vermeiden Nebensätze und Begründungsketten

## 8.3 Fragen über das Modul

Dieser Bereich beantwortet echte Verständnisfragen:

- Zweck
- Betrachtungsgegenstand
- Dimensionen
- Zusammenspiel
- Balance
- Core und Dark Facets
- Grenzen

Die Einleitung wird nicht erneut erzählt.

## 8.4 Editionsvergleich

Der kurze Vergleich:

- stellt Personal und Business direkt gegenüber
- erklärt den unterschiedlichen Bezugspunkt
- bestätigt die gemeinsame Grundarchitektur
- führt keine erfundenen strukturellen Unterschiede ein
- bleibt kompakt

## 8.5 Ausführliche Editionsdokumentation

Personal und Business erhalten eigenständige Texte.

Die Business-Fassung darf nicht durch mechanischen Austausch einzelner Personalbegriffe entstehen.

Die Personal-Fassung darf nicht durch Entfernung beruflicher Begriffe aus der Business-Fassung entstehen.

Gemeinsame Architektur wird einmal erklärt. Danach vertieft jede Edition ihren freigegebenen Kontext.

## 8.6 Factsheets

Factsheets sind:

- schematisch
- kompakt
- terminologisch exakt
- visuell orientiert
- schnell vergleichbar

Factsheets dürfen keine neue Modellinterpretation einführen.

## 8.7 Resultsheets

Resultsheets:

- erklären konkrete Ergebnis-Keys
- trennen Ergebnisebene, Key, Intensität und Kontext
- unterscheiden typische Stärken von möglichen Überdehnungen
- enthalten keine Eignungs- oder Erfolgsprognose
- typisieren nicht die gesamte Person

---

# 9. Wiederholungs-Governance

## 9.1 Grundregel

**WCG-REP-001**  
Eine Aussage besitzt innerhalb der Moduldokumentation einen primären Heimatabschnitt.

**WCG-REP-002**  
Spätere Kapitel dürfen eine frühere Aussage nur aufnehmen, wenn sie mindestens eine neue Perspektive, Konsequenz, Anwendung oder Ergebnisebene hinzufügen.

**WCG-REP-003**  
Synonyme dürfen nicht verwendet werden, um eine inhaltliche Wiederholung zu verschleiern.

**WCG-REP-004**  
Canonbegriffe werden nicht allein zur stilistischen Variation umbenannt.

Beispiel:

`Wirkungsraum` darf nicht abwechselnd als „Wirkzone“, „Energiefeld“ oder „Resonanztyp“ bezeichnet werden.

## 9.2 Wiederholungsarten

Zu prüfen sind:

- wörtliche Wiederholung
- sinngleiche Wiederholung
- wiederholte Definition
- wiederholte Abgrenzung
- identische Beispiele
- parallele Personal-/Business-Absätze
- mehrfacher Einsatz derselben rhetorischen Figur
- wiederholte Satzanfänge

## 9.3 Zulässige Wiederaufnahme

Zulässig sind:

- Canonbegriffe
- kurze Rückverweise
- Keyfacts als bewusste Verdichtung
- notwendige Zusammenfassungen
- technische Bezeichnungen
- Wiederaufnahme mit klarer neuer Funktion

---

# 10. Terminologieregister

Das Terminologieregister besitzt drei Klassen:

1. verbindliche Begriffe
2. kontrollierte Begriffe
3. verbotene Begriffe und Formulierungen

## 10.1 Verbindliche Begriffe

Verbindliche Begriffe werden exakt gemäß Canon und Nomenclature verwendet.

Dazu gehören insbesondere:

- CarlVon
- PNA
- Persona Natura Authentica
- Dynamics Framework
- PNA Dynamics
- Modul
- Modell
- Personal
- Business
- Orientierung
- Prioritäten
- Wirkungsraum
- Rollenprofil
- Facets
- Darkfacets beziehungsweise die jeweils freigegebene sichtbare Schreibweise
- Microevidenzen
- Micro-Pool
- PNA-Evidenzbasis
- Macro-View
- Meta-View
- Composition

## 10.2 Kontrollierte Begriffe

Kontrollierte Begriffe dürfen nur mit der definierten Bedeutung verwendet werden.

### Authentizität

Bezeichnet die Übereinstimmung zwischen natürlicher Orientierung und gelebtem Verhalten.

### Persönlichkeit

Darf nicht mit einem einzelnen Dynamics-Ergebnis gleichgesetzt werden.

### Präferenz

Beschreibt eine bevorzugte Orientierung oder Verfügbarkeit. Sie ist keine unveränderliche Festlegung.

### Stärke

Bezeichnet eine typische konstruktive Ausdrucksmöglichkeit im jeweiligen Kontext. Sie garantiert keine Kompetenz oder Leistung.

### Schwäche

Darf nur als typische mögliche Begrenzung oder Überdehnung im jeweiligen Kontext verwendet werden. Sie ist kein persönliches Defiziturteil.

### Balance

Bezeichnet eine Ergebnislogik ohne eindeutige Vorrangstellung der betreffenden Gegenpole. Balance ist nicht mit Schwäche oder Unentschlossenheit gleichzusetzen.

### Rolle

Bezeichnet im Modell eine interpretative Rollenebene. Sie ist keine Stellenbezeichnung und kein Eignungsurteil.

### Archetyp

Ist von modellspezifischen Rollenbezeichnungen zu unterscheiden. Die universelle Meta-Archetypenlogik bleibt unverändert.

### Diagnostik

Darf nur verwendet werden, wenn die konkrete Aussage durch Canon, Messarchitektur und freigegebene wissenschaftliche Dokumentation gedeckt ist.

### wissenschaftlich

Darf nicht als Qualitätsversprechen ohne konkrete freigegebene Grundlage verwendet werden.

### Evidenz

Ist gemäß Messarchitektur zu verwenden. Dynamics-Ergebnisse, Facets und Darkfacets dürfen nicht als testübergreifende PNA-Evidenz bezeichnet werden.

### Dimension

Darf nur für einen fachlich definierten Betrachtungsbereich verwendet werden. Eine redaktionelle Gliederung erzeugt keine neue Modelldimension.

### Resonanzraum

`Resonanzraum` ist derzeit kein definierter Strukturbegriff des bestehenden Canon oder der Nomenclature.

Der Begriff darf daher höchstens als klar erkennbare narrative Metapher verwendet werden. Er darf nicht als Mess-, Evidenz-, Modell- oder Ergebnisebene dargestellt werden.

Soll `Resonanzraum` ein verbindlicher Systembegriff werden, ist zuerst eine Canon- beziehungsweise Nomenclature-Entscheidung mit Human Approval erforderlich.

## 10.3 Verbotene sichtbare Begriffe

In öffentlich sichtbarem Webcontent nicht verwenden:

- Family
- OpenClaw
- interne Family-Namen als sichtbare Modulnamen
- interne Modell-Keys als Kundenbezeichnung
- numerische Legacy-IDs als sichtbare Modellidentität

## 10.4 Verbotene fachliche Formulierungen

Ohne ausdrücklich freigegebene Nachweisgrundlage nicht verwenden:

- „misst direkt die Persönlichkeit“
- „misst direkt die Achsen“
- „misst direkt die Quadranten“
- „beweist“
- „garantiert“
- „prognostiziert Erfolg“
- „wissenschaftlich bewiesen“
- „wissenschaftlich validiert“
- „objektiv richtig“
- „vollständiges Persönlichkeitsbild“
- „idealer Persönlichkeitstyp“
- „für eine Rolle geeignet“
- „für eine Rolle ungeeignet“
- „richtige Ausprägung“
- „falsche Ausprägung“
- „besserer Typ“
- „schlechterer Typ“

## 10.5 Verbotene oder zu ersetzende Marketingfloskeln

Als unbelegte Selbstbeschreibung oder werbliche Verstärkung nicht verwenden:

- revolutionär
- bahnbrechend
- einzigartig
- Gamechanger
- Superkraft
- Persönlichkeits-DNA
- dein wahres Selbst
- die Wahrheit über dich
- grenzenloses Potenzial
- Potenzial vollständig entfalten
- Potenzial ausschöpfen
- maßgeschneidert
- ganzheitlich
- tiefgreifend
- transformativ
- eine Reise zu dir selbst
- auf ein neues Level heben

Canonisch definierte Fachbedeutungen werden dadurch nicht aufgehoben. Beispielsweise darf „Transformation“ in einem freigegebenen fachlichen oder markenarchitektonischen Zusammenhang verwendet werden; unzulässig ist die unbelegte werbliche Überhöhung.

## 10.6 Erweiterung der Wortlisten

Neue verbotene oder kontrollierte Begriffe werden mit folgenden Angaben ergänzt:

```text
Regel-ID:
Begriff oder Formulierung:
Status: verbindlich | kontrolliert | verboten
Begründung:
Erlaubter Kontext:
Nicht erlaubter Kontext:
Zulässige Alternative:
Freigabe:
Datum:
```

---

# 11. Kapitelvertrag

Vor jeder kapitelweisen Überarbeitung wird ein Kapitelvertrag erstellt.

Pflichtfelder:

```text
Kapitel-ID:
Arbeitstitel:
Zweck:
Zentrale Leserfrage:
Zielgruppe:
Canonquellen:
Governancequellen:
Freigegebene Moduldaten:
Erlaubte Aussagen:
Nicht erlaubte Aussagen:
Neue inhaltliche Leistung:
Bereits an anderer Stelle erklärte Aussagen:
Einzuführende Canonbegriffe:
Kontrollierte Begriffe:
Verbotene Begriffe:
Abgrenzung zum vorherigen Kapitel:
Übergang zum folgenden Kapitel:
Freigabestatus:
```

Ohne Kapitelvertrag beginnt keine finale Textproduktion.

---

# 12. Claim Ledger

Für jedes Kapitel wird intern ein Claim Ledger geführt.

Ein Claim ist eine eigenständig prüfbare fachliche Aussage.

Pflichtfelder:

```text
Claim-ID:
Aussage:
Claim-Typ: Struktur | Messung | Interpretation | Anwendung | Grenze | Marke
Quelle:
Fundstelle:
Status: Supported | Paraphrase | Derived | Unverifiable | Conflict
Edition:
Primärer Heimatabschnitt:
Freigabe:
```

## 12.1 Claim-Status

### Supported

Die Aussage ist direkt durch eine freigegebene Quelle gedeckt.

### Paraphrase

Die Aussage gibt eine vorhandene Quelle ohne Bedeutungszuwachs verständlicher wieder.

### Derived

Die Aussage ist eine nachvollziehbare Ableitung, steht aber nicht ausdrücklich in der Quelle.

`Derived` darf nicht automatisch veröffentlicht werden. Sie benötigt fachliche Prüfung und Human Approval.

### Unverifiable

Für die Aussage wurde keine ausreichende Quelle gefunden.

Die Aussage wird entfernt oder bis zur Klärung gesperrt.

### Conflict

Die Aussage widerspricht einer höherrangigen Quelle oder steht zwischen Quellen im Konflikt.

Die Textarbeit wird an dieser Stelle gestoppt. Der Konflikt wird dokumentiert und zur Entscheidung vorgelegt.

---

# 13. Mehrstufiger Auslieferungsprozess

Ein Webtext wird nicht in einem einzigen Durchlauf erzeugt und freigegeben.

Jeder Durchlauf besitzt eine eigene Aufgabe. Ein Prüfdurchlauf darf nicht stillschweigend neue Fachinhalte einführen.

## Durchlauf 0 – Source Lock

Aufgabe:

- Quellen bestimmen
- Versionen festhalten
- Canon- und Governance-Dateien auflisten
- Modul- und Modellartefakte festlegen
- widersprüchliche oder fehlende Quellen markieren

Output:

- Source Register
- Kapitelvertrag
- Status `READY` oder `BLOCKED`

Fail-Bedingungen:

- unbekannte Modellidentität
- ungeklärte Canonquelle
- widersprüchliche Strukturangaben
- fehlende Freigabe für strukturelle Änderungen

## Durchlauf 1 – Canon Extraction

Aufgabe:

- zulässige Aussagen extrahieren
- Freeze-Felder identifizieren
- verbindliche Namen und Keys sichern
- Mess-, Evidenz-, Interpretations- und Kommunikationsebene trennen

Output:

- Claim Ledger
- Terminologieliste
- Liste verbotener Ableitungen

In diesem Durchlauf wird noch kein erzählerischer Webtext geschrieben.

## Durchlauf 2 – Content Architecture

Aufgabe:

- Aussagen den Kapiteln zuordnen
- primären Heimatabschnitt je Claim bestimmen
- Kapitelreihenfolge festlegen
- Personal- und Business-Anteile trennen
- Factsheet- und Resultsheet-Inhalte abgrenzen

Output:

- Kapitelplan
- Claim-to-Chapter-Matrix
- Wiederholungsrisiken

## Durchlauf 3 – Narrative Draft

Aufgabe:

- natürlichen Erzählfluss herstellen
- Aussagen verständlich paraphrasieren
- Übergänge formulieren
- Zielgruppenansprache anwenden
- Kapitelvertrag einhalten

Verboten:

- neue Claims erzeugen
- Lücken kreativ schließen
- technische Begriffe frei umbenennen
- Marketingaussagen ergänzen

Output:

- Draft mit Claim-Referenzen

## Durchlauf 4 – Halluzinationsprüfung

Aufgabe:

- jede fachliche Aussage atomisieren
- jede Aussage mit dem Claim Ledger abgleichen
- Zahlen, Namen, Ebenen und Beziehungen prüfen
- nicht belegte Kausalitäten suchen
- unbelegte Vergleiche und Alleinstellungsbehauptungen suchen
- erfundene Beispiele, Forschungsergebnisse oder Anwendungsversprechen suchen

Prüffragen:

1. Besitzt jede fachliche Aussage eine Quelle?
2. Wurde aus einer Beschreibung eine Messbehauptung gemacht?
3. Wurde aus einer Möglichkeit eine Gewissheit?
4. Wurde aus einer Interpretation eine Persönlichkeitseigenschaft?
5. Wurde aus einem Einzeltest PNA-Evidenz abgeleitet?
6. Wurde ein Canonbegriff erweitert oder umbenannt?
7. Wurde ein Unterschied zwischen Editionen erfunden?
8. Wurde eine Zahl, Ebene oder Ergebnisanzahl ergänzt?

Output:

- `PASS`
- `FAIL` mit Claim-ID und Fundstelle
- `BLOCKED` bei Quellenkonflikt

Ein `FAIL` wird an den Narrative Draft zurückgegeben. Die Halluzinationsprüfung schreibt die Aussage nicht selbstständig fachlich um.

## Durchlauf 5 – Architektur- und Messprüfung

Aufgabe:

- Framework-, Modul- und Modellhierarchie prüfen
- Dynamics-Auswertung und Micro-Mapping trennen
- PNA-Evidenzbasis korrekt darstellen
- Achsen-, Orientierungs- und Quadrantenlogik prüfen
- Archetyp und modellspezifische Rolle unterscheiden
- direkte Messbehauptungen ausschließen

Output:

- Architecture Review
- Measurement Review
- `PASS`, `FAIL` oder `BLOCKED`

## Durchlauf 6 – Nomenclature Review

Aufgabe:

- sichtbare Titel prüfen
- interne Begriffe entfernen
- CarlVon-, PNA- und Framework-Schreibweise prüfen
- Personal- und Business-Kontextmarker prüfen
- technische Keys auf zulässige Kontexte begrenzen
- UTF-8 und echte Umlaute prüfen

Output:

- Terminology Report
- Liste aller Abweichungen mit Regel-ID

## Durchlauf 7 – Redundanzprüfung

Aufgabe:

- wörtliche Wiederholungen finden
- sinngleiche Wiederholungen finden
- doppelte Definitionen identifizieren
- parallele Personal-/Business-Absätze vergleichen
- wiederkehrende Satzanfänge und Textschablonen finden
- Claim-Heimatabschnitte prüfen

Entscheidung je Fundstelle:

- streichen
- verkürzen
- rückverweisen
- mit neuer Perspektive begründen
- in den Heimatabschnitt verschieben

Output:

- Redundancy Report
- bereinigter Draft ohne neue Fachclaims

## Durchlauf 8 – Sprach- und Natürlichkeitsprüfung

Aufgabe:

- Erzählfluss prüfen
- mechanische Satzmuster entfernen
- Absatzfunktionen prüfen
- Anrede und Tonalität prüfen
- Marketingfloskeln entfernen
- kontrollierte und verbotene Begriffe prüfen
- unnötige Abstraktion reduzieren

Prüfung:

- Klingt der Text wie von einer verantwortlichen menschlichen Redaktion?
- Entwickelt sich der Gedanke natürlich?
- Hat jeder Absatz eine Aufgabe?
- Wiederholt der Text seine eigene Satzmelodie?
- Ist die Sprache verständlich, ohne fachlich flach zu werden?

Output:

- Style Review
- `PASS` oder konkrete Fundstellen mit Regel-ID

## Durchlauf 9 – Editionsprüfung

Aufgabe:

- gemeinsame Architektur bestätigen
- Personal- und Business-Kontexte sauber trennen
- mechanische Spiegeltexte erkennen
- erfundene Editionsunterschiede ausschließen
- Eignungs-, Leistungs- und Rollenurteile im Business-Kontext ausschließen
- therapeutische oder klinische Aussagen im Personal-Kontext ausschließen

Output:

- Edition Consistency Report
- `PASS`, `FAIL` oder `BLOCKED`

## Durchlauf 10 – Web- und Nutzerprüfung

Aufgabe:

- Scanbarkeit prüfen
- Absatzlängen prüfen
- Überschriftenhierarchie prüfen
- Keyfacts auf Schnelllesbarkeit prüfen
- FAQ auf echte Nutzerfragen prüfen
- Tabellen auf Verständlichkeit prüfen
- Links, Sprungziele, Alternativtexte und Edition-Switch prüfen
- mobile und druckbare Darstellung berücksichtigen

Dieser Durchlauf verändert keine fachliche Aussage.

Output:

- Web Delivery Review
- technische oder redaktionelle Findings

## Durchlauf 11 – Final Compliance Review

Aufgabe:

- Canon-Konformität bestätigen
- Governance-Konformität bestätigen
- offene Findings ausschließen
- finale Wortlistenprüfung durchführen
- sichtbare technische Interna ausschließen
- Freigabestatus dokumentieren

Finale Statuswerte:

- `APPROVED FOR HUMAN REVIEW`
- `NEEDS REVISION`
- `BLOCKED`

## Durchlauf 12 – Human Approval und Publish

Aufgabe:

- fachliche und redaktionelle Freigabe
- Entscheidung über `Derived` Claims
- Entscheidung über neue kontrollierte Begriffe
- Entscheidung über Governance-Erweiterungen
- Freigabe zur Veröffentlichung

Nur nach Human Approval erhält der Inhalt den Status:

`APPROVED FOR PUBLISH`

---

# 14. Trennung von Erstellung und Prüfung

**WCG-QA-001**  
Ein Prüfpass besitzt eine klar begrenzte Aufgabe.

**WCG-QA-002**  
Ein Prüfpass darf fachliche Fehler nicht durch unbelegte Neuformulierungen verdecken.

**WCG-QA-003**  
Jedes Finding enthält:

- Regel-ID
- Fundstelle
- beanstandete Aussage
- Begründung
- betroffene Quelle
- erforderliche Aktion

**WCG-QA-004**  
Ein `PASS` darf nur vergeben werden, wenn alle Findings des jeweiligen Durchlaufs geschlossen oder ausdrücklich akzeptiert wurden.

**WCG-QA-005**  
Automatisierte Prüfungen dürfen Wortlisten, Wiederholungen, Struktur und formale Konsistenz prüfen. Sie ersetzen keine fachliche Canonprüfung.

---

# 15. Reviewbericht

Vor jeder wesentlichen Änderung und vor jeder Veröffentlichung wird ein Reviewbericht erstellt.

Pflichtinhalt:

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
Redundanzprüfung:
Terminologieprüfung:
Editionsprüfung:
Webprüfung:
Offene Entscheidungen:
Finaler Status:
Human Approval:
```

---

# 16. Änderungs- und Erweiterungsregel

Die Webcontent Governance wird kapitelweise erweitert.

## 16.1 Neue Regeln

Neue Regeln erhalten:

- eindeutige Regel-ID
- Regeltext
- Begründung
- Geltungsbereich
- Beispiel
- Freigabestatus
- Datum

## 16.2 Bestehende Regeln

Bestehende Regeln werden nicht stillschweigend überschrieben.

Eine Abschwächung, Umdeutung oder Aufhebung benötigt:

- dokumentierte Begründung
- Konfliktprüfung gegen Canon
- Konfliktprüfung gegen bestehende Governance
- Human Approval
- Eintrag im Änderungsprotokoll

## 16.3 Kapitelbezogene Erkenntnisse

Eine neue Regel aus der Bearbeitung eines einzelnen Kapitels wird darauf geprüft, ob sie:

- nur für dieses Kapitel gilt
- für das gesamte Modul gilt
- für alle Module gilt
- in Nomenclature oder Canon gehört

Regeln mit struktureller oder fachlicher Wirkung dürfen nicht allein in dieser Datei verankert werden.

---

# 17. Definition of Done

Ein Webtext ist erst auslieferungsbereit, wenn:

- ein Kapitelvertrag vorliegt
- die Quellen gesperrt und dokumentiert sind
- alle Claims erfasst sind
- keine `Unverifiable` Claims verbleiben
- keine ungeklärten `Conflict` Claims verbleiben
- `Derived` Claims freigegeben oder entfernt sind
- Canon und Governance eingehalten werden
- Messung, Evidenz, Interpretation und Kommunikation getrennt bleiben
- keine unzulässige Typisierung erfolgt
- keine verbotenen Begriffe oder Marketingfloskeln verbleiben
- keine unbegründeten Wiederholungen verbleiben
- Personal und Business kontexttreu formuliert sind
- Factsheets keine neue Interpretation erzeugen
- Resultsheets keine Person vollständig definieren
- UTF-8 und sichtbare Nomenklatur korrekt sind
- alle Prüfdurchläufe bestanden sind
- Human Approval dokumentiert ist

---

# 18. Änderungsprotokoll

## Version 1.2

Status: freigegebene Governance-Erweiterung

Enthält:

- verbindliche Gesamtlänge von 400 Wörtern für jedes Modul-Intro
- eindeutige Definition der gezählten JSON-Felder
- sprachunabhängig wiederholbare Wortzählung
- Einbeziehung optionaler Absatzfelder
- Ausschluss von Titeln, Überschriften, Alt-Texten und technischen Metadaten

Begründung:

Die Modul-Intros sollen über alle Module und Sprachfassungen hinweg einen vergleichbaren redaktionellen Umfang besitzen. Die Länge wird deshalb am Sprach-JSON und nicht am gerenderten HTML kontrolliert.

## Version 1.1

Status: freigegebene Governance-Erweiterung

Enthält:

- verbindliche Leserführung für Modul- und Modelleinleitungen
- Alltagssituation und Beispiel vor abstrakter Modellbeschreibung
- einfache Satzgestaltung mit einem Hauptgedanken
- dosierte Einführung von Fachbegriffen
- lesefreundliche Platzierung methodischer Grenzen
- Abstraktionsprüfung für schwer verständliche Einstiegssprache

Begründung:

Die Bearbeitung des Nexus-Intros hat gezeigt, dass fachlich korrekte Texte dennoch zu akademisch und zu weit von der Lebenssituation des Lesers entfernt beginnen können. Die neuen Regeln gelten deshalb modulübergreifend für öffentlich sichtbare Einleitungen.

## Version 1.0

Status: Initialfassung

Enthält:

- Autoritäts- und Konfliktregeln
- verbindlichen Web-Erzählstil
- Inhaltsdramaturgie für Modulseiten
- Wiederholungs-Governance
- Terminologieregister
- erste No-go-Liste
- Kapitelvertrag
- Claim Ledger
- dreizehn getrennte Auslieferungs- und Prüfdurchläufe
- Halluzinationsprüfung
- Canon-, Mess-, Nomenclature-, Redundanz-, Stil-, Editions- und Webprüfung
- Human-Approval- und Publish-Regel
