<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Livewire\PaymentOrder;
use Illuminate\Http\Request;

class DummyPaymentController extends Controller
{
    /**
     * Simula la captura de un pago y procesa la orden.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\RedirectResponse
     */
    public function capture(Order $order)
    {
        // Usamos el helper app() para obtener una instancia de PaymentOrder
        // y llamamos a su método payOrder, pasando la orden y un ID de transacción ficticio.
        // Esto centraliza la lógica de finalización del pedido.
        $paymentOrderComponent = app(PaymentOrder::class);
        return $paymentOrderComponent->payOrder($order, 'dummy_payment_id_' . uniqid());
    }
}