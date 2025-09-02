<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Color; // Tenemos que importar el modelo Color

class ColorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $colors = ['blue', 'Red' ,'blue', 'white', 'pink', 'black', 'green', 'yellow', 'purple', 'orange', 'brown', 'gray', 'cyan', 'magenta', 'lime', 'teal',];
                
        
        foreach ($colors as $color) {
            Color::create([
                'name' => $color,
            ]);
            // Color::create($color); // Otra forma de hacerlo
            // Color::insert($color); // Otra forma de hacerlo
            // DB::table('colors')->insert($color); // Otra forma de hacerlo
        }
    }
}
