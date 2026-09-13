<?php

namespace App\Providers;

use App\Models\Order;
use App\Models\Quote;
use App\Models\Service;
use App\Models\PortfolioProject;
use App\Models\Product;
use App\Models\Testimonial;
use App\Policies\OrderPolicy;
use App\Policies\QuotePolicy;
use App\Policies\ServicePolicy;
use App\Policies\PortfolioPolicy;
use App\Policies\ProductPolicy;
use App\Policies\TestimonialPolicy;
use App\Models\Cart;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Gate::policy(Order::class, OrderPolicy::class);
        Gate::policy(Quote::class, QuotePolicy::class);
        Gate::policy(Service::class, ServicePolicy::class);
        Gate::policy(PortfolioProject::class, PortfolioPolicy::class);
        Gate::policy(Product::class, ProductPolicy::class);
        Gate::policy(Testimonial::class, TestimonialPolicy::class);

        // Share the current cart item count with the header (badge).
        View::composer('components.header', function (\Illuminate\View\View $view) {
            $cart = auth()->check()
                ? Cart::withCount('items')->where('user_id', auth()->id())->first()
                : Cart::withCount('items')->where('session_id', session()->getId())->first();

            $view->with('cartCount', $cart?->items_count ?? 0);
        });
    }
}
