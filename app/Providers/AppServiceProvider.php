<?php

namespace App\Providers;

use App\Models\Order;
use App\Models\Quote;
use App\Models\Service;
use App\Models\PortfolioProject;
use App\Models\Product;
use App\Models\BlogPost;
use App\Models\Testimonial;
use App\Policies\OrderPolicy;
use App\Policies\QuotePolicy;
use App\Policies\ServicePolicy;
use App\Policies\PortfolioPolicy;
use App\Policies\ProductPolicy;
use App\Policies\BlogPostPolicy;
use App\Policies\TestimonialPolicy;
use Illuminate\Support\Facades\Gate;
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
        Gate::policy(BlogPost::class, BlogPostPolicy::class);
        Gate::policy(Testimonial::class, TestimonialPolicy::class);
    }
}
