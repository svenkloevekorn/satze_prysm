# ✅ TODO – Product Intelligence Platform

> Lebende Liste. Erledigtes wandert nach unten.

---

## 🟢 Phase 0 – Fundament  *(fast komplett erledigt)*

- [ ] GitHub-Repo anlegen + erster Push  *(machst du im Nachgang)*
- [ ] README.md (Basis) schreiben  *(folgt am Ende)*

---

## 🟢 Phase 1 – Stammdaten *(erledigt)*

Siehe Erledigt-Bereich unten.

---

## 🟢 Phase 2 – Modul 1: Market Research *(erledigt)*

Siehe Erledigt-Bereich unten.

---

## 🟢 Phase 3 – Modul 3: Supplier Management *(erledigt)*

Siehe Erledigt-Bereich unten.

---

## 🟢 Phase 4 – Bewertungssystem *(erledigt)*

Siehe Erledigt-Bereich unten.

---

## 🟢 Phase 5 – Modul 2: Product Development *(erledigt)*

Siehe Erledigt-Bereich unten.

---

## 🟢 Phase 6 – Dashboard & Suche *(erledigt)*

Siehe Erledigt-Bereich unten.

---

## ⏸️ Phase 7 – Deployment (Mittwald) – **PAUSIERT**

> **Warten auf User:** GitHub-Repo anlegen + Mittwald-Server vorbereiten.

### Sven muss zuerst erledigen:

- [ ] GitHub-Repo anlegen (Empfehlung: `staeze-produktmanagement`, privat)
- [ ] Mittwald: Webspace / Projekt anlegen
- [ ] Mittwald: SSH-Zugang einrichten
- [ ] Mittwald: PostgreSQL-DB bereitstellen (Version prüfen – ideal 16+)
- [ ] Mittwald: PHP 8.3+ aktivieren
- [ ] Mittwald: Domain / Subdomain zuweisen

### Dann (durch Claude):

- [ ] Remote in Git eintragen + erster Push
- [ ] `.env.production` Template
- [ ] GitHub-Actions Workflow `.github/workflows/deploy.yml`
- [ ] SSH-Key + Deployment-Secrets in GitHub anlegen (Sven)
- [ ] Erste Deployment-Testrunde auf Mittwald
- [ ] User-Dokumentation (`docs/user-guide.md`) schreiben
- [ ] README.md: Produktions-Setup ergänzen

---

## 💡 Ideen / Backlog (post-MVP)

- Variantenmanagement
- Audit Log
- Historie je Objekt
- Aufgaben-System
- Externe Bewertungs-APIs (Amazon, Trustpilot)
- Zentrales Medienmanagement
- KI-Trendanalyse
- KI-Bewertungsauswertung
- KI-Produktvorschläge
- API (REST/GraphQL) für externe Integration
- Mehrsprachigkeit (DE/EN)
- Lieferanten-Portal (externer Login)

---

## ✅ Erledigt

### Phase 0 – Fundament *(2026-04-21)*

- [x] Laravel 13.5 (neueste statt 11) im Projektroot installiert
- [x] `.env` auf PostgreSQL umgestellt (Connection läuft)
- [x] PostgreSQL 18 (via Herd) eingerichtet
- [x] Datenbank `staeze_pm` angelegt + Migrations laufen
- [x] Herd-Site `staeze-pm.test` läuft (HTTP 200)
- [x] Git-Repo initialisiert + erster Commit (`11b2255`)
- [x] Pest läuft + 2/2 Tests grün
- [x] Filament 5 (neueste statt 3) installiert
- [x] Admin-User `admin@admin.com` mit super_admin-Rolle
- [x] `filament-shield` installiert + Permissions generiert
- [x] `spatie/laravel-medialibrary` + Filament-Plugin installiert
- [x] App-Sprache auf Deutsch (`APP_LOCALE=de`)

### Phase 1 – Stammdaten *(2026-04-21)*

