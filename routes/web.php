<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\ServiceController;
use App\Http\Controllers\Frontend\PortfolioController;
use App\Http\Controllers\Frontend\ProductController;
use App\Http\Controllers\Frontend\QuoteController;
use App\Http\Controllers\Frontend\ContactController;
use App\Http\Controllers\Frontend\BeforeAfterController;
use App\Http\Controllers\Frontend\PageController;
use App\Http\Controllers\Frontend\SitemapController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

// Homepage
Route::get('/', [HomeController::class, 'index'])->name('home');

// Services
Route::get('/services', [ServiceController::class, 'index'])->name('services.index');
Route::get('/services/{slug}', [ServiceController::class, 'show'])->name('services.show');

// Portfolio
Route::get('/portfolio', [PortfolioController::class, 'index'])->name('portfolio.index');
Route::get('/portfolio/{slug}', [PortfolioController::class, 'show'])->name('portfolio.show');

// Digital Products
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{slug}', [ProductController::class, 'show'])->name('products.show');

// Quote
Route::get('/get-a-quote', [QuoteController::class, 'create'])->name('quote.create');
Route::post('/get-a-quote', [QuoteController::class, 'store'])->middleware(['auth', 'verified', 'throttle:5,1'])->name('quote.store');

// Contact
Route::get('/contact', [ContactController::class, 'show'])->name('contact');
Route::post('/contact', [ContactController::class, 'store'])->middleware('throttle:5,1')->name('contact.store');

// Static Pages
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/faq', fn () => view('frontend.faq'))->name('faq');
Route::get('/pricing', fn () => view('frontend.pricing'))->name('pricing');
Route::get('/before-after', [BeforeAfterController::class, 'index'])->name('before-after');
Route::get('/page/{slug}', [PageController::class, 'show'])->name('page.show');

// SEO
Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');
Route::get('/robots.txt', function () {
    $content = file_get_contents(public_path('robots.txt'));
    return response($content, 200)
        ->header('Content-Type', 'text/plain')
        ->header('Cache-Control', 'public, max-age=86400');
})->name('robots');

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/
use App\Http\Controllers\AuthController;

Route::middleware(['guest', 'throttle:10,1'])->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');

    // Social login (Google, Facebook)
    Route::get('/auth/{provider}/redirect', [AuthController::class, 'redirectToProvider'])->name('auth.social.redirect');
    Route::get('/auth/{provider}/callback', [AuthController::class, 'handleProviderCallback'])->name('auth.social.callback');

    Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');
    Route::get('/reset-password/{token}', [AuthController::class, 'showResetPassword'])->name('password.reset');
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Email Verification
    Route::get('/verify-email', [AuthController::class, 'showVerifyEmail'])->name('verification.notice');
    Route::post('/verify-email/resend', [AuthController::class, 'sendVerificationEmail'])->name('verification.send');
    Route::get('/verify-email/{id}/{hash}', [AuthController::class, 'verifyEmail'])->middleware('signed')->name('verification.verify');

    // Profile
    Route::get('/profile', [AuthController::class, 'showProfile'])->name('profile');
    Route::put('/profile', [AuthController::class, 'updateProfile'])->name('profile.update');

    // Change Password
    Route::get('/change-password', [AuthController::class, 'showChangePassword'])->name('password.change');
    Route::put('/change-password', [AuthController::class, 'updatePassword'])->name('password.change.update');
});

/*
|--------------------------------------------------------------------------
| Customer Dashboard Routes
|--------------------------------------------------------------------------
*/
use App\Http\Controllers\Frontend\CustomerController;

Route::prefix('account')
    ->middleware(['auth', 'verified'])
    ->name('account.')
    ->group(function () {
        // Dashboard
        Route::get('/', [CustomerController::class, 'dashboard'])->name('dashboard');

        // Orders
        Route::get('/orders', [CustomerController::class, 'orders'])->name('orders');
        Route::get('/orders/{order}', [CustomerController::class, 'orderShow'])->name('orders.show');
        Route::get('/orders/{order}/files/{file}/download', [CustomerController::class, 'downloadOrderFile'])->name('orders.files.download');
        Route::post('/orders/{order}/message', [CustomerController::class, 'orderMessage'])->name('orders.message');
        Route::post('/orders/{order}/revision', [CustomerController::class, 'orderRevision'])->name('orders.revision');
        Route::post('/orders/{order}/mark-read', [CustomerController::class, 'markMessagesRead'])->name('orders.mark-read');

        // Quotes
        Route::get('/quotes', [CustomerController::class, 'quotes'])->name('quotes');
        Route::get('/quotes/{quote}', [CustomerController::class, 'quoteShow'])->name('quotes.show');
        Route::post('/quotes/{quote}/accept', [CustomerController::class, 'quoteAccept'])->name('quotes.accept');
        Route::post('/quotes/{quote}/reject', [CustomerController::class, 'quoteReject'])->name('quotes.reject');

        // Purchases & Downloads
        Route::get('/purchases', [CustomerController::class, 'purchases'])->name('purchases');
        Route::get('/purchases/{purchase}/download/{file}', [CustomerController::class, 'downloadFile'])->name('purchases.download');

        // Payments
        Route::get('/payments', [CustomerController::class, 'payments'])->name('payments');

        // Notifications
        Route::get('/notifications', [CustomerController::class, 'notifications'])->name('notifications');
        Route::get('/notifications/{notification}/open', [CustomerController::class, 'openNotification'])->name('notifications.open');
        Route::post('/notifications/read-all', [CustomerController::class, 'markNotificationsRead'])->name('notifications.read-all');

        // Profile
        Route::get('/profile', [AuthController::class, 'showProfile'])->name('profile');
    });

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
use App\Http\Controllers\Admin\ServiceController as AdminServiceController;
use App\Http\Controllers\Admin\PortfolioController as AdminPortfolioController;
use App\Http\Controllers\Admin\BeforeAfterController as AdminBeforeAfterController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\QuoteController as AdminQuoteController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\CustomerController as AdminCustomerController;
use App\Http\Controllers\Admin\TestimonialController as AdminTestimonialController;
use App\Http\Controllers\Admin\MediaController as AdminMediaController;
use App\Http\Controllers\Admin\SettingController as AdminSettingController;
use App\Http\Controllers\Admin\PageController as AdminPageController;
use App\Http\Controllers\Admin\ReviewController as AdminReviewController;
use App\Http\Controllers\Admin\NotificationController as AdminNotificationController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\HeroSlideController as AdminHeroSlideController;
use App\Http\Controllers\Admin\UserController as AdminUserController;

