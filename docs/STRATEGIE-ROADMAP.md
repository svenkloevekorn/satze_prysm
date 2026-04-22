# 🗺️ Staeze PM – Strategie & Roadmap (Version 1, 2026-04-22)

> Lang-Perspektive: Vom internen Product-Intelligence-Tool zur Startup-Plattform
> mit Online-Shop, Lieferkette, Marketing und Analytics.
>
> Dieses Dokument ist **ein lebendes Planungs-Papier** – keine fixe Festlegung.
> Die Entscheidungen weiter unten sind Empfehlungen mit Begründung, die wir
> gemeinsam schärfen, sobald Geschäftsmodell-Details konkreter werden.

---

## 1. Executive Summary

**Wo stehen wir (2026-04-22):**
- Staeze PM läuft als Filament-Admin mit 11 Resources, 7 Navigations-Gruppen,
  85 automatischen Tests grün
- Kern: Marktanalyse, Produktentwicklung, Lieferanten, Bewertungen,
  Qualitätskriterien, Medien-Galerie, Influencer-Monitoring
- Lokal-Setup über Herd + PostgreSQL, Deployment nach Mittwald vorbereitet

**Wohin wollen wir:**
- Komplettes Startup-Ökosystem für eigene Sportbekleidungs-Marke
  (Cycling, Running, Outdoor)
- End-to-End: Idee → Entwicklung → Produktion → Lager → Shop → Kunde → Retoure → Learning
- Staeze PM bleibt der **Intelligence-Kern**, der über allem sitzt; angebundene
  Systeme liefern Daten, PM wertet aus und steuert

**Kernempfehlungen:**
1. **Shop: Shopware 6 (DACH) oder Shopify** – abhängig vom Tempo (siehe §3)
2. **ERP: nicht selbst bauen** – JTL-Wawi (DACH) oder plentymarkets andocken
3. **Staeze PM als „Brain"** – Shop und ERP sind Satelliten, Daten fließen rein
4. **Modular in 4 Phasen** – zuerst MVP produktiv, dann Shop, dann ERP, dann
   fortgeschrittene Analytics

---

## 2. Das aktuelle Bild und die Vision

### 2.1 Was Staeze PM heute ist

Internes Werkzeug zur **datenbasierten Produktentscheidung**:
- **Marktscan**: Welche Wettbewerbsprodukte gibt es? Was sind deren Preise,
  Stärken, Schwächen?
- **Lieferantenmanagement**: Wer produziert was, zu welchem EK, bei welchem
  MOQ?
- **Entwicklungspipeline**: Von der Idee über Sample bis zum finalen Produkt
- **Qualität**: Kriterien-Checkliste, Bewertungen aus mehreren Quellen
- **Influencer**: Erfassung, Monitoring, Engagement-Tracking

Es gibt **noch keinen Shop, keine Warenwirtschaft, keine Buchhaltung**.

### 2.2 Was Staeze werden will (Startup-Vision)

Die Software soll die **Datenzentrale** bleiben, während operative Systeme
andocken:

```
                   ┌──────────────────┐
                   │   STAEZE PM      │   ← Intelligence-Kern (bleibt)
                   │ (Brain Layer)    │
                   └──────┬───────────┘
                          │
      ┌───────────────────┼───────────────────┐
      │                   │                   │
      ▼                   ▼                   ▼
┌──────────┐      ┌──────────┐       ┌──────────────┐
│  Shop    │      │  ERP /   │       │ Marketing &  │
│  (DACH)  │◄────►│  Wawi    │◄─────►│  Influencer  │
└──────────┘      └──────────┘       └──────────────┘
      │                   │                   │
      ▼                   ▼                   ▼
   Kunden             Lieferanten         Social Channels
```

Die angebundenen Systeme haben klare Aufgaben:
- **Shop** = Sales & Kunde
- **ERP** = Lager, Logistik, Buchhaltung, Retouren
- **Marketing** = Reichweite, Kooperationen, Kampagnen
- **Staeze PM** = Strategische Entscheidungen aus allen Datenquellen

