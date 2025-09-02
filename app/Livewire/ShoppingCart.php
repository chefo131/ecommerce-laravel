<?php

namespace App\Livewire;

use Livewire\Component;
use Gloudemans\Shoppingcart\Facades\Cart;

/**
 * @mixin \Livewire\Component
 */
class ShoppingCart extends Component
{
    protected $listeners = ['cartUpdated' => '$refresh'];

    public function destroy()
    {
        Cart::destroy();
        $this->dispatch('cartUpdated');
    }

    public function delete($rowId)
    {
        Cart::remove($rowId);
        $this->dispatch('cartUpdated');
    }

    public function render()
    {
        return view('livewire.shopping-cart');
    }
}
