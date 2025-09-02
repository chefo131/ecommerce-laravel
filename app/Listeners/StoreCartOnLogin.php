<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login; // 1. Importamos el evento de Login de Laravel
use Gloudemans\Shoppingcart\Facades\Cart;

class StoreCartOnLogin
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
    public function handle(Login $event): void // 2. Escuchamos el evento de Laravel
    {
        // El objeto $event ya contiene el usuario que ha iniciado sesión.
        if (!$event->user) {
            return;
        }
        // 3. Restauramos el carrito. El paquete se encarga de la sesión, no necesitamos código extra.
        Cart::restore($event->user->id);
    }
}
