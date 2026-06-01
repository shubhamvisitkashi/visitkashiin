#!/usr/bin/env bash
# ============================================================
# visitkashi.in — ONE-COMMAND SERVER FIX
# Run this on the production server via SSH:
#   bash server-fix.sh
# ============================================================
set -e
RED='\033[0;31m'; GREEN='\033[0;32m'; YELLOW='\033[1;33m'; BLUE='\033[0;34m'; NC='\033[0m'
ok()   { echo -e "${GREEN}✅ $1${NC}"; }
warn() { echo -e "${YELLOW}⚠️  $1${NC}"; }
err()  { echo -e "${RED}❌ $1${NC}"; }
info() { echo -e "${BLUE}▶  $1${NC}"; }

echo ""
echo -e "${BLUE}╔══════════════════════════════════════════════╗${NC}"
echo -e "${BLUE}║   visitkashi.in — Live Server Fix Script     ║${NC}"
echo -e "${BLUE}╚══════════════════════════════════════════════╝${NC}"
echo ""

# ── STEP 1: Pull latest code ──────────────────────────────────
info "Pulling latest code from GitHub..."
git pull origin main
ok "Code pulled"

# ── STEP 2: Create/verify .env ────────────────────────────────
info "Checking .env file..."
if [ ! -f ".env" ]; then
    err ".env file NOT found!"
    echo ""
    echo "  Create it now with these production values:"
    echo "  ─────────────────────────────────────────────"
    echo "  APP_NAME='Visit Kashi'"
    echo "  APP_ENV=production"
    echo "  APP_KEY=base64:$(php artisan key:generate --show 2>/dev/null || echo 'RUN: php artisan key:generate')"
    echo "  APP_DEBUG=false"
    echo "  APP_URL=https://visitkashi.in"
    echo "  APP_DOMAIN=visitkashi.in"
    echo "  LOG_CHANNEL=daily"
    echo "  LOG_LEVEL=error"
    echo "  DB_CONNECTION=mysql"
    echo "  DB_HOST=127.0.0.1"
    echo "  DB_PORT=3306"
    echo "  DB_DATABASE=YOUR_DB_NAME"
    echo "  DB_USERNAME=YOUR_DB_USER"
    echo "  DB_PASSWORD=YOUR_DB_PASS"
    echo "  CACHE_DRIVER=file"
    echo "  SESSION_DRIVER=file"
    echo "  SESSION_LIFETIME=120"
    echo "  MAIL_MAILER=smtp"
    echo "  MAIL_HOST=smtp.googlemail.com"
    echo "  MAIL_PORT=465"
    echo "  MAIL_USERNAME=help.visitkashi@gmail.com"
    echo "  MAIL_PASSWORD=YOUR_MAIL_APP_PASSWORD"
    echo "  MAIL_ENCRYPTION=ssl"
    echo "  MAIL_FROM_ADDRESS=help.visitkashi@gmail.com"
    echo "  MAIL_FROM_NAME='Visit Kashi'"
    echo "  ─────────────────────────────────────────────"
    echo ""
    read -p "  Press ENTER after creating .env to continue..."
fi

# Verify critical .env values
APP_DEBUG_VAL=$(grep "^APP_DEBUG=" .env | cut -d'=' -f2 | tr -d "'" | tr -d '"' | tr -d ' ')
APP_ENV_VAL=$(grep "^APP_ENV=" .env | cut -d'=' -f2 | tr -d "'" | tr -d '"' | tr -d ' ')
APP_URL_VAL=$(grep "^APP_URL=" .env | cut -d'=' -f2 | tr -d "'" | tr -d '"' | tr -d ' ')

[ "$APP_DEBUG_VAL" = "false" ] && ok ".env: APP_DEBUG=false" || warn ".env: APP_DEBUG should be false (currently: $APP_DEBUG_VAL)"
[ "$APP_ENV_VAL" = "production" ] && ok ".env: APP_ENV=production" || warn ".env: APP_ENV should be production (currently: $APP_ENV_VAL)"
echo "$APP_URL_VAL" | grep -q "visitkashi.in" && ok ".env: APP_URL=$APP_URL_VAL" || warn ".env: APP_URL should be https://visitkashi.in (currently: $APP_URL_VAL)"

# ── STEP 3: Install composer dependencies ────────────────────
info "Installing Composer dependencies (production mode)..."
composer install --no-dev --optimize-autoloader --no-interaction --quiet
ok "Composer done"

