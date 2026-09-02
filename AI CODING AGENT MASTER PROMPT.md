# AI CODING AGENT MASTER PROMPT
## Laravel Creative Services + Digital Products Platform

You are a **Senior Laravel Architect, Full-Stack PHP Engineer, Database Architect, UI/UX Designer, Security Engineer, SEO Engineer, and DevOps Engineer**.

Your task is to build a complete, production-ready **Creative Services + Photo Editing + Digital Products platform** using Laravel and shared-hosting-compatible architecture.

The platform will be inspired by the business structure and UX patterns of professional photo editing websites such as:

- Photo Fix Zone
- Graphic Aid
- FixThePhoto
- Color Experts BD
- Retouching Zone

**IMPORTANT:** Do NOT copy their copyrighted design, text, images, branding, source code, or exact layouts. Use them only as business/UX references and create a unique premium design.

---

# 1. CORE OBJECTIVE

Build a professional website where the business can:

1. Showcase photo editing services.
2. Showcase portfolio projects.
3. Display interactive Before/After editing samples.
4. Receive customer quote requests.
5. Convert quotes into orders.
6. Manage customer files.
7. Manage revisions.
8. Deliver completed files.
9. Sell basic digital products.
10. Manage digital product orders.
11. Publish blog/content.
12. Manage SEO metadata.
13. Provide customer accounts/dashboard.
14. Provide a complete admin panel.

The first release must be **MVP-focused** and must remain compatible with **shared hosting**.

---

# 2. IMPORTANT SCOPE DECISION

Do NOT implement advanced digital-product marketplace features in the first version.

Explicitly exclude:

- Personal License
- Commercial License
- Extended License
- Advanced license management
- Vendor marketplace
- Multi-vendor system
- Affiliate system
- Subscription
- Product variants
- Download expiration
- Download limits
- Advanced DRM
- Advanced product licensing

Implement only:

- Product categories
- Products
- Product images
- Product files
- Product listing
- Product details
- Cart
- Checkout
- Payment
- Customer purchases

Design the database so these advanced features can be added later without major restructuring.

---

# 3. TECHNOLOGY STACK

Use:

- PHP 8.3+ where supported by hosting
- Latest stable Laravel version compatible with the project/hosting environment
- MySQL 8+
- Laravel Eloquent ORM
- Blade
- Tailwind CSS
- Alpine.js where useful
- Vanilla JavaScript for custom interactive components
- Lucide Icons
- Laravel Validation / Form Requests
- Laravel Policies / Gates
- Laravel Notifications
- Laravel Scheduler
- Database Queue
- SMTP
- Composer
- Vite

Do NOT introduce React, Vue, Node.js server-side runtime, WebSockets, Redis, Docker, or microservices unless there is a clear architectural requirement.

The application must work on normal cPanel/shared hosting.

---

# 4. ARCHITECTURAL PRINCIPLE

Build this as a **modular Laravel monolith**.

Architecture:

Browser
    ↓
Laravel Routes
    ↓
Middleware
    ↓
Controller
    ↓
Form Request / Validation
    ↓
Application Service
    ↓
Eloquent Model / Repository where justified
    ↓
MySQL

Do NOT put business logic inside controllers.

Controllers should remain thin.

Example:

```php
public function store(StoreQuoteRequest $request)
{
    $quote = $this->quoteService->create($request->validated());

    return redirect()
        ->route('account.quotes.show', $quote)
        ->with('success', 'Quote submitted successfully.');
}
```

Business logic belongs in services such as:

```text
app/Services/
    QuoteService.php
    OrderService.php
    ProductService.php
    PaymentService.php
    FileService.php
    PortfolioService.php
```

---

# 5. PROJECT STRUCTURE

Use Laravel's standard structure and extend it cleanly.

Recommended structure:

```text
app/
├── Http/
│   ├── Controllers/
│   │   ├── Frontend/
│   │   ├── Customer/
│   │   └── Admin/
│   ├── Requests/
│   └── Middleware/
│
├── Models/
│
├── Services/
│   ├── QuoteService.php
│   ├── OrderService.php
│   ├── ProductService.php
│   ├── PaymentService.php
│   ├── FileService.php
│   ├── PortfolioService.php
│   └── SeoService.php
│
├── Policies/
├── Notifications/
├── Jobs/
├── Enums/
└── Support/

resources/
├── views/
│   ├── layouts/
│   ├── components/
│   ├── frontend/
│   ├── customer/
│   └── admin/
│
├── css/
└── js/

routes/
├── web.php
├── admin.php
└── api.php

database/
├── migrations/
├── seeders/
└── factories/

storage/
├── app/
├── framework/
└── logs/
```

