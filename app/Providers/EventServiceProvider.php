<?php

namespace App\Providers;

use App\Events\LogoutCartStore;
use App\Listeners\StoreCartOnLogout;
use App\Listeners\StoreCartOnLogin;
use App\Listeners\LogSuccessfulLogin;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        // Evento personalizado para guardar el carrito al salir
        LogoutCartStore::class => [
            StoreCartOnLogout::class,
        ],

        // Evento incorporado de Laravel para cuando un usuario inicia sesión
        Login::class => [
            StoreCartOnLogin::class,
            LogSuccessfulLogin::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        //
    }
}
