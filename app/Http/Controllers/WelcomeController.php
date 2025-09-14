<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class WelcomeController extends Controller
{
    public $banner;
    public function __invoke(){

        $categories = Category::all();
        // Ya no necesitamos pasar 'hasPendingOrders' o 'pendiente'. El View Composer se encarga.
        return view('welcome', compact('categories'));
    
    }

        
}
