<?php // database/factories/ProductFactory.php (Ejemplo simplificado)

namespace Database\Factories;

use App\Models\Product;
use App\Models\Subcategory;
use App\Models\Brand;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage; // ¡Importante! Añadir esta línea

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        // --- OPTIMIZACIÓN DE RENDIMIENTO ---
        // Usamos variables estáticas para que los datos se carguen desde la BD solo una vez.
        // En las siguientes 249 llamadas a la factory, se reutilizarán los datos en memoria.
        static $subcategories;
        static $brands;

        // Si las variables aún no están cargadas, las llenamos.
        $subcategories = $subcategories ?? Subcategory::all();
        $brands = $brands ?? Brand::all();

        $subcategory = $subcategories->random();
        $brand = $brands->random();

        // ¡Cambio clave! Usamos unique() para asegurarnos de que no haya nombres de producto repetidos.
        $name = $this->faker->unique()->sentence(3);
        $slug = Str::slug($name) . '-' . uniqid();

        return [
            'name' => $name,
            'slug' => $slug,
            'description' => $this->faker->text(200),
            'price' => $this->faker->randomFloat(2, 10, 200),
            'subcategory_id' => $subcategory->id,
            'brand_id' => $brand->id,
            'quantity' => $this->faker->numberBetween(5, 50),
            'status' => Product::PUBLICADO,
        ];
    }

    /**
     * Configura el estado después de la creación.
     */
    public function configure(): static
    {
        return $this->afterCreating(function (Product $product) {
            // Directorio donde están tus imágenes de ejemplo (dentro de storage/app)
            $sourceDir = 'seed_images/products';

            // ¡CLAVE 1! Especificamos explícitamente el disco 'local' para evitar ambigüedades.
            $files = Storage::disk('local')->files($sourceDir);

            // --- INICIO: CHIVATO DE DEPURACIÓN (NUEVO) ---
            // Si no encuentra imágenes, lo registrará en el log para que lo sepamos.
            if (empty($files)) {
                \Illuminate\Support\Facades\Log::warning("ProductFactory: No se encontraron imágenes en 'storage/app/{$sourceDir}'. El producto con ID {$product->id} se creará sin imagen.");
                return; // Salimos para no continuar si no hay imágenes
            }
            // --- FIN: CHIVATO DE DEPURACIÓN ---

            // Si hay archivos, elige un número aleatorio de imágenes (entre 2 y 4) y añádelas.
            if (!empty($files)) {
                // Seleccionamos un número aleatorio de imágenes para cada producto.
                // Usamos min() para no intentar coger más imágenes de las que existen.
                $imageCount = min(count($files), rand(2, 4));
                // array_rand puede devolver un solo valor si el count es 1, así que lo forzamos a ser un array.
                $randomKeys = (array) array_rand($files, $imageCount);

                foreach ($randomKeys as $key) {
                    // ¡CLAVE 2! Usamos Storage::path() para obtener la ruta absoluta de forma más robusta.
                    $imagePath = Storage::disk('local')->path($files[$key]);

                    // Añade la imagen al producto usando Medialibrary
                    $product->addMedia($imagePath)
                        ->preservingOriginal() // Evita que el archivo original se mueva o borre
                        ->toMediaCollection('products');
                }
            }
        });
    }
}
