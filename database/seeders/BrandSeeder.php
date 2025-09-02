<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $brands = [
            'Apple', 'Samsung', 'Xiaomi', 'Huawei', 'LG', 'Sony',
            'Nintendo', 'Microsoft', 'HP', 'Dell', 'Lenovo', 'Asus',
            'Zara', 'Adidas', 'Nike', 'Puma', 'Rolex', 'Casio',
        ];

        foreach ($brands as $brandName) {
            // Usamos updateOrCreate para evitar duplicados al re-ejecutar el seeder.
            Brand::updateOrCreate(
                ['name' => $brandName], // Criterio de búsqueda
                ['slug' => Str::slug($brandName)] // Datos a crear o actualizar
            );
        }
    }
}
