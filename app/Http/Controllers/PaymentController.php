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
        $provider->setApiCredentials(config('paypal'));
        $token = $provider->getAccessToken();
        $provider->setAccessToken($token);

        try {
            $response = $provider->createOrder([
                "intent" => "CAPTURE",
                "application_context" => [
                    "brand_name" => config('app.name', 'Laravel'),
                    "shipping_preference" => "NO_SHIPPING",
                    "cancel_url" => route('orders.show', $order),
                    "return_url" => route('payment.capture', $order),
                ],
                "purchase_units" => [
                    [
                        "reference_id" => $order->id,
                        "amount" => [
                            "currency_code" => "EUR",
                            "value" => number_format($order->total, 2, '.', '')

                        ]
                    ]
                ]
            ]);

            if (isset($response['id']) && $response['id'] != null) {
                return response()->json(['id' => $response['id']]);
            } else {
                Log::error('Srmklive PayPal create order failed.', ['response' => $response]);
                return response()->json(['error' => 'No se pudo crear la orden en PayPal.'], 500);
            }
        } catch (\Throwable $e) {
            Log::error('Srmklive PayPal Exception on createPayment.', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['error' => 'Ocurrió un error inesperado con PayPal.'], 500);
        }
    }

        public function capturePayment(Request $request, Order $order)
    {
        $provider = new PayPalClient;
        $provider->getAccessToken();
        $response = $provider->capturePaymentOrder($request->token);

        if (isset($response['status']) && $response['status'] == 'COMPLETED') {
            // El pago se ha completado en PayPal
            $transactionId = $response['purchase_units'][0]['payments']['captures'][0]['id'];
            $this->processSuccessfulOrder($order, $transactionId);

            return response()->json(['success' => true]);
        }

        // Si el pago falla o no se completa
        return response()->json(['success' => false, 'message' => 'El pago no pudo ser procesado por PayPal.'], 400);
    }

    /**
     * Procesa una orden después de un pago exitoso.
     * Este método centraliza la lógica post-pago.
     *
     * @param Order $order
     * @return void
     */
    public function processSuccessfulOrder(Order $order, string $transactionId)
    {
        // 1. Actualizar el estado de la orden
        $order->status = Order::PAGADO;
        $order->transaction_id = $transactionId; // Guardamos el ID de la transacción
        $order->save(); // Guardamos ambos cambios

        // 2. Descontar el stock de los productos
        // $order->content ya es un objeto/array gracias al "casting" en el modelo Order.
        // No necesitamos usar json_decode, que es lo que estaba causando el error.
        $items = $order->content;

        foreach ($items as $item) {
            // Buscamos el producto para acceder a sus relaciones
            $product = Product::find($item->id);

            // Lógica para descontar stock según el tipo de producto
            if ($product) {
                if (isset($item->options->size_id)) {
                    // Producto con talla y color
                    $size = $product->sizes()->where('id', $item->options->size_id)->first();
                    if ($size) {
                        $size->colors()->where('color_id', $item->options->color_id)->decrement('quantity', $item->qty);
                    }
                } elseif (isset($item->options->color_id)) {
                    // Producto solo con color
                    $product->colors()->where('color_id', $item->options->color_id)->decrement('quantity', $item->qty);
                } else {
                    // Producto sin variantes
                    $product->decrement('quantity', $item->qty);
                }
            }
        }

        // 3. Vaciar el carrito de compras
        Cart::destroy();
    }
}
