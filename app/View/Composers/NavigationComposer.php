<?php

namespace App\View\Composers;

use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class NavigationComposer
{
    /**
     * Bind data to the view.
     *
     * @param  \Illuminate\View\View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $pendiente = false;
        if (Auth::check()) {
            $pendiente = Order::where('user_id', Auth::id())->where('status', 1)->exists();
        }
        $view->with('pendiente', $pendiente);
    }
}