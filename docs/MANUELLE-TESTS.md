# 🧪 Manuelle Test-Anleitung – Staeze PM

> Eine Schritt-für-Schritt-Anleitung, mit der du selbst alle gebauten Funktionen im Browser testen kannst.
> **Nach jeder neuen Phase sollte hier ein Abschnitt dazukommen.**

---

## 🚦 Vorbereitung

1. **Herd läuft?** → Öffne die Herd-App, prüfe dass `staeze-pm` als Site gelistet ist
2. **PostgreSQL läuft?** → Herd → Services → PostgreSQL muss grün sein
3. **Browser auf:** http://staeze-pm.test
4. **Login:**
   - URL: http://staeze-pm.test/admin/login
   - E-Mail: `admin@admin.com`
   - Passwort: `password`

✅ Erwartung: Du siehst das **Filament-Dashboard auf Deutsch**, links das Navigationsmenü mit **"Stammdaten"** und **"Filament Shield"**.

---

## ✅ Phase 1 – Stammdaten testen

### 🔹 1. Kategorien

**Pfad:** Stammdaten → Kategorien

#### Test-Schritte:

| # | Was tun? | Erwartung |
|---|---|---|
| 1.1 | Öffne die Kategorien-Liste | 7 Kategorien sind da: Cycling Jerseys, Bib Shorts, Running Shirts, Running Shorts, Jackets, T-Shirts, Bags |
| 1.2 | Suche nach "Cycling" | Nur "Cycling Jerseys" wird angezeigt |
| 1.3 | Filter "Aktiv-Status" → "Nur aktive" | Alle 7 Einträge weiterhin sichtbar |
| 1.4 | Klick "Neu" oben rechts | Formular öffnet sich |
| 1.5 | Tippe "Test Kategorie" als Name | Slug-Feld füllt sich automatisch mit `test-kategorie` |
| 1.6 | Speichern | Erfolgs-Meldung, neue Kategorie taucht in der Liste auf |
| 1.7 | Eine Kategorie bearbeiten | Änderungen werden gespeichert |
| 1.8 | Toggle "Aktiv" auf "aus" | Kategorie ist deaktiviert (Filter "Nur inaktive" findet sie) |
| 1.9 | Bulk-Auswahl + Löschen | Bestätigung wird verlangt, dann gelöscht |

#### ❓ Was prüfen?
- Sortierung nach `#` (sort_order) funktioniert
- Slug ist immer einzigartig (gleichen Namen 2× anlegen → Fehler)
- Beschreibung ist optional

---

### 🔹 2. Marken (Brands)

**Pfad:** Stammdaten → Marken

#### Test-Schritte:

| # | Was tun? | Erwartung |
|---|---|---|
| 2.1 | Liste öffnen | (leer, oder Test-Daten) |
| 2.2 | Klick "Neu" → Name "Castelli", Notizen "Italienische Marke" → Speichern | Marke erscheint in der Liste |
| 2.3 | Versuch nochmal "Castelli" anzulegen | Fehler: Name muss einzigartig sein |
| 2.4 | Suche nach "Cas" | "Castelli" erscheint |
| 2.5 | "Castelli" bearbeiten, Aktiv ausschalten | In Filter "Nur inaktive" sichtbar |

---

### 🔹 3. Shops

**Pfad:** Stammdaten → Shops

#### Test-Schritte:

| # | Was tun? | Erwartung |
|---|---|---|
| 3.1 | Liste öffnen | 4 Shops vorhanden: Amazon DE, Bike-Discount, Wiggle, SportScheck |
| 3.2 | Klick auf URL eines Shops in der Tabelle | Öffnet die Website in neuem Tab |
| 3.3 | Klick "Neu" → Name "Test", URL "kein-link" | Fehler: keine gültige URL |
| 3.4 | Land "DE" eingeben | Zeigt sich als Badge |
| 3.5 | Land mit 3 Buchstaben "DEU" versuchen | Fehler oder abgeschnitten (max 2) |

---

### 🔹 4. Bewertungs-Dimensionen

**Pfad:** Stammdaten → Bewertungs-Dimensionen

