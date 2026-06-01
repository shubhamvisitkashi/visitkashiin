#!/usr/bin/env bash
# ============================================================
# Visit Kashi — Production Deployment Script
# Run after every: git pull origin main
#
# Usage:  bash deploy.sh
# ============================================================
set -e

echo ""
echo "╔══════════════════════════════════════════════════╗"
echo "║       Visit Kashi — Deployment Script            ║"
echo "╚══════════════════════════════════════════════════╝"
echo ""

# ── 0. Safety check ──────────────────────────────────────────
if [ ! -f ".env" ]; then
    echo "❌  ERROR: .env file not found!"
    echo "    Copy .env.example to .env and fill in production values."
    echo "    cp .env.example .env && nano .env"
    exit 1
fi

APP_ENV_VALUE=$(grep "^APP_ENV=" .env | cut -d'=' -f2 | tr -d "'" | tr -d '"')
if [ "$APP_ENV_VALUE" = "local" ]; then
    echo "⚠️  WARNING: APP_ENV=local detected in .env on production."
    echo "   Set APP_ENV=production before deploying."
    read -p "   Continue anyway? (y/N): " confirm
    [ "$confirm" != "y" ] && exit 1
fi

# ── 1. Composer dependencies (production, no dev packages) ───
echo "📦  Installing Composer dependencies..."
composer install --no-dev --optimize-autoloader --no-interaction --quiet
echo "    ✓ Composer done"

# ── 2. Clear all caches FIRST (prevents stale config on boot) ─
echo "🧹  Clearing all caches..."
php artisan optimize:clear
echo "    ✓ Caches cleared"

# ── 3. Storage symlink ────────────────────────────────────────
echo "🔗  Creating storage symlink..."
php artisan storage:link --quiet 2>/dev/null || echo "    (symlink already exists)"
echo "    ✓ Storage symlink OK"

# ── 4. Fix permissions ────────────────────────────────────────
echo "🔐  Setting permissions..."
chmod -R 775 storage
chmod -R 775 bootstrap/cache
find storage -type f -exec chmod 664 {} \;
find bootstrap/cache -type f -exec chmod 664 {} \;
echo "    ✓ Permissions set (775 dirs, 664 files)"

# ── 5. Cache config & views (NOT routes — wildcard routes) ────
echo "⚡  Caching config and views..."
php artisan config:cache
php artisan view:cache
# NOTE: php artisan route:cache is intentionally SKIPPED
# This project uses wildcard catch-all routes (/{slug}, /{a}/{b})
# which are incompatible with route caching.
echo "    ✓ Config and views cached"

# ── 6. Generate WebP images for newly uploaded files ──────────
echo "🖼️   Converting new images to WebP..."
php artisan images:webp --quality=82 2>/dev/null || echo "    (images:webp command not yet available)"
echo "    ✓ Image conversion done"

# ── 7. Minify CSS/JS assets ───────────────────────────────────
echo "📦  Minifying CSS/JS assets..."
php artisan assets:minify 2>/dev/null || echo "    (assets:minify command not yet available)"
echo "    ✓ Minification done"

# ── 8. Clean debugbar storage (can fill disk on production) ───
echo "🗑️   Cleaning debugbar storage..."
rm -rf storage/debugbar/* 2>/dev/null || true
echo "    ✓ Debugbar storage cleaned"

echo ""
echo "╔══════════════════════════════════════════════════╗"
echo "║   ✅  Deployment complete!                        ║"
echo "╚══════════════════════════════════════════════════╝"
echo ""
echo "  Validate at: $(grep '^APP_URL=' .env | cut -d'=' -f2)"
echo ""
