<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Simplemente creamos los productos. El método `configure()` dentro de
        // ProductFactory se encargará automáticamente de añadir una imagen a cada
        // producto que se cree, eliminando la necesidad de la lógica anterior.
        Product::factory(250)->create();
    }
}
