# Visit Kashi — Varanasi Travel Platform

**Visit Kashi** is Varanasi's #1 travel platform for boat rides, hotel bookings, cab services, and tour packages.

🌐 Live: [visitkashi.in](https://visitkashi.in)

---

## Tech Stack

| Layer | Technology |
|-------|-----------|
| Backend | PHP 8.x + Laravel 8 |
| Frontend | Bootstrap 5 + Vanilla JS + Plus Jakarta Sans |
| Database | MySQL |
| Server | Apache (XAMPP local / Linux production) |
| Version Control | Git + GitHub |

---

## Local Development Setup

### Requirements
- PHP 8.0+
- MySQL 5.7+
- Composer
- XAMPP (macOS/Windows) or any Apache+PHP environment

### Install

```bash
# Clone the repo
git clone https://github.com/shubhamvisitkashi/visitkashiin.git
cd visitkashiin

# Install PHP dependencies
composer install

# Set up environment
cp .env.example .env
php artisan key:generate

# Edit .env with your local DB credentials
nano .env

# Run database migrations
php artisan migrate

# Create storage symlink
php artisan storage:link

# Set permissions
chmod -R 777 storage bootstrap/cache
```

### Access locally
```
http://localhost/visitkashiin/public/
```

---

## Production Deployment

### First-time setup on server

```bash
git clone https://github.com/shubhamvisitkashi/visitkashiin.git
cd visitkashiin
cp .env.example .env
nano .env          # fill in production values
bash server-fix.sh
```

### Deploy updates

```bash
git pull origin main
bash deploy.sh
```

> See [PRODUCTION_SETUP.md](PRODUCTION_SETUP.md) for full Apache VirtualHost + SSL config.

---

## Key Files

| File | Purpose |
|------|---------|
| `deploy.sh` | One-command production deployment |
| `server-fix.sh` | Full server setup + repair script |
| `.env.example` | Environment variable template |
| `PRODUCTION_SETUP.md` | Apache VirtualHost + SSL guide |
| `app/Console/Commands/MinifyAssets.php` | `php artisan assets:minify` |
| `app/Console/Commands/ConvertImagesToWebP.php` | `php artisan images:webp` |

---

## Admin Panel

```
https://visitkashi.in/admin
```

---

## Important Notes

- **Never commit `.env`** — it contains database and mail credentials
- **Run `php artisan route:clear`** after deployment — wildcard routes (`/{slug}`) are incompatible with `route:cache`
- **Images are gitignored** — upload via Admin Panel after deployment
- **Minified assets** are gitignored — run `php artisan assets:minify` after CSS changes
