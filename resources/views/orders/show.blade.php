<x-layouts.app :title="'Orden: ' . $order->id">

    <div class="container py-12">
        <div class="mx-auto max-w-4xl rounded-lg bg-white p-6 shadow-lg dark:bg-gray-800">

            {{-- Encabezado con el estado de la orden --}}
            <div
                class="mb-6 flex flex-col items-start justify-between gap-4 border-b border-gray-200 pb-4 sm:flex-row sm:items-center dark:border-gray-700">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800 dark:text-white">
                        Detalles de la Orden #{{ $order->id }}
                    </h1>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Realizada el: {{ $order->created_at->format('d/m/Y H:i') }}
                    </p>
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-sm font-medium text-gray-600 dark:text-gray-300">Estado:</span>
                    @php
                        $statusInfo = match ($order->status) {
                            1 => [
                                'text' => 'Pendiente',
                                'classes' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/50 dark:text-yellow-300',
                            ],
                            2 => [
                                'text' => 'Pagado',
                                'classes' => 'bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-300',
                            ],
                            3 => [
                                'text' => 'Enviado',
                                'classes' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/50 dark:text-blue-200',
                            ],
                            4 => [
                                'text' => 'Entregado',
                                'classes' => 'bg-purple-100 text-purple-800 dark:bg-purple-900/50 dark:text-purple-300',
                            ],
                            5 => [
                                'text' => 'Anulado',
                                'classes' => 'bg-red-100 text-red-800 dark:bg-red-900/50 dark:text-red-300',
                            ],
                            default => [
                                'text' => 'Desconocido',
                                'classes' => 'bg-gray-100 text-gray-800 dark:bg-gray-600 dark:text-gray-200',
                            ],
                        };
                    @endphp
                    <span
                        class="{{ $statusInfo['classes'] }} inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold">
                        {{ $statusInfo['text'] }}
                    </span>
                </div>
            </div>

            {{-- Detalles de Envío y Contacto --}}
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                {{-- Columna Izquierda --}}
                <div>
                    <h3 class="mb-2 font-semibold text-gray-700 dark:text-gray-300">Datos de Contacto</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        <span class="font-medium">Recibe:</span> {{ $order->contact }}
                    </p>
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        <span class="font-medium">Teléfono:</span> {{ $order->phone }}
                    </p>
                </div>
                {{-- Columna Derecha --}}
                <div>
                    <h3 class="mb-2 font-semibold text-gray-700 dark:text-gray-300">Detalles del Envío</h3>
                    {{-- ¡AQUÍ ESTÁ LA SOLUCIÓN AL ERROR! --}}
                    {{-- Solo mostramos la dirección si el tipo de envío es 2 (a domicilio) --}}
                    @if ($order->envio_type == 2 && $envio)
                        <address class="text-sm not-italic text-gray-600 dark:text-gray-400">
                            {{ $envio['address'] }}<br>
                            {{ $envio['district'] }}, {{ $envio['city'] }}<br>
                            {{ $envio['department'] }}
                        </address>
                    @else
                        <p class="text-sm text-gray-600 dark:text-gray-400">Recogida en tienda.</p>
                    @endif
                </div>
            </div>

            {{-- Lista de Productos --}}
            <div class="mt-8">
                <h3 class="mb-4 text-lg font-semibold text-gray-800 dark:text-gray-200">Resumen de la Compra</h3>
                <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach ($items as $item)
                        @php
                            // Buscamos el producto para poder enlazar a su página.
                            // Nota: En una aplicación a gran escala, sería más óptimo precargar estos productos en el controlador.
                            $product = \App\Models\Product::find($item['id']);
                        @endphp
                        <li class="flex items-center py-4">
                            <a href="{{ $product ? route('products.show', $product) : '#' }}">
                                <img class="h-20 w-20 rounded-md object-cover"
                                    src="{{ asset($item['options']['image']) }}" alt="{{ $item['name'] }}">
                            </a>
                            <div class="ml-4 flex-1">
                                <p class="font-semibold text-gray-800 dark:text-gray-100">
                                    {{-- ¡SOLUCIÓN! Añadimos el enlace al producto. --}}
                                    <a href="{{ $product ? route('products.show', $product) : '#' }}"
                                        class="hover:text-lime-600 hover:underline dark:hover:text-lime-400">
                                        {{ $item['name'] }}
                                    </a>
                                </p>
                                <div class="text-sm text-gray-500 dark:text-gray-400">
                                    @if (isset($item['options']['color']) && $item['options']['color'])
                                        <span>Color: {{ __($item['options']['color']) }}</span>
                                    @endif
                                    @if (isset($item['options']['size']) && $item['options']['size'])
                                        <span class="ml-2">Talla: {{ $item['options']['size'] }}</span>
                                    @endif
                                </div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Cant: {{ $item['qty'] }}</p>
                            </div>
                            <p class="text-right font-semibold text-gray-800 dark:text-gray-100">
                                € {{ number_format($item['price'], 2, ',', '.') }}
                            </p>
                        </li>
                    @endforeach
                </ul>
            </div>

            {{-- Totales --}}
            <div class="mt-6 border-t border-gray-200 pt-6 dark:border-gray-700">
                <div class="space-y-2 text-right text-gray-700 dark:text-gray-300">
                    <div class="flex justify-end gap-4">
                        <p>Subtotal:</p>
                        <p class="w-24 font-medium">€
                            {{ number_format($order->total - $order->shipping_cost, 2, ',', '.') }}</p>
                    </div>
                    <div class="flex justify-end gap-4">
                        <p>Envío:</p>
                        <p class="w-24 font-medium">€ {{ number_format($order->shipping_cost, 2, ',', '.') }}</p>
                    </div>
                    <div
                        class="flex justify-end gap-4 border-t border-gray-200 pt-2 text-lg font-bold text-gray-900 dark:border-gray-600 dark:text-white">
                        <p>Total:</p>
                        <p class="w-24">€ {{ number_format($order->total, 2, ',', '.') }}</p>
                    </div>
                </div>
            </div>

            {{-- Botón para volver a pagar si la orden está pendiente --}}
            @if ($order->status == 1)
                <div class="mt-8 text-center">
                    <a href="{{ route('orders.payment', $order) }}"
                        class="inline-block rounded-lg bg-lime-600 px-6 py-3 text-base font-semibold text-white shadow-md transition hover:bg-lime-700 focus:outline-none focus:ring-2 focus:ring-lime-500 focus:ring-opacity-75">
                        Proceder al Pago
                    </a>
                </div>
            @endif

        </div>
    </div>

</x-layouts.app>
