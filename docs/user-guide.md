# 📖 Staeze PM – Nutzer-Handbuch

Willkommen! Dieses Handbuch zeigt dir, wie du mit der Product Intelligence Platform arbeitest.

---

## 🚪 Erster Login

1. Öffne den Admin-Bereich (lokal: http://staeze-pm.test/admin, produktiv: siehe Admin)
2. E-Mail + Passwort eingeben
3. Du landest auf dem **Dashboard**

---

## 🧭 Was siehst du im Menü?

Das Menü links ist in **Gruppen** unterteilt:

### 📚 Stammdaten
*Die Basis — muss einmal gepflegt werden, danach nur noch gelegentlich.*

- **Kategorien** – z.B. Cycling Jerseys, Bib Shorts, Running Shirts …
- **Marken** – Wettbewerber-Marken (Castelli, Rapha, …)
- **Shops** – Online-Shops, bei denen du Preise beobachtest
- **Bewertungs-Dimensionen** – z.B. Design, Material, Performance
- **Qualitätskriterien** – z.B. Nahtqualität, Atmungsaktivität

### 🔍 Marktanalyse
*Hier trägst du Wettbewerbsprodukte ein, die du beobachtest.*

- **Wettbewerbsprodukte**

### 🏭 Lieferanten

- **Lieferanten** – Firmen, von denen ihr Produkte bezieht
- **Lieferanten-Produkte** – Was die Lieferanten anbieten

### ⭐ Bewertungen

- **Alle Bewertungen** – Übersicht über alle Bewertungen im System

### 💡 Produkt-Entwicklung

- **Entwicklungs-Pipeline** – Deine Ideen und Entwürfe in 8 Stati
- **Finale Produkte** – Was daraus fertig geworden ist

---

## 🛠 Typische Arbeits-Workflows

### Workflow 1: Ein Wettbewerbsprodukt erfassen

1. **Marktanalyse → Wettbewerbsprodukte → „Neues Wettbewerbsprodukt"**
2. Tab **Allgemein**: Name, Marke wählen (oder **„+ Erstellen"** für neue Marke inline), Kategorie
3. Tab **Eigenschaften**: Materialien, Farben, Größen als Tags eingeben (Komma oder Tab)
4. Tab **Preis & Bewertung**: Mindestpreis, Höchstpreis, Gesamt-Bewertung (1-10), Pros/Kontras
5. Tab **Bilder**: Bis zu 10 Produktbilder hochladen
6. **Speichern**

Danach im Edit-Modus **Shop-Einträge** hinzufügen (Preise pro Shop über Zeit).

### Workflow 2: Wettbewerbsprodukt bewerten

1. Ein Wettbewerbsprodukt öffnen
2. Unten Tab **„Bewertungen"**
3. **„Bewertung hinzufügen"**
4. Wähle:
   - **Art**: Intern (eigene Einschätzung) oder Extern (z.B. Durchschnitt aus Reviews)
   - **Dimension**: z.B. Design – oder leer für Gesamt-Bewertung
   - **Score 1-10**
   - **Kommentar, Positive Punkte, Negative Punkte**
5. **Speichern**

→ Der Durchschnitts-Score erscheint automatisch in der Produkt-Liste.

### Workflow 3: CSV-Import

Du hast eine Excel-Tabelle mit Wettbewerbsprodukten? Export als CSV:

1. **Marktanalyse → Wettbewerbsprodukte → „CSV-Import"**
2. CSV hochladen
3. Spalten werden automatisch erkannt (Mapping prüfen)
4. **Import starten**

Vorlage: `docs/beispiel-imports/wettbewerbsprodukte-beispiel.csv`

**Clever:** Marken und Kategorien, die noch nicht existieren, werden automatisch angelegt.

### Workflow 4: Lieferant + Kontakt + Produkt anlegen

1. **Lieferanten → Lieferanten → „Neu"**
2. Firmenname, Land, Bewertung, Notizen
3. **Speichern** → Detail-Seite öffnet sich
4. Darunter **Ansprechpartner** → „Ansprechpartner hinzufügen" → Name, Email, Telefon
5. Darunter **Produkte des Lieferanten** → „Produkt hinzufügen" → Name, EK, VK, MOQ

Alternativ: CSV-Import unter **Lieferanten-Produkte → „CSV-Import"** – legt Lieferanten automatisch an.

### Workflow 5: Von der Idee zum finalen Produkt

Das ist der **Kern-Workflow** der Plattform.

1. **Produkt-Entwicklung → Entwicklungs-Pipeline → „Neues Entwicklungs-Item"**
2. Status: `1. Idee`
3. Tab **Inspiration & Basis**: Welche Wettbewerbsprodukte inspirieren dich? Welche Lieferanten-Produkte kommen als Basis in Frage?
4. Über die Zeit Status weiterschieben:
   - `2. In Ausarbeitung`
   - `3. Konzept bestätigt`
   - `4. Tech Sheet erstellt`
   - `5. An Lieferant gesendet`
   - `6. Sample erhalten`
   - `7. Überarbeitet`
   - `8. Final` ← **hier passiert etwas Cooles**
5. Sobald Status auf **„Final"** → automatisch ein Eintrag unter **Finale Produkte** angelegt
6. Dort SKU, Selbstkosten, Verkaufspreis ergänzen
7. Auch dort kannst du Bewertungen hinterlegen (z.B. aus Testrunden)

---

## 🔍 Globale Suche

**Tastenkürzel:**
- Mac: `⌘ + K`
- Windows: `Strg + K`

Öffnet ein Suchfeld über allen Ressourcen. Suchst du „Castelli", findest du
- Wettbewerbsprodukte der Marke Castelli
- Entwicklungs-Items mit dem Namen
- usw.

Direkter Sprung auf die Edit-Seite per Klick.

---

## 🏠 Dashboard

Beim Einloggen landest du hier. Was siehst du?

### Kennzahlen (oben)
- **Wettbewerbsprodukte** gesamt
- **Offene Entwicklungen** (nicht-final)
- **Finale Produkte**
- **Aktive Lieferanten**

### Widgets (unten)
- **Offene Entwicklungen** – welche Items brauchen deine Aufmerksamkeit? Deadlines rot, wenn überfällig
- **Unbewertete Wettbewerbsprodukte** – wofür fehlt noch eine Einschätzung?
- **Letzte Änderungen** – was wurde kürzlich bearbeitet?

Jede Zeile ist klickbar → Sprung direkt ins Edit.

---

## ⭐ Bewertungssystem – der Clou

Das Bewertungssystem ist **universell**: jede Bewertung funktioniert an allen drei Produkt-Typen:

1. Wettbewerbsprodukt
2. Lieferanten-Produkt
3. Finales Produkt

**Zwei Bewertungs-Arten:**
- **Intern** (blau) – deine eigene Meinung / dein Test
- **Extern** (gelb) – Marktfeedback, Online-Reviews-Durchschnitt

**Zwei Modi:**
- **Mit Dimension** (z.B. Design 9/10, Material 7/10) → differenzierte Bewertung
- **Ohne Dimension** (leeres Dimension-Feld) → Gesamt-Bewertung

Mehrere Bewertungen pro Produkt + Dimension sind erlaubt (z.B. zwei Personen bewerten Design separat).

---

## 🛡️ Rollen & Rechte

Als `super_admin` siehst du alles und darfst alles. Willst du weitere User mit weniger Rechten anlegen:

1. **Filament Shield → Roles → „Neu"**
2. Rolle anlegen (z.B. „editor")
3. Permissions auswählen (z.B. nur `view_any_brand`, `view_brand`)
4. **Filament Shield → Users** → User öffnen → Rolle zuweisen

---

## 🧯 Wenn etwas nicht funktioniert

1. Seite **einmal neu laden** (F5)
2. **Browser-Cache** leeren
3. **Browser-Konsole** (F12) prüfen auf Fehler
4. Admin informieren – mit möglichst konkretem Hergang:
   - Welche Seite?
   - Was geklickt?
   - Welche Fehlermeldung?

---

## 📞 Kontakt

Technischer Ansprechpartner: Sven Kloevekorn · sven.kloevekorn@marbet.com

---

*Letzte Aktualisierung: 2026-04-21*
