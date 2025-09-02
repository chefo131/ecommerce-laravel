<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Builder; // Debemos importar el Builder
use App\Models\Product; // Debemos importar el modelo Product
use App\Models\Subcategory; // Debemos importar el modelo Subcategory

class ColorProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = Product::whereHas('subcategory', function(Builder $query){
            $query->where('color', true)
                ->where('size', false);
        })->get();

        foreach ($products as $product) {
            $product->colors()->attach([
                1 => [
                    'quantity' => 10,
                ], 
                2 => [
                    'quantity' => 10,
                ], 
                3 => [
                    'quantity' => 10,
                ], 
                4 => [
                    'quantity' => 10,
                ],
                5 => [
                    'quantity' => 10,
                ], 
                6 => [
                    'quantity' => 10,
                ], 
                7 => [
                    'quantity' => 10,
                ], 
                8 => [
                    'quantity' => 10,
                ], 
                9 => [
                    'quantity' => 10,
                ],
                10 => [
                    'quantity' => 10,
                ],
                11 => [
                    'quantity' => 10,
                ],
                12 => [
                    'quantity' => 10,
                ],
                13 => [
                    'quantity' => 10,
                ],
                14 => [
                    'quantity' => 10,
                ],
                15 => [
                    'quantity' => 10,
                ],
                16 => [
                    'quantity' => 10,
                ], 
              
            ]); 
        }
            
    }
}
