# 🔖 Session-State – Staeze PM

> Diese Datei ist der **„wo stehen wir gerade"-Merkzettel** für die nächste Claude-Session.
> Beim Start einer neuen Session bitte lesen (automatisch durch CLAUDE.md-Regel) oder explizit referenzieren.

**Letzte Aktualisierung:** 2026-04-21

---

## 🎯 Aktueller Status

**MVP-Entwicklung komplett abgeschlossen**, Phase 7 (Deployment Mittwald) pausiert und wartet auf User.

### Zahlen

- **9 Commits** auf `main`-Branch (alle lokal, noch nicht gepusht)
- **73 automatische Tests** grün
- **PHPStan Level 5** – 0 Fehler
- **Pint** – alle Files formatiert
- **Site lokal:** http://staeze-pm.test/admin

---

## 📋 Was ist fertig

| Phase | Status | Commit |
|---|---|---|
| 0 Fundament | ✅ | `11b2255` |
| 1 Stammdaten | ✅ | `b3ac116` |
| 2 Market Research | ✅ | `c5e5c59` |
| 3 Supplier Management | ✅ | `b3d22eb` |
| 4 Bewertungssystem (polymorph) | ✅ | `8a90dc4` |
| 5 Produkt-Entwicklung | ✅ | `9f45c3c` |
| 6 Dashboard & Suche | ✅ | `f77e1d7` |
| Top-5-Polish (Pint, PHPStan, CI, Smoketests) | ✅ | `e1b0ecf` |
| Bonus-Features (Export, Reset-Admin, Widget) | ✅ | `d158a80` |
| **7 Deployment Mittwald** | ⏸️ **pausiert** | – |

---

## 🔜 Was als nächstes ansteht

### Priorität 1: Bewertungssystem ausbauen (in TODO.md detailliert)

**A) Multi-Dimension-Bewertung:** In EINEM Formular alle Dimensionen auf einmal
bewerten (Design + Material + Performance + …). Aktuell muss man pro Dimension
einzeln klicken. Details unter `TODO.md` → „Bewertungssystem – Ausbau".

**B) Qualitätskriterien als aktive Checkliste:** Bisher nur Doku-Liste. Ziel:
pro Produkt eine abhakbare Checkliste der Kriterien aus der zugehörigen
Kategorie. Details unter `TODO.md` → „Bewertungssystem – Ausbau".

**C) Produkte nach Kategorie UND Hersteller gruppieren:** In Wettbewerbs- und
Finale-Produkte-Listen umschaltbare Gruppierung nach Kategorie oder Hersteller/Marke
(Wettbewerb: `brand.name`, Lieferant: `supplier.name`). Filament `->groups()`.
Details unter `TODO.md` → „UI-Ausbau: Produkte nach Kategorie gruppieren".

**Reihenfolge:** erst A, dann B, dann C (C ist vergleichsweise klein).

~~D) Bewertungs-Quellen~~ — ✅ erledigt 2026-04-22 (Multi-Select `sources` mit
5 Werten, ersetzt altes intern/extern).

**E) Galerie / Medienmanagement** (groß): Zentrale Bilder-Übersicht mit
Filter nach Quelle/Hersteller/Lieferant/Kategorie. Neue Filament-Page
auf Basis der `media`-Tabelle. Details unter `TODO.md` → „Feature E".

**F) Influencer & Social Media Monitoring** (sehr groß): Neue Entitäten
Influencer + SocialChannel + ChannelMetric + InfluencerPost. 3 Unter-Phasen
(F.1 Stammdaten, F.2 automatisches Monitoring, F.3 Analytics). Details
unter `TODO.md` → „Feature F".

### Phase 7 – Deployment Mittwald

Wartet auf User, der erst folgendes vorbereiten muss:

- [ ] GitHub-Repo anlegen (Vorschlag: `staeze-produktmanagement`, privat)
- [ ] Mittwald: Webspace / Projekt anlegen
- [ ] Mittwald: SSH-Zugang einrichten
- [ ] Mittwald: PostgreSQL-DB bereitstellen (Version prüfen – ideal 16+)
- [ ] Mittwald: PHP 8.3+ aktivieren
- [ ] Mittwald: Domain / Subdomain zuweisen

Danach übernimmt Claude:

- [ ] Remote in Git eintragen + erster Push
- [ ] Secrets (SSH_KEY, DEPLOY_HOST, DEPLOY_PATH, DB_*) in GitHub anlegen
- [ ] `.github/workflows/deploy.yml` schreiben (bauend auf `deploy.sh`)
- [ ] Erste Deployment-Testrunde
- [ ] README ergänzen um Prod-Setup-Anleitung

---

## 🛠️ Bereits vorbereitet für Phase 7

- ✅ `.env.production.example` – Template mit allen Variablen
- ✅ `deploy.sh` – Ausführbares Skript (composer install --no-dev, migrate, caches, storage:link, npm build)
- ✅ `.github/workflows/ci.yml` – CI-Pipeline (Pint + PHPStan + Pest bei Push/PR)

---

## 📁 Dokumentation

| Datei | Inhalt |
|---|---|
| `README.md` | Install, Tech-Stack, Architektur, Troubleshooting |
| `PLAN.md` | Domänen-Modell, Architektur-Prinzipien |
| `TODO.md` | Liste aller Aufgaben (mit Erledigt-Archiv) |
| `CLAUDE.md` | Projekt-Regeln für Claude |
| `SESSION-STATE.md` | **Diese Datei** |
| `docs/MANUELLE-TESTS.md` | ~120 Test-Schritte in Textform |
| `docs/checkliste.html` | **Interaktive HTML-Checkliste** (localStorage, abhakbar) |
| `docs/user-guide.md` | Endnutzer-Handbuch mit 5 Workflows |
| `docs/beispiel-imports/` | CSV-Vorlagen für Import |

---

## 🚀 Schnellstart in neuer Session

```bash
# Terminal
cd /Users/svenk/BEQN_Webprojekte/STZ_staeze-produktmanagement

# Status checken
git log --oneline -5
php artisan test            # sollte 73 passed zeigen

# Site lokal
open http://staeze-pm.test/admin
# Login: admin@admin.com / password

# Test-Checkliste öffnen
open docs/checkliste.html
```

---

## 🧭 Wichtige Konventionen für Claude

1. **Immer Deutsch** antworten (aus `~/CLAUDE.md`)
2. **Immer Tests vor + nach Änderungen** (`php artisan test`)
3. **Pint + PHPStan** müssen grün bleiben
4. **Nach jeder Phase committen** + User fragen zum Abhaken
5. **Nichts in Prod ohne User-OK** (siehe Phase 7)

---

## 💡 Nice-to-haves im Backlog (alles optional)

- Dashboard-Charts (Pie: Entwicklungen pro Status)
- Integration-Test gegen echten Postgres (statt SQLite in-memory)
- Mehrsprachigkeit DE/EN
- Lieferanten-Portal (externer Login)
- KI-Features (Trendanalyse, Bewertungsauswertung)
- Audit Log / Historie
- Variantenmanagement (ERP-Level)
- Laravel Horizon für Queue-Monitoring

Siehe `TODO.md` Abschnitt „Ideen / Backlog" für vollständige Liste.
