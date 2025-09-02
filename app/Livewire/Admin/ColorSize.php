<?php

namespace App\Livewire\Admin;

use App\Models\Color;
use App\Models\Size;
use Livewire\Component;
use Livewire\Attributes\Validate;

class ColorSize extends Component
{
    // Propiedades del componente
    public Size $size;
    public $allColors; // Todos los colores disponibles para el select

    // Propiedades para el formulario de añadir nuevo color
    #[Validate('required|exists:colors,id')]
    public $color_id = '';

    #[Validate('required|numeric|min:1')]
    public $quantity = '';

    // Propiedades para el modal de confirmación de borrado
    public $openModal = false;
    public $colorIdToDelete;

    public function mount(Size $size)
    {
        $this->size = $size;
        $this->allColors = Color::all();
    }

    public function save()
    {
        $this->validate();

        // Buscamos si el color ya está asociado a esta talla
        $existingColor = $this->size->colors()->where('color_id', $this->color_id)->first();

        if ($existingColor) {
            // Si ya existe, actualizamos la cantidad en la tabla pivote
            // 'pivot' es el objeto mágico que nos da acceso a la tabla intermedia
            $newQuantity = $existingColor->pivot->quantity + (int)$this->quantity;
            $this->size->colors()->updateExistingPivot($this->color_id, [
                'quantity' => $newQuantity,
            ]);
        } else {
            // Si no existe, lo añadimos (attach)
            $this->size->colors()->attach($this->color_id, [
                'quantity' => $this->quantity,
            ]);
        }

        // Forzamos a la talla a recargarse desde la BD para que la vista se actualice
        $this->size->refresh();
        $this->reset(['color_id', 'quantity']);
    }

    public function openDeleteModal($colorId)
    {
        $this->colorIdToDelete = $colorId;
        $this->openModal = true;
    }

    public function delete()
    {
        // 'detach' elimina la relación entre la talla y el color
        $this->size->colors()->detach($this->colorIdToDelete);
        $this->size->refresh();
        $this->reset(['openModal', 'colorIdToDelete']);
    }

    public function render()
    {
        $sizeColors = $this->size->colors;
        return view('livewire.admin.color-size', compact('sizeColors'));
    }
}


