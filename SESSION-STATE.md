# 🔖 Session-State – Staeze PM

> Merkzettel für die nächste Claude-Session. Zu Beginn lesen.

**Letzte Aktualisierung:** 2026-04-22

---

## 🎯 Aktueller Status

MVP komplett + alle 5 Feature-Pakete (C, A, E, B, F.1) fertig. Phase 7 (Deployment Mittwald)
pausiert, wartet auf GitHub-Repo + Mittwald-Server.

### Zahlen

- **25+ Commits** auf `main`-Branch (alle lokal, noch nicht gepusht)
- **85 automatische Tests** grün
- **PHPStan Level 5** – 0 Fehler
- **Pint** – alle Files formatiert
- **Site lokal:** http://staeze-pm.test/admin

### Entitäten (am 2026-04-22)

**Stammdaten:** Category (2-Ebenen), Brand (mit Website+Social), Shop, RatingDimension, QualityCriterion
**Marktanalyse:** CompetitorProduct (+ ProductShopEntry)
**Lieferanten:** Supplier (+ Contact, SupplierProduct)
**Bewertungen:** Rating (polymorph, Multi-Select-Sources)
**Produkt-Entwicklung:** DevelopmentItem → FinalProduct (automatisch)
**Qualität:** QualityCheck (polymorph, abhakbar pro Kategorie)
**Medien:** Galerie-Page (übergreifende Bild-Übersicht)
**Social Media:** Influencer, SocialChannel (polymorph), ChannelMetric

### Navigation

1. Stammdaten (Kategorien, Bewertungs-Dimensionen, Qualitätskriterien)
2. Marktanalyse (Wettbewerbsprodukte, Marken, Shops)
3. Lieferanten (Lieferanten, Lieferanten-Produkte)
4. Produkt-Entwicklung (Entwicklungs-Pipeline, Finale Produkte)
5. Bewertungen (Alle Bewertungen)
6. Medien (Medien-Galerie)
7. Social Media (Influencer, Alle Kanäle)

---

## 🔜 Was als nächstes ansteht

### F.2 – Auto-Monitoring Social Channels

Aktuell: Snapshots manuell per Klick. Gewünscht: Cronjob fetcht Follower-/Engagement-Zahlen
automatisch.

- Artisan-Befehl `php artisan staeze:fetch-channel-metrics`
- Platform-APIs oder Scraping (Offene Frage für User!)
- Scheduler-Eintrag (z.B. daily at 3am)
- Test-Scenario

### F.3 – Analytics & Relevanz-Score

- Dashboard-Widgets: Top-5-Channels by Engagement, Wachstum-Chart
- Ranking-Berechnung
- Relevanz-Score für eigene Marke (thematische Kategorie-Treffer)

### Phase 7 – Deployment Mittwald

Wartet auf User:
- GitHub-Repo anlegen
- Mittwald-Server vorbereiten (SSH, Postgres 16+, PHP 8.3+)

---

## 📁 Wichtige Dateien

- `README.md` – Install, Tech-Stack
- `TODO.md` – alle Features (viele als ✅ erledigt markiert)
- `docs/MANUELLE-TESTS.md` – ~180 Test-Schritte
- `docs/checkliste.html` – Interaktive HTML-Checkliste mit localStorage
- `docs/user-guide.md` – Endnutzer-Handbuch
- `SESSION-STATE.md` – diese Datei
- `.env.production.example` + `deploy.sh` – Mittwald-vorbereitet

---

## 🚀 Schnellstart in neuer Session

```bash
cd /Users/svenk/BEQN_Webprojekte/STZ_staeze-produktmanagement
git log --oneline -10
php artisan test            # 85 passed
open http://staeze-pm.test/admin  # admin@admin.com / password
open docs/checkliste.html
```

---

## 🧭 Konventionen

1. **Immer Deutsch** antworten (globale CLAUDE.md-Regel)
2. **Tests vor + nach** Änderungen
3. **Pint + PHPStan** müssen grün bleiben
4. **SESSION-STATE.md** zu Sessionsstart lesen