- [x] Migration + Model + Filament-Resource: `Category` (7 vorgeseedet)
- [x] Migration + Model + Filament-Resource: `Brand`
- [x] Migration + Model + Filament-Resource: `Shop` (4 vorgeseedet)
- [x] Migration + Model + Filament-Resource: `RatingDimension` (5 vorgeseedet: Design, Material, Verarbeitung, Performance, Preis-Leistung)
- [x] Migration + Model + Filament-Resource: `QualityCriterion` (6 vorgeseedet, mit Kategorien-Relation)
- [x] Pivot-Tabelle `category_quality_criterion` (n:m)
- [x] Alle Resources unter Navigations-Gruppe "Stammdaten" gruppiert
- [x] Filter, Suche, deutsche Labels überall
- [x] **14 Tests grün** (Category-Resource + Smoke-Tests aller 4 neuen Resources)
- [x] Doku angelegt: `docs/MANUELLE-TESTS.md` für eigene Browser-Tests

### Phase 2 – Modul 1: Market Research *(2026-04-21)*

- [x] Migration + Model: `CompetitorProduct` (mit Marken/Kategorie-Relation, JSON für Materialien/Farben/Größen, Preisrahmen, Bewertung, Pos/Neg)
- [x] Migration + Model: `ProductShopEntry` (Shop ↔ Produkt mit Preis + Datum + URL + Notiz)
- [x] Filament-Resource: `CompetitorProduct` mit **4-Tab-Formular** (Allgemein, Eigenschaften, Preis & Bewertung, Bilder)
- [x] **RelationManager** „Shop-Einträge" innerhalb des Produkt-Detailviews
- [x] **Bilder-Upload** mit Spatie MediaLibrary (max 10 Bilder, Drag-Sort, Image-Editor)
- [x] **CSV-Import** mit Auto-Anlegen von Marken/Kategorien
- [x] Beispiel-CSV unter `docs/beispiel-imports/wettbewerbsprodukte-beispiel.csv`
- [x] Filter: Marke, Kategorie, Preis-Spanne
- [x] Tabelle: Thumbnail, Marke/Kategorie als Badges, Preis als €, Bewertung als x/10, Anzahl Shops
- [x] 3 Demo-Wettbewerbsprodukte vorgeseedet (Castelli, Rapha, Assos) mit je 2 Shop-Einträgen
- [x] Filament-Imports/Exports/Failed-Imports Tabellen migriert
- [x] Navigation-Gruppe „Marktanalyse"
- [x] **21 Tests grün** (7 neue für CompetitorProduct)
- [x] Test-Doku erweitert: Phase 2 mit 25+ manuellen Test-Schritten

### Phase 3 – Modul 3: Supplier Management *(2026-04-21)*

- [x] Migration + Model: `Supplier` (Name, Land, Adresse, Bewertung 1-10, Notizen, Aktiv)
- [x] Migration + Model: `SupplierContact` (Name, Email, Telefon, Rolle, Notizen – cascade-delete)
- [x] Migration + Model: `SupplierProduct` (EK, VK, MOQ, Materialien/Farben/Größen als JSON, Bilder, Notizen)
- [x] Filament-Resource: `Supplier` mit **2 RelationManagers** (Ansprechpartner + Produkte)
- [x] Filament-Resource: `SupplierProduct` mit **4-Tab-Formular** (Allgemein, Preis & Konditionen, Eigenschaften, Bilder)
- [x] Tabelle: Lieferanten mit Counter-Badges (Kontakte + Produkte)
- [x] Tabelle: Lieferanten-Produkte mit EK/VK/MOQ-Spalten
- [x] **CSV-Import** für Lieferanten-Produkte (legt Lieferanten + Kategorien automatisch an)
- [x] Beispiel-CSV unter `docs/beispiel-imports/lieferanten-produkte-beispiel.csv`
- [x] Filter: Lieferant, Kategorie, Aktiv-Status
- [x] Navigation-Gruppe „Lieferanten"
- [x] 2 Demo-Lieferanten (Textiles Pro Portugal, Sofia Garments) mit Kontakten & Produkten
- [x] **31 Tests grün** (10 neue für Supplier-Modul, inkl. Cascade-Delete-Test)
- [x] Test-Doku erweitert: Phase 3 mit 20+ manuellen Test-Schritten

### Phase 4 – Bewertungssystem *(2026-04-21)*