# ── STEP 4: Generate APP_KEY if missing ──────────────────────
APP_KEY_VAL=$(grep "^APP_KEY=" .env | cut -d'=' -f2 | tr -d ' ')
if [ -z "$APP_KEY_VAL" ] || [ "$APP_KEY_VAL" = "" ]; then
    info "Generating APP_KEY..."
    php artisan key:generate --force
    ok "APP_KEY generated"
else
    ok "APP_KEY already set"
fi

# ── STEP 5: Run database migrations ──────────────────────────
info "Running database migrations..."
php artisan migrate --force 2>&1 | tail -5
ok "Migrations done"

# ── STEP 6: Clear ALL caches first ───────────────────────────
info "Clearing all caches..."
php artisan optimize:clear 2>/dev/null || {
    php artisan view:clear
    php artisan config:clear
    php artisan cache:clear
    php artisan route:clear
}
ok "All caches cleared"

# ── STEP 7: Create storage symlink ───────────────────────────
info "Setting up storage symlink..."
php artisan storage:link 2>/dev/null || ok "Storage symlink already exists"
ok "Storage symlink ready"

# ── STEP 8: Set correct permissions ──────────────────────────
info "Fixing directory permissions..."
chmod -R 775 storage
chmod -R 775 bootstrap/cache
find storage -type d -exec chmod 775 {} \; 2>/dev/null
find storage -type f -exec chmod 664 {} \; 2>/dev/null
find bootstrap/cache -type f -exec chmod 664 {} \; 2>/dev/null
chmod 755 public
ok "Permissions set (775 dirs / 664 files)"

# ── STEP 9: Rebuild caches (config + views only, NOT routes) ─
info "Rebuilding config and view cache..."
php artisan config:cache
php artisan view:cache
# NOTE: php artisan route:cache is intentionally skipped
# (wildcard routes /{slug}, /{a}/{b} break with route cache)
ok "Config and views cached"

# ── STEP 10: Minify assets ───────────────────────────────────
info "Minifying CSS/JS assets..."
php artisan assets:minify 2>/dev/null && ok "Assets minified" || warn "assets:minify not available — skipped"

# ── STEP 11: Convert images to WebP ──────────────────────────
info "Converting images to WebP..."
php artisan images:webp --quality=82 2>/dev/null && ok "Images converted" || warn "images:webp not available — skipped"

# ── STEP 12: Clean debugbar storage ──────────────────────────
info "Cleaning debugbar storage..."
rm -rf storage/debugbar/* 2>/dev/null && ok "Debugbar cleaned" || ok "No debugbar files"

# ── STEP 13: Apache checks ───────────────────────────────────
echo ""
info "Checking Apache modules..."
if command -v apache2ctl &>/dev/null; then
    apache2ctl -M 2>/dev/null | grep -q "rewrite_module" && ok "mod_rewrite enabled" || {
        err "mod_rewrite NOT enabled!"
        echo "  Run: sudo a2enmod rewrite && sudo systemctl restart apache2"
    }
    apache2ctl -M 2>/dev/null | grep -q "headers_module" && ok "mod_headers enabled" || warn "mod_headers not enabled"
    apache2ctl -M 2>/dev/null | grep -q "expires_module" && ok "mod_expires enabled" || warn "mod_expires not enabled"
elif command -v httpd &>/dev/null; then
    httpd -M 2>/dev/null | grep -q "rewrite_module" && ok "mod_rewrite enabled" || err "mod_rewrite NOT enabled!"
else
    warn "Cannot check Apache modules — check manually"
fi

# ── FINAL SUMMARY ────────────────────────────────────────────
echo ""
echo -e "${GREEN}╔══════════════════════════════════════════════╗${NC}"
echo -e "${GREEN}║   ✅  Server fix complete!                    ║${NC}"
echo -e "${GREEN}╚══════════════════════════════════════════════╝${NC}"
echo ""
echo "  URL: $APP_URL_VAL"
echo ""
echo "  If still getting 403, run on the server:"
echo "  sudo a2enmod rewrite headers expires"
echo "  sudo systemctl restart apache2"
echo ""
echo "  Apache VirtualHost must have:"
echo "  <Directory /your/path/public>"
echo "      AllowOverride All"
echo "      Require all granted"
echo "  </Directory>"
echo ""
