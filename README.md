# PicLab — Creative Services + Photo Editing + Digital Products Platform

A production-ready Laravel platform for professional photo editing services, portfolio showcase, and digital product sales. Optimized for shared hosting (cPanel).

## Tech Stack

- **Backend:** Laravel 12, PHP 8.2+, MySQL 8+
- **Frontend:** Blade, Tailwind CSS v4, Alpine.js, Vite
- **Queue:** Database (shared hosting compatible)
- **Auth:** Custom with roles (super_admin, admin, editor, designer, customer)

## Features

### Public Website
- Homepage with hero, services, before/after slider, portfolio, products, testimonials, FAQ
- Service listings with category filtering and detail pages
- Portfolio with category filtering, gallery lightbox, and tags
- Before/After interactive slider (mouse, touch, keyboard)
- Digital products with cart and checkout
- Blog with categories and tags
- Quote request form with file uploads
- Contact form
- Dynamic CMS pages

### Admin Panel
- Dashboard with stats (revenue, orders, quotes, customers)
- Full CRUD for: Services, Portfolio, Products, Blog, Pages, Testimonials, Before/After
- Quote management with status workflow
- Order management with status updates
- Customer management
- Media library with upload
- Review moderation (approve/reject)
- Site settings management

### Customer Dashboard
- Profile management
- Order history
- Quote history

### SEO
- Dynamic sitemap.xml
- robots.txt
- Schema.org JSON-LD (Organization, Product, Article)
- Open Graph + Twitter Card meta tags
- Canonical URLs

### Security
- CSRF protection on all forms
- Role-based middleware (admin, editor)
- Authorization policies (7 models)
- Private file storage
- Password hashing (bcrypt)
- Session regeneration on login
- Rate limiting ready

## Requirements

- PHP 8.2+ with extensions: pdo_mysql, mbstring, openssl, tokenizer, xml, curl, gd
- MySQL 8.0+
- Composer 2.x
- Node.js 18+ (for frontend build)

## Installation

### 1. Clone & Install

```bash
git clone <repository-url> piclab
cd piclab
composer install
npm install
```

### 2. Environment Setup

```bash
cp .env.example .env
php artisan key:generate
```

Edit `.env` with your database credentials:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=piclab
DB_USERNAME=root
DB_PASSWORD=

MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=587
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@piclab.com"
MAIL_FROM_NAME="${APP_NAME}"
```

### 3. Database Setup

```bash
# Create database (via phpMyAdmin or CLI)
mysql -u root -e "CREATE DATABASE piclab CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci"

# Run migrations and seeders
php artisan migrate:fresh --seed
```

### 4. Storage Link

```bash
php artisan storage:link
```

### 5. Build Frontend

```bash
npm run build
```

### 6. Start Development Server

```bash
php artisan serve
```

Visit: `http://localhost:8000`

## Default Accounts

| Role | Email | Password |
|------|-------|----------|
| Super Admin | admin@piclab.com | password |
| Editor | editor@piclab.com | password |
| Designer | designer@piclab.com | password |
| Customer | john@example.com | password |
| Customer | jane@example.com | password |

## Project Structure

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Admin/          # 11 admin controllers
│   │   ├── Frontend/       # 9 frontend controllers
│   │   ├── AuthController.php
│   │   └── Controller.php
│   ├── Middleware/
│   │   ├── AdminMiddleware.php
│   │   └── EditorMiddleware.php
│   └── Requests/
├── Models/                 # 30+ Eloquent models
├── Enums/                  # 6 PHP backed enums
├── Policies/               # 7 authorization policies
├── Services/
├── Notifications/
├── Jobs/
└── Support/

resources/
├── views/
│   ├── admin/              # Admin panel views
│   ├── auth/               # Authentication views
│   ├── components/         # Reusable Blade components
│   ├── customer/           # Customer dashboard views
│   ├── frontend/           # Public website views
│   └── layouts/            # Layout templates
├── css/
└── js/

routes/
├── web.php                 # 120+ routes
└── console.php

database/
├── migrations/             # 14 migration files
├── seeders/                # 8 seeders
└── factories/
```

## Development

```bash
# Start all development services
composer dev

# Or individually:
php artisan serve          # HTTP server
php artisan queue:listen   # Queue worker
npm run dev                # Vite dev server with HMR
```

## Testing

```bash
# Run all tests
php test

# Run with coverage
php test --coverage
```

## Production Deployment

See [DEPLOYMENT.md](DEPLOYMENT.md) for shared hosting deployment guide.

## Security

See [SECURITY.md](SECURITY.md) for security documentation.

## Database

See [DATABASE.md](DATABASE.md) for database schema documentation.

## License

MIT License