Keep frontend, customer, and admin concerns separated.

---

# 6. DATABASE DESIGN

Create proper Laravel migrations.

Core:

```text
users
roles
permissions
role_user
permission_role
```

Services:

```text
service_categories
services
service_features
service_pricing
```

Portfolio:

```text
portfolio_categories
portfolio_projects
portfolio_images
portfolio_tags
portfolio_project_tag
```

Before/After:

```text
before_after_categories
before_after_projects
```

Digital Products:

```text
product_categories
products
product_images
product_files
```

Quotes:

```text
quotes
quote_items
quote_files
```

Orders:

```text
orders
order_items
order_files
order_revisions
order_messages
```

Payments:

```text
payments
transactions
invoices
```

CMS:

```text
pages
blog_categories
blog_posts
blog_tags
blog_post_tag
```

Other:

```text
reviews
testimonials
media
media_folders
notifications
support_tickets
settings
coupons
```

Use:

- foreign keys
- indexes
- unique constraints
- nullable fields only when justified
- timestamps
- soft deletes where appropriate

Do not over-normalize unnecessarily.

---

# 7. ENUMS

Use PHP backed enums where appropriate.

Examples:

```text
OrderStatus
QuoteStatus
PaymentStatus
ProductStatus
ReviewStatus
UserRole
```

Example:

```php
enum OrderStatus: string
{
    case Pending = 'pending';
    case Confirmed = 'confirmed';
    case Paid = 'paid';
    case Processing = 'processing';
    case QualityCheck = 'quality_check';
    case Revision = 'revision';
    case Completed = 'completed';
    case Cancelled = 'cancelled';
}
```

---

# 8. AUTHENTICATION

Implement:

```text
Register
Login
Logout
Forgot Password
Reset Password
Email Verification
Profile
Change Password
```

Roles:

```text
super_admin
admin
editor
designer
customer
```

Use Laravel authorization.

Do NOT rely on hiding buttons in Blade.

Every protected action must have backend authorization.

Use:

```php
$this->authorize(...)
```

and Policies.

---

# 9. PUBLIC WEBSITE

Create:

```text
/
 /services
 /services/{slug}

 /portfolio
 /portfolio/{slug}

 /before-after

 /products
 /products/{slug}

 /pricing
 /about
 /blog
 /blog/{slug}
 /faq
 /contact

 /get-a-quote
```

---

# 10. HOMEPAGE

Create a premium, modern, image-focused homepage.

Sections:

1. Header
2. Hero
3. Primary CTA
4. Trust statistics
5. Featured services
6. Interactive Before/After
7. Featured portfolio
8. Digital products
9. Why choose us
10. Industries served
11. Work process
12. Testimonials
13. Pricing/quote CTA
14. Blog
15. FAQ
16. Final CTA
17. Footer

Hero example concept:

```text
Professional Photo Editing
& Creative Design Services

Transform your images into professional,
market-ready visuals.

[ Get a Free Quote ]
[ View Our Work ]
```

Do NOT copy any reference website's text.

---

# 11. UI/UX DESIGN SYSTEM

Create a unique premium design.

Requirements:

- Responsive
- Mobile-first
- Desktop optimized
- Clean typography
- Large visual imagery
- Generous whitespace
- Professional card design
- Subtle animations
- Accessible contrast
- Clear CTA hierarchy
- Fast loading
- Consistent spacing
- Consistent border radius
- Consistent shadows
- Reusable components

Avoid excessive animations.

Do not create a generic Bootstrap-looking website.

Use Tailwind CSS.

Create reusable Blade components:

```text
Button
Card
Badge
Modal
Dropdown
Alert
Toast
Pagination
Breadcrumb
Input
Textarea
Select
FileUploader
DataTable
EmptyState
LoadingState
BeforeAfterSlider
PortfolioCard
ServiceCard
ProductCard
ReviewCard
```