Route::prefix('admin')
    ->middleware(['auth', 'admin'])
    ->name('admin.')
    ->group(function () {
        Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

        // Notifications
        Route::get('notifications', [AdminNotificationController::class, 'index'])->name('notifications.index');
        Route::get('notifications/{notification}/open', [AdminNotificationController::class, 'open'])->name('notifications.open');
        Route::post('notifications/read-all', [AdminNotificationController::class, 'markAllRead'])->name('notifications.read-all');

        // Services
        Route::resource('services', AdminServiceController::class)->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);

        // Portfolio
        Route::resource('portfolio', AdminPortfolioController::class)->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);

        // Before/After
        Route::resource('before-after', AdminBeforeAfterController::class)->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);

        // Products
        Route::resource('products', AdminProductController::class)->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);

        // Quotes
        Route::resource('quotes', AdminQuoteController::class)->only(['index', 'show', 'update', 'destroy']);
        Route::post('quotes/{quote}/convert', [AdminQuoteController::class, 'convertToOrder'])->name('quotes.convert');

        // Orders
        Route::resource('orders', AdminOrderController::class)->only(['index', 'show', 'update', 'destroy']);
        Route::post('orders/{order}/files', [AdminOrderController::class, 'uploadFile'])->name('orders.files.upload');
        Route::get('orders/{order}/files/{file}/download', [AdminOrderController::class, 'downloadFile'])->name('orders.files.download');
        Route::delete('orders/{order}/files/{file}', [AdminOrderController::class, 'deleteFile'])->name('orders.files.destroy');

        // Customers
        Route::resource('customers', AdminCustomerController::class)->only(['index', 'show', 'destroy']);

        // Testimonials
        Route::resource('testimonials', AdminTestimonialController::class)->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);

        // Hero Slides (homepage slider)
        // Declared before the resource: PATCH hero-slides/{heroSlide} (the resource
        // `update` route) would otherwise match the reorder endpoint first.
        Route::patch('hero-slides/reorder', [AdminHeroSlideController::class, 'reorder'])->name('hero-slides.reorder');
        Route::resource('hero-slides', AdminHeroSlideController::class)
            ->only(['index', 'create', 'store', 'edit', 'update', 'destroy'])
            ->parameters(['hero-slides' => 'heroSlide']);

        // Pages
        Route::resource('pages', AdminPageController::class)->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);

        // Reviews
        Route::resource('reviews', AdminReviewController::class)->only(['index', 'update', 'destroy']);

        // Users
        Route::resource('users', AdminUserController::class)->only(['index', 'edit', 'update', 'destroy']);

        // Admin own profile
        Route::get('profile', [AdminUserController::class, 'profile'])->name('users.profile');
        Route::put('profile', [AdminUserController::class, 'updateProfile'])->name('users.profile.update');

        // Media
        Route::delete('media', [AdminMediaController::class, 'bulkDestroy'])->name('media.bulkDestroy');
        Route::patch('media/{media}', [AdminMediaController::class, 'update'])->name('media.update');
        Route::get('media/{media}/preview', [AdminMediaController::class, 'preview'])->name('media.preview');
        Route::get('media/{media}/download', [AdminMediaController::class, 'download'])->name('media.download');
        Route::resource('media', AdminMediaController::class)->only(['index', 'store', 'destroy']);

        // Settings
        Route::get('settings', [AdminSettingController::class, 'index'])->name('settings.index');
        Route::put('settings', [AdminSettingController::class, 'update'])->name('settings.update');

        // Styleguide (design system reference)
        Route::get('styleguide', fn () => view('admin.styleguide'))->name('styleguide');
    });

/*
|--------------------------------------------------------------------------
| Cart & Checkout Routes
|--------------------------------------------------------------------------
*/
use App\Http\Controllers\Frontend\CartController;
use App\Http\Controllers\Frontend\CheckoutController;

Route::prefix('cart')->name('cart.')->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('index');
    Route::post('/add', [CartController::class, 'add'])->name('add');
    Route::patch('/{item}', [CartController::class, 'update'])->name('update');
    Route::delete('/{item}', [CartController::class, 'remove'])->name('remove');
    Route::delete('/', [CartController::class, 'clear'])->name('clear');
});

Route::prefix('checkout')->name('checkout.')->group(function () {
    Route::get('/', [CheckoutController::class, 'show'])->name('show');
    Route::post('/', [CheckoutController::class, 'process'])->middleware('throttle:3,1')->name('process');
    Route::get('/success/{order}', [CheckoutController::class, 'success'])->name('success');
});
