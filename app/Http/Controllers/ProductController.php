<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    public function show(Product $product)
    {
        // ¡Añade esta línea para depurar!
        //dd($product->load('sizes', 'colors')); 

        return view('products.show', compact('product'));
    }
}