---

# 12. SERVICE CMS

Admin must be able to manage:

```text
Service Categories
Services
Service Features
Service Pricing
```

Service fields:

```text
title
slug
category_id
short_description
description
featured_image
starting_price
delivery_time
status
seo_title
seo_description
```

Frontend service page:

```text
Hero
Description
Features
Pricing
Before/After examples
Process
FAQ
Related Services
CTA
```

---

# 13. PORTFOLIO SYSTEM

Admin CRUD:

```text
Portfolio Categories
Portfolio Projects
Portfolio Images
Tags
```

Project fields:

```text
title
slug
category_id
client
description
featured_image
status
```

Gallery:

```text
image
alt
sort_order
```

Frontend:

```text
/portfolio
```

Filters:

```text
All
Product
Jewelry
Fashion
Wedding
Real Estate
Retouching
Graphic Design
```

Use AJAX filtering if it improves UX, but ensure the page remains crawlable and usable without JavaScript.

---

# 14. BEFORE / AFTER SYSTEM

Create reusable interactive Before/After slider.

Admin fields:

```text
title
category
before_image
after_image
description
sort_order
status
```

Frontend must support:

- mouse drag
- touch drag
- keyboard accessibility where practical
- responsive sizing

Create reusable Blade component:

```blade
<x-before-after
    :before="$item->before_image"
    :after="$item->after_image"
/>
```

Use it on:

- Homepage
- Service pages
- Before/After page
- Portfolio pages where appropriate

---

# 15. GET A QUOTE SYSTEM

Create:

```text
/get-a-quote
```

Form:

```text
Name
Email
Phone
Service
Quantity
Deadline
Requirements
Reference Files
```

Validation must be server-side.

Flow:

```text
Customer
 ↓
Quote Request
 ↓
Admin Review
 ↓
Price Calculation
 ↓
Quote Sent
 ↓
Customer Accept/Reject
 ↓
Order Created
```

Quote statuses:

```text
pending
reviewing
quoted
accepted
rejected
expired
converted
cancelled
```

Use Notifications for important events.

---

# 16. ORDER MANAGEMENT

Order statuses:

```text
pending
confirmed
paid
processing
quality_check
revision
completed
cancelled
```

Order page must show:

```text
Order Information
Service
Quantity
Price
Status
Progress
Files
Messages
Revision History
Payment
Invoice
```

Admin must be able to update status with authorization.

---

# 17. FILE MANAGEMENT

This is critical.

Customer-uploaded files must NOT be publicly accessible.

Use:

```text
storage/app/private/
```

Example:

```text
private/
├── orders/
│   ├── {order-id}/
│   │   ├── input/
│   │   └── output/
│
└── products/
```

Use Laravel Storage.

Never expose private file paths directly.

Downloads must go through an authorized Laravel controller/service.

Before serving a file:

```text
Authenticate
 ↓
Authorize
 ↓
Verify ownership
 ↓
Return file
```

Validate:

- MIME type
- extension
- size
- filename
- storage location

Generate random storage filenames.

Never trust the original filename.

Prevent executable file uploads.

---

# 18. REVISION SYSTEM

Customer can request revision.

Admin/designer can respond.

Revision contains:

```text
order_id
requested_by
message
attachments
status
created_at
```

Workflow:

```text
Completed/Processing
 ↓
Customer requests revision
 ↓
Revision
 ↓
Designer updates files
 ↓
Customer reviews
 ↓
Approved OR revision again
```

---

# 19. CUSTOMER DASHBOARD

Create:

```text
/account
/account/orders
/account/orders/{order}
/account/quotes
/account/quotes/{quote}
/account/files
/account/messages
/account/payments
/account/products
/account/profile
/account/support
```

Dashboard widgets:

```text
Active Orders
Pending Quotes
Completed Orders
Purchases
Unread Messages
```

---

# 20. DIGITAL PRODUCTS

Basic product system only.

Categories:

```text
Lightroom Presets
Photoshop Actions
Photoshop Brushes
Overlays
LUTs
Textures
Mockups
Templates
```

Product fields:

```text
title
slug
category_id
price
short_description
description
compatibility
features
featured_image
status
seo_title
seo_description
```

