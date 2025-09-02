<?php

namespace App\Livewire;

use App\Models\Category; // ¡Importante! Añadimos la importación del modelo Category
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Product;
use Livewire\Attributes\Url;
use Illuminate\Database\Eloquent\Builder;


class CategoryFilter extends Component
{
    use WithPagination;
    
    // ¡AQUÍ ESTÁ LA MAGIA!
    // Al indicar que $category es de tipo Category, Livewire automáticamente
    // buscará en la base de datos la categoría que coincida con el slug de la URL.
    public Category $category;
    public $marca;
    public $view = 'grid';

    // Esta propiedad se sincronizará con el parámetro 'subcategory' de la URL.
    #[Url(as: 'subcategory', keep: true)]
    public $subcategoriaSeleccionada = '';

    public function limpiar(){
        $this->reset(['subcategoriaSeleccionada', 'marca']);
    }

    public function render()
    {
        //Relacion entre Categorías y Productos con un filtro simple
        /* $products = $this->category->products()
            ->where('status', 2)->paginate(20); */
         $productsQuery = Product::query()->whereHas('subcategory.category', function(Builder $query){
            $query->where('id', $this->category->id);
        });

        if ($this->subcategoriaSeleccionada) {
            $productsQuery = $productsQuery->whereHas('subcategory', function(Builder $query){
            $query->where('slug', $this->subcategoriaSeleccionada);
            });
        }

        if ($this->marca) {
            $productsQuery = $productsQuery->whereHas('brand', function(Builder $query){
            $query->where('name', $this->marca);
            });
        }

        $products = $productsQuery->paginate(20);

        return view('livewire.category-filter', compact('products'));
    }
}
        
