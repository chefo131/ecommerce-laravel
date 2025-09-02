<?php

namespace App\Livewire;

use Livewire\Component;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\Storage;
use App\Models\Product;

/**
 * @mixin \Livewire\Component
 */
class AddCartItem extends Component
{
    /** @var \App\Models\Product */
    public $product;

    /** @var int */
    public $quantity; //Stock de productos

    /** @var int */
    public $qty = 1;

    /** @var array */
    public $options = [
        // Se inicializa en mount()
    ];

    public function mount()
    {
        $this->quantity = qty_available($this->product->id);
        // Inicializamos las opciones aquí para mantener la consistencia con los otros componentes del carrito
        $this->options = [
            'size_id' => null,
            'color_id' => null,
            'image' => null
        ];
        // Usamos Medialibrary para obtener la URL completa de la primera imagen.
        // Esto simplifica las vistas del carrito, ya que no necesitan usar Storage::url().
        if ($this->product->getFirstMediaUrl('products')) {
            $this->options['image'] = $this->product->getFirstMediaUrl('products');
        }
    }

    public function decrement()
    {
        $this->qty = max(1, $this->qty - 1);
    }

    public function increment()
    {
        $this->qty = min($this->quantity, $this->qty + 1);
    }

    protected $rules = [
        'qty' => 'required|numeric|min:1',
    ];
    protected $messages = [
        'qty.required' => 'La cantidad es requerida.',
        'qty.min' => 'Debes añadir al menos un producto.',
    ];

    public function addItem()
    {
        $this->validate();
        $this->addToCart();
        $this->quantity = qty_available($this->product->id);
        $this->reset('qty'); // Solo reseteamos la cantidad, no los IDs que no se usan
    }

    /**
     * Encapsulates the logic for adding an item to the cart and emitting the update event.
     * This method is protected so it can be called by child components.
     */
    protected function addToCart(): void
    {
        Cart::add([
            'id' => $this->product->id,
            'name' => $this->product->name,
            'qty' => $this->qty,
            'price' => $this->product->price,
            'options' => $this->options,
        ]);

        $this->dispatch('cartUpdated');
    }

    public function render()
    {
        return view('livewire.add-cart-item');
    }
}
