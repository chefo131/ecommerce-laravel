<?php

namespace App\Livewire;

use App\Models\Product;
use Livewire\Attributes\On;
use Livewire\Component;

class ShowProduct extends Component
{
    public Product $product;

    public function mount(Product $product)
    {
        $this->product = $product;
    }

    /**
     * Escucha el evento 'review-added' emitido por el componente AddReview.
     * Cuando se añade una reseña, este método se ejecuta.
     */
    #[On('review-added')]
    public function refreshProduct()
    {
        // Simplemente refrescamos la instancia del producto desde la base de datos
        // para obtener la nueva reseña y actualizar la vista.
        $this->product->refresh();
    }

    public function render()
    {
        // Este componente utilizará la vista que crearemos a continuación.
        // Le pasamos el producto para que la vista tenga acceso a todos sus datos.
        return view('livewire.show-product');
    }
}

