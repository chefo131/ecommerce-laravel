<?php

namespace App\Livewire;

use App\Mail\OrderConfirmation;
use Illuminate\Support\Facades\Mail;
use App\Models\Order;
use App\Models\Size;
use App\Models\Color;
use App\Models\Product;
use Livewire\Component;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class PaymentOrder extends Component
{
    use AuthorizesRequests;
    public Order $order;
    public $items;

   public function mount(Order $order)
    {
        $this->order = $order;
        // Usamos la política para asegurar que el usuario solo puede ver sus propias órdenes.
        $this->authorize('view', $order);
        // ¡SOLUCIÓN! En lugar de leer del carrito global (que puede estar vacío),
        // leemos los productos del campo 'content' de la propia orden.
        $this->items = $this->order->content;
    }

    /**
     * Este método se llama cuando el pago se aprueba (tanto en PayPal como en Dummy).
     *
     * @param string|null $paymentId El ID de la transacción de PayPal (opcional).
     */
    public function payOrder(Order $order, $paymentId = null)
    {
        // Autorizamos que el usuario pueda pagar esta orden
        // Usamos la política para asegurar que el usuario es el dueño y la orden está pendiente.
        $this->authorize('payment', $order);

        // 1. Asociamos los productos del carrito a la orden y descontamos el stock.
        // ESTE ES EL PASO CRUCIAL QUE FALTABA.
        // Usamos $order->content para asegurar que procesamos los productos guardados en la orden.
        // ¡CORRECCIÓN! Nos aseguramos de que 'content' sea un array antes de iterar.
        // Si 'content' es null (porque la orden se creó con un carrito vacío o por un error),
        // lo tratamos como un array vacío para evitar el error "foreach() argument must be of type array|object, null given".
        $itemsToProcess = $order->content ?? [];

        foreach ($itemsToProcess as $item) {
            // ¡CORRECCIÓN! Como $this->order->content es un array (por el 'cast' en el modelo Order),
            // cada $item es un array asociativo, no un objeto. Debemos usar la sintaxis de array ($item['...'])
            // en lugar de la de objeto ($item->...). Este era el origen del error "Attempt to read property on array".

            // Asociamos el producto en la tabla pivote 'order_product'
            $order->products()->attach($item['id'], [
                'quantity' => $item['qty'],
                'price' => $item['price'],
            ]);

            // Descontamos el stock de la variante correcta de forma segura
            if (!empty($item['options']['size_id']) && !empty($item['options']['color_id'])) {
                // Producto con talla y color
                $size = Size::find($item['options']['size_id']);
                if ($size) {
                    $color = $size->colors()->find($item['options']['color_id']);
                    if ($color && $color->pivot) {
                        $color->pivot->decrement('quantity', $item['qty']);
                    }
                }
            } elseif (!empty($item['options']['color_id'])) {
                // Producto solo con color
                $product = Product::find($item['id']);
                if ($product) {
                    $color = $product->colors()->find($item['options']['color_id']);
                    if ($color && $color->pivot) {
                        $color->pivot->decrement('quantity', $item['qty']);
                    }
                }
            } else {
                // Producto simple
                $product = Product::find($item['id']);
                if ($product) {
                    $product->decrement('quantity', $item['qty']);
                }
            }
        }

        // 2. Actualizamos el estado de la orden a 'PAGADO'
        $order->status = Order::PAGADO;
        $order->transaction_id = $paymentId; // Guardamos el ID de la transacción si existe
        $order->save();

        // ¡Enviamos el email de confirmación al usuario!
        try {
            Mail::to($order->user)->send(new OrderConfirmation($order));
        } catch (\Exception $e) {
            // Si el envío de email falla, registramos el error en los logs pero no detenemos el proceso.
            // El usuario ya ha pagado y la orden está registrada. Lo importante es que la compra se complete.
            // El error "Invalid Credentials" de Mailtrap se verá aquí, pero la app seguirá funcionando.
            Log::error("Error al enviar email de confirmación para la orden {$order->id}: " . $e->getMessage());
        }

        // 3. Vaciamos el carrito de compras
        Cart::destroy();

        // 4. Redirigimos a la página de éxito
        return redirect()->route('orders.success', $order);
    }

    public function render()
    {
        // La lógica de la vista ya está en el blade, aquí solo lo renderizamos
        return view('livewire.payment-order');
    }
}