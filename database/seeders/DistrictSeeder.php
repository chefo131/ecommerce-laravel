<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\District;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DistrictSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtenemos las primeras 4 ciudades para añadirles distritos
        $cities = City::take(4)->get();

        foreach ($cities as $city) {
            District::factory()->count(5)->create([
                'city_id' => $city->id
            ]);
        }
    }
}