- [x] Enum `RatingType` (Intern/Extern mit Label + Farbe)
- [x] Migration + Model: `Rating` (polymorph über `ratable_type` + `ratable_id`, mit Dimension, User, Score 1-10, Kommentar, Pos/Neg, Datum)
- [x] Trait `App\Models\Concerns\HasRatings` (wiederverwendbar, mit `averageScore()`)
- [x] Trait eingebunden in `CompetitorProduct` UND `SupplierProduct`
- [x] Morph-Map registriert (`competitor_product`, `supplier_product` statt voller Klassennamen)
- [x] Eigenständige Filament-Resource `Rating` („Alle Bewertungen") unter Navigation „Bewertungen"
- [x] **RelationManager** `RatingsRelationManager` in CompetitorProduct + SupplierProduct
- [x] Durchschnitts-Score-Spalte in beiden Produkt-Listen (`ratings_avg_score`)
- [x] Formulare: Art (intern/extern) farbig, Dimension optional (leer = Gesamtbewertung), User automatisch gesetzt
- [x] Filter: Art, Objekt-Typ, Dimension
- [x] 5 Demo-Bewertungen vorgeseedet (Castelli 3×, Rapha 1×, Lieferanten-Produkt 1×)
- [x] **41 Tests grün** (10 neue für Ratings, inkl. polymorphe Relationen, Validierung, AverageScore)
- [x] Test-Doku erweitert: Phase 4 mit 20+ manuellen Test-Schritten

### Phase 5 – Modul 2: Product Development *(2026-04-21)*

- [x] Enum `DevelopmentStatus` mit 8 Stati, jeweils Label + Farbe + Heroicon
- [x] Migration + Model `DevelopmentItem` (Name, Kategorie, User, Status, Materialien/Farben/Größen JSON, Zielpreis, Deadline, Bilder)
- [x] Migration + Model `FinalProduct` (SKU, EK/VK, Launch-Datum, Beschreibung, Bilder, mit `HasRatings`)
- [x] Pivot-Tabellen `competitor_product_development_item` + `development_item_supplier_product` (n:m)
- [x] **Auto-Anlegen des FinalProduct** bei Status-Wechsel auf „final" (im `booted()` Model-Hook)
- [x] Filament-Resource `DevelopmentItem` mit **4-Tab-Formular** (Allgemein / Eigenschaften / Inspiration & Basis / Bilder)
- [x] Filament-Resource `FinalProduct` mit Sections + **Marge %** berechnet in Tabelle
- [x] RelationManager Ratings auch für FinalProduct
- [x] Status-Badges mit individueller Farbe + Icon pro Status
- [x] Deadline-Warnung (rot wenn überschritten)
- [x] Rating-Formular um `final_product` erweitert (polymorph)
- [x] Navigation-Gruppe „Produkt-Entwicklung"
- [x] 3 Demo-Entwicklungs-Items vorgeseedet (Staeze Pro Summer Jersey v1, Race Bib 2026, Daily Tee)
- [x] Inspirations-Verknüpfungen: Staeze Jersey ← Castelli, Staeze Bib ← Rapha
- [x] **51 Tests grün** (10 neue für DevelopmentItem + FinalProduct, inkl. Auto-Finalisierung-Test)
- [x] Test-Doku erweitert: Phase 5 mit 25+ manuellen Test-Schritten

### Phase 6 – Dashboard & Suche *(2026-04-21)*

- [x] **4 Dashboard-Widgets:**
  - `StatsOverview`: 4 Kennzahlen-Karten mit 60s-Polling
  - `OffeneEntwicklungenWidget`: max. 5 nicht-finale Items mit Status-Badges + Deadline-Warnung
  - `UnbewerteteProdukteWidget`: Produkte ohne Bewertung
  - `LetzteAenderungenWidget`: zuletzt geänderte Wettbewerbsprodukte
- [x] Widgets im AdminPanelProvider registriert
- [x] **Globale Suche** (⌘+K / Strg+K) in allen 6 Resources aktiviert
- [x] `getGloballySearchableAttributes` + `getGlobalSearchResultDetails` für alle Resources
- [x] **Bugfix:** Postgres `DISTINCT`-Fehler bei JSON-Spalten in DevelopmentItem-Edit-Seite (Select auf id+name limitiert)
- [x] Regression-Test für JSON-Bug
- [x] **58 Tests grün** (7 neue, davon 6 für Widgets + 1 Regression-Test)
- [x] Test-Doku erweitert: Phase 6 mit 10+ manuellen Test-Schritten
