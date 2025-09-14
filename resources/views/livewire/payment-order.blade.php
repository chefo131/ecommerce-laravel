<div x-data="{ envio_type: @entangle('envio_type') }" class="container py-12">
    <div class="grid grid-cols-1 gap-8 md:grid-cols-2">

        {{-- Columna Izquierda: Detalles del Envío y Contacto --}}
        <div class="space-y-6">
            {{-- Detalles del Envío --}}
            <div class="rounded-lg bg-white p-6 shadow-lg dark:bg-gray-800">
                <h2 class="mb-4 text-lg font-semibold text-gray-800 dark:text-gray-200">Detalles del Envío</h2>
                @if ($order->envio_type == 1)
                    <div class="rounded-md bg-blue-50 p-4 dark:bg-blue-900/20">
                        <p class="text-sm text-blue-800 dark:text-blue-200">
                            <span class="font-bold">Recogida en tienda:</span> Has elegido recoger los productos en
                            nuestra tienda.
                        </p>
                        <p class="mt-1 text-xs text-blue-700 dark:text-blue-300">Dirección: Rué del Percebe N.º 13</p>
                    </div>
                @else
                    <div class="rounded-md bg-green-50 p-4 dark:bg-green-900/20">
                        <p class="text-sm text-green-800 dark:text-green-200">
                            <span class="font-bold">Envío a domicilio:</span> Los productos serán enviados a la
                            siguiente dirección:
                        </p>
                        <address class="mt-2 not-italic text-gray-700 dark:text-gray-300">
                            {{-- Laravel convierte automáticamente el JSON en un array --}}
                            {{ $order->envio['address'] }}<br>
                            {{ $order->envio['district'] }}, {{ $order->envio['city'] }}<br>
                            {{ $order->envio['department'] }}<br>
                            <span class="text-xs">Ref: {{ $order->envio['references'] }}</span>
                        </address>
                    </div>
                @endif
            </div>

            {{-- Detalles de Contacto --}}
            <div class="rounded-lg bg-white p-6 shadow-lg dark:bg-gray-800">
                <h2 class="mb-4 text-lg font-semibold text-gray-800 dark:text-gray-200">Datos de Contacto</h2>
                <p class="text-sm text-gray-700 dark:text-gray-300">
                    <span class="font-medium">Persona que recibe:</span> {{ $order->contact }}
                </p>
                <p class="text-sm text-gray-700 dark:text-gray-300">
                    <span class="font-medium">Teléfono de contacto:</span> {{ $order->phone }}
                </p>
            </div>
        </div>

        {{-- Columna Derecha: Resumen del Pedido y Pago --}}
        <div class="rounded-lg bg-white p-6 shadow-lg dark:bg-gray-800">
            <h2 class="mb-4 text-lg font-semibold text-gray-800 dark:text-gray-200">
                Resumen del Pedido (Orden: {{ $order->id }})
            </h2>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700/50">
                        <tr>
                            <th scope="col"
                                class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">
                                Producto</th>
                            <th scope="col"
                                class="px-4 py-3 text-center text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">
                                Precio</th>
                            <th scope="col"
                                class="px-4 py-3 text-center text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">
                                Cant.</th>
                            <th scope="col"
                                class="px-4 py-3 text-right text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">
                                Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white dark:divide-gray-700 dark:bg-gray-800">
                        @foreach ($items as $item)
                            <tr>
                                <td class="whitespace-nowrap px-4 py-4">
                                    <div class="flex items-center">
                                        {{-- ¡AQUÍ ESTÁ LA IMAGEN! --}}
                                        <div class="h-16 w-16 flex-shrink-0">
                                            {{-- Usamos asset() para generar la URL completa y un fallback por si no hay imagen --}}
                                            <img class="h-16 w-16 rounded-md object-cover"
                                                src="{{ asset($item['options']['image']) }}" alt="{{ $item['name'] }}">
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                {{ $item['name'] }}
                                            </div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                                @if (isset($item['options']['color']) && $item['options']['color'])
                                                    Color: {{ __($item['options']['color']) }}
                                                @endif
                                                @if (isset($item['options']['size']) && $item['options']['size'])
                                                    - Talla: {{ $item['options']['size'] }}
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td
                                    class="whitespace-nowrap px-4 py-4 text-center text-sm text-gray-500 dark:text-gray-300">
                                    € {{ number_format($item['price'], 2, ',', '.') }}
                                </td>
                                <td
                                    class="whitespace-nowrap px-4 py-4 text-center text-sm text-gray-500 dark:text-gray-300">
                                    {{ $item['qty'] }}
                                </td>
                                <td
                                    class="whitespace-nowrap px-4 py-4 text-right text-sm font-medium text-gray-900 dark:text-white">
                                    € {{ number_format($item['price'] * $item['qty'], 2, ',', '.') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-6 border-t border-gray-200 pt-4 dark:border-gray-700">
                <div class="flex justify-between text-sm text-gray-700 dark:text-gray-300">
                    <p>Subtotal</p>
                    <p>€ {{ number_format($order->total - $order->shopping_cost, 2, ',', '.') }}</p>
                </div>
                <div class="flex justify-between text-sm text-gray-700 dark:text-gray-300">
                    <p>Gastos de envío</p>
                    <p>
                        @if ($order->envio_type == 1 || $order->shopping_cost == 0)
                            Gratis
                        @else
                            € {{ number_format($order->shopping_cost, 2, ',', '.') }}
                        @endif
                    </p>
                </div>
                <div
                    class="mt-2 flex justify-between border-t border-gray-200 pt-2 text-lg font-bold text-gray-900 dark:border-gray-600 dark:text-white">
                    <p>Total</p>
                    <p>€ {{ number_format($order->total, 2, ',', '.') }}</p>
                </div>
            </div>

            <div class="mt-6">
                {{-- Aquí irá el contenido de la pasarela de pago --}}
                {{-- Ahora la comprobación respeta la variable de entorno PAYMENT_GATEWAY --}}
                @if (env('PAYMENT_GATEWAY') == 'paypal' && config('services.paypal.client_id') && config('services.paypal.secret'))
                    <div id="paypal-button-container"></div>
                @else
                    {{-- Pasarela de pago "Dummy" para pruebas --}}
                    <div class="rounded-lg bg-gray-100 p-4 text-center dark:bg-gray-900">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">Modo de Prueba (Pasarela
                            Falsa)</h3>
                        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                            Haz clic en el botón para simular un pago con éxito de forma instantánea.
                        </p>
                        <button wire:click="payOrder()" wire:loading.attr="disabled"
                            class="mt-4 inline-flex items-center rounded-md border border-transparent bg-lime-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-lime-700 focus:outline-none focus:ring-2 focus:ring-lime-500 focus:ring-offset-2 disabled:opacity-50">
                            <span wire:loading.remove wire:target="payOrder">Simular Pago</span>
                            <span wire:loading wire:target="payOrder">Procesando...</span>
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- ¡AQUÍ EMPIEZA LA MAGIA! --}}
@push('scripts')
    {{-- Solo incluimos y ejecutamos el script de PayPal si estamos en modo PayPal --}}
    @if (env('PAYMENT_GATEWAY') == 'paypal' && config('services.paypal.client_id') && config('services.paypal.secret'))
        {{-- 1. Incluimos el SDK de PayPal con nuestro Client ID --}}
        <script src="https://www.paypal.com/sdk/js?client-id={{ config('services.paypal.client_id') }}&currency=EUR&enable-funding=card"></script>

        <script>
            // 2. Renderizamos los botones de PayPal
            paypal.Buttons({
                // 3. createOrder: Se ejecuta cuando el usuario hace clic en el botón de PayPal.
                //    Ahora llama a nuestro backend para crear la orden de forma segura.
                createOrder: (data, actions) => {
                    return fetch("{{ route('payment.create', $order) }}", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": "{{ csrf_token() }}",
                        },
                    }).then((res) => {
                        if (!res.ok) {
                            // Si el servidor devuelve un error, lo mostramos y rechazamos la promesa
                            return res.json().then(errorData => {
                                console.error("Error al crear la orden en el servidor:", errorData);
                                throw new Error(errorData.error || 'Error del servidor');
                            });
                        }
                        return res.json();
                    }).then((orderData) => {
                        // Devolvemos el ID de la orden que nos dio nuestro backend
                        return orderData.id;
                    });
                },

                // 4. onApprove: Se ejecuta cuando el usuario aprueba el pago en la ventana de PayPal.
                //    Ahora llama a nuestro backend para capturar el pago.
                onApprove: (data, actions) => {
                    return fetch("{{ route('payment.capture', $order) }}", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": "{{ csrf_token() }}",
                        },
                        body: JSON.stringify({
                            orderID: data.orderID
                        })
                    }).then((res) => res.json()).then((orderData) => {
                        // El backend ya procesó todo. Solo redirigimos a la página de éxito.
                        window.location.href = "{{ route('orders.success', $order) }}";
                    });
                },

                // 5. onCancel: Se ejecuta si el usuario cierra la ventana de PayPal o cancela.
                onCancel: (data) => {
                    // Simplemente redirigimos a la página de detalles de la orden.
                    window.location.href = "{{ route('orders.show', $order) }}";
                }
            }).render('#paypal-button-container'); // Le decimos dónde dibujar los botones
        </script>
    @endif
@endpush