#### Test-Schritte:

| # | Was tun? | Erwartung |
|---|---|---|
| 4.1 | Liste öffnen | 5 Dimensionen: Design, Material, Verarbeitung, Performance, Preis-Leistung |
| 4.2 | Sortierung nach `#` | Reihenfolge: 1=Design, 2=Material, ... |
| 4.3 | Klick "Neu" → Name "Komfort" anlegen | Erscheint in der Liste |
| 4.4 | Doppelten Namen anlegen | Fehler: Name muss einzigartig sein |

---

### 🔹 5. Qualitätskriterien

**Pfad:** Stammdaten → Qualitätskriterien

#### Test-Schritte:

| # | Was tun? | Erwartung |
|---|---|---|
| 5.1 | Liste öffnen | 6 Kriterien vorhanden (Nahtqualität, Atmungsaktivität, Passform, Wasserdichtigkeit, Polsterung, Reflektoren) |
| 5.2 | Spalte "Kategorien" prüfen | Jedes Kriterium hat passende Kategorie-Badges |
| 5.3 | Filter "Kategorie" → "Jackets" auswählen | Nur Kriterien sichtbar, die für Jacken gelten (Nahtqualität, Atmungsaktivität, Passform, Wasserdichtigkeit, Reflektoren) |
| 5.4 | Klick "Neu" → "UV-Schutz" + Kategorien "Cycling Jerseys, T-Shirts" auswählen | Kriterium wird gespeichert |
| 5.5 | Bearbeiten → eine Kategorie entfernen | Wird gespeichert, Badge verschwindet |

---

### 🔹 6. Rollen & Rechte (Filament Shield)

**Pfad:** Filament Shield → Roles

#### Test-Schritte:

| # | Was tun? | Erwartung |
|---|---|---|
| 6.1 | Roles-Liste öffnen | Mindestens "super_admin" sichtbar |
| 6.2 | Neue Rolle "editor" anlegen | Funktioniert |
| 6.3 | Permissions zuweisen (z.B. nur "view_any_brand") | Speichern klappt |

> ⚠️ **Wichtig:** Du als super_admin **umgehst alle Permission-Checks** (`Gate::before` in `AppServiceProvider.php`). Du siehst also immer alles. Um Rollen wirklich zu testen, musst du dich mit einem Test-User ohne super_admin-Rolle einloggen.

---

## 🧪 Automatische Tests laufen lassen

Im Terminal im Projektordner:

```bash
php artisan test
```

**Erwartung (Stand Phase 1):** `14 passed`

---

## 🔧 Was tun, wenn was nicht klappt?

### ❌ "Datenbank nicht verbunden" / 500-Fehler

```bash
# Verbindung prüfen
psql -h 127.0.0.1 -p 5432 -U postgres -c "SELECT 1;"

# DB neu aufbauen + seed
php artisan migrate:fresh --seed
```

### ❌ "Permission denied" im Admin (403)

```bash
# Super-Admin-Rolle neu zuweisen
php artisan tinker --execute="\App\Models\User::where('email', 'admin@admin.com')->first()->syncRoles(['super_admin']);"
```

### ❌ Login funktioniert nicht / falsches Passwort

```bash
# Passwort zurücksetzen
php artisan tinker --execute="\$u = \App\Models\User::where('email', 'admin@admin.com')->first(); \$u->password = bcrypt('password'); \$u->save();"
```

### ❌ Caches sind kaputt

```bash
php artisan optimize:clear
php artisan filament:cache-components
```

### ❌ Site `staeze-pm.test` nicht erreichbar

```bash
# Herd neu linken
herd link staeze-pm
herd restart
```

---

## 📋 Test-Checkliste pro Phase

Wenn ich (Claude) eine neue Phase abgeschlossen habe, gehe so vor:

1. ✅ Im Browser einloggen
2. ✅ Neue Module anklicken (siehe Abschnitte oben für die Phase)
3. ✅ Mindestens 1× **Anlegen**, 1× **Bearbeiten**, 1× **Löschen** durchspielen
4. ✅ Suche und Filter ausprobieren
5. ✅ `php artisan test` → muss grün sein
6. ✅ Wenn alles passt → Punkt in `TODO.md` abhaken lassen

