<div>
    @php
        // Para asegurar que 'items' es siempre un array, incluso si 'content' es null
        $items = $order->content ?? [];
    @endphp
    <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-5 gap-6 container py-8">
        <div class="xl:col-span-3">
            {{-- Resumen del Pedido --}}
            <div class="bg-white rounded-lg shadow-lg px-6 py-4 mb-6">
                <p class="text-gray-700 uppercase"><span class="font-semibold">Número de orden:</span> {{ $order->id }}</p>
            </div>

            <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
                {{-- Detalles de envío o recogida --}}
                <div class="grid grid-cols-2 gap-6 text-gray-700">
                    <div>
                        <p class="text-lg font-semibold uppercase">Envío</p>
                        @if ($order->envio_type == 1)
                            <p class="text-sm">Los productos deben ser recogidos en tienda</p>
                            <p class="text-sm">Calle Falsa 123</p>
                        @else
                            <p class="text-sm">Los productos serán enviados a:</p>
                            <p class="text-sm">{{ $order->envio['address'] ?? 'Dirección no especificada' }}</p>
                            <p>{{ $order->envio['department'] ?? '' }} - {{ $order->envio['city'] ?? '' }} - {{ $order->envio['district'] ?? '' }}</p>
                        @endif
                    </div>
                    <div>
                        <p class="text-lg font-semibold uppercase">Datos de contacto</p>
                        <p class="text-sm">Persona que recibe: {{ $order->contact }}</p>
                        <p class="text-sm">Teléfono de contacto: {{ $order->phone }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-lg p-6 text-gray-700 mb-6">
                <p class="text-xl font-semibold mb-4">Resumen</p>
                <table class="table-auto w-full">
                    <thead>
                        <tr>
                            <th></th>
                            <th>Precio</th>
                            <th>Cant</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach ($items as $item)
                            <tr>
                                <td>
                                    <div class="flex items-center">
                                        <img class="h-16 w-16 object-cover mr-4" src="{{ $item['options']['image'] ?? asset('img/no-image.png') }}" alt="">
                                        <article>
                                            <h1 class="font-bold">{{ $item['name'] }}</h1>
                                            <div class="flex text-xs">
                                                @if (!empty($item['options']['color']))
                                                    <span>Color: {{ $item['options']['color'] }}</span>
                                                @endif
                                                @if (!empty($item['options']['size']))
                                                    <span class="mx-1">-</span>
                                                    <span>Talla: {{ $item['options']['size'] }}</span>
                                                @endif
                                            </div>
                                        </article>
                                    </div>
                                </td>
                                <td class="text-center">{{ number_format($item['price'], 2) }} €</td>
                                <td class="text-center">{{ $item['qty'] }}</td>
                                <td class="text-center">{{ number_format($item['price'] * $item['qty'], 2) }} €</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="xl:col-span-2">
            <div class="bg-white rounded-lg shadow-lg p-6">
                <div class="flex justify-between items-center mb-4">
                    <img class="h-8" src="{{ asset('img/payment-methods.png') }}" alt="">
                    <div class="text-gray-700 text-right">
                        <p class="text-sm font-semibold">
                            Subtotal: {{ number_format($order->total - $order->shopping_cost, 2) }} €
                        </p>
                        <p class="text-sm font-semibold">
                            Envío: {{ number_format($order->shopping_cost, 2) }} €
                        </p>
                        <p class="text-lg font-semibold uppercase">
                            Total: {{ number_format($order->total, 2) }} €
                        </p>
                    </div>
                </div>

                {{-- ¡LA SOLUCIÓN! Este formulario envía la petición al controlador correcto --}}
                <div class="mt-4">
                    <form action="{{ route('payment.dummy.capture', $order) }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">
                            Pagar con Pasarela de Prueba
                        </button>
                    </form>
                </div>

                <hr class="my-4">

                {{-- Aquí iría el contenedor del botón de PayPal --}}
                <div id="paypal-button-container"></div>
            </div>
        </div>
    </div>
</div>