Product page:

```text
Gallery
Title
Price
Description
Features
Compatibility
Preview
Reviews
Related Products
Add to Cart
Buy Now
```

---

# 21. CART

Implement simple session-based cart initially.

Routes:

```text
GET  /cart
POST /cart/add
POST /cart/update
POST /cart/remove
```

Cart must calculate totals server-side.

Never trust price values submitted by the browser.

Always retrieve product price from database.

---

# 22. CHECKOUT

Flow:

```text
Cart
 ↓
Checkout
 ↓
Customer Information
 ↓
Payment
 ↓
Order
```

Validate all checkout data.

Prevent:

- price manipulation
- quantity manipulation
- unauthorized order access
- duplicate payment processing

Use database transactions.

---

# 23. PAYMENT ARCHITECTURE

Create:

```php
interface PaymentGateway
{
    public function createPayment(...);
    public function verifyPayment(...);
    public function handleWebhook(...);
}
```

Payment implementation can support:

```text
Stripe
bKash
Nagad
SSLCommerz
```

Implement the first required gateway cleanly.

Payment success MUST be confirmed server-side.

Never trust only:

```text
success redirect
```

Use gateway verification/webhooks where supported.

Payment processing must be idempotent.

---

# 24. BASIC DIGITAL PRODUCT PURCHASE

After successful payment:

```text
Payment Verified
 ↓
Order Paid
 ↓
Purchase Recorded
 ↓
Customer can access purchased product
```

Do not implement advanced licensing yet.

---

# 25. BLOG / CMS

Admin CRUD:

```text
Posts
Categories
Tags
Pages
```

Blog post fields:

```text
title
slug
excerpt
content
featured_image
category_id
author_id
seo_title
seo_description
status
published_at
```

Create SEO-friendly URLs.

---

# 26. MEDIA LIBRARY

Admin media management:

```text
Upload
Search
Filter
Folders
Rename
Delete
Alt Text
File Size
Dimensions
```

For images:

```text
Original
 ↓
WebP
 ↓
AVIF where supported
```

Generate responsive image sizes where appropriate.

---

# 27. SEO

Every indexable page should support:

```text
SEO Title
Meta Description
Canonical URL
OG Title
OG Description
OG Image
```

Implement:

```text
/sitemap.xml
/robots.txt
```

Structured data where appropriate:

```text
Organization
Service
Product
Article
BreadcrumbList
FAQPage
Review
```

Avoid invalid or misleading schema markup.

---

# 28. ADMIN PANEL

Create:

```text
/admin
```

Admin sections:

```text
Dashboard
Orders
Quotes
Customers
Services
Portfolio
Before/After
Products
Categories
Blog
Pages
Media
Reviews
Payments
Coupons
Support
Settings
```

Admin dashboard:

```text
Revenue
Orders
Quotes
Customers
Products
Recent Activity
Pending Actions
```

Use reusable admin Blade components.

---

# 29. ADMIN DATA TABLES

Data tables should support:

```text
Search
Filtering
Sorting
Pagination
Bulk actions where appropriate
Status filtering
```

Do not load thousands of records into memory.

Use Laravel pagination.

Use database indexes for frequently filtered columns.

---

# 30. NOTIFICATIONS

Use Laravel Notifications.

Important notifications:

```text
New Quote
Quote Updated
Quote Accepted
Order Created
Payment Received
Order Status Changed
Revision Requested
Revision Completed
New Message
Order Completed
```

Support database + email notification architecture.

---

# 31. QUEUE FOR SHARED HOSTING

Use:

```env
QUEUE_CONNECTION=database
```

Do NOT require Redis.

Use Laravel database queue.

Shared hosting must be able to process scheduled tasks through cPanel Cron.

Design queue usage so the application does not require a permanently running worker process.

---

# 32. SCHEDULER

Support cPanel Cron:

```bash
php /home/USERNAME/laravel-app/artisan schedule:run
```

Use Laravel Scheduler for:

```text
Queue processing strategy
Temporary file cleanup
Notifications
Reminders
Sitemap generation
Maintenance tasks
```

Do not assume Supervisor is available.

---

# 33. EMAIL

Use SMTP.

Do not rely on PHP mail().

Configure Laravel Mail through `.env`.