---

## ✅ Phase 2 – Marktanalyse (Wettbewerbsprodukte) testen

**Pfad im Menü:** Marktanalyse → Wettbewerbsprodukte

> Dies ist das erste „große" Modul mit Tabs, Bildern, Relationen und CSV-Import. Bitte testet es besonders gründlich.

### 🔹 6. Wettbewerbsprodukt anlegen (Tab-Formular)

| # | Was tun? | Erwartung |
|---|---|---|
| 6.1 | Liste öffnen | 3 vorgeseedete Produkte sichtbar (Castelli Climber, Rapha Pro Team Bib, Assos Mille GT) |
| 6.2 | Spalte „Shops" | Zeigt Anzahl der Shop-Einträge pro Produkt (sollte 2 sein) |
| 6.3 | Klick „Neues Wettbewerbsprodukt" | Formular mit **4 Tabs** öffnet sich: Allgemein, Eigenschaften, Preis & Bewertung, Bilder |
| 6.4 | Tab „Allgemein": Name leer lassen → Speichern | Validierungsfehler („required") |
| 6.5 | Name „Test Produkt" + Kategorie auswählen | Pflichtfelder erfüllt |
| 6.6 | Im Marken-Dropdown: „Erstellen" klicken → neue Marke „Mavic" anlegen | Marke wird im Dropdown sofort verfügbar |
| 6.7 | Tab „Eigenschaften": Material „Polyester" eintippen + Komma | Tag wird erstellt |
| 6.8 | Mehrere Größen tippen: S, M, L | Drei Tags entstehen |
| 6.9 | Tab „Preis & Bewertung": Min 50, Max 80, Bewertung 7 | Werden gespeichert |
| 6.10 | Bewertung 11 eingeben | Validierung blockt (max 10) |
| 6.11 | Tab „Bilder": JPG-Bild hochladen | Upload startet, Vorschau erscheint |
| 6.12 | Speichern | Produkt erscheint in der Liste mit Thumbnail |

### 🔹 7. Wettbewerbsprodukt bearbeiten + Shop-Einträge

| # | Was tun? | Erwartung |
|---|---|---|
| 7.1 | Eines der Demo-Produkte bearbeiten | Edit-Form öffnet sich, Felder vorbefüllt |
| 7.2 | Unter dem Formular: **„Shop-Einträge"** Tabelle sichtbar | Zwei Einträge mit Shops und Preisen |
| 7.3 | „Shop-Eintrag hinzufügen" → Shop wählen, URL + Preis + Datum eintragen | Wird gespeichert |
| 7.4 | Klick auf URL eines Shop-Eintrags | Öffnet in neuem Tab |
| 7.5 | Shop-Eintrag löschen | Bestätigung → weg |

### 🔹 8. Filter & Suche

| # | Was tun? | Erwartung |
|---|---|---|
| 8.1 | Suche nach „Castelli" | Nur Castelli-Produkte sichtbar |
| 8.2 | Filter „Marke" → Rapha auswählen | Nur Rapha-Produkte |
| 8.3 | Filter „Kategorie" → „Cycling Jerseys" | Nur Trikots |
| 8.4 | Filter „Preis": von 100, bis 200 | Nur Produkte in der Preisspanne |
| 8.5 | Spalte „Preis bis" ein-/ausblenden | Funktioniert |
| 8.6 | Sortierung nach „Bewertung" | Höchste zuerst (10), niedrigste zuletzt |

### 🔹 9. CSV-Import

| # | Was tun? | Erwartung |
|---|---|---|
| 9.1 | Klick „CSV-Import" oben rechts | Modal öffnet sich |
| 9.2 | CSV-Datei hochladen: `docs/beispiel-imports/wettbewerbsprodukte-beispiel.csv` | Spalten werden automatisch erkannt |
| 9.3 | Spalten-Mapping prüfen (sollte stimmen) | Bestätigen |
| 9.4 | Import starten | Notification: „3 Zeilen importiert" |
| 9.5 | Liste neu laden | Drei neue Produkte: Gore C5, Pearl Izumi Quest, Salomon Sense |
| 9.6 | Eines der importierten Produkte öffnen | Marke + Kategorie sind verlinkt, Materialien als Tags |

