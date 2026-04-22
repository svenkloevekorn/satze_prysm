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

### ✅ F.2 + F.3 erledigt (2026-04-22)

- SimulatedFetcher + Artisan `staeze:fetch-channel-metrics` + Scheduler-Hook
- CSV-Import für ChannelMetrics + „Snapshots jetzt holen"-Action
- SocialStatsWidget + TopChannelsWidget mit Relevanz-Score
- Siehe `docs/STRATEGIE-ROADMAP.md` für echte API-Integration (F.2 Phase 2)

### 📄 Strategische Roadmap erstellt

**Siehe `docs/STRATEGIE-ROADMAP.md`** (2026-04-22). Enthält:
- Shop-Empfehlung: Shopware 6 (DACH-Fokus) oder Shopify (Time-to-Market)
- ERP: nicht selbst bauen → JTL-Wawi andocken
- 11 Satelliten-Software-Ideen (Shop-Sync, Kampagnen-Manager, Retouren-Intelligence, Trend-Radar, Nachhaltigkeit, B2B-Portal, Lieferanten-Portal, Mobile-App etc.)
- 6-Phasen-Plan, Kostenschätzung, Risiken

### Priorität 1 für nächste Sessions

1. **Phase 7 – Deployment Mittwald** (wartet auf User: GitHub-Repo + Mittwald-Server)
2. **Admin-Settings-Modul** (spatie/laravel-settings) – API-Keys + Feature-Toggles zentralisieren
3. **Variantenmanagement-Basis** – SKUs + Lagerstatus (Voraussetzung für Shop-Anbindung)
4. **Audit Log** – spatie/laravel-activitylog

### Strategische Entscheidungen (User muss treffen)

1. **Shop: Shopware 6 oder Shopify?** → Roadmap-Dokument liefert Entscheidungshilfe
2. **Wann real launchen?** – Timing bestimmt Phase-Reihenfolge
3. **Wie viele Produkte bei Launch?** – Lagerinvestition
4. **D2C only oder auch B2B?** – Beeinflusst Shop-Auswahl

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
