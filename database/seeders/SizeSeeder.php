<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Size;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Builder; // Debemos importar el Builder
use App\Models\Product; // Debemos importar el modelo Product

class SizeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sizes = ['Talla S', 'Talla M', 'Talla L', 'Talla XL'];
        foreach ($sizes as $sizeName) {
            // Usamos firstOrCreate para evitar duplicados si el seeder se ejecuta más de una vez.
            Size::firstOrCreate(['name' => $sizeName]);

            
        }
    }
}
