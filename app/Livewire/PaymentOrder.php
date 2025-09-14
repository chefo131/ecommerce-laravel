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
    public function payOrder(Order $order = null, $paymentId = null)
    {
        // Si no se pasa una orden, usamos la que está en la propiedad del componente.
        // Esto mantiene la compatibilidad con el botón "dummy".
        $orderToProcess = $order ?? $this->order;

        // Autorizamos que el usuario pueda pagar esta orden
        $this->authorize('payment', $orderToProcess);

        // 1. Asociamos los productos del carrito a la orden y descontamos el stock.
        // ESTE ES EL PASO CRUCIAL QUE FALTABA.
        // Usamos $orderToProcess->content para asegurar que procesamos los productos guardados en la orden.
        foreach ($orderToProcess->content as $item) {
            // ¡CORRECCIÓN! Como $this->order->content es un array (por el 'cast' en el modelo Order),
            // cada $item es un array asociativo, no un objeto. Debemos usar la sintaxis de array ($item['...'])
            // en lugar de la de objeto ($item->...). Este era el origen del error "Attempt to read property on array".

            // Asociamos el producto en la tabla pivote 'order_product'
            $orderToProcess->products()->attach($item['id'], [
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
        $orderToProcess->status = Order::PAGADO;
        $orderToProcess->transaction_id = $paymentId; // Guardamos el ID de la transacción si existe
        $orderToProcess->save();

        // ¡Enviamos el email de confirmación al usuario!
        Mail::to($orderToProcess->user)->send(new OrderConfirmation($orderToProcess));

        // 3. Vaciamos el carrito de compras
        Cart::destroy();

        // 4. Redirigimos a la página de éxito
        return redirect()->route('orders.success', $orderToProcess);
    }

    public function render()
    {
        // La lógica de la vista ya está en el blade, aquí solo lo renderizamos
        return view('livewire.payment-order');
    }
}