<?php

namespace App\Livewire;

use App\Models\Product;
use App\Models\Review;
use Livewire\Component;
use Livewire\Attributes\Validate;

class AddProductReview extends Component
{
    public Product $product;

    #[Validate('required|integer|min:1|max:5', message: 'Debes seleccionar una calificación.')]
    public $rating = 5;

    #[Validate('required|min:10', message: 'Tu comentario debe tener al menos 10 caracteres.')]
    public $comment = '';

    public function mount(Product $product)
    {
        $this->product = $product;
    }

    public function saveReview()
    {
        $this->validate();

        // Una última comprobación por si las moscas
        if (!$this->product->canBeReviewed()) {
            $this->dispatch('swal', [
                'icon' => 'error',
                'title' => '¡Acción no permitida!',
                'text' => 'No cumples los requisitos para dejar una reseña.'
            ]);
            return;
        }

        $this->product->reviews()->create([
            'comment' => $this->comment,
            'rating' => $this->rating,
            'user_id' => auth()->id(),
            // ¡Añadido! Asegura que la reseña entre en estado pendiente.
            'status' => Review::PENDIENTE,
        ]);

        // Limpiamos el formulario
        $this->reset(['comment', 'rating']);

        // Refrescamos la página o un componente padre para mostrar la nueva reseña
        $this->dispatch('reviewAdded');

        // Mostramos una notificación de éxito
        $this->dispatch('swal', [
            'icon' => 'success',
            'title' => '¡Gracias por tu opinión!',
            'text' => 'Tu reseña ha sido enviada y está pendiente de aprobación.'
        ]);
    }

    public function render()
    {
        return view('livewire.add-product-review');
    }
}
