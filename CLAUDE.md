# CLAUDE.md – Projektregeln

> Wird von Claude Code automatisch gelesen.
> Globale Regeln aus `~/CLAUDE.md` gelten zusätzlich (Deutsch, Beginner-Mode, etc.).

---

## 🎯 Projekt

**Product Intelligence Platform** – zentrale Software für Marktanalyse, Produktentwicklung und Lieferantenverwaltung im Bereich Sportbekleidung (Cycling, Running, Outdoor).

Volles Briefing: siehe Konversation / `PLAN.md`.

---

## 🧱 Tech-Stack (verbindlich)

- **Laravel 13** (aktuell installiert: 13.5)
- **PHP 8.4+** via **Laravel Herd** (keine Docker-Container für PHP/Node)
- **PostgreSQL 18** (via Herd) als Datenbank
  - ⚠️ Briefing-Konflikt: Punkt 16 nennt MySQL, User hat explizit PostgreSQL gewählt. Immer PostgreSQL nutzen, es sei denn User sagt ausdrücklich um.
- **Filament 5** als Admin-UI (neueste Stable, nicht 3 oder 4!)
- **filament-shield** für Rollen/Rechte (Version muss zu Filament 5 passen)
- **spatie/laravel-medialibrary** für Anhänge
- **Pest** für Tests (nicht PHPUnit direkt)
- Frontend-Erweiterungen (React/Vue) nur wenn ein Modul es braucht – nicht default

---

## 🌐 Umgebungen

- **Lokal:** Laravel Herd, Site-URL `staeze-pm.test`
- **Produktion:** Mittwald, Deployment via GitHub Actions

---

## 🧭 Arbeitsweise (für Claude)

1. **Immer auf Deutsch** antworten (aus globaler Regel).
2. **Beginner-freundlich** – Schritte erklären, Fachbegriffe kurz erläutern.
3. **Plan → TODO → Umsetzung**: Vor größeren Änderungen Plan checken / anpassen.
4. **Nach jeder Code-Änderung** `php artisan test` ausführen, bevor "fertig" gesagt wird.
5. **Neue Features brauchen Tests** (Pest Feature-Tests für Filament Resources).
6. **TODO.md pflegen:** Am Ende jeder Aufgabe fragen, ob abgehakt werden soll.
7. **Vor Destruktivem** (rm, git reset, DB drop) immer nachfragen.

---

## 📐 Architektur-Konventionen

- **Filament-Resources** statt eigener Controller, wo möglich.
- **Models schlank halten** – Business-Logik in `app/Actions/` oder `app/Services/`.
- **Enums** für Status-Workflows (z. B. `DevelopmentStatus`) in `app/Enums/`.
- **Polymorphe Beziehungen** für `Rating`, `Attachment`, `Note`.
- **Form Requests** nur für Nicht-Filament-Endpoints.
- **Tests** liegen in `tests/Feature/` (HTTP) und `tests/Unit/` (Logik).

---

## 📁 Ordnerstruktur (Konventionen)

```
app/
  Actions/          ← Business-Logik (invokable classes)
  Enums/            ← Status, Types
  Filament/         ← Resources, Widgets, Pages
  Models/           ← Eloquent Models (schlank!)
  Services/         ← Externe Services (Imports, etc.)
tests/
  Feature/
  Unit/
docs/
  user-guide.md     ← Endnutzer-Doku
```

---

## 🚫 Nicht machen (post-MVP)

Laut Briefing Punkt 17 **NICHT** im MVP umsetzen:
- Variantenmanagement (ERP-Level)
- Audit Log / Historie
- Aufgaben-/To-do-System im System
- externe Bewertungsquellen-APIs
- zentrales Medienmanagement
- aktive KI

Wenn User das erwähnt: freundlich auf Backlog in TODO.md verweisen.

---

## 🧠 Domänen-Glossar

- **Competitor Product** = Wettbewerbsprodukt (beobachtet am Markt)
- **Development Item** = Designidee / Produkt-in-Entwicklung
- **Final Product** = fertiges Produkt (nach Status "final")
- **Supplier Product** = Produkt, das ein Lieferant anbietet
- **Rating Dimension** = Bewertungs-Achse (Design, Material, ...)
- **Quality Criterion** = konkretes Qualitätsmerkmal (Nahtqualität, Passform, ...)
- **MOQ** = Minimum Order Quantity (Mindestabnahmemenge)