> 💡 **Wichtig:** Marken und Kategorien, die in der CSV neu vorkommen, werden **automatisch angelegt** (z.B. „Gore Wear", „Pearl Izumi", „Salomon").

### 🔹 10. Bilder-Verwaltung

| # | Was tun? | Erwartung |
|---|---|---|
| 10.1 | Produkt mit Bildern öffnen → Tab „Bilder" | Vorschauen sichtbar |
| 10.2 | Bilder per Drag & Drop umsortieren | Reihenfolge bleibt erhalten |
| 10.3 | Bild bearbeiten (Stift-Icon) → zuschneiden | Crop-Editor öffnet sich |
| 10.4 | Bild löschen | Verschwindet aus Vorschau |
| 10.5 | Mehr als 10 Bilder hochladen versuchen | Wird blockiert (Max 10) |

---

## ✅ Phase 3 – Lieferantenverwaltung testen

**Pfad im Menü:** Lieferanten → Lieferanten / Lieferanten-Produkte

### 🔹 11. Lieferanten anlegen & Stammdaten

| # | Was tun? | Erwartung |
|---|---|---|
| 11.1 | Liste „Lieferanten" öffnen | 2 vorgeseedete Firmen: Textiles Pro Portugal (PT, 9/10), Sofia Garments (TR, 7/10) |
| 11.2 | Spalten „Kontakte" und „Produkte" | Jeweils Anzahl als Badge |
| 11.3 | Klick „Neu" → Firmenname leer lassen → Speichern | Validierungsfehler „required" |
| 11.4 | Firmenname „Alpine Outdoor Ltd", Land „IT", Bewertung 11 | Validierung blockt Bewertung >10 |
| 11.5 | Bewertung auf 8 setzen, Adresse + Notiz eintragen, speichern | Lieferant erscheint in Liste |
| 11.6 | Filter „Aktiv-Status" → „Nur aktive" | Funktioniert |

### 🔹 12. Ansprechpartner (Contacts RelationManager)

| # | Was tun? | Erwartung |
|---|---|---|
| 12.1 | „Textiles Pro Portugal" öffnen | Detail-Seite mit Sections „Stammdaten", „Bewertung & Notizen" und darunter **Ansprechpartner** + **Produkte** |
| 12.2 | Unter „Ansprechpartner" → „Ansprechpartner hinzufügen" → Name „Paulo", Rolle „Export Manager", Email, Telefon | Wird gespeichert, in Liste |
| 12.3 | Auf E-Mail in der Tabelle klicken | Copy-Icon → kopiert in Zwischenablage |
| 12.4 | Ansprechpartner bearbeiten | Form öffnet sich vorbefüllt |
| 12.5 | E-Mail „nicht-gültig" eintragen | Validierungsfehler |
| 12.6 | Ansprechpartner löschen | Bestätigung, dann weg |

### 🔹 13. Lieferanten-Produkte (inline im Lieferanten)

| # | Was tun? | Erwartung |
|---|---|---|
| 13.1 | Weiter im Lieferanten-Detail: „Produkte des Lieferanten" sichtbar | 1 Produkt (z.B. „Pro Summer Jersey PT-001") mit EK 18.50 €, VK 69 €, MOQ 100 |
| 13.2 | „Produkt hinzufügen" → Name, Kategorie, EK 20, VK 60, MOQ 50 | Wird gespeichert |
| 13.3 | Produkt löschen | Verschwindet |

### 🔹 14. Lieferanten-Produkte (als eigener Menüpunkt mit Tabs)

**Pfad:** Lieferanten → Lieferanten-Produkte

