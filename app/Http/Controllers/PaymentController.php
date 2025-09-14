<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Srmklive\PayPal\Services\PayPal as PayPalClient; // ¡Importamos el nuevo cliente!


class PaymentController extends Controller
{
    public function createPayment(Request $request, Order $order)
    {
        $provider = new PayPalClient;
        $provider->getAccessToken(); // Esto ya configura las credenciales y el token internamente

        try {
            $response = $provider->createOrder([
                "intent" => "CAPTURE",
                "application_context" => [
                    "brand_name" => config('app.name', 'Laravel'),
                    "shipping_preference" => "NO_SHIPPING", // No necesitamos la dirección de envío de PayPal
                    // "cancel_url" => route('orders.show', $order), // ¡ELIMINADO! Esto lo gestiona el JS de la vista.
                ],
                "purchase_units" => [
                    [
                        "reference_id" => $order->id,
                        "amount" => [
                            "currency_code" => "EUR",
                            // ¡LA SOLUCIÓN! Forzamos el formato a "1500.50" sin comas de miles.
                            "value" => number_format($order->total, 2, '.', '') 

                        ]
                    ]
                ]
            ]);

            if (isset($response['id']) && $response['id'] != null) {
                return response()->json(['id' => $response['id']]);
            } else {
                // Mejoramos el log para capturar toda la respuesta de PayPal en caso de error
                Log::error('PayPal API Error: No se pudo crear la orden.', [
                    'order_id' => $order->id,
                    'paypal_response' => $response
                ]);
                return response()->json(['error' => 'No se pudo crear la orden en PayPal.'], 500);
            }
        } catch (\Throwable $e) {
            // Log más detallado para excepciones
            Log::error('Excepción al crear orden en PayPal.', [
                'order_id' => $order->id,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['error' => 'Ocurrió un error inesperado al contactar con PayPal.'], 500);
        }
    }

        public function capturePayment(Request $request, Order $order)
    {
        $provider = new PayPalClient;
        $provider->getAccessToken();
        
        try {
            // Usamos el 'orderID' que nos envía el JavaScript.
            $response = $provider->capturePaymentOrder($request->orderID);

            if (isset($response['status']) && $response['status'] == 'COMPLETED') {
                // El pago se ha completado en PayPal
                $transactionId = $response['purchase_units'][0]['payments']['captures'][0]['id'];

                // Llamamos al método del componente Livewire para centralizar la lógica.
                app(\App\Livewire\PaymentOrder::class)->payOrder($order, $transactionId);

                return response()->json(['success' => true]);
            }
        } catch (\Throwable $e) {
            Log::error('Excepción al capturar pago en PayPal.', [
                'order_id' => $order->id,
                'paypal_order_id' => $request->orderID,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['success' => false, 'message' => 'Ocurrió un error inesperado al procesar el pago.'], 500);
        }

        // Si el pago falla o no se completa
        return response()->json(['success' => false, 'message' => 'El pago no pudo ser procesado por PayPal.'], 400);
    }
}
