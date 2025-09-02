<?php

namespace App\Livewire\Admin;

use App\Models\Brand;
use Livewire\Component;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\Product;
use Illuminate\Support\Str;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Validate;

class CreateProduct extends Component
{

    // Propiedades para cargar datos
    public $categories;

    // Propiedad para almacenar las características de la categoría seleccionada
    public $productFeatures = ['color' => false, 'size' => false];

    // Propiedades del formulario
    public $category_id = "";
    public $subcategory_id = "";
    public $brand_id = "";
    public $name = '';
    public $slug = '';
    public $description = '';
    public $price = '';
    public $quantity = '';
    public $status = Product::BORRADOR; // Por defecto es borrador

    // Reglas de validación dinámicas
    protected function rules()
    {
        return [
            'category_id' => 'required|exists:categories,id',
            'subcategory_id' => 'required|exists:subcategories,id',
            'brand_id' => 'required|exists:brands,id',
            'name' => 'required|string|min:3',
            'slug' => 'required|string|unique:products,slug',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'status' => 'required|in:1,2',
            // ¡LA CLAVE! La cantidad solo es requerida si el producto no tiene variantes.
            'quantity' => ($this->productFeatures['color'] || $this->productFeatures['size']) ? 'nullable' : 'required|integer|min:1',
        ];
    }

    public function mount()
    {
        $this->categories = Category::all();
    }

    // Propiedades computadas para selects dependientes
    #[Computed]
    public function subcategories()
    {
        return Subcategory::where('category_id', $this->category_id)->get();
    }

    #[Computed]
    public function brands()
    {
        // Asumimos que una categoría tiene marcas asociadas
        // Esto requiere definir la relación en el modelo Category
        return Brand::whereHas('categories', function ($query) {
            $query->where('category_id', $this->category_id);
        })->get();
    }

    // Hook para cuando se actualiza la categoría
    public function updatedCategoryId($value)
    {
        // Al cambiar de categoría, reseteamos la subcategoría y la marca
        $this->reset(['subcategory_id', 'brand_id']);

        // Buscamos la categoría seleccionada para obtener sus características
        $category = Category::find($value);
        if ($category) {
            // Actualizamos la propiedad con las características de la categoría
            $this->productFeatures = $category->features;
        }
    }

    // Hook para generar el slug automáticamente
    public function updatedName($value)
    {
        $this->slug = Str::slug($value);
    }

    public function render()
    {
        return view('livewire.admin.create-product')
            ->layout('components.layouts.app.admin');
    }

    // Método para guardar el producto
    public function save()
    {
        $this->validate();

        // El campo 'quantity' se inicializa a null por si no se usa
        $quantityValue = ($this->productFeatures['color'] || $this->productFeatures['size']) ? null : $this->quantity;

        $product = new Product();
        $product->name = $this->name;
        $product->slug = $this->slug;
        $product->description = $this->description;
        $product->price = $this->price;
        $product->subcategory_id = $this->subcategory_id;
        $product->brand_id = $this->brand_id;
        $product->quantity = $quantityValue;
        
        // Por defecto, el producto se crea como borrador (status = 1)
        $product->status = 1;

        $product->save();

        // Redirigir al usuario a la página de edición de este producto
        return redirect()->route('admin.products.edit', $product);
    }
}
