<?php

namespace App\Livewire\Admin;

use App\Models\Product;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class ShowProduct extends Component
{
    use WithPagination;

    public $searchTerm = '';
    


    public function render()
    {
        $products = Product::query()
            // Carga eficiente (Eager Loading): Le decimos a Laravel que traiga las relaciones
            // en una sola consulta para evitar el problema N+1.
            ->with(['subcategory.category'])
            ->where('name', 'like', '%' . $this->searchTerm . '%')
            ->orderBy('id', 'desc')
            ->paginate(10);

        // Aquí está la solución:
        // Le decimos explícitamente a este componente de página completa
        // que use nuestro layout de administración personalizado.
        return view('livewire.admin.show-product', compact('products'))
            ->layout('components.layouts.app.admin');
    }

    // Muestra la alerta de confirmación
    public function confirmDelete($productId)
    {
        $this->dispatch('swal:confirm', [
            'icon' => 'warning',
            'title' => '¿Estás seguro?',
            'text' => '¡No podrás revertir esta acción! Se eliminará el producto y todas sus imágenes asociadas.',
            'confirmButtonText' => 'Sí, ¡bórralo!',
            'cancelButtonText' => 'Cancelar',
            'method' => 'deleteProduct', // El método que se ejecutará si se confirma
            'params' => $productId,     // El ID del producto a eliminar
        ]);
    }

    // Ejecuta la eliminación del producto
    #[On('deleteProduct')]
    public function deleteProduct($id)
    {
        try {
            $product = Product::findOrFail($id);
            // MediaLibrary se encarga de borrar los ficheros asociados automáticamente
            $product->delete();

            $this->dispatch('swal', [
                'icon' => 'success',
                'title' => '¡Eliminado!',
                'text' => 'El producto ha sido eliminado con éxito.',
            ]);
        } catch (\Exception $e) {
            $this->dispatch('swal', [
                'icon' => 'error',
                'title' => '¡Error!',
                'text' => 'Ocurrió un error al eliminar el producto. Es posible que esté asociado a ventas existentes.',
            ]);
        }
    }

}