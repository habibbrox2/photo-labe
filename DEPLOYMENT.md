# Deployment Guide — Shared Hosting (cPanel)

## Overview

This guide covers deploying PhotoLabe to a shared hosting environment using cPanel.

## Directory Structure on Server

```
/home/username/
├── laravel-app/              # Laravel application root
│   ├── app/
│   ├── bootstrap/
│   ├── config/
│   ├── database/
│   ├── resources/
│   ├── routes/
│   ├── storage/              # Writable by web server
│   └── vendor/
│
├── public_html/              # Web root (public only)
│   ├── index.php             # Laravel front controller
│   ├── .htaccess             # Apache rewrite rules
│   └── build/                # Vite compiled assets
```

## Step-by-Step Deployment

### 1. Upload Files

Upload the entire project to your hosting via FTP or File Manager.

### 2. Move Public Assets

Move the `public/` directory contents to `public_html/`:

```bash
# From project root
cp -r public/* public_html/
cp public/.htaccess public_html/
```

### 3. Update `public_html/index.php`

Edit `public_html/index.php` to point to the correct paths:

```php
<?php

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = __DIR__.'/../laravel-app/storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
require __DIR__.'/../laravel-app/vendor/autoload.php';

// Bootstrap Laravel and handle the request...
(require_once __DIR__.'/../laravel-app/bootstrap/app.php')
    ->handleRequest(Request::capture());
```

### 4. Environment Configuration

```bash
# Copy .env.example to .env
cp .env.example .env

# Edit .env with production settings
nano .env
```

Key production settings:

```env
APP_NAME="PhotoLabe"
APP_ENV=production
APP_KEY=base64:YOUR_KEY_HERE
APP_DEBUG=false
APP_URL=https://yourdomain.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_password

FILESYSTEM_DISK=local
QUEUE_CONNECTION=database
SESSION_DRIVER=database
CACHE_STORE=database

MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=587
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@yourdomain.com"
MAIL_FROM_NAME="${APP_NAME}"
```

### 5. Set Permissions

```bash
chmod -R 775 storage/
chmod -R 775 bootstrap/cache/
chmod -R 775 storage/app/
chmod -R 775 storage/framework/
chmod -R 775 storage/logs/
```

### 6. Run Artisan Commands

```bash
# Via SSH or cPanel Terminal
cd /home/username/laravel-app

php artisan migrate --force
php artisan storage:link
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize
```

### 7. Create `.htaccess` for `public_html`

```apache
<IfModule mod_rewrite.c>
    <IfModule mod_negotiation.c>
        Options -MultiViews -Indexes
    </IfModule>

    RewriteEngine On

    # Handle Front Controller...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]
</IfModule>
```

## Cron Jobs

Set up cron jobs in cPanel for the Laravel scheduler:

```
* * * * * cd /home/username/laravel-app && php artisan schedule:run >> /dev/null 2>&1
```

## Queue Workers

For database queue, use the scheduler to process jobs:

```php
// In app/Console/Kernel.php or routes/console.php
$schedule->command('queue:work --stop-when-empty')->everyMinute();
```

Or create a cron that runs queue workers periodically:

```
*/5 * * * * cd /home/username/laravel-app && php artisan queue:work --stop-when-empty >> /dev/null 2>&1
```

## File Storage

Private files are stored in `storage/app/private/` and are NOT publicly accessible.

Public files are stored in `storage/app/public/` and accessed via `storage/` symlink.

### Brand Assets (Logo + Hero Banners)

The site logo and homepage hero banners live in `storage/app/public/` and are
referenced by Blade templates as `asset('storage/...')`. Because user-uploaded
storage is not in git, these files must be copied to the server manually
**once** after the first deploy (and whenever they change):

| Local file (source) | Server path (destination) | Used by |
|---|---|---|
| `storage/app/public/brand/logo.png` | same | header, footer, admin sidebar, JSON-LD |
| `storage/app/public/brand/logo-mark.png` | same | square fallback icon |
| `storage/app/public/demo/hero/hero-jewelry.jpg` | same | hero slide 1 |
| `storage/app/public/demo/hero/hero-headphone.jpg` | same | hero slide 2 |
| `storage/app/public/demo/hero/hero-shoes.jpg` | same | hero slide 3 |
| `storage/app/public/demo/hero/hero-sunglass.jpg` | same | hero slide 4 |
| `storage/app/public/demo/hero/hero-model.jpg` | same | hero slide 5 |
| `storage/app/public/demo/hero/hero-extra-1.jpg` | same | hero slide 6 |
| `storage/app/public/demo/hero/hero-extra-2.jpg` | same | hero slide 7 + login panel background |
| `storage/app/public/demo/hero/hero-extra-3.jpg` | same | hero slide 8 |

Source of truth for originals: `G:\Web\photolab\storage\Web Banner\`
(`Logo/` → brand, `Banner/` → hero; copied via the mapping in the run doc
`.freebuff/run.md`). This folder is **not** committed to git.

Upload with cPanel File Manager, or from a machine with SSH access:

```bash
scp -r storage/app/public/brand storage/app/public/demo/hero \
    user@server:/home/username/laravel-app/storage/app/public/
```

Then on the server:

```bash
php artisan storage:link          # ensure public/storage symlink exists
php artisan db:seed --class=HeroSlideSeeder --force   # hero slide rows in DB
php artisan cache:clear           # hero slides are cached for 1 hour
```

> Hero slide rows live in the database (`hero_slides` table). If the seeder
> cannot run (shared hosting without CLI), create the rows via the admin panel
> at **Admin → Hero Slides** using the image paths above — the paths are
> relative to `storage/app/public/` (e.g. `demo/hero/hero-jewelry.jpg`).

To verify after deploy: the homepage header shows the real logo, and the hero
slider rotates through 8 slides (dots at bottom-right of the hero).

## SSL/HTTPS

1. Install SSL certificate via cPanel (Let's Encrypt recommended)
2. Force HTTPS in `.env`:
   ```env
   APP_URL=https://yourdomain.com
   ```
3. Add to `public_html/.htaccess`:
   ```apache
   RewriteCond %{HTTPS} off
   RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]
   ```

## Performance

Enable OPcache in cPanel or `.htaccess`:

```apache
<IfModule mod_php.c>
    php_flag opcache.enable 1
    php_flag opcache.enable_cli 1
    php_value opcache.memory_consumption 128
    php_value opcache.max_accelerated_files 10000
</IfModule>
```

## Troubleshooting

| Issue | Solution |
|-------|----------|
| 500 Error | Check `storage/logs/laravel.log`, verify `.env` |
| Assets not loading | Run `npm run build`, verify `public_html/build/` |
| File upload fails | Check `storage/` permissions (775) |
| Queue not processing | Verify cron job is running |
| Email not sending | Check SMTP credentials in `.env` |
