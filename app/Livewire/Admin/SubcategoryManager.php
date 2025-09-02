<?php

namespace App\Livewire\Admin;

use App\Models\Category;
use App\Models\Subcategory;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Validate;

class SubcategoryManager extends Component
{
    use WithPagination;

    public Category $category;

    // Propiedades para el formulario (crear/editar)
    public ?Subcategory $editingSubcategory = null;
    
    // Propiedades para el modal de borrado
    public $showDeleteModal = false;
    public ?Subcategory $deletingSubcategory = null;

    #[Validate('required|string|min:3')]
    public $name = '';

    public $slug = '';

    #[Validate('required|boolean')]
    public $hasColor = false;

    #[Validate('required|boolean')]
    public $hasSize = false;

    public function mount(Category $category)
    {
        $this->category = $category;
        $this->initializeForm();
    }

    public function initializeForm(?Subcategory $subcategory = null)
    {
        $this->editingSubcategory = $subcategory;

        if ($this->editingSubcategory) {
            // Editando una subcategoría existente
            $this->name = $this->editingSubcategory->name;
            $this->slug = $this->editingSubcategory->slug;
            $this->hasColor = $this->editingSubcategory->color;
            $this->hasSize = $this->editingSubcategory->size;
        } else {
            // Creando una nueva subcategoría
            $this->name = '';
            $this->slug = '';
            // Aquí está la clave: inicializar con los valores de la categoría padre
            $this->hasColor = (bool) $this->category->features['color'];
            $this->hasSize = (bool) $this->category->features['size'];
        }
        
        // Limpiar errores de validación al cambiar de modo
        $this->resetValidation();
    }

    public function updatedName($value)
    {
        $this->slug = Str::slug($value);
    }

    public function save()
    {
        $this->validate();

        $data = [
            'category_id' => $this->category->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'color' => $this->hasColor,
            'size' => $this->hasSize,
        ];

        if ($this->editingSubcategory) {
            $this->editingSubcategory->update($data);
            session()->flash('message', 'Subcategoría actualizada con éxito.');
        } else {
            Subcategory::create($data);
            session()->flash('message', 'Subcategoría creada con éxito.');
        }

        $this->reset('name', 'slug');
        $this->initializeForm(); // Re-inicializar para el modo 'crear'
    }
    
    public function edit(Subcategory $subcategory)
    {
        $this->initializeForm($subcategory);
    }

    public function cancelEdit()
    {
        $this->initializeForm();
    }

    public function confirmDelete(Subcategory $subcategory)
    {
        $this->deletingSubcategory = $subcategory;
        $this->showDeleteModal = true;
    }

    public function deleteSubcategory()
    {
        if ($this->deletingSubcategory) {
            // Comprobamos si la subcategoría tiene productos asociados
            if ($this->deletingSubcategory->products()->exists()) {
                session()->flash('error', 'No se puede eliminar la subcategoría porque tiene productos asociados.');
                $this->showDeleteModal = false;
                return;
            }

            $this->deletingSubcategory->delete();
            session()->flash('message', 'Subcategoría eliminada con éxito.');

            $this->showDeleteModal = false;
            $this->deletingSubcategory = null; // Limpiar la propiedad
        }
    }

    public function render()
    {
        $subcategories = $this->category->subcategories()->paginate(10);
        return view('livewire.admin.subcategory-manager', compact('subcategories'))
            ->layout('components.layouts.app.admin');
    }
}