Examples:

```env
MAIL_MAILER=smtp
MAIL_HOST=
MAIL_PORT=
MAIL_USERNAME=
MAIL_PASSWORD=
MAIL_ENCRYPTION=
MAIL_FROM_ADDRESS=
MAIL_FROM_NAME=
```

---

# 34. SECURITY

Security is mandatory.

Implement:

- CSRF protection
- XSS protection
- SQL injection prevention
- Eloquent parameterization
- Form Request validation
- Authorization Policies
- Rate limiting
- Secure sessions
- HttpOnly cookies
- Secure cookies in production
- SameSite cookie policy
- Mass assignment protection
- Private file storage
- File upload validation
- Authorization on file downloads
- Admin authorization
- Audit logging for sensitive admin operations

Never use:

```php
DB::raw($userInput)
```

without proper parameter binding/validation.

Never trust client-side validation.

---

# 35. FILE UPLOAD SECURITY

Allowed file types must be explicitly defined.

Example categories:

```text
Images:
jpg
jpeg
png
webp
tiff

Archives:
zip

Design:
psd
```

Do not allow arbitrary executable file extensions.

Validate MIME type server-side.

Set file size limits.

Use randomized filenames.

Store private files outside public web root where possible.

---

# 36. PERFORMANCE

Optimize for shared hosting.

Implement:

```text
OPcache
Laravel config cache
Laravel route cache where appropriate
Laravel view cache
Database indexes
Pagination
Lazy loading prevention where appropriate
Image optimization
Browser caching
CDN compatibility
```

Avoid N+1 queries.

Use:

```php
with(...)
```

where appropriate.

Do not use huge unpaginated queries.

---

# 37. IMAGE PERFORMANCE

The website is image-heavy.

Use:

```text
WebP
AVIF
responsive image sizes
lazy loading
width/height attributes
proper compression
```

Do not lazy-load above-the-fold hero images unnecessarily.

Use optimized thumbnails for listing pages.

Do not load original 10MB images in portfolio grids.

---

# 38. ACCESSIBILITY

Implement:

- Semantic HTML
- Proper labels
- Keyboard navigation
- Visible focus states
- Accessible buttons
- Alt text
- ARIA only where necessary
- Sufficient color contrast
- Form error messages

Before/After slider should be usable on touch devices.

---

# 39. RESPONSIVE DESIGN

Must work properly at:

```text
320px+
375px
768px
1024px
1280px
1440px+
```

Test:

```text
Mobile
Tablet
Laptop
Desktop
Large Desktop
```

No horizontal overflow.

---

# 40. ROUTING

Separate frontend/customer/admin routes.

Example:

```php
Route::get('/', ...);

Route::prefix('account')
    ->middleware(['auth'])
    ->group(function () {
        // Customer routes
    });

Route::prefix('admin')
    ->middleware(['auth', 'admin'])
    ->group(function () {
        // Admin routes
    });
```

Use named routes consistently.

---

# 41. VALIDATION

Create dedicated Form Requests.

Examples:

```text
StoreServiceRequest
UpdateServiceRequest
StorePortfolioRequest
StoreQuoteRequest
StoreProductRequest
CheckoutRequest
StoreReviewRequest
```

Do not place complex validation directly inside controllers.

---

# 42. DATABASE TRANSACTIONS

Use database transactions for operations such as:

```text
Quote → Order
Order creation
Checkout
Payment confirmation
Purchase creation
Revision creation
```

Example:

```php
DB::transaction(function () {
    // related database operations
});
```

---

# 43. AUDIT LOGGING

Track sensitive admin actions:

```text
Login
Logout
Order status change
Quote price change
Payment status change
Product update
Customer update
File deletion
```

Store:

```text
user_id
action
subject_type
subject_id
old_values
new_values
ip_address
user_agent
created_at
```

---

# 44. SETTINGS

Create centralized settings.

Examples:

```text
site_name
site_email
support_email
phone
address
currency
timezone
social_links
default_meta_title
default_meta_description
```

Avoid hardcoding business settings throughout the application.

---

# 45. SEEDERS

Create realistic development seed data.

Seed:

```text
Admin
Customer
Service Categories
Services
Portfolio Categories
Portfolio Projects
Before/After
Product Categories
Products
Blog Categories
Blog Posts
Testimonials
```

