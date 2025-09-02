<?php

namespace App\Livewire\Admin;

use App\Models\Brand;
use App\Models\Category;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;

class CreateCategory extends Component
{
    use WithFileUploads;

    // --- Propiedades del formulario ---
    public $name = '';
    public $slug = '';
    public $icon = '';
    public $image;
    public $brands = [];
    public $features = [
        'color' => false,
        'size' => false,
    ];

    // --- Propiedades para cargar datos ---
    public $allBrands;

    // El método mount se ejecuta al iniciar el componente.
    public function mount()
    {
        $this->allBrands = Brand::all();
    }

    // Hook que se ejecuta automáticamente cuando la propiedad 'name' se actualiza.
    public function updatedName($value)
    {
        $this->slug = Str::slug($value);
    }

    // Definimos las reglas de validación
    public function rules()
    {
        return [
            'name' => 'required|string|min:3',
            'slug' => 'required|string|unique:categories,slug',
            'icon' => 'nullable|string',
            'image' => 'required|image|max:1024', // 1MB Max
            'brands' => 'required|array|min:1',
            'features.color' => 'boolean',
            'features.size' => 'boolean',
        ];
    }

    // Método para guardar la nueva categoría
    public function save()
    {
        $this->validate();

        // Limpiamos el código del icono para guardar solo las clases
        $icon_code = $this->icon;
        if (preg_match('/class="([^"]+)"/', $this->icon, $matches)) {
            $icon_code = $matches[1];
        }

        // Creamos la categoría
        $category = Category::create([
            'name' => $this->name,
            'slug' => $this->slug,
            // Guardamos el código limpio
            'icon' => $icon_code,
            'features' => $this->features,
        ]);

        // Guardamos la imagen asociada
        $category->addMedia($this->image->getRealPath())
            ->toMediaCollection('categories');

        // Sincronizamos las marcas asociadas
        $category->brands()->attach($this->brands);

        // ¡LA MAGIA! Emitimos un evento para que otros componentes se enteren.
        $this->dispatch('category-created');

        // Reseteamos el formulario para que quede limpio
        $this->reset(['name', 'slug', 'icon', 'image', 'brands', 'features']);

        // Enviamos una notificación de éxito
        $this->dispatch('swal', [
            'icon' => 'success',
            'title' => '¡Categoría Creada!',
            'text' => 'La nueva categoría se ha añadido correctamente.',
        ]);
    }

    public function render()
    {
        return view('livewire.admin.create-category');
    }
}