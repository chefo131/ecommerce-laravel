<?php

namespace App\Livewire;

use App\Models\Category;
use Livewire\Component;
use Livewire\Attributes\On;

class Navigation extends Component
{
    public $categories;

    // El método mount se ejecuta una vez, al cargar el componente.
    public function mount()
    {
        $this->loadCategories();
    }

    // Usamos el atributo #[On] para escuchar eventos.
    // Cuando se dispare 'category-created' o 'category-updated', se llamará a este método.
    #[On('category-created')]
    #[On('category-updated')]
    public function loadCategories()
    {
        $this->categories = Category::all();
    }

    public function render()
    {
        return view('livewire.navigation');
    }
}