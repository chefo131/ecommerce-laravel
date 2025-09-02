<?php

namespace App\Livewire\Admin;

use App\Models\Product;
use App\Models\Size;
use Livewire\Component;

class SizeProduct extends Component
{
    // Propiedades públicas
    public Product $product;
    public $allSizes; // Todas las tallas globales para el select

    // --- FORMULARIO PARA ASOCIAR UNA NUEVA TALLA ---
    public $size_id = '';

    // --- PROPIEDADES PARA EL MODAL DE BORRADO ---
    public $openModal = false;
    public $sizeIdToDelete;

    // Mensajes de error personalizados
    protected $messages = [
        'size_id.required' => 'Debes seleccionar una talla.',
        'size_id.unique' => 'La talla seleccionada ya está asociada a este producto.',
    ];

    public function mount(Product $product)
    {
        $this->product = $product;
        $this->allSizes = Size::all();
    }

    public function addSize()
    {
        $this->validate([
            // Validamos que la talla exista y que no esté ya en este producto
            'size_id' => 'required|exists:sizes,id|unique:product_size,size_id,NULL,id,product_id,' . $this->product->id
        ]);

        // 'attach' es el método para añadir registros en una tabla pivote
        $this->product->sizes()->attach($this->size_id);

        $this->reset('size_id');
        $this->product->refresh(); // Recargamos el producto para que la vista se actualice
    }

    public function openDeleteModal($sizeId)
    {
        $this->sizeIdToDelete = $sizeId;
        $this->openModal = true;
    }

    public function removeSize()
    {
        // 'detach' elimina el registro de la tabla pivote
        $this->product->sizes()->detach($this->sizeIdToDelete);
        $this->reset(['openModal', 'sizeIdToDelete']);
        $this->product->refresh(); // Recargamos para actualizar la lista
    }

    public function render()
    {
        // Obtenemos las tallas asociadas a ESTE producto a través de la relación
        $productSizes = $this->product->sizes;
        return view('livewire.admin.size-product', compact('productSizes'));
    }
}
