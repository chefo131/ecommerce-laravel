<?php

namespace App\Livewire;

use App\Models\Color;
use Livewire\Component;
use Gloudemans\Shoppingcart\Facades\Cart;

/**
 * @mixin \Livewire\Component
 */
class UpdateCartItemColor extends Component
{
    public $rowId;
    public $qty;
    public $quantity;

    public function mount()
    {
        $item = Cart::get($this->rowId);
        $this->qty = $item->qty;
        $color = Color::where('name', $item->options->color)->first();
        $this->quantity = qty_available($item->id, $color->id);
    }

    public function decrement()
    {
        $this->qty = $this->qty - 1;
        Cart::update($this->rowId, $this->qty);
        $this->emit('cartUpdated');
    }

    public function increment()
    {
        $this->qty = $this->qty + 1;
        Cart::update($this->rowId, $this->qty);
        $this->emit('cartUpdated');
    }

    public function render()
    {
        return view('livewire.update-cart-item-color');
    }
}
