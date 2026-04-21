# 📐 Plan – Product Intelligence Platform

> Stand: 2026-04-21 · Version: 0.1 (MVP)

---

## 1. Tech-Stack (final)

| Schicht | Technologie | Begründung |
|---|---|---|
| Framework | Laravel 11 (neueste) | moderner Standard, LTS-nah |
| PHP | 8.3+ (via Herd) | Herd-Default |
| Datenbank | PostgreSQL 16 | besser für strukturierte Daten, JSON-Support, zukunftssicher (Konflikt zum Briefing s. CLAUDE.md) |
| Admin-UI | Filament 3 | schnellstes CRUD, deckt ~80% des MVP ab |
| Frontend-Extras | Optional später React/Vue | nur wenn einzelne Module es brauchen |
| Auth | Filament-eigenes Auth + `filament-shield` (Rollen) | einfach, erweiterbar |
| Storage | Lokales Filesystem (MVP) | später S3/R2 möglich |
| Queue | Datenbank-Queue (MVP) | keine extra Infra |
| Tests | Pest | Laravel-Standard, lesbar |
| Deployment | Mittwald via GitHub Actions | laut Briefing |
| Lokale Umgebung | Laravel Herd (nativ) | PHP + Node nativ, kein Docker nötig |
| DB lokal | PostgreSQL via Herd (falls verfügbar) oder Docker-Container nur für DB | je nach Herd-Version |

---

## 2. Domänen-Modell (Kern-Entities)

```
User ──┐
       ├── Brand ─────────── CompetitorProduct ──┬── ProductShopEntry ── Shop
       │                                         │
Category ───┬── CompetitorProduct                │
            ├── DevelopmentItem ── FinalProduct  │
            ├── SupplierProduct                  │
            └── QualityCriterion                 │
                                                 │
Supplier ── SupplierContact                      │
         └── SupplierProduct                     │
                                                 │
RatingDimension ── Rating ───────────────────────┘
                    │
                    └── (polymorph: Competitor/Supplier/Final)
```

**Kern-Beziehungen:**
- `Category` ist zentrale Taxonomie, wird von fast allem referenziert
- `Rating` ist **polymorph** (`ratable_type`, `ratable_id`) → funktioniert für Wettbewerbs-, Lieferanten- und Finalprodukte
- `Attachment` ist **polymorph** (`attachable_type`, `attachable_id`) → Bilder/Dateien an beliebigen Objekten
- `Note` ist **polymorph** (`notable_type`, `notable_id`)
- `DevelopmentItem` → `FinalProduct` (1:1 bei Status "final")
- `DevelopmentItem` ↔ `CompetitorProduct` (n:m, "inspiriert von")
- `DevelopmentItem` ↔ `SupplierProduct` (n:m, "basiert auf")

---

## 3. Module & Reihenfolge der Umsetzung

### Phase 0 – Fundament (vor allem anderen)
- Laravel-Projekt aufsetzen
- Herd + PostgreSQL lauffähig
- Filament 3 installiert
- Auth (Filament) + Basis-User
- Git + GitHub-Repo
- Tests-Setup (Pest)

### Phase 1 – Stammdaten
- Categories (CRUD)
- Brands (CRUD)
- Shops (CRUD)
- RatingDimensions (CRUD)
- QualityCriteria (CRUD, mit Category-Zuordnung)

### Phase 2 – Modul 1: Market Research
- CompetitorProduct (CRUD inkl. Bilder, Preise, Pos/Neg)
- ProductShopEntry (Zwischentabelle mit Preis+Datum)
- Import: CSV für CompetitorProducts

### Phase 3 – Modul 3: Supplier Management *(vor Product Dev, da referenziert)*
- Supplier (CRUD)
- SupplierContact (CRUD, Relation)
- SupplierProduct (CRUD)
- Import: CSV für SupplierProducts

### Phase 4 – Bewertungssystem
- Rating-Engine (polymorph)
- Score + Kommentar + Pos/Neg
- Zuordnung Rating → Produkt
- Filter/Übersicht

### Phase 5 – Modul 2: Product Development
- DevelopmentItem (inkl. Status-Workflow)
- Verknüpfungen zu Wettbewerbs-/Lieferantenprodukten
- Übergang `final` → FinalProduct

### Phase 6 – Dashboard & Suche
- Filament-Dashboard-Widgets (4 Widgets)
- globale Suche (Filament-eigene)
- Spalten-Filter je Resource

### Phase 7 – Deployment
- GitHub-Actions-Workflow für Mittwald
- `.env.production` Template
- Deployment-Doku im README

---

## 4. Architektur-Prinzipien

- **Filament-Resources** statt eigener Controller, solange möglich
- **Form Requests** nur für nicht-Filament-Endpoints (z. B. API später)
- **Models schlank**, Logik in `Actions/` oder `Services/`
- **Enums** für Status-Workflows (`DevelopmentStatus`)
- **Polymorphe Beziehungen** für Rating/Attachment/Note
- **Alle Änderungen mit Tests** (Pest, Feature-Tests für Filament Resources)

---

## 5. Offene Fragen (vor Start zu klären)

1. ❓ **DB endgültig PostgreSQL?** (Briefing sagt MySQL)
2. ❓ **Mehrsprachigkeit?** DE only oder DE+EN?
3. ❓ **Auth-Details:** Wer darf rein? Nur interne User, oder später Lieferanten-Login?
4. ❓ **Rollen/Rechte:** Brauchen wir von Anfang an Rollen (Admin / Editor / Viewer)?
5. ❓ **GitHub-Repo:** Existiert schon, oder neu anlegen?
6. ❓ **Mittwald:** Hast du schon Zugangsdaten / SSH-Key / Deploy-Target?

---

## 6. Was NICHT im MVP ist (laut Briefing)

- Variantenmanagement (Größen/Farben als Varianten-Objekte)
- Audit Log / Historie
- Aufgaben-/To-do-System im System
- externe Bewertungs-APIs
- zentrales Medienmanagement
- aktive KI
