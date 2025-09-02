<?php

namespace App\Livewire\Admin;

use App\Models\Order;
use Livewire\Component;
use Livewire\WithPagination;

class ShowOrders extends Component
{
    use WithPagination;

    public function render()
    {
        // Eager loading 'user' y 'orderStatus' para prevenir problemas de N+1.
        // Esto hace que la página cargue mucho más rápido.
        $orders = Order::with(['user', 'orderStatus'])
            ->orderBy('id', 'desc')
            ->paginate(10);

        return view('livewire.admin.show-orders', [
            'orders' => $orders,
        ])->layout('components.layouts.app.admin');
    }
}

