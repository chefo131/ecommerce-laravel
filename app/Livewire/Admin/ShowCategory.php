<?php

namespace App\Livewire\Admin;

use App\Models\Category;
use App\Models\Subcategory;
use Livewire\Component;
use Illuminate\Support\Str;

class ShowCategory extends Component
{
    /**
     * La categoría que se está mostrando.
     * @var \App\Models\Category
     */
    public Category $category;

    /**
     * Datos del formulario para la nueva subcategoría.
     * @var array
     */
    public $subcategory = [
        'name' => '',
        'slug' => '',
        'color' => false,
        'size' => false,
    ];

    /**
     * Reglas de validación para el formulario.
     */
    protected $rules = [
        'subcategory.name' => 'required|string|max:255',
        'subcategory.slug' => 'required|string|max:255|unique:subcategories,slug',
        'subcategory.color' => 'required|boolean',
        'subcategory.size' => 'required|boolean',
    ];

    /**
     * Mensajes de error personalizados para la validación.
     */
    protected $messages = [
        'subcategory.name.required' => 'El nombre de la subcategoría es obligatorio.',
        'subcategory.slug.required' => 'El slug es obligatorio.',
        'subcategory.slug.unique' => 'Este slug ya ha sido tomado, por favor elija otro nombre.',
        'subcategory.color.required' => 'Debe especificar si esta subcategoría usará colores.',
        'subcategory.size.required' => 'Debe especificar si esta subcategoría usará tallas.',
    ];

    /**
     * Se ejecuta cuando el componente es montado.
     */
    public function mount(Category $category)
    {
        $this->category = $category;
    }

    /**
     * Se ejecuta cuando la propiedad 'subcategory.name' se actualiza.
     * Genera automáticamente el slug a partir del nombre gracias a los hooks de Livewire.
     */
    public function updated($propertyName)
    {
        if ($propertyName == 'subcategory.name') {
            $this->subcategory['slug'] = Str::slug($this->subcategory['name']);
        }
    }

    /**
     * Guarda la nueva subcategoría en la base de datos.
     */
    public function save()
    {
        $this->validate();
        $this->category->subcategories()->create($this->subcategory);
        $this->reset('subcategory');
        $this->dispatch('saved');
        $this->category = $this->category->fresh(); // Refresca la categoría para mostrar la nueva subcategoría
    }

    public function render()
    {
        return view('livewire.admin.show-category')
            ->layout('components.layouts.app.admin');
    }
}