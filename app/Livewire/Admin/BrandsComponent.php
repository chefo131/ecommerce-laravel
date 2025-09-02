<?php

namespace App\Livewire\Admin;

use App\Models\Brand;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Str;

class BrandsComponent extends Component
{
    use WithPagination;

    // --- Propiedades del componente ---
    public $search = '';
    public $showEditModal = false;

    // --- Propiedades del formulario (patrón de propiedades separadas para mayor robustez) ---
    public ?Brand $editing = null;
    public $brand_id = null;
    public $name = '';

    // --- Reglas de validación ---
    protected function rules()
    {
        // La validación ahora apunta a la propiedad 'name' y usa 'brand_id' para la regla 'unique'
        return [
            'name' => 'required|min:3|unique:brands,name,' . $this->brand_id,
        ];
    }

    protected $messages = [
        'name.required' => 'El nombre de la marca es obligatorio.',
        'name.min' => 'El nombre debe tener al menos 3 caracteres.',
        'name.unique' => 'Ya existe una marca con este nombre.',
    ];

    // --- Métodos de reseteo ---
    public function resetForm()
    {
        $this->reset(['editing', 'brand_id', 'name']);
        $this->resetValidation();
    }

    // --- Acciones del CRUD ---

    /**
     * Muestra el modal para crear una nueva marca.
     */
    public function create()
    {
        $this->resetForm();
        $this->showEditModal = true;
    }

    /**
     * Muestra el modal para editar una marca existente.
     */
    public function edit(Brand $brand)
    {
        $this->resetForm();
        $this->editing = $brand;
        $this->brand_id = $brand->id;
        $this->name = $brand->name;
        $this->showEditModal = true;
    }

    /**
     * Guarda la marca (nueva o existente).
     */
    public function save()
    {
        $this->validate();

        // Usamos updateOrCreate para unificar la lógica de creación y edición
        Brand::updateOrCreate(
            ['id' => $this->brand_id], // Busca por ID, si es null, crea
            [
                'name' => $this->name,
                'slug' => Str::slug($this->name)
            ]
        );

        $this->showEditModal = false;
        $this->resetForm();

        $this->dispatch('swal', [
            'icon' => 'success',
            'title' => '¡Hecho!',
            'text' => $this->brand_id ? 'Marca actualizada correctamente.' : 'Marca creada correctamente.',
        ]);
    }

    /**
     * Elimina una marca.
     */
    public function delete(Brand $brand)
    {
        // Comprobamos si la marca tiene productos asociados
        if ($brand->products()->exists()) {
            $this->dispatch('swal', [
                'icon' => 'error',
                'title' => '¡Acción denegada!',
                'text' => 'No se puede eliminar la marca porque tiene productos asociados.',
            ]);
            return;
        }

        $brand->delete();
    }

    // --- Renderizado ---
    public function render()
    {
        $brands = Brand::where('name', 'like', '%' . $this->search . '%')
            ->paginate(10);

        return view('livewire.admin.brands-component', compact('brands'))
            ->layout('components.layouts.app.admin');
    }
}
