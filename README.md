<div align="center">

# PhotoLabe

**Creative Services + Photo Editing + Digital Products Platform**

A production-ready Laravel platform for professional photo editing services, portfolio showcase, and digital product sales. Optimized for shared hosting (cPanel).

<br/>

![Laravel](https://img.shields.io/badge/Laravel-12-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?style=for-the-badge&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-8.0+-4479A1?style=for-the-badge&logo=mysql&logoColor=white)
![Tailwind CSS](https://img.shields.io/badge/Tailwind-v4-06B6D4?style=for-the-badge&logo=tailwind-css&logoColor=white)
![Alpine.js](https://img.shields.io/badge/Alpine.js-3-8BC0D0?style=for-the-badge&logo=alpine.js&logoColor=white)
![Vite](https://img.shields.io/badge/Vite-Build-646CFF?style=for-the-badge&logo=vite&logoColor=white)

![License](https://img.shields.io/badge/License-MIT-22C55E?style=for-the-badge)
![Status](https://img.shields.io/badge/Status-Production_Ready-22C55E?style=for-the-badge)
![Hosting](https://img.shields.io/badge/Hosting-cPanel_Compatible-F59E0B?style=for-the-badge)

<br/>

[**Features**](#-features) · [**Tech Stack**](#-tech-stack) • [**Installation**](#-installation) • [**Documentation**](#-documentation) • [**License**](#-license)

---

</div>

## Table of Contents

- [Overview](#-overview)
- [Features](#-features)
- [Tech Stack](#-tech-stack)
- [Requirements](#-requirements)
- [Installation](#-installation)
- [Default Accounts](#-default-accounts)
- [Project Structure](#-project-structure)
- [Development](#-development)
- [Testing](#-testing)
- [Documentation](#-documentation)
- [License](#-license)

---

## Overview

**PhotoLabe** is a complete business platform built for creative studios that offer:

- Professional photo editing services (clipping path, retouching, restoration, etc.)
- Portfolio showcasing
- Digital product sales (presets, LUTs, templates, brushes)
- Customer engagement through quote requests

The application is **shared-hosting friendly** (cPanel-compatible) using database queues and works out of the box on most affordable hosting providers.

---

## Features

### Public Website

| Module | Description |
|---|---|
| **Homepage** | Hero, services, before/after slider, portfolio, products, testimonials, FAQ |
| **Services** | Category filtering and detailed service pages |
| **Portfolio** | Category filtering, gallery lightbox, tags |
| **Before/After** | Interactive slider with mouse, touch, and keyboard support |
| **Products** | Digital downloads with cart and checkout |
| **Quotes** | Quote request form with file uploads |
| **Contact** | Contact form |
| **CMS Pages** | Dynamic pages |

### Admin Panel

| Module | Description |
|---|---|
| **Dashboard** | Revenue, orders, quotes, customers statistics |
| **CRUD Management** | Services, Portfolio, Products, Pages, Testimonials, Before/After |
| **Quote Workflow** | Status management |
| **Order Management** | Status updates and tracking |
| **Customers** | Customer management |
| **Media Library** | Upload and organize assets |
| **Reviews** | Approve / reject moderation |
| **Settings** | Site-wide configuration |

### Customer Dashboard

- Profile management
- Order history
- Quote history

### SEO & Performance

- Dynamic `sitemap.xml`
- `robots.txt`
- Schema.org JSON-LD (Organization, Product, Article)
- Open Graph and Twitter Card meta tags
- Canonical URLs

### Security

- CSRF protection on every form
- Role-based middleware (`admin`, `editor`)
- Authorization policies for **7 models**
- Private file storage
- Password hashing (bcrypt)
- Session regeneration on login
- Rate limiting ready

---

## Tech Stack

| Layer | Technology |
|---|---|
| **Backend Framework** | Laravel 12 |
| **Language** | PHP 8.2+ |
| **Database** | MySQL 8.0+ |
| **Frontend** | Blade Templates |
| **Styling** | Tailwind CSS v4 |
| **Interactivity** | Alpine.js |
| **Build Tool** | Vite |
| **Queue Driver** | Database (shared-hosting compatible) |
| **Authentication** | Custom with roles |

**Roles supported:** `super_admin`, `admin`, `editor`, `designer`, `customer`

---

## Requirements

Make sure your environment has:

- **PHP 8.2+** with extensions:
  - `pdo_mysql`
  - `mbstring`
  - `openssl`
  - `tokenizer`
  - `xml`
  - `curl`
  - `gd`
- **MySQL 8.0+**
- **Composer 2.x**
- **Node.js 18+** (for frontend build)

---

## Installation

Follow the steps below to set up the project locally.

### 1. Clone & Install Dependencies

```bash
git clone <repository-url> photolabe
cd photolabe
composer install
npm install
```

### 2. Environment Setup

```bash
cp .env.example .env
php artisan key:generate
```

Then edit `.env` with your credentials:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=photolab_db
DB_USERNAME=root
DB_PASSWORD=

MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=587
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@photolabe.com"
MAIL_FROM_NAME="${APP_NAME}"
```

### 3. Database Setup

```bash
mysql -u root -e "CREATE DATABASE photolab_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci"

php artisan migrate:fresh --seed
```

### 4. Storage Symlink

```bash
php artisan storage:link
```

### 5. Build Frontend Assets

```bash
npm run build
```

### 6. Start the Development Server

```bash
php artisan serve
```

Visit **[http://localhost:8000](http://localhost:8000)** in your browser.

---

## Default Accounts

> Change these credentials immediately in any production environment.

| Role | Email | Password |
|---|---|---|
| Super Admin | `admin@photolabe.com` | `password` |
| Editor | `editor@photolabe.com` | `password` |
| Designer | `designer@photolabe.com` | `password` |
| Customer | `john@example.com` | `password` |
| Customer | `jane@example.com` | `password` |

---

## Project Structure

<details>
<summary><b>Click to expand the project structure</b></summary>

```
photolabe/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/          # 11 admin controllers
│   │   │   ├── Frontend/       # 9 frontend controllers
│   │   │   ├── AuthController.php
│   │   │   └── Controller.php
│   │   ├── Middleware/
│   │   │   ├── AdminMiddleware.php
│   │   │   └── EditorMiddleware.php
│   │   └── Requests/
│   ├── Models/                 # 30+ Eloquent models
│   ├── Enums/                  # 6 PHP backed enums
│   ├── Policies/               # 7 authorization policies
│   ├── Services/
│   ├── Notifications/
│   ├── Jobs/
│   └── Support/
│
├── resources/
│   ├── views/
│   │   ├── admin/              # Admin panel views
│   │   ├── auth/               # Authentication views
│   │   ├── components/         # Reusable Blade components
│   │   ├── customer/           # Customer dashboard views
│   │   ├── frontend/           # Public website views
│   │   └── layouts/            # Layout templates
│   ├── css/
│   └── js/
│
├── routes/
│   ├── web.php                 # 120+ routes
│   └── console.php
│
└── database/
    ├── migrations/             # 14 migration files
    ├── seeders/                # 8 seeders
    └── factories/
```

</details>

---

## Development

### Start all development services at once

```bash
composer dev
```

### Or run them individually

```bash
php artisan serve          # HTTP server
php artisan queue:listen   # Queue worker
npm run dev                # Vite dev server with HMR
```

---

## Testing

```bash
# Run all tests (uses the in-memory SQLite test database automatically)
php artisan test

# Run a single suite
php artisan test --filter=QuoteFlowTest

# Run with coverage report
php artisan test --coverage
```

---

## Documentation

Additional documentation is available in the repository:

| Document | Description |
|---|---|
| [DEPLOYMENT.md](DEPLOYMENT.md) | Shared hosting deployment guide |
| [SECURITY.md](SECURITY.md) | Security documentation and best practices |
| [DATABASE.md](DATABASE.md) | Database schema documentation |

---

## Contributing

Contributions are welcome. Please:

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

---

## License

Distributed under the **MIT License**. See `LICENSE` for more information.

---

<div align="center">

**Built with Laravel, Tailwind, and Alpine.js**

If you find this project useful, consider giving it a star on GitHub.

</div>