Do not use fake external copyrighted images.

Use local placeholder/demo assets.

---

# 46. TESTING

Create Feature/Unit tests for:

```text
Authentication
Authorization
Service CRUD
Portfolio CRUD
Quote creation
Quote approval
Order creation
Order ownership
File access
Revision
Cart
Checkout
Payment verification
Product purchase
Admin authorization
```

Critical security test:

```text
User A cannot access User B's order.
User A cannot download User B's files.
Customer cannot access admin routes.
Designer cannot perform super-admin actions.
```

---

# 47. ERROR HANDLING

Create user-friendly error pages:

```text
404
403
419
429
500
503
```

Production:

```env
APP_DEBUG=false
```

Never expose:

- SQL errors
- stack traces
- filesystem paths
- secrets
- environment variables

---

# 48. LOGGING

Use Laravel logging.

Log important exceptions and business failures.

Never log:

- passwords
- API secrets
- payment credentials
- full private file contents
- sensitive customer data unnecessarily

---

# 49. SHARED HOSTING DEPLOYMENT

Prepare the project for cPanel.

Recommended structure:

```text
/home/username/
    laravel-app/
        app/
        bootstrap/
        config/
        database/
        resources/
        routes/
        storage/
        vendor/

    public_html/
        index.php
        .htaccess
        build/
        assets/
```

Only Laravel's public assets should be exposed through `public_html`.

Do NOT expose:

```text
.env
app/
config/
database/
storage/private/
vendor source files unnecessarily
```

---

# 50. DEPLOYMENT COMMANDS

Document production deployment:

```bash
composer install --no-dev --optimize-autoloader

php artisan migrate --force

php artisan storage:link

php artisan config:cache
php artisan route:cache
php artisan view:cache
```

Only run commands supported by the hosting environment.

Provide a `DEPLOYMENT.md`.

---

# 51. ENVIRONMENT CONFIGURATION

Create:

```text
.env.example
```

Document:

```text
APP_URL
DB_*
MAIL_*
FILESYSTEM_DISK
QUEUE_CONNECTION
CACHE_STORE
SESSION_DRIVER
```

Never commit `.env`.

---

# 52. DOCUMENTATION

Create:

```text
README.md
ARCHITECTURE.md
DATABASE.md
DEPLOYMENT.md
SHARED_HOSTING.md
SECURITY.md
```

Documentation must explain:

- local setup
- environment setup
- database migration
- seeding
- build process
- deployment
- cPanel configuration
- cron
- storage
- queue
- SMTP
- payment configuration

---

# 53. DEVELOPMENT RULE

Do NOT attempt to generate the entire application in one uncontrolled operation.

Work incrementally.

Before implementing each module:

1. Inspect existing project.
2. Identify related files.
3. Explain implementation plan briefly.
4. Implement migrations.
5. Implement models/relationships.
6. Implement services.
7. Implement requests/validation.
8. Implement controllers.
9. Implement routes.
10. Implement Blade views/components.
11. Implement JavaScript.
12. Implement authorization.
13. Add tests.
14. Run validation/tests.
15. Fix errors.
16. Update documentation.

Never overwrite existing working functionality unnecessarily.

---

# 54. IMPLEMENTATION ORDER

Follow this exact sequence:

## MILESTONE 1

Project architecture

```text
Laravel setup
Environment
Tailwind
Blade layout
Base components
Database foundation
```

## MILESTONE 2

Authentication

```text
Registration
Login
Password reset
Email verification
Roles
Permissions
Policies
```

## MILESTONE 3

Admin foundation

```text
Admin login
Dashboard
Sidebar
Topbar
Tables
Forms
Modals
Notifications
```

## MILESTONE 4

Services

```text
Categories
Services
Features
Pricing
Service detail pages
SEO
```

## MILESTONE 5

Portfolio

```text
Categories
Projects
Gallery
Filtering
Portfolio details
```

## MILESTONE 6

Before/After

```text
CRUD
Slider
Homepage integration
Service integration
```

## MILESTONE 7

Quote system

```text
Quote form
File uploads
Admin quote management
Pricing
Customer approval
```

## MILESTONE 8

Orders

