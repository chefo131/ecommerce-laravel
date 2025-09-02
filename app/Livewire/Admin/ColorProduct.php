<?php

namespace App\Livewire\Admin;

use App\Models\Color;
use App\Models\Product;
use Livewire\Component;
use Livewire\Attributes\Validate;

class ColorProduct extends Component
{
    // Propiedades del componente
    public Product $product;
    public $allColors;     // Todos los colores disponibles para el select

    // Propiedades para el formulario de añadir nuevo color
    #[Validate('required|exists:colors,id')]
    public $color_id = '';

    #[Validate('required|numeric|min:1')]
    public $quantity = '';

    // Propiedades para el modal de confirmación de borrado
    public $openModal = false;
    public $colorIdToDelete;

    public function mount()
    {
        $this->allColors = Color::all();
    }

    public function saveColor()
    {
        $this->validate();

        // Para máxima robustez, consultamos directamente la relación en la BD
        // en lugar de confiar en la colección en memoria ($this->productColors),
        // que puede tener un estado "fantasma".
        $existingColor = $this->product->colors()->where('color_id', $this->color_id)->first();

        if ($existingColor) {
            // Si ya existe, simplemente sumamos la cantidad
            // ¡CORRECCIÓN CLAVE! Accedemos a la cantidad a través del objeto 'pivot'.
            // 'pivot' es el puente que nos da acceso a los datos de la tabla intermedia.
            $newQuantity = $existingColor->pivot->quantity + (int)$this->quantity;
            $this->product->colors()->updateExistingPivot($this->color_id, [
                'quantity' => $newQuantity,
            ]);
        } else {
            // Si no existe, lo añadimos (attach)
            $this->product->colors()->attach($this->color_id, [
                'quantity' => $this->quantity,
            ]);
        }

        // ¡LA SOLUCIÓN! Forzamos al modelo a recargarse desde la BD.
        // Esto actualiza el producto y todas sus relaciones (como los pivots).
        $this->product->refresh();
        $this->reset(['color_id', 'quantity']);

        // Enviamos un evento para notificar al usuario
        $this->dispatch('swal', [
            'icon' => 'success',
            'title' => '¡Hecho!',
            'text' => 'Color agregado/actualizado correctamente.'
        ]);
    }

    // Actualiza la cantidad de un color existente directamente desde la lista
    public function updateQty($colorId, $newQuantity)
    {
        // Validamos que la cantidad sea un número válido
        if (!is_numeric($newQuantity) || $newQuantity < 0) {
            $this->dispatch('swal', ['icon' => 'error', 'title' => 'Error', 'text' => 'La cantidad debe ser un número válido.']);
            return;
        }

        $this->product->colors()->updateExistingPivot($colorId, [
            'quantity' => $newQuantity,
        ]);

        // Refrescamos también aquí para asegurar consistencia.
        $this->product->refresh();
    }

    // Abre el modal de confirmación para eliminar
    public function openDeleteModal($colorId)
    {
        $this->colorIdToDelete = $colorId;
        $this->openModal = true;
    }

    // Elimina la relación color-producto
    public function deleteColor()
    {
        // CAMBIO DE LÓGICA: En lugar de eliminar la relación (detach),
        // actualizamos la cantidad en la tabla pivote a 0.
        // Esto es más intuitivo para el usuario.
        $this->product->colors()->updateExistingPivot($this->colorIdToDelete, [
            'quantity' => 0,
        ]);
        // Y por supuesto, refrescamos aquí también después de borrar.
        $this->product->refresh();
        $this->reset(['openModal', 'colorIdToDelete']);

        $this->dispatch('swal', [
            'icon' => 'success',
            'title' => '¡Eliminado!',
            'text' => 'El color se ha eliminado del producto.'
        ]);
    }

    public function render()
    {
        // Hacemos que la vista siempre reciba la colección de colores más actualizada,
        // eliminando cualquier posibilidad de estado "fantasma".
        return view('livewire.admin.color-product', [
            'productColors' => $this->product->colors
        ]);
    }
}
