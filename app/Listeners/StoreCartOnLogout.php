<?php

namespace App\Listeners;

use App\Events\LogoutCartStore;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\Auth;

class StoreCartOnLogout
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
    public function handle(LogoutCartStore $event): void
    {
        $user = Auth::user();

        if (!$user) {
            return;
        }

        // Guarda el contenido actual del carrito en la base de datos serializado
        Cart::store($user->id);

        // (Opcional) Limpia la sesión manual para que no se duplique al siguiente login
        // session()->forget('cart');
    }
}
