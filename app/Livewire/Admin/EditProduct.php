<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\Attributes\Computed;
use Illuminate\Validation\Rule;
use App\Models\Product;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\Brand;
use Illuminate\Support\Str;
use Livewire\Attributes\On;


class EditProduct extends Component
{
    public Product $product;

    // Propiedades para los selects
    public $category_id = "";
    public $subcategory_id = "";
    public $brand_id = "";

    // Propiedades del producto
    public $name = '';
    public $slug = '';
    public $description = '';
    public $price = '';
    public $quantity = '';

    // Propiedades para cargar datos
    public $categories;


    public function mount(Product $product)
    {
        $this->product = $product;

        // Cargar todas las categorías para el select
        $this->categories = Category::all();

        // Inicializar las propiedades del formulario con los datos del producto
        $this->name = $product->name;
        $this->slug = $product->slug;
        $this->description = $product->description;
        $this->price = $product->price;
        $this->quantity = $product->quantity;
        $this->brand_id = $product->brand_id;
        $this->subcategory_id = $product->subcategory_id;
        $this->category_id = $product->subcategory->category_id;
    }

    // Reglas de validación dinámicas
    public function rules()
    {
        return [
            'category_id' => 'required|exists:categories,id',
            'subcategory_id' => 'required|exists:subcategories,id',
            'brand_id' => 'required|exists:brands,id',
            'name' => 'required|string|min:3',
            'slug' => [
                'required',
                'string',
                // La regla 'unique' debe ignorar el producto actual para no dar error
                Rule::unique('products')->ignore($this->product->id),
            ],
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            // ¡LA CLAVE! La cantidad solo es requerida si el producto no tiene variantes.
            'quantity' => ($this->product->subcategory->category->features['color'] || $this->product->subcategory->category->features['size']) ? 'nullable' : 'required|integer|min:1',
        ];
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
        return Brand::whereHas('categories', function ($query) {
            $query->where('category_id', $this->category_id);
        })->get();
    }

    // Hook para cuando se actualiza la categoría
    public function updatedCategoryId($value)
    {
        // Al cambiar de categoría, reseteamos la subcategoría y la marca
        $this->reset(['subcategory_id', 'brand_id']);
    }
 
    // Hook para generar el slug automáticamente
    public function updatedName($value)
    {
        $this->slug = Str::slug($value);
    }
 
    public function save()
    {
        $this->validate();

        // El campo 'quantity' se inicializa a null por si no se usa
        $quantityValue = ($this->product->subcategory->category->features['color'] || $this->product->subcategory->category->features['size']) ? null : $this->quantity;

        // Actualizamos el producto con los datos del formulario
        $this->product->update([
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'price' => $this->price,
            'subcategory_id' => $this->subcategory_id,
            'brand_id' => $this->brand_id,
            'quantity' => $quantityValue,
        ]);

        // Enviamos un mensaje flash a la sesión para notificar al usuario
        session()->flash('message', 'Producto actualizado con éxito.');
    }

    // Este método "escucha" el evento 'productUpdated' emitido por el componente hijo
    #[On('productUpdated')]
    public function refreshProduct()
    {
        // Simplemente refrescamos la instancia del producto para obtener los últimos datos
        $this->product->refresh();
    }

    public function render()
    {
        return view('livewire.admin.edit-product')->layout('components.layouts.app.admin');
    }
}