---

## 3. Shop-System für den DACH-Markt

### 3.1 Kandidaten im Überblick

| System | Typ | Stärken | Schwächen | Lizenz |
|---|---|---|---|---|
| **Shopify** | SaaS | Schnellster Time-to-Market, riesige App-Ökosystem, tolle Performance, internationaler Default | Monatsgebühr + Transaktionsgebühr, weniger tiefe Anpassung, Dependency auf Shopify | SaaS, ab ~39 €/Monat |
| **Shopware 6** | Open Source / SaaS | **DACH-Marktführer im Mid-Market**, sehr B2B-fähig, headless-fähig, große DE-Community, ausgereifte DACH-Module (Rechnung, DHL, Klarna) | Komplexer als Shopify, Selbst-Hosting erfordert Ops-Know-how | Community: kostenlos, Pro/Enterprise ab ~199 €/Monat |
| **WooCommerce** | Open Source | Kostenlos, WordPress-Ökosystem, sehr flexibel | Performance-kritisch bei Skalierung, viele Plugins nötig, fragiler | Kostenlos |
| **JTL-Shop** | Open Source | **Perfekte Integration mit JTL-Wawi** (DACH-ERP), Deutsche Rechtssicherheit | Kleinere Community als Shopware, altbackener Look | Kostenlos |
| **plentymarkets** | SaaS | **Shop + ERP + Marktplatz-Anbindung in einem**, sehr DACH-fokussiert, inkl. DATEV | Proprietär, Lock-in, monatliche Kosten skalieren mit Umsatz | SaaS, ab ~39 €/Monat |
| **Magento/Adobe Commerce** | Open Source / SaaS | Sehr mächtig | Overkill für Startup, teuer, komplex | Open Source / Enterprise ab 5-stellig/Jahr |

### 3.2 Was andere deutsche Cycling/Running-Marken nutzen

Kurzer Marktblick (Stand 2026, Beobachtung, nicht erschöpfend):
- **Maloja** (Cycling/Outdoor): Shopware
- **Gonso** (Cycling): Shopware
- **Ortovox** (Outdoor): Shopify Plus
- **Edelrid** (Outdoor): Shopware
- **Veloheld** (Cycling, kleineres DACH-Startup): Shopware
- **7mesh** (Cycling, internationaler Competitor): Shopify
- Viele D2C-Startups starten mit **Shopify** und **migrieren ab ~1-2M €
  Umsatz auf Shopware**, um tiefere Individualisierung zu haben

### 3.3 Unsere Entscheidungsmatrix

| Kriterium | Gewicht | Shopify | Shopware 6 | JTL-Shop |
|---|---|---|---|---|
| Time-to-Market (wichtig als Startup) | hoch | 10 | 6 | 7 |
| DACH-Rechtsicherheit out-of-the-box | hoch | 7 | 10 | 9 |
| B2B-Fähigkeit (Großhändler später?) | mittel | 6 | 10 | 8 |
| Headless-API für Staeze-Integration | hoch | 10 | 10 | 6 |
| Kostenstruktur Startup-freundlich | hoch | 8 | 9 | 10 |
| Performance bei Skalierung | mittel | 10 | 9 | 7 |
| Influencer-/Marketing-Integrationen | mittel | 10 | 7 | 5 |
| Anpassung / Custom-Features | hoch | 5 | 10 | 7 |
| **Gesamt (gewichtet)** | | **~75** | **~82** | **~68** |

### 3.4 Empfehlung

**Zwei Szenarien:**

**Szenario A: „Schnell am Markt" (in 2-3 Monaten)**
→ **Shopify starten.** Minimal-Setup, Test-Produkte online, Marketing-Traffic
aufbauen, Kundenverhalten messen. Später migrieren falls nötig.

**Szenario B: „Direkt mit Langzeitperspektive bauen" (in 4-6 Monaten)**
→ **Shopware 6 (Community Edition).** Auf eigenem Mittwald-Server, eigenes
Theme, saubere API-Anbindung an Staeze PM von Anfang an.

