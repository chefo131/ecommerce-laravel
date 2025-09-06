<?php

namespace App\Livewire\Admin;

use App\Mail\ReviewApproved;
use App\Models\Review;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Mail;

class ManageReviews extends Component
{
    use WithPagination;

    public $status = Review::PENDIENTE; // Por defecto, mostramos las pendientes

    // Usamos el queryString para que el estado se refleje en la URL y se pueda compartir
    protected $queryString = [
        'status' => ['except' => Review::PENDIENTE, 'as' => 's'],
    ];

    public function setStatus($newStatus)
    {
        $this->status = $newStatus;
        $this->resetPage(); // Reseteamos la paginación al cambiar de filtro
    }

    public function approve(Review $review)
    {
        $review->status = Review::APROBADO;
        $review->save();

        // ¡Aquí enviamos el email al usuario!
        Mail::to($review->user)->send(new ReviewApproved($review));

        $this->dispatch('swal', [
            'icon' => 'success',
            'title' => '¡Aprobada!',
            'text' => 'La reseña ha sido aprobada y ahora es visible en la página del producto.'
        ]);
    }

    public function reject(Review $review)
    {
        $review->status = Review::RECHAZADO;
        $review->save();

        $this->dispatch('swal', [
            'icon' => 'info',
            'title' => '¡Rechazada!',
            'text' => 'La reseña ha sido rechazada y no será visible.'
        ]);
    }

    public function render()
    {
        $reviews = Review::where('status', $this->status)
            ->with(['user', 'product']) // Carga eficiente de relaciones para evitar N+1 queries
            ->latest('id')
            ->paginate(10);

        // Contadores para las pestañas
        $counters = [
            'pending' => Review::where('status', Review::PENDIENTE)->count(),
            'approved' => Review::where('status', Review::APROBADO)->count(),
            'rejected' => Review::where('status', Review::RECHAZADO)->count(),
        ];

        return view('livewire.admin.manage-reviews', compact('reviews', 'counters'))
            ->layout('components.layouts.app.admin'); // Asumimos que tienes un layout de admin llamado 'layouts.admin'
    }
}
