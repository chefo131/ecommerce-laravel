<?php

namespace App\Livewire\Admin;

use App\Models\Brand;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;


class EditCategory extends Component
{
    use WithFileUploads;

    // --- Propiedades del componente ---
    public Category $category;

    // --- Propiedades del formulario ---
    public $name = '';
    public $slug = '';
    public $icon = '';
    public $image; // Para la nueva imagen
    public $brands = [];
    public $features = [
        'color' => false,
        'size' => false,
    ];

    // --- Propiedades para cargar datos ---
    public $allBrands;

    // El método mount se ejecuta al iniciar el componente.
    // Aquí cargamos la categoría a editar y poblamos el formulario.
    public function mount(Category $category)
    {
        $this->category = $category;
        $this->allBrands = Brand::all();

        // Poblamos las propiedades del formulario con los datos de la categoría
        $this->name = $category->name;
        $this->slug = $category->slug;
        $this->icon = $category->icon;
        // Obtenemos los IDs de las marcas asociadas a esta categoría
        $this->brands = $category->brands->pluck('id')->toArray();
        $this->features = $category->features;
    }

    // Hook que se ejecuta automáticamente cuando la propiedad 'name' se actualiza.
    // Genera el slug en tiempo real.
    public function updatedName($value)
    {
        $this->slug = Str::slug($value);
    }

    // Definimos las reglas de validación dinámicamente
    public function rules()
    {
        return [
            'name' => 'required|string|min:3',
            // La regla 'unique' debe ignorar el registro actual
            'slug' => [
                'required',
                'string',
                Rule::unique('categories')->ignore($this->category->id),
            ],
            'icon' => 'nullable|string',
            // La imagen es opcional al editar
            'image' => 'nullable|image|max:1024', // 1MB Max
            'brands' => 'required|array|min:1',
            'features.color' => 'boolean',
            'features.size' => 'boolean',
        ];
    }

    // Método para guardar los cambios en la categoría
    public function save()
    {
        $this->validate();

        // Limpiamos el código del icono para guardar solo las clases
        $icon_code = $this->icon;
        if (preg_match('/class="([^"]+)"/', $this->icon, $matches)) {
            $icon_code = $matches[1];
        }

        // Actualizamos los datos de la categoría
        $this->category->update([
            'name' => $this->name,
            'slug' => $this->slug,
            // Guardamos el código limpio
            'icon' => $icon_code,
            'features' => $this->features,
        ]);

        // Si se ha subido una nueva imagen
        if ($this->image) {
            // Guardamos la nueva imagen y MediaLibrary se encarga de reemplazar la antigua si existe
            $this->category->addMedia($this->image->getRealPath())
                ->toMediaCollection('categories');
        }

        // Sincronizamos las marcas asociadas
        $this->category->brands()->sync($this->brands);

        // ¡LA MAGIA! Emitimos un evento para que el menú se entere del cambio.
        $this->dispatch('category-updated');

        // Enviamos una notificación de éxito
        $this->dispatch('swal', [
            'icon' => 'success',
            'title' => '¡Categoría actualizada!',
            'text' => 'La categoría se ha actualizado correctamente.',
        ]);

        // Redirigimos al listado de categorías
        return redirect()->route('admin.categories.index');
    }

    public function render()
    {
        return view('livewire.admin.edit-category');
    }
}