| # | Was tun? | Erwartung |
|---|---|---|
| 14.1 | Liste öffnen | Alle Lieferanten-Produkte übergreifend sichtbar (mindestens die 2 vorgeseedete) |
| 14.2 | Klick „Neues Lieferanten-Produkt" | Formular mit **4 Tabs**: Allgemein, Preis & Konditionen, Eigenschaften, Bilder |
| 14.3 | Tab „Allgemein": Name + Lieferant wählen (Pflichtfelder) | Andere Felder optional |
| 14.4 | Tab „Preis & Konditionen": EK/VK/MOQ | Werden gespeichert |
| 14.5 | Tab „Eigenschaften": Materialien/Farben/Größen als Tags | Komma → neuer Tag |
| 14.6 | Tab „Bilder": 2 Bilder hochladen | Vorschau sichtbar |
| 14.7 | Filter „Lieferant" → Mehrere auswählen | Zeigt nur Produkte dieser Lieferanten |
| 14.8 | Filter „Kategorie" | Funktioniert |
| 14.9 | Sortierung nach EK-Preis | Funktioniert |

### 🔹 15. CSV-Import für Lieferanten-Produkte

| # | Was tun? | Erwartung |
|---|---|---|
| 15.1 | Liste „Lieferanten-Produkte" → „CSV-Import" | Modal öffnet sich |
| 15.2 | Beispieldatei `docs/beispiel-imports/lieferanten-produkte-beispiel.csv` hochladen | Spalten automatisch erkannt |
| 15.3 | Import starten | Notification: „3 Zeilen importiert" |
| 15.4 | Liste neu laden | 3 neue Produkte, inkl. *neuer* Lieferant „Alpine Outdoor Ltd" (automatisch angelegt!) |
| 15.5 | Neu-angelegten Lieferanten öffnen | Ist aktiv, Name übernommen |

> 💡 **Clever:** Auch Lieferanten werden automatisch angelegt, wenn sie in der CSV neu sind — genau wie bei den Wettbewerbsprodukten.

### 🔹 16. Cascade-Delete (wichtig!)

| # | Was tun? | Erwartung |
|---|---|---|
| 16.1 | Einen Lieferanten mit 2 Kontakten + 1 Produkt anlegen | OK |
| 16.2 | Lieferanten löschen → Bestätigen | Kontakte UND Produkte verschwinden mit (Cascade) |

---

## ✅ Phase 4 – Bewertungssystem testen

**Menü:** Bewertungen → Alle Bewertungen

> Das Bewertungssystem ist **polymorph**: die gleiche Bewertungs-Logik funktioniert
> für Wettbewerbsprodukte UND Lieferanten-Produkte (und später Entwicklungsprodukte).

### 🔹 17. Übersicht aller Bewertungen

| # | Was tun? | Erwartung |
|---|---|---|
| 17.1 | „Alle Bewertungen" öffnen | Mindestens 5 Bewertungen vorhanden (vorgeseedet) |
| 17.2 | Spalte „Objekt-Typ" | Zeigt „Wettbewerber" oder „Lieferant" als Badge |
| 17.3 | Spalte „Dimension" | Entweder Dimension-Name oder „Gesamt" als Platzhalter |
| 17.4 | Spalte „Art" | **Intern** (blau) oder **Extern** (gelb) farbig |
| 17.5 | Spalte „Score" | Als „x/10" formatiert |
| 17.6 | Filter „Art" → nur intern | Nur interne Bewertungen |
| 17.7 | Filter „Objekt-Typ" → Wettbewerbsprodukt | Nur Bewertungen zu Wettbewerbsprodukten |
| 17.8 | Filter „Dimension" → „Design" | Nur Design-Bewertungen |

### 🔹 18. Bewertung bei Wettbewerbsprodukt hinzufügen

**Pfad:** Marktanalyse → Wettbewerbsprodukte → (ein Produkt öffnen)