**Mein Bauchgefühl:** Als echtes Startup mit klarer Marke und Individualanspruch
ist **Shopware 6** die nachhaltigere Wahl. Shopify ist der Default, wenn „nur
schnell launchen" das Ziel ist. Du willst aber eine Marke aufbauen und deine
Produktdaten tief integrieren – das ist Shopware-Territorium.

**Endgültige Entscheidung:** Braucht ein eigenes Gespräch über Budget, Team
und Zeitplan. Dieses Dokument liefert die Grundlage.

---

## 4. ERP: Selbst bauen oder andocken?

### 4.1 Was ein ERP/Warenwirtschaftssystem können muss

Minimum für ein DACH-Startup mit Lagerhaltung:
- Artikel- und Variantenverwaltung (Größen × Farben)
- Lagerbestände (Mehr-Lager, Retouren)
- Wareneingang (Lieferschein, Charge, Seriennummer)
- Versand-Anbindung (DHL, DPD, GLS, Hermes)
- Rechnung/Gutschrift (USt, Auslandslieferungen, OSS)
- Buchhaltungs-Export (DATEV, Lexware)
- Marktplatz-Anbindung (Amazon, eBay, Zalando falls später)
- Retourenmanagement

### 4.2 Eigen-Entwicklung – ehrlicher Aufwand

Ein halbwegs nutzbares ERP zu bauen bedeutet realistisch:
- **12-18 Monate Entwicklung** bei einem 2-Personen-Team
- **Erhebliche Rechtsrisiken** (RKSV, GoBD, Finanzamt-konforme Rechnungen)
- **Schnittstellen-Wartung** (DHL APIs, Zahlungsprovider)
- Schlimmstenfalls: **Du wirst zum ERP-Hersteller statt Sportbekleidungs-Marke**

### 4.3 Andock-Kandidaten

| System | Typ | Stärken | Integration mit Staeze PM |
|---|---|---|---|
| **JTL-Wawi** | Desktop + Cloud | Sehr DACH-stark, kostenlose Basis-Version, exzellente Shop-Integration (JTL-Shop, Shopware, Shopify über Connector), Connector zu Amazon/eBay/Zalando | REST-API über JTL-Connector, saubere bidirektionale Sync möglich |
| **plentymarkets** | SaaS All-in-One | Shop + Wawi + Marktplätze in einem, sehr DACH-kompatibel | REST/SOAP-API, aber Architektur zwingt zur Komplettlösung |
| **Odoo** | Open Source | Mächtig, modular (Inventory, Accounting, CRM), hostbar | Python-basiert, eigene API, aufwendigere Integration |
| **Weclapp** | SaaS | Deutsches Cloud-ERP, DATEV-zertifiziert, gute UX | REST-API mit guter Doku |
| **Xentral** | SaaS / On-Premise | Sehr DACH-fokussiert, modern, startup-friendly | REST-API, API-first Ansatz |

### 4.4 Empfehlung

**Klar: nicht selbst bauen.** Stattdessen:

**Phase 1 (Launch-Jahr):** **JTL-Wawi** anbinden
- Kostenlose Basis-Version reicht für Start
- Perfekte Integration mit Shopware (oder Shopify via Connector)
- DATEV-Export für Steuerberater

**Phase 2 (ab ~500k € Jahresumsatz):** Auf **Xentral** oder **Weclapp**
wechseln, wenn API-first und Cloud wichtiger werden.

**Staeze PM übernimmt NICHT** Lagerbestand, Rechnungen, Versand – das macht
das ERP. Staeze PM bekommt aber Informationen zurück:
- Welche Produkte verkaufen sich wie gut?
- Wo sind Retouren-Spitzen (Produktbewertung → Qualitätsmanagement-Link!)
- Welche Größen gehen in welchem Land?

---

## 5. Satelliten-Software: Was wir bauen oder andocken können

### 5.1 Was wir *in* Staeze PM bauen sollten (Kern-Erweiterungen)

