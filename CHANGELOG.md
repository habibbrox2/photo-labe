# Changelog

All notable changes to the PicLab project will be documented in this file.

---

## [Milestone 1] - 2026-09-02

### ✅ Project Architecture & Foundation

#### Setup
- Created Laravel 12.69.1 project with PHP 8.2.12
- Configured MySQL database connection (phpMyAdmin/XAMPP)
- Installed Tailwind CSS v4 + Vite build system
- Configured `.env` for shared hosting compatibility

#### Database (14 migrations)
- **users** - Extended with role, phone, avatar, address, soft deletes
- **service_categories** - Service category management
- **services** - Service listings with pricing, features, SEO
- **service_features** - Individual service feature items
- **service_pricing** - Service pricing tiers
- **portfolio_categories** - Portfolio category management
- **portfolio_projects** - Portfolio project listings
- **portfolio_images** - Portfolio gallery images
- **portfolio_tags** - Portfolio tag system
- **portfolio_project_tag** - Many-to-many pivot
- **before_after_categories** - Before/after categories
- **before_after_projects** - Before/after slider items
- **product_categories** - Digital product categories
- **products** - Product listings with pricing
- **product_images** - Product gallery images
- **product_files** - Downloadable product files
- **quotes** - Customer quote requests
- **quote_items** - Individual quote line items
- **quote_files** - Uploaded quote files
- **orders** - Order management
- **order_items** - Order line items
- **order_files** - Input/output files
- **order_revisions** - Revision requests
- **order_messages** - Order messaging
- **payments** - Payment records
- **transactions** - Payment transactions
- **invoices** - Invoice generation
- **pages** - CMS pages
- **blog_categories** - Blog category management
- **blog_posts** - Blog post listings
- **blog_tags** - Blog tag system
- **blog_post_tag** - Many-to-many pivot
- **reviews** - Polymorphic review system
- **testimonials** - Client testimonials
- **media** - Media library
- **media_folders** - Media folder management
- **support_tickets** - Support ticket system
- **support_ticket_messages** - Ticket messaging
- **settings** - Centralized settings
- **coupons** - Discount coupon system
- **coupon_usages** - Coupon usage tracking
- **carts** - Shopping cart
- **cart_items** - Cart line items
- **purchases** - Digital product purchases
- **audit_logs** - Admin action audit trail
- **personal_access_tokens** - API authentication

#### Models (30+ Eloquent models)
- User (with role helpers and relationships)
- ServiceCategory, Service, ServiceFeature, ServicePricing
- PortfolioCategory, PortfolioProject, PortfolioImage, PortfolioTag
- BeforeAfterCategory, BeforeAfterProject
- ProductCategory, Product, ProductImage, ProductFile
- Quote, QuoteItem, QuoteFile
- Order, OrderItem, OrderFile, OrderRevision, OrderMessage
- Payment, Transaction, Invoice
- Page, BlogCategory, BlogPost, BlogTag
- Review, Testimonial
- Media, MediaFolder
- SupportTicket, SupportTicketMessage
- Setting, Coupon, CouponUsage
- Cart, CartItem, Purchase, AuditLog

#### Enums (6 PHP Backed Enums)
- OrderStatus (pending → cancelled)
- QuoteStatus (pending → converted)
- PaymentStatus (pending → refunded)
- ProductStatus (draft, published, archived)
- ReviewStatus (pending, approved, rejected, spam)
- UserRole (super_admin → customer)

#### Blade Layout & Components
- `layouts/app.blade.php` - Main layout with header/footer
- `components/header.blade.php` - Responsive nav with mobile menu
- `components/footer.blade.php` - Footer with CTA banner

#### Frontend Pages (13 views)
- Homepage with 10 sections (hero, stats, services, before/after, portfolio, products, why us, process, testimonials, FAQ)
- Services index & detail pages
- Portfolio index & detail pages
- Products index & detail pages
- Blog index & detail pages
- Quote submission form
- Contact form
- About, FAQ, Pricing, Before/After pages

#### Customer Dashboard (3 views)
- Dashboard with widgets
- Orders page
- Quotes page
- Profile page

#### Admin Dashboard (1 view)
- Dashboard with stat cards (placeholder for Milestone 3)

#### Controllers (7 frontend controllers)
- HomeController, ServiceController, PortfolioController
- ProductController, BlogController, QuoteController
- ContactController

#### Routes (26 routes)
- All public frontend routes
- Customer dashboard routes
- Admin dashboard routes
- Cart routes

#### Seeders (8 seeders)
- UserSeeder (admin, editor, designer, 2 customers)
- ServiceSeeder (4 categories, 6 services with features & pricing)
- PortfolioSeeder (6 categories, 6 projects, tags)
- BeforeAfterSeeder (3 categories, 4 projects)
- ProductSeeder (8 categories, 4 products)
- BlogSeeder (4 categories, 3 posts, tags)
- TestimonialSeeder (5 testimonials)
- SettingSeeder (9 site settings)

#### CSS & JS
- Tailwind CSS v4 with custom theme
- Alpine.js integration for interactive components
- Before/After slider with touch support

---

