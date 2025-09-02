<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index()
    {
        // Preparamos la consulta base para las órdenes del usuario.
        $ordersQuery = Order::query()->where('user_id', Auth::id());

        // Si en la URL viene un parámetro 'status', filtramos por ese estado.
        if (request('status')) {
            $ordersQuery->where('status', request('status'));
        }

        // Obtenemos las órdenes filtradas y paginadas.
        $orders = $ordersQuery->latest('id')->paginate(10);

        // Calculamos los contadores para las tarjetas de estado, SIEMPRE sobre TODAS las órdenes del usuario.
        $baseCountQuery = Order::where('user_id', Auth::id());

        $pendiente = (clone $baseCountQuery)->where('status', 1)->count();
        $pagado = (clone $baseCountQuery)->where('status', 2)->count();
        $enviado = (clone $baseCountQuery)->where('status', 3)->count();
        $entregado = (clone $baseCountQuery)->where('status', 4)->count();
        $anulado = (clone $baseCountQuery)->where('status', 5)->count();

        // Pasamos todas las variables a la vista.
        return view('orders.index', compact('orders', 'pendiente', 'pagado', 'enviado', 'entregado', 'anulado'));
    }

    public function show(Order $order)
    {
        // Usamos la policy para verificar que el usuario pueda 'ver' esta orden
        $this->authorize('view', $order);
        $items = $order->content; // Laravel ya lo convierte a array por nosotros
        $envio = $order->envio;   // Laravel ya lo convierte a array por nosotros
        return view('orders.show', compact('order', 'items', 'envio'));
    }

    public function payment(Order $order)
    {
        // Usamos la policy para verificar que el usuario pueda 'pagar' esta orden
        $this->authorize('payment', $order);

        $items = $order->content; // Laravel ya lo convierte a array por nosotros

        return view('orders.payment', compact('order', 'items'));
    }

    public function success(Order $order)
    {
        // Usamos la policy para verificar que el usuario pueda 'ver' esta orden
        $this->authorize('view', $order);
        return view('orders.success', compact('order'));
    }
}