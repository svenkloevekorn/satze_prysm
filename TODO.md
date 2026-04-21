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

## 🟡 Phase 4 – Bewertungssystem

- [ ] Migration: polymorphe `ratings` Tabelle (ratable_type, ratable_id)
- [ ] Model `Rating` + Relation zu Dimension
- [ ] RelationManager in Filament für Ratings
- [ ] Übersichts-Seite: unbewertete Produkte
- [ ] Tests

---

## 🟡 Phase 5 – Modul 2: Product Development

- [ ] Enum `DevelopmentStatus` (8 Stati)
- [ ] Migration + Model + Resource: `DevelopmentItem`
- [ ] Migration: `development_competitor_product` (n:m)
- [ ] Migration: `development_supplier_product` (n:m)
- [ ] Übergang "final" → `FinalProduct`
- [ ] Tests

---

## 🟡 Phase 6 – Dashboard & Suche

- [ ] Dashboard-Widget: letzte Produkte
- [ ] Dashboard-Widget: offene Entwicklungen
- [ ] Dashboard-Widget: unbewertete Produkte
- [ ] Dashboard-Widget: letzte Änderungen
- [ ] Filament Global Search konfigurieren

---

## 🟡 Phase 7 – Deployment (Mittwald)

- [ ] `.env.production` Template
- [ ] GitHub-Actions Workflow `.github/workflows/deploy.yml`
- [ ] SSH-Key + Deployment-Secrets in GitHub anlegen
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