## [Milestone 2] - 2026-09-02

### ✅ Authentication System

#### Auth Controller
- `AuthController` with all authentication methods
- Login with email/password + remember me
- Registration with name, email, password confirmation
- Logout with session invalidation
- Forgot password (send reset link)
- Reset password (token-based)
- Email verification (send + verify)
- Profile management (name, email, phone, avatar)
- Change password (current password required)
- Role-based redirect (admin → admin dashboard)

#### Middleware
- `AdminMiddleware` - Checks admin role, returns 403 if unauthorized
- `EditorMiddleware` - Checks editor/admin role, returns 403 if unauthorized
- Registered in `bootstrap/app.php`

#### Authorization Policies (7 policies)
- `OrderPolicy` - Admin/editor can view all, user can view own
- `QuotePolicy` - Admin/editor can view all, user can view own
- `ServicePolicy` - Admin can CRUD
- `PortfolioPolicy` - Admin can CRUD
- `ProductPolicy` - Admin can CRUD
- `BlogPostPolicy` - Admin/editor can create, author can edit own
- `TestimonialPolicy` - Admin can CRUD

#### Auth Views (7 views)
- `auth/login.blade.php` - Login form with validation errors
- `auth/register.blade.php` - Registration form with password confirmation
- `auth/forgot-password.blade.php` - Password reset request form
- `auth/reset-password.blade.php` - New password form with token
- `auth/verify-email.blade.php` - Email verification prompt
- `auth/profile.blade.php` - Profile edit + change password + email verification status
- `auth/change-password.blade.php` - Dedicated change password page

#### Routes
- Guest routes: login, register, forgot-password, reset-password
- Auth routes: logout, verify-email, profile, change-password
- Admin routes protected with `admin` middleware
- Customer dashboard protected with `verified` middleware

#### Security
- CSRF protection on all forms
- Password hashing with bcrypt
- Session regeneration on login
- Session invalidation on logout
- Role-based authorization via middleware
- Policy-based authorization via Gates
- Email verification required for customer dashboard

---

## [Milestone 12] - 2026-09-02

### ✅ CMS - Blog, Pages, Media, Reviews

#### Blog System (Enhanced)
- Blog post CRUD with categories and tags
- Blog post detail with content rendering, author, related posts
- Blog category and tag management
- SEO metadata on blog posts

#### Pages System
- Page CRUD in admin (list, create, edit, delete)
- Page templates (default, full-width, sidebar)
- Frontend page display with `/page/{slug}` routes
- SEO metadata on pages

#### Media Library
- Grid view with image previews
- Multi-file upload
- Search and type filter
- Delete with confirmation

#### Reviews System
- Admin review management (list, approve/reject, delete)
- Status workflow (pending → approved/rejected/spam)
- Quick approve/reject buttons
- Filter by status

#### Admin Sidebar Updates
- Added Pages link
- Added Reviews link

---

## [Milestone 10] - 2026-09-02

### ✅ Digital Products with Cart & Checkout

#### Cart System
- CartController with add, update, remove, clear methods
- Session-based cart for guests, user-based for logged-in users
- Cart page with items, quantities, totals, remove/clear actions
- Server-side price calculation (never trust browser prices)
- Duplicate item handling (quantity increment)

#### Checkout Flow
- CheckoutController with show, process, success methods
- Checkout page with billing info, payment method, order summary
- Order creation with database transactions
- Purchase recording for digital products
- Download count tracking
- Cart clearing after order
- Order confirmation page with details

#### Cart Views
- `frontend/cart/index.blade.php` — Cart with items, totals, actions
- `frontend/checkout/index.blade.php` — Checkout form
- `frontend/checkout/success.blade.php` — Order confirmation

## [Milestone 13] - 2026-09-02

### ✅ SEO System

#### Sitemap
- Dynamic sitemap.xml generation (`/sitemap.xml`)
- Includes: homepage, static pages, services, portfolio, products, blog posts, pages
- Last-modified dates, changefreq, priority
- 31 URLs in sitemap
- Cache headers for performance

#### Robots.txt
- Dynamic robots.txt (`/robots.txt`)
- Allows all public pages
- Disallows: /admin/, /account/, /cart/, /checkout/
- Includes sitemap URL

#### Schema Markup (JSON-LD)
- `<x-seo-meta>` Blade component for reusable SEO
- Organization schema on homepage
- Product schema on product detail pages
- Article schema on blog post detail pages
- Automatic publisher/brand information

#### Open Graph & Meta
- OG title, description, image, type, url on all pages
- Twitter Card meta tags
- Canonical URLs
- Proper title and description on all pages

#### SEO Routes
- `/sitemap.xml` — Dynamic sitemap
- `/robots.txt` — Crawler directives

---

## [Milestone 4] - 2026-09-02

### ✅ Service System

- Service index with category filtering tabs (Alpine.js)
- Service detail page with:
  - Hero section
  - Description
  - Features grid
  - Before/After slider integration
  - Pricing sidebar
  - Process section (3 steps)
  - CTA banner
  - Related services
- Service category count badges on filter tabs
- Delivery time display on service cards

## [Milestone 5] - 2026-09-02

