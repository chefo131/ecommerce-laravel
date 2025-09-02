<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Storage; // Importar Storage
use Illuminate\Support\Str;             // Importar Str

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Subcategory>
 */
class SubcategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // --- Lógica para copiar imágenes ---
        $sourceDir = 'seed_images/subcategories';
        $targetDir = 'subcategories'; // Directorio dentro de storage/app/public

        // Asegurarse de que el directorio de destino exista en el disco público
        Storage::disk('public')->makeDirectory($targetDir);

        // Obtener lista de archivos del directorio de origen (local)
        $files = Storage::disk('local')->files($sourceDir);

        $imagePath = null; // Valor por defecto si no hay imágenes

        if (!empty($files)) {
            // Elegir un archivo al azar
            $randomFile = $files[array_rand($files)]; // Ruta completa dentro del disco local
            $fileName = basename($randomFile);        // Solo el nombre del archivo

            // Ruta de destino en el disco público
            $targetPath = $targetDir . '/' . $fileName;

            // Copiar el archivo del disco local al disco público
            Storage::disk('public')->put(
                $targetPath,
                Storage::disk('local')->get($randomFile)
            );

            // Guardar la ruta relativa al disco público
            $imagePath = $targetPath;
        }
        // --- Fin lógica para copiar imágenes ---

        $name = $this->faker->unique()->sentence(2); // Nombre único

        return [
            // 'category_id' se asignará normalmente desde el Seeder
            'name' => $name,
            'slug' => Str::slug($name),
            'color' => false, // O usa $this->faker->boolean() si aplica
            'size' => false,  // O usa $this->faker->boolean() si aplica
            'image' => $imagePath, // Usar la ruta de la imagen copiada
        ];
    }
}






