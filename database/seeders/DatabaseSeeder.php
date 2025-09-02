<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage; // Necesitamos Storage para limpiar directorios

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run(): void
    {
        // 1. Limpiar directorios de imágenes para evitar duplicados en cada re-seed
        Storage::deleteDirectory('categories');
        Storage::deleteDirectory('products');

        // 2. Crear los directorios de nuevo
        Storage::makeDirectory('categories');
        Storage::makeDirectory('products');

        // 3. Llamar a los seeders en un orden lógico de dependencias
        $this->call(UserSeeder::class);
        $this->call(OrderStatusSeeder::class);
        $this->call(BrandSeeder::class);
        $this->call(CategorySeeder::class);
        $this->call(SubcategorySeeder::class);
        $this->call(ProductSeeder::class); // Crea los productos
        $this->call(ColorSeeder::class);
        $this->call(SizeSeeder::class);
        $this->call(ColorProductSeeder::class); // Asigna colores a productos
        $this->call(ColorSizeSeeder::class);    // Asigna colores a tallas
        $this->call(DepartmentSeeder::class); // ¡Este seeder ya crea ciudades y distritos!
        $this->call(OrderSeeder::class);
    }
}



