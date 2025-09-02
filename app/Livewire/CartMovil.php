<?php

namespace App\Livewire;

use Livewire\Component;
use Gloudemans\Shoppingcart\Facades\Cart; // Importar para usar Cart

class CartMovil extends Component
{
    protected $listeners = ['cartUpdated' => '$refresh'];

    public function render()
    {
        return view('livewire.cart-movil');
    }
}
