<?php

namespace Database\Seeders;

use App\Models\Subcategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class SubcategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $subcategories = [
            // Celulares y tablets (Category ID: 1)
            [
                'category_id' => 1,
                'name' => 'Celulares y smartphones',
                'slug' => Str::slug('Celulares y smartphones'),
                'color' => true,
            ],
            [
                'category_id' => 1,
                'name' => 'Accesorios para smartphones',
                'slug' => Str::slug('Accesorios para smartphones'), // Corregido doble espacio
                'color' => true,
            ],
            [
                'category_id' => 1,
                'name' => 'Smartwatches',
                'slug' => Str::slug('Smartwatches'),
            ],

            // TV, audio y video (Category ID: 2)
            [
                'category_id' => 2,
                'name' => 'TV y video',
                'slug' => Str::slug('TV y video'),
            ],
            [
                'category_id' => 2,
                'name' => 'Audífonos',
                'slug' => Str::slug('Audífonos'),
            ],
            [
                'category_id' => 2,
                'name' => 'Audio para el hogar',
                'slug' => Str::slug('Audio para el hogar'),
            ],

            // Consola y videojuegos (Category ID: 3)
            [
                'category_id' => 3,
                'name' => 'Xbox',
                'slug' => Str::slug('Xbox'),
            ],
            [
                'category_id' => 3,
                'name' => 'PlayStation',
                'slug' => Str::slug('PlayStation'),
            ],
            [
                'category_id' => 3,
                'name' => 'Videojuegos para PC',
                'slug' => Str::slug('Videojuegos para PC'),
            ],
            [
                'category_id' => 3,
                'name' => 'Nintendo',
                'slug' => Str::slug('Nintendo'),
            ],

            // Computación (Category ID: 4)
            [
                'category_id' => 4,
                'name' => 'Portátiles',
                'slug' => Str::slug('Portátiles'),
            ],
            [
                'category_id' => 4,
                'name' => 'PC de escritorio',
                'slug' => Str::slug('PC de escritorio'),
            ],
            [
                'category_id' => 4,
                'name' => 'Almacenamiento',
                'slug' => Str::slug('Almacenamiento'),
            ],
            [
                'category_id' => 4,
                'name' => 'Accesorios de PC',
                'slug' => Str::slug('Accesorios de PC'),
            ],

            // Moda (Category ID: 5)
            [
                'category_id' => 5,
                'name' => 'Mujer',
                'slug' => Str::slug('Mujer'),
                'color' => true,
                'size' => true,
            ],
            [
                'category_id' => 5,
                'name' => 'Hombres',
                'slug' => Str::slug('Hombres'),
                'color' => true,
                'size' => true,
            ],
            [
                'category_id' => 5,
                'name' => 'Lentes',
                'slug' => Str::slug('Lentes'),
            ],
            [
                'category_id' => 5,
                'name' => 'Relojes',
                'slug' => Str::slug('Relojes'),
                'color' => true,
            ],
        ];

        // Obtener la lista de imágenes de semilla para subcategorías
        $sourceDir = 'seed_images/subcategories';
        $imageFiles = \Illuminate\Support\Facades\Storage::disk('local')->files($sourceDir);

        foreach ($subcategories as $data) {
            // Usamos updateOrCreate para evitar duplicados si el seeder se ejecuta varias veces.
            $subcategory = Subcategory::updateOrCreate(
                ['slug' => $data['slug']], // Criterio de búsqueda
                $data // Datos a crear o actualizar
            );

            // Si la subcategoría no tiene imagen y tenemos imágenes disponibles, le asignamos una.
            if (!$subcategory->image && !empty($imageFiles)) {
                $randomImage = $imageFiles[array_rand($imageFiles)];
                $subcategory->image = $randomImage;
                $subcategory->save();
            }
        }
    }
}
