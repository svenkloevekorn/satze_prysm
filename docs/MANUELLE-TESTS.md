# 🧪 Manuelle Test-Anleitung – Staeze PM

> Eine Schritt-für-Schritt-Anleitung, mit der du selbst alle gebauten Funktionen im Browser testen kannst.
> **Nach jeder neuen Phase sollte hier ein Abschnitt dazukommen.**

---

## 🚦 Vorbereitung

1. **Herd läuft?** → Öffne die Herd-App, prüfe dass `staeze-pm` als Site gelistet ist
2. **PostgreSQL läuft?** → Herd → Services → PostgreSQL muss grün sein
3. **Browser auf:** http://staeze-pm.test
4. **Login:**
   - URL: http://staeze-pm.test/admin/login
   - E-Mail: `admin@admin.com`
   - Passwort: `password`

✅ Erwartung: Du siehst das **Filament-Dashboard auf Deutsch**, links das Navigationsmenü mit **"Stammdaten"** und **"Filament Shield"**.

---

## ✅ Phase 1 – Stammdaten testen

### 🔹 1. Kategorien

**Pfad:** Stammdaten → Kategorien

#### Test-Schritte:

| # | Was tun? | Erwartung |
|---|---|---|
| 1.1 | Öffne die Kategorien-Liste | 7 Kategorien sind da: Cycling Jerseys, Bib Shorts, Running Shirts, Running Shorts, Jackets, T-Shirts, Bags |
| 1.2 | Suche nach "Cycling" | Nur "Cycling Jerseys" wird angezeigt |
| 1.3 | Filter "Aktiv-Status" → "Nur aktive" | Alle 7 Einträge weiterhin sichtbar |
| 1.4 | Klick "Neu" oben rechts | Formular öffnet sich |
| 1.5 | Tippe "Test Kategorie" als Name | Slug-Feld füllt sich automatisch mit `test-kategorie` |
| 1.6 | Speichern | Erfolgs-Meldung, neue Kategorie taucht in der Liste auf |
| 1.7 | Eine Kategorie bearbeiten | Änderungen werden gespeichert |
| 1.8 | Toggle "Aktiv" auf "aus" | Kategorie ist deaktiviert (Filter "Nur inaktive" findet sie) |
| 1.9 | Bulk-Auswahl + Löschen | Bestätigung wird verlangt, dann gelöscht |

#### ❓ Was prüfen?
- Sortierung nach `#` (sort_order) funktioniert
- Slug ist immer einzigartig (gleichen Namen 2× anlegen → Fehler)
- Beschreibung ist optional

---

### 🔹 2. Marken (Brands)

**Pfad:** Stammdaten → Marken

#### Test-Schritte:

| # | Was tun? | Erwartung |
|---|---|---|
| 2.1 | Liste öffnen | (leer, oder Test-Daten) |
| 2.2 | Klick "Neu" → Name "Castelli", Notizen "Italienische Marke" → Speichern | Marke erscheint in der Liste |
| 2.3 | Versuch nochmal "Castelli" anzulegen | Fehler: Name muss einzigartig sein |
| 2.4 | Suche nach "Cas" | "Castelli" erscheint |
| 2.5 | "Castelli" bearbeiten, Aktiv ausschalten | In Filter "Nur inaktive" sichtbar |

---

### 🔹 3. Shops

**Pfad:** Stammdaten → Shops

#### Test-Schritte:

| # | Was tun? | Erwartung |
|---|---|---|
| 3.1 | Liste öffnen | 4 Shops vorhanden: Amazon DE, Bike-Discount, Wiggle, SportScheck |
| 3.2 | Klick auf URL eines Shops in der Tabelle | Öffnet die Website in neuem Tab |
| 3.3 | Klick "Neu" → Name "Test", URL "kein-link" | Fehler: keine gültige URL |
| 3.4 | Land "DE" eingeben | Zeigt sich als Badge |
| 3.5 | Land mit 3 Buchstaben "DEU" versuchen | Fehler oder abgeschnitten (max 2) |

---

### 🔹 4. Bewertungs-Dimensionen

**Pfad:** Stammdaten → Bewertungs-Dimensionen

#### Test-Schritte:

| # | Was tun? | Erwartung |
|---|---|---|
| 4.1 | Liste öffnen | 5 Dimensionen: Design, Material, Verarbeitung, Performance, Preis-Leistung |
| 4.2 | Sortierung nach `#` | Reihenfolge: 1=Design, 2=Material, ... |
| 4.3 | Klick "Neu" → Name "Komfort" anlegen | Erscheint in der Liste |
| 4.4 | Doppelten Namen anlegen | Fehler: Name muss einzigartig sein |

---

### 🔹 5. Qualitätskriterien

**Pfad:** Stammdaten → Qualitätskriterien

#### Test-Schritte:

| # | Was tun? | Erwartung |
|---|---|---|
| 5.1 | Liste öffnen | 6 Kriterien vorhanden (Nahtqualität, Atmungsaktivität, Passform, Wasserdichtigkeit, Polsterung, Reflektoren) |
| 5.2 | Spalte "Kategorien" prüfen | Jedes Kriterium hat passende Kategorie-Badges |
| 5.3 | Filter "Kategorie" → "Jackets" auswählen | Nur Kriterien sichtbar, die für Jacken gelten (Nahtqualität, Atmungsaktivität, Passform, Wasserdichtigkeit, Reflektoren) |
| 5.4 | Klick "Neu" → "UV-Schutz" + Kategorien "Cycling Jerseys, T-Shirts" auswählen | Kriterium wird gespeichert |
| 5.5 | Bearbeiten → eine Kategorie entfernen | Wird gespeichert, Badge verschwindet |

---

### 🔹 6. Rollen & Rechte (Filament Shield)

**Pfad:** Filament Shield → Roles

#### Test-Schritte:

| # | Was tun? | Erwartung |
|---|---|---|
| 6.1 | Roles-Liste öffnen | Mindestens "super_admin" sichtbar |
| 6.2 | Neue Rolle "editor" anlegen | Funktioniert |
| 6.3 | Permissions zuweisen (z.B. nur "view_any_brand") | Speichern klappt |

> ⚠️ **Wichtig:** Du als super_admin **umgehst alle Permission-Checks** (`Gate::before` in `AppServiceProvider.php`). Du siehst also immer alles. Um Rollen wirklich zu testen, musst du dich mit einem Test-User ohne super_admin-Rolle einloggen.

---

## 🧪 Automatische Tests laufen lassen

Im Terminal im Projektordner:

```bash
php artisan test
```

**Erwartung (Stand Phase 1):** `14 passed`

---

## 🔧 Was tun, wenn was nicht klappt?

### ❌ "Datenbank nicht verbunden" / 500-Fehler

```bash
# Verbindung prüfen
psql -h 127.0.0.1 -p 5432 -U postgres -c "SELECT 1;"

# DB neu aufbauen + seed
php artisan migrate:fresh --seed
```

### ❌ "Permission denied" im Admin (403)

```bash
# Super-Admin-Rolle neu zuweisen
php artisan tinker --execute="\App\Models\User::where('email', 'admin@admin.com')->first()->syncRoles(['super_admin']);"
```

### ❌ Login funktioniert nicht / falsches Passwort

```bash
# Passwort zurücksetzen
php artisan tinker --execute="\$u = \App\Models\User::where('email', 'admin@admin.com')->first(); \$u->password = bcrypt('password'); \$u->save();"
```

### ❌ Caches sind kaputt

```bash
php artisan optimize:clear
php artisan filament:cache-components
```

### ❌ Site `staeze-pm.test` nicht erreichbar

```bash
# Herd neu linken
herd link staeze-pm
herd restart
```

---

## 📋 Test-Checkliste pro Phase

Wenn ich (Claude) eine neue Phase abgeschlossen habe, gehe so vor:

1. ✅ Im Browser einloggen
2. ✅ Neue Module anklicken (siehe Abschnitte oben für die Phase)
3. ✅ Mindestens 1× **Anlegen**, 1× **Bearbeiten**, 1× **Löschen** durchspielen
4. ✅ Suche und Filter ausprobieren
5. ✅ `php artisan test` → muss grün sein
6. ✅ Wenn alles passt → Punkt in `TODO.md` abhaken lassen

---

## 🌍 Spätere Phasen *(Platzhalter)*

### Phase 2 – Wettbewerbsprodukte *(folgt)*
### Phase 3 – Lieferantenverwaltung *(folgt)*
### Phase 4 – Bewertungssystem *(folgt)*
### Phase 5 – Produkt-Entwicklung *(folgt)*
### Phase 6 – Dashboard & Suche *(folgt)*
### Phase 7 – Deployment Mittwald *(folgt)*