| # | Was tun? | Erwartung |
|---|---|---|
| 18.1 | „Castelli Climber Jersey" öffnen | Am unteren Ende **Tab „Bewertungen"** sichtbar |
| 18.2 | Tab „Bewertungen" öffnen | 3 vorgeseedete Bewertungen (Design, Material, externe Gesamt) |
| 18.3 | „Bewertung hinzufügen" | Formular öffnet sich **ohne** „Objekt-Typ"-Feld (automatisch gesetzt!) |
| 18.4 | Art „intern", Dimension „Verarbeitung", Score 9, Kommentar „Nähte super sauber" | Wird gespeichert |
| 18.5 | Score 11 eingeben | Validierung blockt (1-10) |
| 18.6 | Neue Bewertung ohne Dimension → leer lassen | Als **Gesamt-Bewertung** gespeichert |
| 18.7 | Bewertung bearbeiten | Formular vorausgefüllt |
| 18.8 | Bewertung löschen | Verschwindet |

### 🔹 19. Bewertung bei Lieferanten-Produkt hinzufügen

**Pfad:** Lieferanten → Lieferanten-Produkte → (ein Produkt öffnen) → Tab „Bewertungen"

| # | Was tun? | Erwartung |
|---|---|---|
| 19.1 | „Pro Summer Jersey PT-001" öffnen | Tab „Bewertungen" mit 1 vorgeseedete Bewertung |
| 19.2 | Bewertung hinzufügen (intern, Material, 8, Kommentar) | OK |
| 19.3 | Zurück zur **Übersicht „Alle Bewertungen"** | Neue Bewertung mit Objekt-Typ „Lieferant" taucht auf |

### 🔹 20. Durchschnitts-Bewertung in Listen

| # | Was tun? | Erwartung |
|---|---|---|
| 20.1 | Liste „Wettbewerbsprodukte" | Neue Spalte „⌀ Bewertung" zeigt Schnitt als „x.x/10" |
| 20.2 | Liste „Lieferanten-Produkte" | Gleiche Spalte vorhanden |
| 20.3 | Produkt ohne Bewertung | Zeigt „–" |

### 🔹 21. Bewertung über die eigenständige Resource (quer)

| # | Was tun? | Erwartung |
|---|---|---|
| 21.1 | „Alle Bewertungen" → „Neu" | **Vollständiges** Formular: Objekt-Typ + Objekt + Bewertung |
| 21.2 | Objekt-Typ „Wettbewerbsprodukt" wählen | Feld „Objekt" zeigt nur Wettbewerbsprodukte zur Auswahl |
| 21.3 | Objekt-Typ ändern auf „Lieferanten-Produkt" | Feld „Objekt" leert sich, zeigt jetzt Lieferanten-Produkte |
| 21.4 | Speichern, zurück zum Produkt → Bewertungen-Tab | Neue Bewertung sichtbar |

---

## ✅ Phase 5 – Produkt-Entwicklung testen

**Menü:** Produkt-Entwicklung → Entwicklungs-Pipeline / Finale Produkte

### 🔹 22. Entwicklungs-Pipeline – Liste & Status

| # | Was tun? | Erwartung |
|---|---|---|
| 22.1 | „Entwicklungs-Pipeline" öffnen | 3 vorgeseedete Items: Staeze Pro Summer Jersey v1 (In Ausarbeitung), Staeze Race Bib 2026 (Tech Sheet erstellt), Staeze Daily Tee (Idee) |
| 22.2 | Status-Spalte | Jeder Status hat **eigene Farbe + Icon**: Idee (grau), In Arbeit (blau), Konzept/Tech/Sample/Revised (gelb), Final (grün) |
| 22.3 | Filter „Status" → nur „Idee" | Zeigt nur Staeze Daily Tee |
| 22.4 | Filter „Verantwortlich" → Admin | Zeigt nur meine Items |
| 22.5 | Deadline-Spalte | Falls Deadline überschritten und nicht final → **rot** |

### 🔹 23. Entwicklungs-Item anlegen (4-Tab-Formular)

| # | Was tun? | Erwartung |
|---|---|---|
| 23.1 | „Neu" klicken | Formular mit 4 Tabs: Allgemein / Eigenschaften / Inspiration & Basis / Bilder & Skizzen |
| 23.2 | Tab „Allgemein": Name + Kategorie Pflicht, Status-Dropdown zeigt alle 8 Stati mit Nummern | OK |
| 23.3 | Verantwortlich ist automatisch ich | OK |
| 23.4 | Deadline setzen | Funktioniert |
| 23.5 | Tab „Eigenschaften": Materialien, Farben, Größen als Tags + Zielpreis | Speichert |
| 23.6 | Tab „Inspiration & Basis": Wettbewerbsprodukt + Lieferanten-Produkt verknüpfen | Speichert als n:m-Relation |
| 23.7 | Tab „Bilder & Skizzen": max 15 Uploads | Drag-Sort funktioniert |
| 23.8 | Speichern | Neues Item in Liste |

