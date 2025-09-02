<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Category; // Asegúrate de importar Category
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Celulares y tablets',
                'features' => [
                    'color' => true,
                    'size' => false,
                ],
                'icon' => 'fa-solid fa-mobile-screen-button',
            ],
            [
                'name' => 'TV, audio y video',
                'features' => [
                    'color' => false,
                    'size' => false,
                ],
                'icon' => 'fa-solid fa-tv',
            ],
            [
                'name' => 'Consola y videojuegos',
                'features' => [
                    'color' => true,
                    'size' => false,
                ],
                'icon' => 'fa-solid fa-gamepad',
            ],
            [
                'name' => 'Computación',
                'features' => [
                    'color' => true,
                    'size' => false,
                ],
                // Ahora guardamos solo las clases, lo que es más limpio y seguro.
                // El icono 'fa-laptop' funcionó, así que lo mantenemos.
                'icon' => 'fa-solid fa-laptop',
            ],
            [
                'name' => 'Moda',
                'features' => [
                    'color' => true,
                    'size' => true,
                ],
                'icon' => 'fa-solid fa-shirt',
            ],
        ];

        // Obtener todas las marcas para asociarlas
        $brands = Brand::all();

        // Obtener la lista de imágenes de semilla
        $sourceDir = 'seed_images/categories';
        $imageFiles = Storage::disk('local')->files($sourceDir);

        foreach ($categories as $categoryData) {
            // 1. Creamos o actualizamos la categoría con sus datos básicos.
            $category = Category::updateOrCreate(
                ['name' => $categoryData['name']], // Criterio de búsqueda
                [
                    'slug' => Str::slug($categoryData['name']),
                    'icon' => $categoryData['icon'],
                    'features' => $categoryData['features'],
                ]
            );

            // 2. Si la categoría no tiene una imagen, le asignamos una.
            if (!$category->getFirstMedia('categories') && !empty($imageFiles)) {
                // Elegimos una imagen al azar del directorio de semillas
                $randomImage = $imageFiles[array_rand($imageFiles)];
                $category->addMedia(Storage::disk('local')->path($randomImage))
                    ->preservingOriginal() // Importante para que no se borre el original
                    ->toMediaCollection('categories');
            }

            // 3. Usamos sync() en lugar de attach() para las relaciones.
            // sync() se encarga de añadir y quitar relaciones para que coincidan con el array que le pasamos.
            // Esto evita errores de clave duplicada si el seeder se ejecuta varias veces.
            $category->brands()->sync($brands->random(4)->pluck('id'));
        }
    }
}
