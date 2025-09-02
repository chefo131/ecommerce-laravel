<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;
 
class DummyPaymentController extends Controller
{
    /**
     * Simula la captura de un pago exitoso.
     *
     * Este método actúa como un puente. Recibe la orden, instancia el
     * PaymentController real y llama al método que procesa una orden
     * exitosa, devolviendo una respuesta JSON que el script del frontend espera.
     *
     * @param Order $order
     * @return JsonResponse
     */
    public function capture(Order $order): JsonResponse
    {
        // Instanciamos el controlador de pago real para reutilizar su lógica.
        $paymentController = new PaymentController(); // Esto ahora funciona porque el método es public

        // Llamamos al método que hace todo el trabajo: cambiar estado, descontar stock, etc.
        $paymentController->processSuccessfulOrder($order, 'dummy_tx_' . Str::random(17)); // Corrección aquí

        // Devolvemos la respuesta de éxito que el script de payment.blade.php está esperando.
        return response()->json(['success' => true]);
    }
}