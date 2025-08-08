<?php

namespace App\Providers;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use App\Interfaces\Shopify\ProductoInterface;
use App\Services\Shopify\ShopifyOrderService;
use App\Services\Shopify\ShopifyProductService;
use App\Interfaces\Shopify\OrderProviderInterface;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(ProductoInterface::class, fn () => new ShopifyProductService());

        $this->app->bind(OrderProviderInterface::class, fn () => new ShopifyOrderService());
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        URL::forceRootUrl(config('app.url'));

        if (Str::startsWith(config('app.url'), 'https://')) {
            URL::forceScheme('https');
        }
    }
}
