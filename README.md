# Staeze PM – Product Intelligence Platform

Interne Software zur Marktanalyse, Produktentwicklung und Lieferantenverwaltung im Bereich Sportbekleidung (Cycling, Running, Outdoor).

**Ziel:** Datenbasierte Produktentwicklung statt Bauchgefühl.

---

## 🧱 Tech-Stack

| Schicht | Technologie |
|---|---|
| Framework | Laravel 13 |
| PHP | 8.4 (via Laravel Herd lokal) |
| Datenbank | PostgreSQL 18 |
| Admin-UI | Filament 5 |
| Rollen & Rechte | `bezhansalleh/filament-shield` + Spatie Permission |
| Anhänge | `spatie/laravel-medialibrary` |
| Tests | Pest 4 |
| Deployment | Mittwald via GitHub Actions |

---

## 🚀 Lokales Setup

### Voraussetzungen

- [Laravel Herd](https://herd.laravel.com/) (enthält PHP 8.4, PostgreSQL 18, Composer, Node)
- Git

### Installation

```bash
git clone <repo-url> staeze-produktmanagement
cd staeze-produktmanagement

# Abhängigkeiten
composer install
npm install

# Umgebung vorbereiten
cp .env.example .env
php artisan key:generate

# Datenbank anlegen (einmalig)
psql -h 127.0.0.1 -p 5432 -U postgres -c "CREATE DATABASE staeze_pm;"

# Migrationen + Testdaten
php artisan migrate --seed

# Admin-User anlegen
php artisan make:filament-user
# (oder: admin@admin.com / password wird per Seeder erstellt)

# Shield: Rollen & Permissions generieren
php artisan shield:generate --all --panel=admin

# Herd-Site verlinken
herd link staeze-pm
```

App läuft dann auf **http://staeze-pm.test** – Admin-Panel unter **http://staeze-pm.test/admin**.

Login: `admin@admin.com` / `password`

### Frontend-Assets

```bash
npm run dev    # Dev-Modus mit Watcher
npm run build  # Produktions-Build
```

### Tests

```bash
php artisan test
```

Erwartung: **58+ Tests grün**.

---

## 📁 Projekt-Struktur

```
app/
  Enums/               # DevelopmentStatus, RatingType
  Filament/
    Imports/           # CSV-Importer
    Resources/         # 9 Filament-Resources (alle CRUDs)
    Widgets/           # Dashboard-Widgets
  Models/
    Concerns/          # HasRatings Trait
  Providers/           # Gate::before, Morph-Map
database/
  migrations/          # DB-Schema
  seeders/             # Demo-Daten
docs/
  MANUELLE-TESTS.md    # ~100 Browser-Test-Schritte
  beispiel-imports/    # CSV-Vorlagen
  user-guide.md        # Endnutzer-Dokumentation
tests/
  Feature/Filament/    # Pest-Tests
```

---

## 📐 Fachliches Modell (Überblick)

```
Stammdaten: Category · Brand · Shop · RatingDimension · QualityCriterion
Marktanalyse: CompetitorProduct (↔ Shop via ProductShopEntry)
Lieferanten: Supplier (hat Contacts + SupplierProducts)
Entwicklung: DevelopmentItem (8 Stati) → FinalProduct (bei "final" auto-erstellt)
Bewertungen: Rating (polymorph an allen Produkt-Typen)
```

**Kernfeature:** Setzt man ein Entwicklungs-Item auf Status „Final", wird automatisch
ein `FinalProduct` erzeugt (Model-Hook in `booted()`).

---

## 📥 CSV-Import

Beispiel-CSV-Dateien liegen unter `docs/beispiel-imports/`:

- `wettbewerbsprodukte-beispiel.csv`
- `lieferanten-produkte-beispiel.csv`

Im Admin-Panel oben rechts auf **„CSV-Import"**. Marken, Lieferanten und Kategorien werden bei Bedarf automatisch angelegt.

---

## 🔐 Rollen & Rechte

- **super_admin** – kann alles (umgeht alle Permission-Checks via `Gate::before`)
- Weitere Rollen lassen sich über das Admin-Panel → **Filament Shield → Roles** anlegen

---

## 🌍 Produktions-Deployment (Mittwald)

> ⏸️ **In Vorbereitung.** Siehe `TODO.md` – Phase 7.

Grobe Schritte (werden ergänzt):

1. GitHub-Repo anlegen, lokal pushen
2. Mittwald-Webspace aufsetzen (PostgreSQL 16+, PHP 8.3+)
3. SSH-Key + Deployment-Secrets in GitHub konfigurieren
4. GitHub-Actions Workflow (`.github/workflows/deploy.yml`) triggert bei Push auf `main`
5. Auf Server: `composer install --no-dev`, `migrate --force`, `optimize`, Shield cachen

Detail-Anleitung folgt nach Vorbereitung des Servers.

---

## 🧪 Manuelles Testen

Siehe [`docs/MANUELLE-TESTS.md`](docs/MANUELLE-TESTS.md) – Schritt-für-Schritt-Anleitung für alle Module (~100 Test-Schritte).

---

## 📖 Nutzer-Dokumentation

Siehe [`docs/user-guide.md`](docs/user-guide.md).

---

## ℹ️ Konventionen

- **Immer** `php artisan test` vor „fertig" (CLAUDE.md-Regel)
- **Neue Features** brauchen Tests
- **Änderungen an TODO.md** einchecken

---

## 🆘 Troubleshooting

Siehe Abschnitt „Was tun, wenn was nicht klappt?" in `docs/MANUELLE-TESTS.md`.

Typische Fälle:
- **500 bei Edit-Seite mit Postgres** → Cache leeren: `php artisan optimize:clear`
- **403 im Admin** → `php artisan tinker --execute="\App\Models\User::where('email','admin@admin.com')->first()->syncRoles(['super_admin']);"`
- **DB neu aufbauen** → `php artisan migrate:fresh --seed`
