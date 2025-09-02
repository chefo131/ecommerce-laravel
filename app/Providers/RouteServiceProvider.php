<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to your application's "home" route.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string

     */
    public const HOME = '/';

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // La configuración de las rutas (web.php, api.php)
        // ya se gestiona en bootstrap/app.php en Laravel 12,
        // por lo que este método puede permanecer vacío.

    }
}
