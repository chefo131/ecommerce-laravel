<?php

namespace App\Livewire\Actions;

use App\Events\LogoutCartStore;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class Logout
{
    /**
     * Log the user out of the application.

     */
    public function __invoke(): \Illuminate\Http\RedirectResponse
    {
        // 1. Disparamos nuestro evento personalizado ANTES de cerrar la sesión.
        // El listener 'StoreCartOnLogout' necesita al usuario autenticado
        // para saber a quién pertenece el carrito que va a guardar.
        if (Auth::check()) {
            LogoutCartStore::dispatch();
        }

        // 2. Ahora sí, cerramos la sesión del usuario.
        Auth::guard('web')->logout();

        Session::invalidate();
        Session::regenerateToken();

        return redirect('/');
    }
}
