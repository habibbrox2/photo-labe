# Security Documentation

## Overview

PhotoLabe implements industry-standard security practices to protect against common web vulnerabilities.

## Security Features

### Authentication
- **Password Hashing:** bcrypt with configurable rounds (default 12)
- **Session Regeneration:** New session ID on login
- **Session Invalidation:** Complete session destroy on logout
- **Remember Me:** Secure token-based persistent login
- **CSRF Protection:** All forms include CSRF tokens

### Authorization
- **Role-Based Access Control (RBAC):** 5 roles (super_admin, admin, editor, designer, customer)
- **Middleware Protection:**
  - `auth` — Requires authentication
  - `admin` — Requires admin role
  - `editor` — Requires editor/admin role
  - `verified` — Requires verified email
- **Authorization Policies:** 6 model policies (Order, Quote, Service, Portfolio, Product, Testimonial)
- **Gate Definitions:** Policy-based authorization checks

### Input Validation
- **Form Request Validation:** Dedicated request classes for complex validation
- **Server-Side Validation:** All inputs validated on server
- **Mass Assignment Protection:** `$fillable` whitelists on all models
- **Type Hinting:** Strong typing throughout

### SQL Injection Prevention
- **Eloquent ORM:** Parameterized queries by default
- **Query Builder:** Parameterized bindings
- **No Raw SQL:** Avoids `DB::raw()` with user input
- **Input Sanitization:** All user input sanitized before use

### XSS Protection
- **Blade Escaping:** `{{ }}` auto-escapes HTML
- **CSRF Tokens:** Prevents cross-site request forgery
- **Content Security Policy:** Ready for CSP headers
- **HTTPOnly Cookies:** Session cookies not accessible via JavaScript

### File Upload Security
- **MIME Type Validation:** Server-side file type checking
- **Extension Whitelist:** Only allowed extensions accepted
- **File Size Limits:** Configurable per-type limits
- **Random Filenames:** Original filenames not stored
- **Private Storage:** Uploaded files in `storage/app/private/`
- **No Executable Files:** .php, .sh, .exe blocked

### API Security
- **Rate Limiting:** Ready for throttle middleware
- **Token-Based Auth:** Sanctum-ready architecture
- **CORS Configuration:** Configurable allowed origins

### Session Security
- **Secure Cookies:** HttpOnly, Secure, SameSite flags
- **Session Timeout:** Configurable lifetime (default 120 min)
- **Session Encryption:** Optional session data encryption
- **CSRF Token Rotation:** Token refreshed on each request

## Security Checklist

### Pre-Deployment
- [ ] `APP_DEBUG=false` in production
- [ ] Strong `APP_KEY` generated
- [ ] `.env` not publicly accessible
- [ ] `storage/` directory not web-accessible
- [ ] `vendor/` directory not web-accessible
- [ ] `config/` directory not web-accessible
- [ ] `database/` directory not web-accessible
- [ ] File permissions set correctly (775 for writable dirs)

### Application
- [ ] All forms have CSRF tokens
- [ ] All inputs validated server-side
- [ ] All database queries use parameterized bindings
- [ ] All file uploads validated (type, size)
- [ ] All routes have proper authorization
- [ ] Passwords hashed with bcrypt
- [ ] Sessions regenerated on login
- [ ] Sessions invalidated on logout
- [ ] No sensitive data in logs
- [ ] No debug output in production

### Server
- [ ] SSL/HTTPS enabled
- [ ] OPcache enabled
- [ ] Security headers configured
- [ ] Error pages configured (404, 403, 500)
- [ ] Cron jobs secured
- [ ] Database user has minimal privileges

## Security Headers

Add to `public_html/.htaccess`:

```apache
<IfModule mod_headers.c>
    Header set X-Content-Type-Options "nosniff"
    Header set X-Frame-Options "SAMEORIGIN"
    Header set X-XSS-Protection "1; mode=block"
    Header set Referrer-Policy "strict-origin-when-cross-origin"
    Header set Permissions-Policy "camera=(), microphone=(), geolocation=()"
</IfModule>
```

## Rate Limiting

Add to `routes/web.php` for sensitive routes:

```php
Route::post('/login', [AuthController::class, 'login'])
    ->middleware('throttle:5,1');  // 5 attempts per minute

Route::post('/register', [AuthController::class, 'register'])
    ->middleware('throttle:3,1');  // 3 attempts per minute

Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])
    ->middleware('throttle:3,1');
```

## Audit Logging

Track sensitive actions in `audit_logs` table:
- User login/logout
- Order status changes
- Quote price changes
- Payment status changes
- Product updates
- Customer updates
- File deletions

## Vulnerability Reporting

Report security vulnerabilities to: security@photolabe.com

## Security Updates

- Keep Laravel and dependencies updated
- Monitor security advisories
- Apply patches promptly
- Review dependencies regularly
