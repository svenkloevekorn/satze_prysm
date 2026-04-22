# 🔖 Session-State – STAEZE : Prysm

> Merkzettel für die nächste Claude-Session. Zu Beginn lesen.

**Letzte Aktualisierung:** 2026-04-22 (Ende der Session: Breezy/2FA)

---

## 🎯 Aktueller Status

Produktname steht fest: **Prysm** (Header: „STAEZE : Prysm", Claim: „From insight to product").
Staeze = Marke des Users. Claim gehört **NICHT** ins Header-Logo (nur in Login-Subtitle/Marketing).

MVP + alle 5 Feature-Pakete (C, A, E, B, F.1/F.2/F.3) fertig.
**Phase 8 Querschnittsfunktionen fertig:** Admin-Settings, Audit Log, Tagging, Nachhaltigkeit.
**Phase 9 Auth/User-Mgmt fertig:** UserResource + filament-breezy (2FA, Browser-Sessions, „Mein Profil").

Phase 7 (Deployment Mittwald) pausiert, wartet auf GitHub-Repo + Mittwald-Server.

### Zahlen

- **35+ Commits** auf `main`-Branch (alle lokal, noch nicht gepusht)
- **102 automatische Tests** grün
- **PHPStan Level 5** – 0 Fehler
- **Pint** – alle Files formatiert
- **Site lokal:** http://staeze-pm.test/admin

### Branding / Konventionen

- **Filament Panel Brand:** `->brandName('STAEZE : Prysm')` in `AdminPanelProvider.php`
- **APP_NAME** (`.env` lokal): "Prysm" – `.env` ist gitignored, in Produktion manuell setzen
- **Navigation Groups** (in Reihenfolge, alle per Default eingeklappt):
  Dashboard · Marktanalyse · Lieferanten · Produkt-Entwicklung · Bewertungen · Medien · Social Media · **System** (am Ende)
- **Gruppe „System"** enthält: Benutzer · Einstellungen · Kategorien · Bewertungs-Dimensionen · Qualitätskriterien · Rollen (Filament Shield)

### Entitäten

**Stammdaten:** Category (2-Ebenen), Brand (mit Website+Social), Shop, RatingDimension, QualityCriterion
**Marktanalyse:** CompetitorProduct (+ ProductShopEntry)
**Lieferanten:** Supplier (+ Contact, SupplierProduct)
**Bewertungen:** Rating (polymorph, Multi-Select-Sources)
**Produkt-Entwicklung:** DevelopmentItem → FinalProduct (automatisch bei Status=Final)
**Qualität:** QualityCheck (polymorph, abhakbar pro Kategorie)
**Medien:** Galerie-Page (übergreifende Bild-Übersicht)
**Social Media:** Influencer, SocialChannel (polymorph), ChannelMetric
**User:** eigene UserResource, is_active + last_login_at + LogsActivity

### Installierte Filament-Plugins

- `bezhansalleh/filament-shield` → Rollen/Permissions (unter System)
- `filament/spatie-laravel-tags-plugin` → Tag-Input/Column
- `spatie/laravel-settings` + Filament-Plugin → Admin-Settings
- `spatie/laravel-activitylog` → Audit Log (RelationManager `activitiesAsSubject`)
- `jeffgreco13/filament-breezy` v3.2 → Mein Profil, 2FA (TOTP), Browser-Sessions

---

## 🔜 Was als nächstes ansteht

### Offene hochpriorisierte TODOs (siehe TODO.md)

1. **User-Impersonation** – `stechstudio/filament-impersonate` (nur super_admin, Banner beim Wechsel)
2. **Phase 7 – Deployment Mittwald** (wartet auf User: GitHub-Repo + Mittwald-Server)
3. **Variantenmanagement-Basis** – SKUs + Lagerstatus (Voraussetzung für Shop-Anbindung)
4. **Import-Historie & Bulk-Edit** (Dashboard-Widget, Bulk-Actions in Produkt-Tabellen)
5. **Kampagnen-Manager** (baut auf Influencer-Modul auf)
6. **Trend-Radar-Struktur** (ohne externe APIs)

### Strategische Entscheidungen (User muss treffen)

1. **Shop: Shopware 6 oder Shopify?** → `docs/STRATEGIE-ROADMAP.md`
2. **Wann real launchen?**
3. **Wie viele Produkte bei Launch?**
4. **D2C only oder auch B2B?**

---

## 📁 Wichtige Dateien

- `CLAUDE.md` – Projekt-Regeln (Punkt 10: Handbuch vor jedem Commit aktualisieren!)
- `README.md` – Install, Tech-Stack
- `TODO.md` – alle Features, Phase-8-Block Erledigt
- `docs/handbuch.html` – **Endnutzer-Handbuch** (Tailwind, gelayoutet) · MUSS bei nutzer-sichtbaren Änderungen aktualisiert werden
- `docs/MANUELLE-TESTS.md` – ~220 Test-Schritte (Phase 8 inkl.)
- `docs/checkliste.html` – Interaktive HTML-Checkliste mit localStorage
- `docs/user-guide.md` – ältere Markdown-Doku (wird vom handbuch.html abgelöst)
- `docs/STRATEGIE-ROADMAP.md` – Shop/ERP/Satelliten-Strategie
- `SESSION-STATE.md` – diese Datei

---

## 🚀 Schnellstart in neuer Session

```bash
cd /Users/svenk/BEQN_Webprojekte/STZ_staeze-produktmanagement
git log --oneline -10
php artisan test            # 102 passed
open http://staeze-pm.test/admin  # admin@admin.com / password
open docs/handbuch.html
```

---

## 🧭 Konventionen (aus CLAUDE.md)

1. **Immer Deutsch** antworten (globale CLAUDE.md-Regel)
2. **Tests vor + nach** Änderungen (`php artisan test`)
3. **Pint + PHPStan** müssen grün bleiben (`vendor/bin/pint --test`, `vendor/bin/phpstan analyse`)
4. **SESSION-STATE.md** zu Sessionsstart lesen
5. **docs/handbuch.html** MUSS vor jedem Commit mit nutzer-sichtbaren Änderungen aktualisiert werden (CLAUDE.md Punkt 10)
6. **TODO.md + SESSION-STATE.md pflegen**
7. **Vor Destruktivem** (rm, git reset, DB drop) immer nachfragen
