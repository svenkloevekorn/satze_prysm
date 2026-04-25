# Prysm – Product Intelligence Platform

> **Staeze : Prysm** – „From insight to product."

Interne Software zur Marktanalyse, Produktentwicklung und Lieferantenverwaltung für die Sportbekleidungs-Marke **Staeze** (Cycling, Running, Outdoor).

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
git clone git@github.com:svenkloevekorn/satze_prysm.git prysm
cd prysm

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
herd link prysm
```

App läuft dann auf **http://prysm.test** – Admin-Panel unter **http://prysm.test/admin**.

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

Erwartung: **114+ Tests grün** (Stand 2026-04-24, Phase 10).

---

## 📁 Projekt-Struktur

```
app/
  Enums/               # DevelopmentStatus, RatingType, SocialPlatform …
  Filament/
    Imports/           # CSV-Importer
    Pages/             # Settings, Medien-Galerie
    Resources/         # 14 Filament-Resources (alle CRUDs)
    Widgets/           # 7 Dashboard-Widgets (inkl. „Letzte CSV-Imports")
  Models/
    Concerns/          # HasRatings, HasQualityChecks
  Providers/           # Gate::before, Morph-Map
  Settings/            # Spatie-Settings-Klassen
database/
  migrations/          # DB-Schema
  seeders/             # Demo-Daten
docs/
  handbuch.html        # Endnutzer-Handbuch (Tailwind, gelayoutet)
  MANUELLE-TESTS.md    # ~220 Browser-Test-Schritte
  checkliste.html      # Interaktive HTML-Checkliste mit localStorage
  STRATEGIE-ROADMAP.md # Shop-Strategie, ERP, Satelliten-Software
  beispiel-imports/    # CSV-Vorlagen
tests/
  Feature/Filament/    # Pest-Tests (Resources, Widgets, Bulk-Edit, Impersonation)
```

---

## 📐 Fachliches Modell (Überblick)

```
Stammdaten:    Category · Brand · Shop · RatingDimension · QualityCriterion
Marktanalyse:  CompetitorProduct (↔ Shop via ProductShopEntry)
Lieferanten:   Supplier (hat Contacts + SupplierProducts)
Entwicklung:   DevelopmentItem (8 Stati) → FinalProduct (bei „final" auto-erstellt)
Bewertungen:   Rating (polymorph an allen Produkt-Typen)
Qualität:      QualityCheck (polymorph, Checkliste pro Kategorie)
Social:        Influencer · SocialChannel (polymorph) · ChannelMetric
Querschnitt:   Tags · ActivityLog · Settings · Nachhaltigkeits-Felder
Auth:          User · Rollen (Shield) · 2FA (Breezy) · Impersonation
```

**Kernfeature Produkt-Lifecycle:** Setzt man ein Entwicklungs-Item auf Status „Final",
wird automatisch ein `FinalProduct` erzeugt (Model-Hook in `booted()`).

**Phase 10 (Support-Tools):** User-Impersonation („Anmelden als" für super_admin),
Bulk-Edit-Actions in Produkt-Tabellen, Dashboard-Widget „Letzte CSV-Imports" mit
Fehler-CSV-Download.

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

> ⏸️ **Wartet auf Server-Setup.** Siehe `TODO.md` – Phase 7.

**Aktueller Stand (2026-04-25):**

- ✅ GitHub-Repo angelegt: `git@github.com:svenkloevekorn/satze_prysm.git`
- ✅ Lokale `.env.production.example` für Mittwald + PostgreSQL vorbereitet
- ✅ CI-Workflow auf manuellen Trigger umgestellt (läuft nicht bei jedem Push)
- ⏸️ Mittwald-Server mit PostgreSQL-Tarif wird neu eingerichtet

**Geplanter Ablauf, sobald Server steht:**

1. SSH-Key auf Mittwald hinterlegen (Alias `prysm` in `~/.ssh/config`)
2. PostgreSQL-DB im mStudio anlegen
3. PHP 8.4 für die Domain einstellen
4. Composer auf dem Server bereitstellen
5. Code-Verzeichnis vorbereiten + `.env` mit echten DB-Credentials hinterlegen
6. GitHub-Secrets eintragen (`MITTWALD_HOST`, `MITTWALD_USER`, `MITTWALD_SSH_KEY`, `MITTWALD_DEPLOY_PATH`)
7. `.github/workflows/deploy.yml` schreiben (CI + rsync-Deploy + remote `migrate --force`)
8. Erster Test-Push, danach automatischer Deploy bei jedem Push auf `main`

**Strategische Roadmap:** siehe `docs/STRATEGIE-ROADMAP.md`
- **Shop:** zuerst Shopify, später ggf. Wechsel auf Shopware
- **ERP:** anbinden statt selbst bauen (JTL-Wawi oder ähnlich)

---

## 🧪 Manuelles Testen

Siehe [`docs/MANUELLE-TESTS.md`](docs/MANUELLE-TESTS.md) – Schritt-für-Schritt-Anleitung für alle Module (~250 Test-Schritte).
Interaktiv abhaken: [`docs/checkliste.html`](docs/checkliste.html).

**Feature-Pitches (Entscheidungs-Vorlagen):**
- [`docs/pitch-kampagnen-manager.html`](docs/pitch-kampagnen-manager.html)
- [`docs/pitch-marketing-kalender.html`](docs/pitch-marketing-kalender.html)
- [`docs/pitch-variantenmanagement.html`](docs/pitch-variantenmanagement.html)

---

## 📖 Nutzer-Dokumentation

Endnutzer-Handbuch: **[`docs/handbuch.html`](docs/handbuch.html)** (Tailwind-Layout, im Browser öffnen).

Ältere Markdown-Variante: [`docs/user-guide.md`](docs/user-guide.md).

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
