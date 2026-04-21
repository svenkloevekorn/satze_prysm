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

## 🟡 Phase 2 – Modul 1: Market Research

- [ ] Migration + Model: `CompetitorProduct`
- [ ] Migration: `product_shop_entries` (Shop ↔ Produkt, mit Preis + Datum)
- [ ] Migration: polymorphe `attachments` Tabelle
- [ ] Filament-Resource: `CompetitorProduct` inkl. Bilder, Shop-Einträge, Pos/Neg
- [ ] CSV-Import für CompetitorProducts
- [ ] Tests

---

## 🟡 Phase 3 – Modul 3: Supplier Management

- [ ] Migration + Model + Resource: `Supplier`
- [ ] Migration + Model + Resource: `SupplierContact` (Relation zu Supplier)
- [ ] Migration + Model + Resource: `SupplierProduct`
- [ ] CSV-Import für SupplierProducts
- [ ] Tests

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
