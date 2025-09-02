<?php

namespace App\Livewire;

use Livewire\Component;
// Import Collection for type-hinting and initializing $products
use Illuminate\Support\Collection;


class CategoryProducts extends Component
{
     public $category; // This public property will be filled by Livewire
                      // when you pass a category to the component, e.g.,
                      // <livewire:category-products :category="$someCategory" />

    public Collection $products; // To store the products of the category

    /**
     * The mount method is called when the component is initialized.
     * We'll use it to load the published products for the given category.
     */
   public function mount()
{
    $this->products = collect();

    if ($this->category) {
        //dd($this->category->name); // Para verificar que la categoría se carga

        $query = $this->category->products()->where('status', 2);

        //dd($query->count()); // Para ver cuántos productos cumplen el 'status' ANTES del take()

        $this->products = $query->take(15)->get();

        //dd($this->products->count()); // Para ver cuántos productos tienes DESPUÉS del take()
        //dd($this->products); // Para ver la colección completa de productos cargados
    } else {
        //dd('La categoría es null');
    }
}

    
    public function render()
    {
         // Pass the $products collection to your Blade view.
        // The $category public property is also automatically available in the view.
        return view('livewire.category-products', [
            'products' => $this->products,
        ]);
    }
}
