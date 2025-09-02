<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CategoryController extends Controller
{
    //
    public function index(): View
    {
        // Por ahora, solo devolvemos la vista. Más adelante aquí obtendremos las categorías de la BD.
        return view('admin.categories.index');
    }
}
