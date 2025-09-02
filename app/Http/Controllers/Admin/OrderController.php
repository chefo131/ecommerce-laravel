<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Muestra la lista de órdenes con filtros por estado.
     */
    public function index()
    {
        // Preparamos la consulta base, cargando la relación 'user' para evitar N+1 queries.
        $query = Order::query()->with('user')->latest('id');

        // Si en la URL viene un parámetro 'status', filtramos por ese estado.
        if (request('status')) {
            $query->where('status', request('status'));
        }

        // Obtenemos las órdenes paginadas.
        $orders = $query->paginate(10);

        // Calculamos los contadores para las tarjetas de estado.
        $pendiente = Order::where('status', 1)->count();
        $pagado = Order::where('status', 2)->count();
        $enviado = Order::where('status', 3)->count();
        $entregado = Order::where('status', 4)->count();
        $anulado = Order::where('status', 5)->count();
        $all = $pendiente + $pagado + $enviado + $entregado + $anulado;

        // Pasamos todas las variables a la vista.
        return view('admin.orders.index', compact('orders', 'pendiente', 'pagado', 'enviado', 'entregado', 'anulado', 'all'));
    }

    /**
     * Muestra los detalles de una orden específica.
     * Usamos Route-Model Binding para que Laravel nos entregue el objeto Order directamente.
     */
    public function show(Order $order)
    {
        return view('admin.orders.show', compact('order'));
    }

    /**
     * Actualiza el estado de una orden.
     */
    public function update(Request $request, Order $order)
    {
        // Validamos que el estado sea un número entre 1 y 5.
        $request->validate([
            'status' => 'required|in:1,2,3,4,5'
        ]);

        // Actualizamos el estado de la orden.
        $order->status = $request->status;
        $order->save();

        // Redirigimos de vuelta a la página de detalle con un mensaje de éxito.
        return redirect()->route('admin.orders.show', $order)->with('success', 'El estado de la orden se ha actualizado correctamente.');
    }
}
