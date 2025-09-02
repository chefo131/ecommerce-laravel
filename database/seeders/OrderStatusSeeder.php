<?php

namespace Database\Seeders;

use App\Models\OrderStatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrderStatusSeeder extends Seeder
{
    public function run(): void
    {
        OrderStatus::create(['id' => 1, 'name' => 'Pendiente']);
        OrderStatus::create(['id' => 2, 'name' => 'Pagado']);
        OrderStatus::create(['id' => 3, 'name' => 'Enviado']);
        OrderStatus::create(['id' => 4, 'name' => 'Entregado']);
        OrderStatus::create(['id' => 5, 'name' => 'Anulado']);
    }
}