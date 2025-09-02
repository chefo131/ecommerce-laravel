<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class LogSuccessfulLogin
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(Login $event): void
    {
        // $event->user contiene el usuario que acaba de iniciar sesión
        // $event->guard contiene el guard que se usó
        // $event->remember contiene si se marcó "remember me"

        $user = $event->user;
        $guard = $event->guard;
        $sessionId = session()->getId(); // Obtener el ID de sesión actual

        Log::info('User logged in via ' . $guard . ' guard. User: ' . ($user?->email ?? 'N/A') . ', Session ID: ' . $sessionId . ', Auth Check: ' . (auth()->guard($guard)->check() ? 'true' : 'false'));

        // Opcional: Forzar el guardado de la sesión si sospechas que no se hace a tiempo.
        // session()->save();
    
    }
}
