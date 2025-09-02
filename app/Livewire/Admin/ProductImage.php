<?php

namespace App\Livewire\Admin;

use App\Models\Product;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class ProductImage extends Component
{
    use WithFileUploads;

    public Product $product;

    // Le decimos a Livewire que esta propiedad puede recibir ficheros y cómo validarlos.
    #[Validate('required|image|max:1024')] // 1MB Max
    public $image; // Propiedad para el binding del fichero

    // ¡LA MAGIA! Este método se llama automáticamente cuando la propiedad 'image' se actualiza.
    public function updatedImage()
    {
        $this->saveImage();
    }

    // Guardar la imagen subida
    public function saveImage()
    {
        $this->validate();

        try {
            // Usamos Medialibrary para añadir la imagen a la colección 'products'
            $this->product->addMedia($this->image->getRealPath())
                ->usingName($this->image->getClientOriginalName())
                ->toMediaCollection('products');

            // Reseteamos la propiedad de la imagen para limpiar el input
            $this->reset('image');

            // ¡Feedback para el usuario!
            $this->dispatch('swal', [
                'icon' => 'success',
                'title' => '¡Éxito!',
                'text' => 'La imagen se ha subido correctamente.',
            ]);

            // ¡EL GRITO! Avisamos a otros componentes que el producto ha sido actualizado.
            $this->dispatch('productUpdated');

        } catch (\Exception $e) {
            // En caso de un error inesperado, informamos al usuario.
            $this->dispatch('swal', [
                'icon' => 'error',
                'title' => '¡Error!',
                'text' => 'Ocurrió un error al subir la imagen. Por favor, inténtalo de nuevo.',
            ]);
        }
    }

    // Eliminar una imagen
    public function deleteImage($mediaId)
    {
        try {
            // Buscamos la imagen por su ID y la eliminamos
            Media::find($mediaId)->delete();
            $this->dispatch('swal', [
                'icon' => 'success',
                'title' => '¡Eliminada!',
                'text' => 'La imagen se ha eliminado correctamente.',
            ]);
            // Avisamos también al borrar para que el padre se refresque
            $this->dispatch('productUpdated');
        } catch (\Exception $e) {
            $this->dispatch('swal', [
                'icon' => 'error',
                'title' => '¡Error!',
                'text' => 'Ocurrió un error al eliminar la imagen.',
            ]);
        }
    }

    // Este método se llamará automáticamente cuando se reordenen los elementos
    public function updateMediaOrder($items)
    {
        // Medialibrary tiene una función integrada para reordenar basada en un array de IDs
        Media::setNewOrder(collect($items)->pluck('value')->toArray());
        $this->dispatch('swal', ['icon' => 'success', 'title' => '¡Orden actualizado!']);
        // Y también al reordenar, para que el padre sepa del cambio
        $this->dispatch('productUpdated');
    }

    public function render()
    {
        return view('livewire.admin.product-image');
    }
}