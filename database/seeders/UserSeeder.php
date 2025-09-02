<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Usamos firstOrCreate para que no intente crear los roles si ya existen.
        $roleAdmin = Role::firstOrCreate(['name' => 'admin']);
        Role::firstOrCreate(['name' => 'manager']);
        $roleComprador = Role::firstOrCreate(['name' => 'comprador']);
 
        // Usamos una variable para capturar el usuario creado o encontrado.
        $user = User::firstOrCreate(
            ['email' => 'josem@example.com'], // Atributos para buscar
            [
                'name' => 'Jose',
                'password' => Hash::make('12345678'),
            ] // Atributos a usar si no se encuentra el registro
        );
 
        // ¡El paso clave! Asignamos el rol de administrador al usuario.
        $user->assignRole($roleAdmin);

        // --- Creación de usuarios de prueba ---
        // Creamos 50 usuarios de prueba y les asignamos el rol de 'comprador'.
        // El método 'each' nos permite ejecutar una acción por cada usuario creado.
        User::factory(50)->create()->each(function ($user) use ($roleComprador) {
            $user->assignRole($roleComprador);
        });
    }
}
