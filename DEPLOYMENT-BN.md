# PhotoLabe — ওয়েব হোস্টিংয়ে ডিপ্লয় গাইড (বাংলা)

cPanel ভিত্তিক শেয়ার্ড হোস্টিংয়ে PhotoLabe (Laravel 12) ডিপ্লয় করার সম্পূর্ণ ধাপে ধাপে গাইড।

---

## ধাপ ০: শুরুর আগে যা যা দরকার

- cPanel অ্যাক্সেসসহ শেয়ার্ড হোস্টিং (PHP **8.2+**, MySQL 5.7+/8.0)
- আপনার ডোমেইন (যেমন `yourdomain.com`)
- FTP/File Manager অ্যাক্সেস অথবা SSH (দিলে সবচেয়ে সহজ)
- PHP এক্সটেনশন চালু থাকতে হবে: `pdo_mysql`, `mbstring`, `openssl`, `curl`, `gd`, `zip`, `fileinfo`, `exif`

> **PHP ভার্সন চেক:** cPanel → *Select PHP Version* বা *MultiPHP Manager* থেকে PHP 8.2 বা 8.3 সিলেক্ট করুন।

---

## ধাপ ১: লোকাল প্রজেক্ট প্রোডাকশন-রেডি করুন

লোকাল মেশিনে (G:\Web\photolab) প্রজেক্ট রুটে:

```bash
# ১. প্রোডাকশন ডিপেন্ডেন্সি ইনস্টল
composer install --no-dev --optimize-autoloader

# ২. Vite অ্যাসেট বিল্ড (CSS/JS)
npm run build
```

বিল্ড শেষে `public/build/` ফোল্ডারে কম্পাইল করা অ্যাসেট তৈরি হবে — এটা আপলোড বাধ্যতামূলক।

---

## ধাপ ২: ডাটাবেস তৈরি করুন (cPanel)

1. cPanel → **MySQL® Databases** খুলুন
2. নতুন ডাটাবেস তৈরি করুন: যেমন `cpaneluser_photolab`
3. নতুন ইউজার তৈরি করুন + শক্তিশালী পাসওয়ার্ড: যেমন `cpaneluser_photouser`
4. **Add User To Database** → ইউজারকে ডাটাবেসে যোগ করুন → **ALL PRIVILEGES** দিন
5. তিনটি মান নোট করে রাখুন: ডাটাবেস নাম, ইউজারনেম, পাসওয়ার্ড (cPanel সাধারণত ইউজারনেমের সামনে prefix বসায়)

---

## ধাপ ৩: ফাইল আপলোড করুন

Laravel-এর কাঠামো হবে এরকম:

```
/home/username/
├── laravel-app/          # পুরো প্রজেক্ট এখানে (public বাদে)
│   ├── app/
│   ├── bootstrap/
│   ├── config/
│   ├── database/
│   ├── resources/
│   ├── routes/
│   ├── storage/
│   ├── vendor/
│   ├── composer.json
│   └── ...
└── public_html/          # শুধু public/ ফোল্ডারের ভেতরের জিনিস
    ├── index.php
    ├── .htaccess
    └── build/
```

**পদ্ধতি A — File Manager (SSH না থাকলে):**

1. পুরো প্রজেক্টকে zip করে cPanel File Manager-এ `/home/username/`-এ আপলোড করে Extract করুন
2. ফোল্ডারের নাম দিন `laravel-app`
3. `laravel-app/public/`-এর **ভেতরের সব ফাইল** কপি করে `public_html/`-এ পেস্ট করুন (`.htaccess` সহ)

**পদ্ধতি B — SSH/SCP (দ্রুততম):**

```bash
# পুরো প্রজেক্ট
scp -r . user@server:/home/username/laravel-app/

# public কনটেন্ট আলাদা করে
ssh user@server "cp -r /home/username/laravel-app/public/* /home/username/public_html/"
```

> `.env` ও `.git` আপলোড করবেন না — `.env` পরের ধাপে সার্ভারে তৈরি করব।

---

## ধাপ ৪: `public_html/index.php` ঠিক করুন

cPanel File Manager-এ `public_html/index.php` এডিট করে পাথ পরিবর্তন করুন (`../laravel-app/...`):

```php
<?php

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

if (file_exists($maintenance = __DIR__.'/../laravel-app/storage/framework/maintenance.php')) {
    require $maintenance;
}

require __DIR__.'/../laravel-app/vendor/autoload.php';

(require_once __DIR__.'/../laravel-app/bootstrap/app.php')
    ->handleRequest(Request::capture());
```

---

## ধাপ ৫: `.env` তৈরি করুন

`laravel-app/` ফোল্ডারে `.env.example` কপি করে `.env` নাম দিন, তারপর এডিট করুন:

```env
APP_NAME="PhotoLabe"
APP_ENV=production
APP_KEY=                 # একটু পরে generate করব
APP_DEBUG=false          # প্রোডাকশনে অবশ্যই false
APP_URL=https://yourdomain.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=cpaneluser_photolab
DB_USERNAME=cpaneluser_photouser
DB_PASSWORD=আপনার_ডিবি_পাসওয়ার্ড

FILESYSTEM_DISK=local
QUEUE_CONNECTION=database
SESSION_DRIVER=database
CACHE_STORE=database

MAIL_MAILER=smtp
MAIL_HOST=mail.yourdomain.com   # অথবা হোস্টিং প্রোভাইডারের SMTP
MAIL_PORT=587
MAIL_USERNAME=noreply@yourdomain.com
MAIL_PASSWORD=মেইল_পাসওয়ার্ড
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@yourdomain.com"
MAIL_FROM_NAME="${APP_NAME}"
```

> **নিরাপত্তা:** `APP_DEBUG=false` না থাকলে এরর মেসেজে ডাটাবেস পাসওয়ার্ডসহ সব তথ্য ভিজিটর দেখে ফেলতে পারে!

---

## ধাপ ৬: পারমিশন সেট করুন

SSH/টার্মিনাল থাকলে:

```bash
cd /home/username/laravel-app
chmod -R 775 storage/ bootstrap/cache/
```

SSH না থাকলে File Manager-এ `storage/` ও `bootstrap/cache/` ফোল্ডারের পারমিশন **775** করুন (কিছু হোস্টে 755-ই যথেষ্ট — লগইন ইউজারের মালিকানা থাকলে)।

---

## ধাপ ৭: আর্টিসান কমান্ড চালান

cPanel → **Terminal** (অথবা SSH) খুলে:

```bash
cd /home/username/laravel-app

# ১. অ্যাপ কী জেনারেট
php artisan key:generate --force

# ২. ডাটাবেস টেবিল তৈরি
php artisan migrate --force

# ৩. স্টোরেজ সিমলিংক (লোগো/ব্যানার দেখানোর জন্য জরুরি)
php artisan storage:link

# ৪. হিরো স্লাইড সিড
php artisan db:seed --class=HeroSlideSeeder --force

# ৫. পারফরম্যান্স ক্যাশ
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan cache:clear
```

> **CLI নেই?** কিছু শেয়ার্ড হোস্টে আর্টিসান চালানো যায় না। তখন:
> - মাইগ্রেশন: লোকালে SQL ডাম্প নিয়ে phpMyAdmin-এ ইমপোর্ট করুন (`mysqldump --no-data` + `--data` আলাদা, অথবা সম্পূর্ণ ডাম্প)
> - `storage:link` করা না গেলে: `public_html/storage`-এ একটা ছোট PHP স্ক্রিপ্ট দিয়ে symlink বানান, অথবা `storage/app/public` কনটেন্ট সরাসরি কপি করুন
> - হিরো স্লাইড: অ্যাডমিন প্যানেলের **Admin → Hero Slides** থেকে ম্যানুয়ালি যোগ করুন (ইমেজ পাথ: `demo/hero/hero-jewelry.jpg` ইত্যাদি)

---

## ধাপ ৮: ব্র্যান্ড অ্যাসেট (লোগো + ব্যানার) আপলোড করুন

লোগো ও হিরো ব্যানার git-এ থাকে না, তাই ম্যানুয়ালি আপলোড করতে হবে:

| ফাইল | সার্ভারে পাথ | কোথায় ব্যবহৃত |
|---|---|---|
| `logo.png` | `storage/app/public/brand/` | হেডার, অ্যাডমিন সাইডবার, JSON-LD |
| `logo-mark.png` | `storage/app/public/brand/` | স্কয়ার আইকন |
| `footer-logo.png` | `storage\Web Banner\Logo\Web-Logo-2.png` | ফুটার ব্র্যান্ড প্যানেল (২০০০×২০০০ স্কয়ার লোগো) |
| `hero-jewelry.jpg` | `storage/app/public/demo/hero/` | হিরো স্লাইড ১ |
| `hero-headphone.jpg` | `storage/app/public/demo/hero/` | হিরো স্লাইড ২ |
| `hero-shoes.jpg` | `storage/app/public/demo/hero/` | হিরো স্লাইড ৩ |
| `hero-sunglass.jpg` | `storage/app/public/demo/hero/` | হিরো স্লাইড ৪ |
| `hero-model.jpg` | `storage/app/public/demo/hero/` | হিরো স্লাইড ৫ |
| `hero-extra-1.jpg` | `storage/app/public/demo/hero/` | হিরো স্লাইড ৬ |
| `hero-extra-2.jpg` | `storage/app/public/demo/hero/` | হিরো স্লাইড ৭ + লগইন প্যানেল |
| `hero-extra-3.jpg` | `storage/app/public/demo/hero/` | হিরো স্লাইড ৮ |

