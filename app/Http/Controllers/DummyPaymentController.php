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
        // Instanciamos el componente de Livewire para usar su lógica de pago.
        // Esto centraliza la lógica de finalización del pedido.
        $paymentOrderComponent = new PaymentOrder();
        $paymentOrderComponent->order = $order;

        // Llamamos al método que finaliza la orden, pasándole un ID de transacción ficticio.
        return $paymentOrderComponent->payOrder('dummy_payment_id_' . uniqid());
    }
}