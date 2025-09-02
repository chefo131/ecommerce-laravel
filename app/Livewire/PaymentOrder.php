<?php

namespace App\Livewire;

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
        $this->items = Cart::content();
    }

    /**
     * Este método se llama cuando el pago se aprueba (tanto en PayPal como en Dummy).
     *
     * @param string|null $paymentId El ID de la transacción de PayPal (opcional).
     */
    public function payOrder($paymentId = null)
    {
        // Autorizamos que el usuario pueda pagar esta orden
        $this->authorize('payment', $this->order);

        // 1. Asociamos los productos del carrito a la orden y descontamos el stock.
        // ESTE ES EL PASO CRUCIAL QUE FALTABA.
        foreach ($this->items as $item) {
            // Asociamos el producto en la tabla pivote 'order_product'
            $this->order->products()->attach($item->id, [
                'quantity' => $item->qty,
                'price' => $item->price,
            ]);

            // Descontamos el stock de la variante correcta
            if ($item->options->size_id && $item->options->color_id) {
                // Producto con talla y color
                $size = Size::find($item->options->size_id);
                $size->colors()->find($item->options->color_id)->pivot->decrement('quantity', $item->qty);
            } elseif ($item->options->color_id) {
                // Producto solo con color
                Product::find($item->id)->colors()->find($item->options->color_id)->pivot->decrement('quantity', $item->qty);
            } else {
                // Producto simple
                Product::find($item->id)->decrement('quantity', $item->qty);
            }
        }

        // 2. Actualizamos el estado de la orden a 'PAGADO'
        $this->order->status = Order::PAGADO;
        $this->order->payment_id = $paymentId; // Guardamos el ID de la transacción si existe
        $this->order->save();

        // 3. Vaciamos el carrito de compras
        Cart::destroy();

        // 4. Redirigimos a la página de éxito
        return redirect()->route('orders.success', $this->order);
    }

    public function render()
    {
        // La lógica de la vista ya está en el blade, aquí solo lo renderizamos
        return view('livewire.payment-order');
    }
}