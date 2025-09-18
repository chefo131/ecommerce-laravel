<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Srmklive\PayPal\Services\PayPal as PayPalClient;
use App\Livewire\PaymentOrder; // ¡Importamos el componente Livewire!

class PaymentController extends Controller
{
    /**
     * Crea una orden de pago en PayPal.
     * Este método es llamado por el SDK de JavaScript de PayPal cuando el usuario hace clic en el botón de pago.
     */
    public function createPayment(Request $request, Order $order)
    {
        // 1. Instanciamos el cliente de PayPal
        $provider = new PayPalClient;
        $provider->getAccessToken();

        // 2. Creamos la orden en PayPal con los detalles de nuestra orden
        $response = $provider->createOrder([
            "intent" => "CAPTURE",
            "purchase_units" => [
                [
                    "amount" => [
                        "currency_code" => "EUR", // Asegúrate que esta es tu moneda
                        "value" => $order->total,
                    ],
                    'description' => 'Compra en ' . config('app.name'),
                ],
            ],
        ]);

        // 3. Verificamos la respuesta de PayPal
        if (isset($response['id']) && $response['id'] != null) {
            // Si todo fue bien, devolvemos la respuesta JSON que el script de PayPal espera.
            return response()->json($response);
        }

        // Si hubo un error, lo registramos y devolvemos un error 500.
        Log::error('Error al crear la orden en PayPal.', ['response' => $response ?? 'Sin respuesta']);
        return response()->json(['error' => 'No se pudo crear la orden de pago.'], 500);
    }

    /**
     * Captura el pago en PayPal y finaliza el proceso de la orden.
     */
    public function capturePayment(Request $request, Order $order)
    {
        // 1. Preparamos el cliente de PayPal
        $provider = new PayPalClient;
        $provider->getAccessToken();

        // 2. Capturamos el pago usando el ID que nos envía el JavaScript de PayPal.
        $response = $provider->capturePaymentOrder($request->orderID);

        // 3. Si la captura es exitosa (estado 'COMPLETED')...
        // Si la captura es exitosa (estado 'COMPLETED')...
        if (isset($response['status']) && $response['status'] == 'COMPLETED') {

            // ...usamos el helper app() para obtener una instancia de nuestro componente Livewire...
            $paymentOrderComponent = app(PaymentOrder::class);

            // ...y llamamos al método payOrder(), pasándole la orden y el ID de la transacción de PayPal.
            // Este método se encargará de enviar el email, vaciar el carrito y todo lo demás.
            // El redirect que devuelve este método será ignorado, ya que el JS se encarga de la redirección final.
            $paymentOrderComponent->payOrder($order, $response['id']);
        }

        // 4. Devolvemos la señal de éxito al JavaScript para que pueda redirigir.
        return response()->json($response);
    }
}