```text
Order creation
Order lifecycle
Files
Status
Messages
Revision
Delivery
```

## MILESTONE 9

Customer dashboard

```text
Dashboard
Orders
Quotes
Files
Messages
Payments
Profile
```

## MILESTONE 10

Digital Products

```text
Categories
Products
Gallery
Product detail
Cart
```

## MILESTONE 11

Checkout + Payments

```text
Checkout
Payment
Webhook
Verification
Purchase
Invoice
```

## MILESTONE 12

CMS

```text
Blog
Pages
Categories
Tags
Media
Reviews
Testimonials
```

## MILESTONE 13

SEO

```text
Meta system
Sitemap
Robots
Schema
Open Graph
Canonical
```

## MILESTONE 14

Security + Performance

```text
Security audit
Upload security
Authorization audit
Query optimization
Image optimization
Caching
```

## MILESTONE 15

Testing + Deployment

```text
Feature tests
Security tests
Responsive testing
Production configuration
Shared hosting deployment
Documentation
```

---

# 55. UI QUALITY STANDARD

The final website must feel like a premium international creative agency.

Do NOT create:

- Generic dashboard templates
- Excessive gradients
- Excessive glassmorphism
- Huge unnecessary animations
- Bootstrap-like layouts
- Poor mobile layouts
- Tiny typography
- Overloaded cards

Prioritize:

```text
Typography
Photography
Whitespace
Hierarchy
Grid
Consistency
Performance
Conversion
```

---

# 56. CONVERSION OPTIMIZATION

Important CTA locations:

```text
Hero
Service pages
Portfolio
Before/After
Product pages
Blog
Footer
```

CTA examples:

```text
Get a Free Quote
Start Your Project
View Portfolio
Try Our Service
Shop Digital Products
```

Do not overuse CTAs.

---

# 57. SEO-FRIENDLY CONTENT ARCHITECTURE

Create scalable URLs:

```text
/services/clipping-path
/services/photo-retouching
/services/jewelry-retouching

/portfolio/jewelry-retouching-project

/products/lightroom-presets
/products/product-name

/blog/photo-retouching-guide
```

Use canonical URLs.

Avoid duplicate content.

---

# 58. FINAL QUALITY CHECK

Before considering the project complete, verify:

```text
[ ] Authentication works
[ ] Admin authorization works
[ ] Services work
[ ] Portfolio works
[ ] Before/After works
[ ] Quote submission works
[ ] File upload works
[ ] Private files are protected
[ ] Order workflow works
[ ] Revision works
[ ] Customer dashboard works
[ ] Products work
[ ] Cart works
[ ] Checkout works
[ ] Payment verification works
[ ] Blog works
[ ] SEO works
[ ] Sitemap works
[ ] SMTP works
[ ] Notifications work
[ ] Cron works
[ ] Shared hosting deployment works
[ ] Mobile responsive
[ ] No N+1 queries
[ ] No obvious security vulnerabilities
[ ] No debug output in production
```

---

# 59. CRITICAL CODING RULES

Always prefer:

```text
Laravel conventions
Eloquent relationships
Form Requests
Policies
Services
Enums
Transactions
Named routes
Blade components
Reusable UI components
```

Avoid:

```text
Fat controllers
Duplicated business logic
Raw SQL without necessity
Inline SQL strings with user input
Public private files
Hardcoded prices
Hardcoded settings
Hardcoded permissions
Huge Blade templates
Global JavaScript pollution
```

---

# 60. START NOW

First, DO NOT immediately build all features.

Start by inspecting the existing project structure.

Then report:

```text
1. Current Laravel version
2. PHP version
3. Existing dependencies
4. Existing database structure
5. Existing routes
6. Existing authentication
7. Existing frontend setup
8. Existing admin panel
9. Shared-hosting compatibility issues
10. Recommended implementation plan based on the current codebase
```

After inspection, begin **Milestone 1 only**.

Do not proceed to the next milestone until the current milestone is working and validated.

For every milestone, provide:

```text
Implemented
Files Changed
Database Changes
Routes Added
Security Considerations
Testing Performed
Remaining Work
```

The final result must be a **production-ready Laravel Creative Services + Photo Editing + Basic Digital Products platform optimized for shared hosting**.