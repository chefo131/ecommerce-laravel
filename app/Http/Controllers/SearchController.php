<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function __invoke(Request $request)
    {
        $name = $request->name;

        $products = Product::where('name', 'like', "%" . $name . "%")

            ->with([ // Carga eficiente de relaciones
                'images' => fn($query) => $query->select('id', 'imageable_id', 'imageable_type', 'path'), // Asume que 'images' es una relación
                'subcategory' => fn($query) => $query->select('id', 'category_id', 'name'), // Carga subcategoría
                'subcategory.category' => fn($query) => $query->select('id', 'name') // Carga categoría de la subcategoría
            ])
            ->where('status', 2)
            ->paginate(8);

        return view('search', compact('products'));
    }
}