**সোর্স ফোল্ডার (লোকাল):** `G:\Web\photolab\storage\Web Banner\` — `Logo/` → brand, `Banner/` → hero।

File Manager দিয়ে আপলোড করুন অথবা SSH দিয়ে:

```bash
scp -r storage/app/public/brand storage/app/public/demo/hero \
    user@server:/home/username/laravel-app/storage/app/public/
```

**যাচাই:** হোমপেজের হেডারে আসল লোগো দেখা যাচ্ছে + হিরো স্লাইডারে ৮টি ডট।

---

## ধাপ ৯: Cron Job সেট করুন (স্কিডিউলার + কিউ)

cPanel → **Cron Jobs** → প্রতি মিনিটে:

```
* * * * * cd /home/username/laravel-app && php artisan schedule:run >> /dev/null 2>&1
```

কিউ (কন্টাক্ট/রিপ্লাই ইমেইল পাঠানোর জন্য দরকারি):

```
*/5 * * * * cd /home/username/laravel-app && php artisan queue:work --stop-when-empty >> /dev/null 2>&1
```

> কিউ ক্রন না থাকলে contact ফর্মের স্টাফ নোটিফিকেশন ও ইনবক্স রিপ্লাই ইমেইল পাঠানো হবে না।

---

## ধাপ ১০: SSL ও HTTPS ফোর্স

1. cPanel → **SSL/TLS Status** → ডোমেইনে Let's Encrypt সার্টিফিকেট চালু করুন (বেশিরভাগ হোস্ট ফ্রি দেয়)
2. `public_html/.htaccess`-এর শুরুতে যোগ করুন:

```apache
RewriteEngine On
RewriteCond %{HTTPS} off
RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]
```

3. `.htaccess`-এ session/cookie HTTPS-এ নিরাপদ রাখতে `.env` ঠিক রাখুন: `APP_URL=https://yourdomain.com`

---

## ধাপ ১১: ফাইনাল চেকলিস্ট

- [ ] হোমপেজ লোড হয়, লোগো দেখা যায়
- [ ] হিরো স্লাইডারে ৮টি ব্যানার ঘোরে
- [ ] Contact ও Quote ফর্ম সাবমিট হয়, ইনবক্সে এন্ট্রি পড়ে
- [ ] অ্যাডমিন লগইন (`/admin/login`) কাজ করে
- [ ] রিপ্লাই ইমেইল যায় (ক্রন/কিউ চালু থাকলে)
- [ ] `https://` এ গেলে তালা আইকন দেখা যায়
- [ ] `APP_DEBUG=false` কনফার্ম

---

## সমস্যা সমাধান (Troubleshooting)

| সমস্যা | সমাধান |
|---|---|
| **500 Error** | `storage/laravel.log` (পাথ: `laravel-app/storage/logs/laravel.log`) দেখুন; `.env` ফাইল আছে কিনা ও পারমিশন ঠিক কিনা যাচাই করুন |
| **CSS/JS লোড হচ্ছে না** | `npm run build` চেক করুন; `public_html/build/` আছে কিনা দেখুন |
| **লোগো/ব্যানার দেখাচ্ছে না** | `storage:link` চেক করুন (`public_html/storage` সিমলিংক থাকতে হবে) |
| **ডাটাবেস কানেকশন এরর** | DB_HOST সাধারণত `127.0.0.1` বা `localhost`; ক্রেডেনশিয়াল cPanel থেকে মিলিয়ে নিন |
| **ইমেইল যাচ্ছে না** | SMTP ক্রেডেনশিয়াল যাচাই করুন; হোস্টিং প্রোভাইডারের পোর্ট 25/465/587 সাপোর্ট দেখুন |
| **পেজ ক্যাশে আটকে** | `php artisan config:clear && php artisan cache:clear` চালান |
| **মাইগ্রেশন এরর** | PHP ভার্সন 8.2+ কিনা ও সব এক্সটেনশন চালু কিনা দেখুন |

---

## আপডেট ডিপ্লয় (পরবর্তীবার)

কোড পরিবর্তনের পর:

```bash
# লোকালে
npm run build

# পরিবর্তিত ফাইল আপলোড করুন (FTP/scp), তারপর সার্ভারে:
php artisan migrate --force
php artisan config:cache && php artisan route:cache && php artisan view:cache
php artisan cache:clear
```

---

সাহায্যের জন্য ইংরেজি বিস্তারিত গাইড: `DEPLOYMENT.md`
