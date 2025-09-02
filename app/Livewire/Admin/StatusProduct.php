<?php

namespace App\Livewire\Admin;

use App\Models\Product;
use Livewire\Component;

class StatusProduct extends Component
{
    public Product $product;
    public $status;

    // El método mount se ejecuta al iniciar el componente.
    // Aquí, inicializamos la propiedad $status con el estado actual del producto.
    public function mount()
    {
        $this->status = $this->product->status;
    }

    // Este método se llamará desde la vista cuando hagamos clic en el interruptor.
    public function updateStatus()
    {
        // Cambiamos el estado del producto: si es 1 (Borrador) lo pasa a 2 (Publicado), y viceversa.
        $this->product->status = ($this->status == 1) ? 2 : 1;
        $this->product->save();

        // Actualizamos la propiedad local del componente para que la vista reaccione al cambio.
        $this->status = $this->product->status;

        // Enviamos una notificación al usuario para confirmar la acción.
        $this->dispatch('swal', [
            'icon' => 'success',
            'title' => '¡Estado Actualizado!',
            'text' => 'El producto ha sido ' . ($this->status == 2 ? 'publicado' : 'puesto como borrador') . '.'
        ]);
    }

    public function render()
    {
        return view('livewire.admin.status-product');
    }
}
