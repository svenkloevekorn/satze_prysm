#!/usr/bin/env bash
# =========================================================
# Staeze PM – Deployment-Skript
# =========================================================
# Wird auf dem Server ausgeführt (z.B. von GitHub Actions aus via SSH).
# Voraussetzung: Code ist bereits aktualisiert (git pull / rsync erfolgt).
#
# Usage: bash deploy.sh

set -euo pipefail

echo "🚀 Staeze PM Deployment"
echo "========================"

# 1. In Wartungsmodus gehen (optional – nur bei Migrations mit Breaking Changes)
# php artisan down --render="errors::503" || true

# 2. Composer-Abhängigkeiten (ohne dev, optimiert)
echo "📦 Composer install"
composer install --no-dev --prefer-dist --optimize-autoloader --no-interaction --no-progress

# 3. Datenbank-Migrationen
echo "🗃️  Migrate"
php artisan migrate --force

# 4. Permission-Cache + Shield neu laden
echo "🛡️  Permissions aktualisieren"
php artisan permission:cache-reset

# 5. Caches neu bauen
echo "🧹 Caches leeren + aufbauen"
php artisan config:clear
php artisan route:clear
php artisan view:clear

php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

# Filament-spezifisch
php artisan icons:cache
php artisan filament:optimize

# 6. Storage-Symlink (falls nötig, einmalig)
if [ ! -L public/storage ]; then
    echo "🔗 Storage-Symlink"
    php artisan storage:link
fi

# 7. Frontend-Assets (falls vorhanden)
if [ -f package.json ]; then
    echo "🎨 npm build"
    npm ci --omit=dev
    npm run build
fi

# 8. Queue-Worker neu starten (falls Horizon/Queue läuft)
# php artisan queue:restart

# 9. Maintenance aus
# php artisan up

echo "✅ Deployment fertig"
