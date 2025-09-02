<?php

namespace App\Livewire;

use App\Models\Product;
use App\Models\Review;
use Livewire\Component;

class AddReview extends Component
{
    public Product $product;
    public $rating = 0;
    public $comment = '';

    protected $rules = [
        'rating' => 'required|integer|min:1|max:5',
        'comment' => 'required|string|min:10',
    ];

    protected $messages = [
        'rating.required' => 'Por favor, selecciona una calificación de estrellas.',
        'comment.required' => 'El comentario no puede estar vacío.',
        'comment.min' => 'El comentario debe tener al menos 10 caracteres.',
    ];

    public function save()
    {
        $this->validate();

        $this->product->reviews()->create([
            'user_id' => auth()->id(),
            'rating' => $this->rating,
            'comment' => $this->comment,
            'status' => Review::PENDIENTE, // ¡Aquí está la clave! Se guarda como pendiente.
        ]);

        // Reseteamos el formulario
        $this->reset(['rating', 'comment']);

        // Refrescamos la información del producto para que el método canBeReviewed() se re-evalúe
        $this->product->refresh();

        // Enviamos una notificación al usuario
        $this->dispatch('swal', [
            'icon' => 'success',
            'title' => '¡Gracias por tu reseña!',
            'text' => 'Tu reseña ha sido enviada y será publicada tras ser revisada por nuestro equipo.'
        ]);
    }

    public function render()
    {
        return view('livewire.add-review');
    }
}