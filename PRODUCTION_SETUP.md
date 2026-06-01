# Visit Kashi — Production Server Setup Guide

> This file is for the server admin only. Do NOT commit production credentials.

## 1. SSH into the server

```bash
ssh user@visitkashi.in
cd /var/www/visitkashiin   # or your actual path
```

## 2. Pull latest code

```bash
git pull origin main
```

## 3. Create / update .env on the production server

The `.env` file is NOT in git (intentionally). Create it once:

```bash
cp .env.example .env
nano .env
```

Set these values for production:

```env
APP_NAME='Visit Kashi'
APP_ENV=production
APP_KEY=base64:YOUR_KEY_HERE          # run: php artisan key:generate
APP_DEBUG=false
APP_URL=https://visitkashi.in
APP_DOMAIN=visitkashi.in

LOG_CHANNEL=daily
LOG_LEVEL=error

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=visitkashiin_prod         # your production DB name
DB_USERNAME=visitkashi_user           # your production DB user
DB_PASSWORD=YOUR_STRONG_PASSWORD

CACHE_DRIVER=file
SESSION_DRIVER=file
SESSION_LIFETIME=120
QUEUE_CONNECTION=sync

MAIL_MAILER=smtp
MAIL_HOST=smtp.googlemail.com
MAIL_PORT=465
MAIL_USERNAME=help.visitkashi@gmail.com
MAIL_PASSWORD=YOUR_APP_PASSWORD
MAIL_ENCRYPTION=ssl
MAIL_FROM_ADDRESS=help.visitkashi@gmail.com
MAIL_FROM_NAME='Visit Kashi'
```

## 4. Run the deployment script

```bash
bash deploy.sh
```

This automatically runs:
- composer install (--no-dev)
- php artisan optimize:clear
- php artisan storage:link
- chmod 775 storage/ bootstrap/cache/
- php artisan config:cache
- php artisan view:cache
- php artisan images:webp
- php artisan assets:minify

## 5. Apache VirtualHost (production)

```apache
<VirtualHost *:80>
    ServerName visitkashi.in
    ServerAlias www.visitkashi.in
    Redirect permanent / https://visitkashi.in/
</VirtualHost>

<VirtualHost *:443>
    ServerName visitkashi.in
    ServerAlias www.visitkashi.in
    DocumentRoot /var/www/visitkashiin/public

    <Directory /var/www/visitkashiin/public>
        AllowOverride All
        Require all granted
        Options -Indexes
    </Directory>

    SSLEngine on
    SSLCertificateFile    /etc/letsencrypt/live/visitkashi.in/fullchain.pem
    SSLCertificateKeyFile /etc/letsencrypt/live/visitkashi.in/privkey.pem

    ErrorLog  /var/log/apache2/visitkashi-error.log
    CustomLog /var/log/apache2/visitkashi-access.log combined
</VirtualHost>
```

## 6. Quick rollback

```bash
git log --oneline -5          # find the good commit hash
git checkout <HASH>            # rollback to that commit
bash deploy.sh                 # redeploy
```

## 7. Permission reference

| Path                | Dirs  | Files |
|---------------------|-------|-------|
| storage/            | 775   | 664   |
| bootstrap/cache/    | 775   | 664   |
| public/             | 755   | 644   |

Owner: www-data:www-data (on Ubuntu/Debian)
       apache:apache   (on CentOS/RHEL)
