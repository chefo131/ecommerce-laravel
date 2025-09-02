<?php

namespace App\Livewire\Admin;

use App\Models\Category;
use Livewire\Component;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;

class ManageCategories extends Component
{
    // Usamos una propiedad computada para que la consulta a la BD
    // se "cachee" durante el ciclo de vida de la petición, haciéndolo más eficiente.
    #[Computed]
    public function categories()
    {
        return Category::orderBy('id', 'desc')->get();
    }

    // Este método "escucha" el evento 'category-created' que hemos creado.
    // El simple hecho de recibir el evento hace que Livewire refresque el componente.
    #[On('category-created')]
    public function refreshCategoryList()
    {
        // No necesitamos poner nada aquí. La magia ocurre sola.
        // Al recibir el evento, Livewire re-renderiza el componente,
        // y la propiedad computada `$this->categories` se volverá a calcular.
    }

    // Muestra la alerta de confirmación antes de borrar
   public function confirmDelete($id)
    {
        $category = Category::find($id);

        if (!$category) {
            // Si por alguna razón la categoría no existe, mostramos un error y paramos.
            $this->dispatch('swal', ['icon' => 'error', 'title' => 'Error', 'text' => 'Categoría no encontrada.']);
            return;
        }

        $this->dispatch('swal:confirm', [
            'icon' => 'warning',
            'title' => '¿Estás seguro?',
            'text' => "¡No podrás revertir esto! Se eliminará la categoría '{$category->name}' y todas sus subcategorías asociadas.",
            'method' => 'destroyCategory',
            'params' => $category->id,
        ]);
    }

    // Este método "escucha" el evento de confirmación y ejecuta el borrado.
     #[On('destroyCategory')]
    public function destroy($id)
    {
        $category = Category::find($id);

        if ($category) {
            $category->delete();
            $this->dispatch('swal', [
                'icon' => 'success',
                'title' => '¡Eliminada!',
                'text' => 'La categoría ha sido eliminada con éxito.',
            ]);
        } else {
            $this->dispatch('swal', [
                'icon' => 'error',
                'title' => '¡Error!',
                'text' => 'No se pudo encontrar la categoría para eliminar.',
            ]);
        }
    }

    public function render()
    {
        return view('livewire.admin.manage-categories');
    }
}
