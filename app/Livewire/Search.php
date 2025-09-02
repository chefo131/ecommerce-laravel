<?php

namespace App\Livewire;

use App\Models\Product;
use Livewire\Component;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Facades\Log;

class Search extends Component

{
    public string $searchTerm = ''; // Cambiado de 'search' a 'searchTerm' para mayor claridad
    public EloquentCollection $products; // Para almacenar los productos encontrados
    public bool $showResults = false; // Controla la visibilidad del desplegable de resultados

    public function mount(): void
    {
        $this->products = new EloquentCollection(); // Inicializar la colección de productos
    }

    // Este método se ejecuta automáticamente cuando $searchTerm cambia (debido a wire:model.live)
    public function updatedSearchTerm(string $value): void
    {
        // Mover la lógica de búsqueda aquí
        $trimmedValue = trim($value);


        if (!empty($trimmedValue)) {
            try {
                $this->products = Product::where('name', 'like', "%" . $trimmedValue . "%")

                    ->with([ // Carga eficiente de relaciones. 'media' es la nueva relación.
                        'media',
                        'subcategory' => fn($query) => $query->select('id', 'category_id', 'name'), // Carga subcategoría
                        'subcategory.category' => fn($query) => $query->select('id', 'name') // Carga categoría de la subcategoría
                    ])
                    ->where('status', 2)
                    ->take(10) // Limitar el número de resultados para mejorar el rendimiento
                    ->get();
            } catch (\Throwable $e) {
                Log::error('Error en Search->updatedSearchTerm al obtener productos: ' . $e->getMessage() . ' en ' . $e->getFile() . ':' . $e->getLine());
                $this->products = new EloquentCollection(); // Asegurar que products esté vacío en caso de error
            }
            $this->showResults = true; // Mostrar resultados si hay término y/o productos

        } else {
            $this->products = new EloquentCollection(); // Limpiar productos si el término de búsqueda está vacío
            $this->showResults = false; // Ocultar resultados
        }
    }

    // Método para mostrar los resultados (ej. al hacer foco en el input)
    public function displayResults(): void
    {
        if (trim($this->searchTerm) !== '') {
            // Si ya hay un término, updatedSearchTerm ya debería haber cargado los productos.
            // Solo necesitamos asegurarnos de que el desplegable sea visible.
            $this->showResults = true;
        }
    }

    /**
     * Oculta el desplegable de resultados de búsqueda.
     * Este método es típicamente invocado por eventos como `wire:click.outside`.
     */
    public function hideResults(): void
    {
        $this->showResults = false;
    }

    public function render()
    {
        return view('livewire.search', [

            'products' => $this->products, // Pasamos la propiedad $products a la vista
        ]);
    }
}
