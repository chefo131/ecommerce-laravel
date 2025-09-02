<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Storage; // Importar Storage
use Illuminate\Support\Str;             // Importar Str

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = $this->faker->unique()->words(2, true); // Nombre único para evitar colisiones

        return [
            'name' => ucfirst($name),
            'slug' => Str::slug($name),
            'icon' => '<i class="fas fa-tag"></i>', // Icono de ejemplo
            // El campo 'features' se sobrescribirá en el seeder, pero es bueno tener un default
            'features' => '{"color": false, "size": false}',
        ];
    }
}