**5.1.1 Variantenmanagement**
Größen × Farben × evtl. Passformen. Aktuell haben wir nur Arrays im JSON –
für echte Bestandsführung brauchen wir SKUs pro Variante, ab Moment wenn
wir mit dem Shop koppeln.

**5.1.2 Global-Admin-Settings**
- Zentrale Einstellungsseite unter „System → Einstellungen"
- Feature-Toggles (z.B. „Influencer-Monitoring aktivieren", „Auto-Fetch aktiv")
- API-Keys verschlüsselt speichern (Shopify-Token, Shopware-Credentials,
  Meta Graph API, TikTok, DHL-Account etc.)
- Default-Werte (Standard-Lieferant, Standard-Währung, Mehrwertsteuer)
- Umsetzung mit `spatie/laravel-settings` oder `filament/spatie-laravel-settings-plugin`
- Rollen-geschützt: nur super_admin darf editieren

**5.1.3 Audit Log / Historie**
- Wer hat wann was geändert?
- Spatie-Activitylog integrieren
- Pro Model als RelationManager „Änderungshistorie"
- Compliance-relevant wenn wir später Investoren haben

**5.1.4 Import-Historie & Bulk-Edit**
- Übersicht aller CSV-Imports und welche Zeilen fehlschlugen
- Bulk-Edit für Produkte (Preisänderungen, Status-Updates)

