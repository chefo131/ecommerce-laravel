<?php

namespace App\Providers;

use App\Models\User;
use App\Models\Product;
use App\Models\Order;
use App\Policies\ProductPolicy;
use App\Policies\OrderPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // Aquí registramos que el modelo 'Order' usa la política 'OrderPolicy'
        Product::class => ProductPolicy::class,
        Order::class => OrderPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        Gate::define('view-dashboard', function (User $user) {
            return $user->id == 1;
        });
    }
}