### 🔹 24. 🎯 AUTOMATISCHER Übergang auf „Finales Produkt"

Das ist das **Kernfeature dieser Phase**: wenn ein Entwicklungs-Item den Status **„Final"** bekommt, wird automatisch ein `FinalProduct` angelegt.

| # | Was tun? | Erwartung |
|---|---|---|
| 24.1 | Unter „Finale Produkte" nachsehen | Liste ist leer |
| 24.2 | Ein beliebiges Entwicklungs-Item öffnen, Status auf **„8. Final"** setzen, speichern | Erfolg |
| 24.3 | Zur Liste „Finale Produkte" wechseln | **Neues finales Produkt erscheint!** Name + Kategorie + Preis wurden übernommen |
| 24.4 | Zurück ins Entwicklungs-Item, nochmal speichern (ohne Änderung) | Kein Duplikat entsteht |
| 24.5 | Entwicklungs-Item-Name ändern und speichern | Finales Produkt **nicht** automatisch geändert – das ist volle Kontrolle für dich |
| 24.6 | Im finalen Produkt: SKU „STZ-ABC123", VK-Preis, Beschreibung nachpflegen | Speichert |

### 🔹 25. Finale Produkte – Liste & Features

| # | Was tun? | Erwartung |
|---|---|---|
| 25.1 | Liste öffnen | Zeigt deine finalen Produkte, sortiert nach Launch-Datum |
| 25.2 | Spalte „Marge %" | Berechnet automatisch: ((VK - EK) / VK) × 100 |
| 25.3 | Klick „Neu" → manuell ein finales Produkt anlegen (ohne Entwicklungs-Item) | Geht auch |
| 25.4 | SKU doppelt eintragen | Validation-Error (muss einzigartig sein) |
| 25.5 | Tab „Bewertungen" unten im Finalen Produkt | Funktioniert genauso wie bei Wettbewerbs-/Lieferanten-Produkten |

### 🔹 26. Bewertungen an Finalen Produkten

| # | Was tun? | Erwartung |
|---|---|---|
| 26.1 | Finales Produkt öffnen → Tab „Bewertungen" | Zunächst leer |
| 26.2 | Bewertung hinzufügen (intern, Dimension Design, 9/10) | Erscheint in Tabelle |
| 26.3 | Zur Übersicht „Alle Bewertungen" → Objekt-Typ-Filter | Option „Finales Produkt" jetzt auch verfügbar |
| 26.4 | Spalte „⌀ Bewertung" in der Finale-Produkte-Liste | Zeigt Durchschnitt an |

### 🔹 27. Status-Workflow in natürlicher Reihenfolge

Durchlaufe mit einem Test-Item alle 8 Stati in Reihenfolge:

| # | Status setzen | Erwartung |
|---|---|---|
| 27.1 | 1. Idee (Default) | Grau, Glühbirne |
| 27.2 | 2. In Ausarbeitung | Blau, Stift |
| 27.3 | 3. Konzept bestätigt | Blau, Häkchen |
| 27.4 | 4. Tech Sheet erstellt | Gelb, Dokument |
| 27.5 | 5. An Lieferant gesendet | Gelb, Papierflieger |
| 27.6 | 6. Sample erhalten | Gelb, Geschenk |
| 27.7 | 7. Überarbeitet | Gelb, Kreispfeil |
| 27.8 | 8. Final | **Grün**, Trophäe → **Finales Produkt wird angelegt** |

---

## 🌍 Spätere Phasen *(Platzhalter)*

### Phase 6 – Dashboard & Suche *(folgt)*
### Phase 7 – Deployment Mittwald *(folgt)*