**5.1.5 Tagging-System**
- Querschnittliche Tags über alle Produkt-Typen
- Smart-Tags („Best-Seller", „Neu", „Trend")
- Als Filter überall

### 5.2 Was wir als Satelliten-Software bauen könnten

**5.2.1 🛒 Shop-Sync-Bridge** (HOCH PRIORISIERT)
- Eigenes Laravel-Package/-Modul (oder kleiner Service)
- Aufgabe: Finale Produkte → Shopware/Shopify pushen
- Bidirektional: Verkaufsdaten zurück in Staeze PM
- Queue-basiert, Event-getrieben
- Konfigurierbar: Welche Felder synchen? Preis-Strategie? Bilder?

**5.2.2 🎯 Kampagnen-Manager für Influencer**
- Aufbauend auf Influencer-Modul
- Entitäten: Campaign, CampaignSlot, Briefing, Deliverable
- Status: Anfrage → Zusage → Briefing → Live → Abgerechnet
- Produkt-Zuordnung (welche Influencer hat welches Produkt gestaged)
- Performance-Anknüpfung an Shop (Discount-Codes pro Influencer → Sales)

**5.2.3 🔁 Retouren-Intelligence**
- Integration mit ERP: Retouren-Gründe fließen in Staeze PM
- Automatische Verknüpfung Retoure → Qualitätskriterium
- Dashboard „Top-Retourengründe pro Produkt"
- Closed-Loop zur Produktverbesserung

**5.2.4 📅 Marketing-Kalender**
- Content-Planung (Social-Posts, Newsletter, PR)
- Verknüpfung mit Produkt-Launches + Influencer-Kampagnen
- Freigabe-Workflow (Entwurf → Review → Genehmigt → Geplant)
- Einfacher Kalender-View

**5.2.5 📈 Trend-Radar**
- Integration Google Trends API
- TikTok/Instagram Hashtag-Monitoring
- Abgleich mit eigenen Produktkategorien
- Automatische Warnung: „Suchvolumen für 'gravel bib shorts' +180 % seit 3 Wochen"

**5.2.6 🌱 Nachhaltigkeits-Tracker**
- Pro Produkt: CO2-Fußabdruck, Recycling-Anteil, Fair-Trade-Zertifikate
- Supplier-Scoring erweitern
- Kann als Marketing-Argument für den Shop rausgehen (Transparenz)

**5.2.7 🏪 B2B-Wholesale-Portal**
- Eigenes Portal für Wiederverkäufer/Händler
- Eigene Preise, MOQ-Rabatte, Credit-Limits
- An ERP (Wawi) + Shop angedockt
- Erst relevant bei eigener D2C-Marke mit Wholesale-Kanal

**5.2.8 🎨 PIM-Export für Marktplätze**
- Amazon, Zalando, AboutYou, Otto haben eigene Produktdaten-Anforderungen
- Staeze PM könnte Feeds/Exports in deren Formaten erzeugen
- Später wichtig, nicht jetzt

**5.2.9 🎁 Lieferanten-Portal** (externer Login)
- Lieferant logged sich selbst ein
- Lädt Produktdaten, Bilder, Preise hoch
- Beantwortet Fragen zu Mustern
- Reduziert deinen Pflegeaufwand massiv

**5.2.10 📱 Mobile-App für Qualitätscheck**
- Im Showroom/Lager Qualitätschecks am Produkt durchführen
- QR-Code scan → Produkt → Checkliste abhaken
- Offline-fähig, später syncen
- Laravel + Capacitor oder React Native als eigenständige App, API zum PM

**5.2.11 💬 Kunden-Feedback-Ingestion**
- Bewertungen aus Shop automatisch als externe Ratings importieren
- Trustpilot-/Google-Review-Monitoring
- NPS-Berechnung pro Produkt

### 5.3 Was wir tendenziell NICHT selbst bauen sollten

- **Buchhaltung** (zu komplex, regulatorisch): Anbinden, nicht bauen
- **Versand-Tracking-Interface**: Nutze ERP-eigene Lösung
- **Kassensystem (POS)**: Shopify POS / lightspeed andocken falls nötig
- **E-Mail-Marketing**: Klaviyo / MailerLite / CleverReach andocken
- **Rechtsdokumente-Generator**: Trusted Shops oder ähnlich

---

## 6. Priorisierter Implementierungsplan

### 🎯 Phase 1: „Produktiv schalten" (nächste 4-6 Wochen)

Ziel: **Staeze PM läuft auf Mittwald, Team kann arbeiten.**

1. **Phase 7 abschließen** — Deployment Mittwald, GitHub-Actions CI/CD
2. **Global-Admin-Settings-Modul** (5.1.2)
3. **Variantenmanagement-Basis** (5.1.1) — mindestens SKUs + Lagerstatus-Feld
4. **Audit Log** (5.1.3) für Compliance-Grundlage

**Outcome:** Produktivumgebung läuft, Basis für Shop-Anbindung da.

### 🛒 Phase 2: „Shop-Launch" (Monat 2-5)

Ziel: **Erste Produkte online verkaufen.**

5. **Shopware 6 aufsetzen** (oder Shopify, je nach Entscheidung oben)
6. **Shop-Sync-Bridge bauen** (5.2.1) – finale Produkte → Shop
7. **JTL-Wawi anbinden** – Lager + Rechnungen
8. **Basic Retouren-Flow** – erst manuell, dann automatisiert

**Outcome:** Ein fertiges Staeze-Produkt geht live, wird bestellt, versandt,
bezahlt.

### 🎯 Phase 3: „Marketing aktivieren" (Monat 4-7, parallel zu Phase 2)

Ziel: **Influencer- und Kampagnen-Management professionell.**

9. **Kampagnen-Manager** (5.2.2) für Influencer-Kooperationen
10. **F.2 echte API-Integration** – Instagram Graph API, TikTok Business API
    (ersetzen den SimulatedFetcher)
11. **Marketing-Kalender** (5.2.4)
12. **E-Mail-Marketing-Tool anbinden** (Klaviyo empfohlen, Shop+CRM-Fokus)

**Outcome:** Du kannst Kampagnen planen, messen und mit Influencern skalieren.

### 📈 Phase 4: „Intelligence vertiefen" (Monat 6-12)

Ziel: **Datenbasierte Entscheidungen werden Routine.**

13. **Retouren-Intelligence** (5.2.3) – Closed-Loop Qualität
14. **Trend-Radar** (5.2.5)
15. **Nachhaltigkeits-Tracker** (5.2.6)
16. **Erweiterte Analytics-Dashboards** – Benchmarks, Forecasting
17. **Lieferanten-Portal** (5.2.9) – Reduktion Pflegeaufwand

**Outcome:** Staeze wird zum strategischen Steuerungstool.

### 🌍 Phase 5: „Skalierung & Expansion" (Monat 12+)

18. **Mobile-App für Qualitätscheck** (5.2.10)
19. **PIM-Export für Marktplätze** (5.2.8)
20. **B2B-Wholesale-Portal** (5.2.7)
21. **Mehrsprachigkeit** DE/EN (Staeze PM + Shop)
22. **Internationalisierung** Länderfilialen

### 🧠 Phase 6: „KI-Features" (Monat 15+)

23. **Trend-/Bewertungs-Analyse mit LLM** – Clusterung von Kundenfeedback
24. **Produktvorschläge** basierend auf Marktdaten + Lieferanten-Kapazitäten
25. **Bild-Analyse** – automatische Qualitätsbewertung über Produktbilder

---

## 7. Tech-Stack-Erwägungen

### 7.1 Was passt zum heutigen Setup

Staeze PM läuft auf Laravel 13 / Filament 5 / PostgreSQL 18. Das ist ein
ausgezeichneter Startpunkt:
- Laravel ist produktiv, deutsche Community groß, Hosting (Mittwald/Forge) einfach
- Filament perfekt für interne Admin-Tools
- PostgreSQL skaliert für unsere Größenordnung locker in die Millionen Zeilen

### 7.2 Was sich dazugesellen wird

| Baustein | Empfehlung | Begründung |
|---|---|---|
| Queue | Laravel Horizon + Redis | Bei Shop-Sync und API-Fetching essentiell |
| Logging | Laravel + Sentry | Produktion will gutes Error-Tracking |
| Monitoring | Uptime Kuma oder HealthChecks.io | Sollte vor 1. Live-Umsatz stehen |
| E-Mails | Postmark / Resend | Zustellrate im Startup entscheidend |
| Storage | Mittwald-Bucket oder AWS S3/R2 | Sobald Bilder über 1 GB sind |
| Full-Text-Search | Meilisearch (self-hosted) | Filaments Global Search reicht noch lange |
| Background-Jobs | Supervisor + Redis | Cronjobs mit Laravel Scheduler |

### 7.3 Warum nicht alles in einer App

Manche Teile gehören technisch getrennt:
- **Shop-Sync-Bridge**: eigener Laravel-Service, der über HTTP mit Staeze PM
  spricht. Grund: Sync-Fehler sollen nicht Staeze PM abstürzen lassen.
- **Mobile App**: eigenständig, nutzt Staeze-PM-API.
- **B2B-Portal**: eigene Filament-Instanz auf subdomain, andere Auth,
  andere Security-Anforderungen.

**Staeze PM bleibt der Kern** mit zentraler Datenbank. Satelliten sprechen
über sauber definierte APIs.

---

## 8. Kostenschätzung und Aufwand (Indikation)

### 8.1 Software-Kosten (monatlich)

| Posten | Minimum | Ausbau |
|---|---|---|
| Mittwald-Hosting Staeze PM | 15 € | 30 € |
| Shopware Self-Hosted | 0 € | 199 € (Rise) |
| Shopify | 39 € | 389 € (Plus) |
| JTL-Wawi | 0 € | 100 € (Hosted) |
| Klaviyo | 0 € (bis 250 Kontakte) | 45 € / 150 € |
| Sentry | 0 € (50k Events) | 29 € |
| Postmark | 0 € (100 Mails) | 15 € |
| Redis-Hosting | 0 € (Mittwald inkl.) | – |
| **Summe (Minimum Shopware-Route)** | ~15 €/Monat | ~400 €/Monat |
| **Summe (Minimum Shopify-Route)** | ~54 €/Monat | ~450 €/Monat |

### 8.2 Entwicklungs-Aufwand (grober Rahmen)

Für jede Phase ca., bei 2-Personen-Team (1× Entwicklung, 1× Fachseite):

- **Phase 1 (Produktiv schalten):** 4-6 Wochen
- **Phase 2 (Shop-Launch):** 3-5 Monate
- **Phase 3 (Marketing):** 3-4 Monate (parallelisierbar)
- **Phase 4 (Intelligence vertiefen):** 4-6 Monate
- **Phase 5+6 (Skalierung + KI):** 9-12 Monate

**Insgesamt bis zur voll ausgebauten Plattform: ~18-24 Monate**.

Das ist die Vollkosten-Sicht – wenn du fokussiert launchen willst, kannst du
mit Phase 1 + 2 in **6 Monaten produktiv** sein.

---

## 9. Risiken & offene Entscheidungen

### 9.1 Hohe Unsicherheiten

- **Welches Shop-System?** Shopware oder Shopify ist keine Tech-, sondern
  eine Business-Entscheidung (Individualisierung vs. Time-to-Market)
- **Wie groß planen?** 1 Produkt bei Launch oder 20? Bestimmt Lager-Investment
- **Wer macht Logistik?** Eigenes Lager vs. Fulfillment-Dienstleister (Fiege,
  Hermes-Fulfillment)
- **B2B oder nur D2C?** Ändert Shop + ERP-Auswahl

### 9.2 Technische Schuld, die wir vermeiden sollten

- **API-Keys hardcoden**: Muss von Anfang an sauber in Settings + ENV
- **Shop direkt in Staeze PM integrieren**: Falsch, soll getrennt bleiben
- **Eigenes ERP bauen**: Wie schon erwähnt – Falle
- **Kein Monitoring/Error-Tracking vom Start weg**: Wir brauchen Sentry ab Phase 1

### 9.3 Rechtliche Pflichten (Stand DE, 2026)

- **Impressum, Datenschutzerklärung, AGB, Widerrufsbelehrung**: Pflicht
  ab Shop-Launch, Trusted Shops oder eRecht24 sinnvoll
- **GoBD-konforme Rechnungen**: ERP-Sache, nicht Staeze-PM
- **DSGVO-Compliance**: Auch Staeze PM muss Daten-Export und Lösch-Funktionen
  haben, wenn dort Kundendaten/Influencer-Daten liegen
- **PSD2 / Starke Kundenauthentifizierung**: Shop-Sache

---

## 10. Was ich als Nächstes empfehle

In der Reihenfolge:

1. **Kurz-Workshop (intern):** Shop-Entscheidung treffen (Shopware vs. Shopify)
2. **Business-Plan durchgehen:** Produktanzahl bei Launch, Zielumsatz Jahr 1,
   Kanäle (eigener Shop, Marktplätze, Händler)
3. **Phase 1 starten:** Mittwald-Deployment + Admin-Settings + Varianten
4. **Shop in einer Testumgebung aufsetzen** (parallel zur Phase-1-Entwicklung)
5. **ERP-Evaluation:** JTL-Wawi in einer Test-Instanz spielen

---

## 11. Mein Gesamt-Fazit

Staeze PM ist ein **exzellenter Fundament** für das, was du vorhast. Das
Modell, PM als **Intelligence-Layer über Shop + ERP + Marketing** zu positionieren,
ist die richtige Architektur für ein datengetriebenes Startup.

**Nicht alles selbst bauen** – das ist die größte Versuchung, wenn man eine
eigene Software-Basis hat. Shop = Shopware/Shopify, ERP = JTL/Weclapp,
E-Mail = Klaviyo, Zahlung = Stripe/Mollie.

**Staeze PM bleibt das Gehirn.** Darum bauen wir die Shop-Sync-Bridge, die
Retouren-Intelligence, das Influencer-Kampagnen-Modul. Das sind die Teile, die
aus generischen Tools keine Standard-Lösung haben.

**Für die nächsten 3 Monate:**
1. Phase 7 (Deployment) ✅
2. Admin-Settings mit API-Key-Management ✅
3. Shop-Entscheidung treffen
4. Variantenmanagement bauen
5. Shop parallel aufsetzen
