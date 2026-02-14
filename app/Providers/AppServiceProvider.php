<?php

namespace App\Providers;

use App\Models\CashRegisterSession;
use App\Models\Product;
use App\Models\Purchase;
use App\Policies\CashRegisterSessionPolicy;
use App\Policies\ProductPolicy;
use App\Policies\PurchasePolicy;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */

        protected $policies = [
        Product::class => ProductPolicy::class,
        Purchase::class => PurchasePolicy::class,
        CashRegisterSession::class => CashRegisterSessionPolicy::class,
    ];

    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