### ✅ Portfolio System

- Portfolio index with category filtering tabs (Alpine.js)
- Portfolio detail page with:
  - Hero section with category and client info
  - Featured image
  - Description
  - Gallery grid with lightbox (Alpine.js)
  - Tags display
  - Related projects
- Admin portfolio gallery management:
  - Multiple image upload on create
  - Append images on edit (existing preserved)
  - Gallery preview in admin edit form
- Lightbox features:
  - Keyboard navigation (left/right arrows, Escape)
  - Touch swipe support
  - Image counter
  - Click outside to close

## [Milestone 6] - 2026-09-02

### ✅ Before/After Slider System

- Reusable `<x-before-after>` Blade component
- Interactive slider with:
  - Mouse drag support
  - Touch drag support
  - Keyboard accessibility (arrow keys, Home, End)
  - ARIA attributes
  - Responsive sizing
- JavaScript module (`before-after.js`)
- Integrated on:
  - Homepage (4 items)
  - Service detail pages (service-specific)
  - Standalone Before/After page (all items)

---

## [Milestone 3] - 2026-09-02

### ✅ Admin Panel

#### Admin Layout
- `admin/layouts/app.blade.php` - Admin layout with sidebar navigation and topbar
- Responsive sidebar with mobile overlay
- Active state highlighting for current section
- User info and logout in sidebar footer
- "View Site" link in topbar

#### Admin Dashboard
- Revenue, Orders, Pending Quotes, Customers stat widgets
- Services, Portfolio, Products, Blog count widgets
- Recent Quotes list with status badges
- Recent Orders list with status badges

#### Admin CRUD - Services
- Service list with search, status filter, pagination
- Create service form (title, category, description, pricing, SEO)
- Edit service form with image preview
- Delete with confirmation

#### Admin CRUD - Portfolio
- Portfolio list with search and pagination
- Create project form (title, category, client, tags, image)
- Edit project form with tag management
- Delete with confirmation

#### Admin CRUD - Before/After
- Before/After list with pagination
- Create form with before/after image upload
- Edit form with image preview
- Delete with confirmation

#### Admin CRUD - Products
- Product list with search, status filter, pagination
- Create product form (title, category, pricing, features, SEO)
- Edit product form with features textarea
- Delete with confirmation

#### Admin CRUD - Blog
- Blog list with search, status filter, pagination
- Create post form (title, category, content, tags, SEO)
- Edit post form with tag management
- Delete with confirmation

#### Admin CRUD - Testimonials
- Testimonial list with pagination
- Create/edit forms with rating, active/featured toggles
- Delete with confirmation

#### Admin - Quotes
- Quote list with search, status filter, pagination
- Quote detail view with files, update form
- Status update with price and notes

#### Admin - Orders
- Order list with search, status filter, pagination
- Order detail view with files, status update
- Admin notes

#### Admin - Customers
- Customer list with search and pagination
- Customer detail view with order history

#### Admin - Media Library
- Grid view with image previews
- Multi-file upload
- Search and type filter
- Delete with confirmation

#### Admin - Settings
- General settings (name, email, phone, address, currency)
- SEO defaults (meta title, description)

#### Admin Controllers (11 controllers)
- ServiceController, PortfolioController, BeforeAfterController
- ProductController, BlogController, QuoteController
- OrderController, CustomerController, TestimonialController
- MediaController, SettingController

#### Admin Views (25+ views)
- 12 index views (list/table with search, filter, pagination)
- 6 create forms
- 6 edit forms
- 3 detail/show views (quotes, orders, customers)
- 1 media library (grid layout)
- 1 settings page

#### Admin Routes (60+ routes)
- Resource routes for all CRUD entities
- Quote/Order/Customer show and update routes
- Media upload and delete routes
- Settings index and update routes

---

## [Milestone 14] - 2026-09-02

### ✅ Security & Performance

#### Security
- SecurityHeaders middleware (XSS, CSP, HSTS, clickjacking)
- Content-Security-Policy with whitelisted sources
- X-Content-Type-Options: nosniff
- X-Frame-Options: DENY
- Referrer-Policy: strict-origin-when-cross-origin
- Permissions-Policy: camera=(), microphone=(), geolocation=()
- Cross-Origin headers (COEP, COOP, CORP)
- HSTS in production

#### Rate Limiting
- Login/Register: 10 requests per minute
- Contact form: 5 requests per minute
- Quote form: 5 requests per minute
- Checkout: 3 requests per minute

#### Performance
- CacheService for all high-traffic queries
- Tag-based cache invalidation
- Homepage query caching (services, portfolio, products, blog, testimonials)
- `cache:warm` artisan command
- Database query optimization (eager loading)

---

## [Milestone 15] - 2026-09-02

### ✅ Testing & Deployment Documentation

#### Documentation
- README.md — Full project overview, setup, credentials
- DEPLOYMENT.md — Shared hosting deployment guide
- SECURITY.md — Security measures documentation
- DATABASE.md — Database schema documentation

---

## Remaining Work

See [REMAINING_STEPS.md](REMAINING_STEPS.md) for the full implementation plan.
