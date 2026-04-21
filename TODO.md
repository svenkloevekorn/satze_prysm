# ✅ TODO – Product Intelligence Platform

> Lebende Liste. Erledigtes wandert nach unten.

---

## 🟢 Phase 0 – Fundament

- [ ] Laravel 11 im Ordner `STZ_staeze-produktmanagement` installieren
- [ ] `.env` einrichten (PostgreSQL-Connection)
- [ ] PostgreSQL 16 lokal einrichten (Herd oder Docker)
- [ ] Datenbank `staeze_pm` anlegen + Migration-Test
- [ ] Herd-Site einrichten (`staeze-pm.test`)
- [ ] Git-Repo initialisieren + `.gitignore` prüfen
- [ ] GitHub-Repo anlegen + erster Push
- [ ] Pest installieren + erster Dummy-Test grün
- [ ] Filament 3 installieren
- [ ] ersten Admin-User anlegen (`php artisan make:filament-user`)
- [ ] `filament-shield` (Rollen/Rechte) installieren
- [ ] `spatie/laravel-medialibrary` für Anhänge installieren
- [ ] README.md (Basis) schreiben

---

## 🟡 Phase 1 – Stammdaten

- [ ] Migration + Model + Filament-Resource: `Category`
- [ ] Migration + Model + Filament-Resource: `Brand`
- [ ] Migration + Model + Filament-Resource: `Shop`
- [ ] Migration + Model + Filament-Resource: `RatingDimension`
- [ ] Migration + Model + Filament-Resource: `QualityCriterion` (mit Category-Relation)
- [ ] Seeder mit MVP-Kategorien (Cycling Jerseys, Bib Shorts, etc.)
- [ ] Tests für alle Stammdaten-CRUDs

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

<!-- Abgehakte Punkte wandern hierher -->
