<x-layouts.app.admin>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
            Detalles de la Orden #{{ $order->id }}
        </h2>
    </x-slot>

    <div class="container py-12">
        {{-- Mensaje de éxito --}}
        @if (session('success'))
            <div class="mb-6 rounded-lg border-l-4 border-green-500 bg-green-100 p-4 text-green-700 dark:bg-green-900/20 dark:text-green-300"
                role="alert">
                <p class="font-bold">¡Éxito!</p>
                <p>{{ session('success') }}</p>
            </div>
        @endif

        <div class="grid grid-cols-1 gap-8 md:grid-cols-2">
            {{-- Columna Izquierda: Estado, Contacto y Envío --}}
            <div class="space-y-6">
                {{-- Actualizar Estado --}}
                <div class="rounded-lg bg-white p-6 shadow-lg dark:bg-gray-800">
                    <h3 class="mb-4 text-lg font-semibold text-gray-800 dark:text-gray-200">Actualizar Estado del Pedido
                    </h3>
                    <form action="{{ route('admin.orders.update', $order) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="flex items-center space-x-4">
                            <select name="status"
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-lime-500 focus:ring-lime-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                                @foreach (App\Models\Order::STATUS_LABELS as $status => $label)
                                    <option value="{{ $status }}"
                                        @if ($order->status == $status) selected @endif>{{ $label }}</option>
                                @endforeach
                            </select>
                            <button type="submit"
                                class="rounded-md bg-lime-600 px-4 py-2 text-white hover:bg-lime-700">
                                Actualizar
                            </button>
                        </div>
                    </form>
                </div>

                {{-- Datos de Contacto --}}
                <div class="rounded-lg bg-white p-6 shadow-lg">
                    <h3 class="mb-4 text-lg font-semibold text-gray-800 dark:text-gray-200">Datos de Contacto</h3>
                    <p class="text-sm text-gray-700 dark:text-gray-300">
                        <span class="font-medium">Recibe:</span> {{ $order->contact }}
                    </p>
                    <p class="text-sm text-gray-700 dark:text-gray-300">
                        <span class="font-medium">Teléfono:</span> {{ $order->phone }}
                    </p>
                    {{-- ¡AQUÍ ESTÁ EL EMAIL! --}}
                    <p class="text-sm text-gray-700 dark:text-gray-300">
                        <span class="font-medium">Email del Usuario:</span> {{ $order->user->email }}
                    </p>
                </div>

                {{-- Detalles del Envío --}}
                <div class="rounded-lg bg-white p-6 shadow-lg dark:bg-gray-800">
                    <h3 class="mb-4 text-lg font-semibold text-gray-800 dark:text-gray-200">Detalles del Envío</h3>
                    @if ($order->envio_type == 1)
                        <p class="text-sm text-gray-700 dark:text-gray-300">Recogida en tienda.</p>
                    @else
                        <address class="not-italic text-gray-700 dark:text-gray-300">
                            {{ $order->envio['address'] }}<br>
                            {{ $order->envio['district'] }}, {{ $order->envio['city'] }}<br>
                            {{ $order->envio['department'] }}<br>
                            <span class="text-xs">Ref: {{ $order->envio['references'] }}</span>
                        </address>
                    @endif
                </div>
            </div>

            {{-- Columna Derecha: Resumen de la Compra --}}
            <div class="rounded-lg bg-white p-6 shadow-lg dark:bg-gray-800">
                <h3 class="mb-4 text-lg font-semibold text-gray-800 dark:text-gray-200">Resumen de la Compra</h3>
                <div class="space-y-4">
                    {{-- ¡AQUÍ USAMOS LA SINTAXIS DE ARRAY PARA SOLUCIONAR EL ERROR! --}}
                    @foreach ($order->content as $item)
                        <div class="flex items-center justify-between border-b pb-2 dark:border-gray-700">
                            <div class="flex items-center">
                                <img class="h-16 w-16 rounded-md object-cover" src="{{ $item['options']['image'] }}"
                                    alt="{{ $item['name'] }}">
                                <div class="ml-4">
                                    <p class="font-semibold text-gray-900 dark:text-white">{{ $item['name'] }}</p>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">
                                        @if (isset($item['options']['color']) && $item['options']['color'])
                                            Color: {{ __($item['options']['color']) }}
                                        @endif
                                        @if (isset($item['options']['size']) && $item['options']['size'])
                                            - Talla: {{ $item['options']['size'] }}
                                        @endif
                                    </p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Cant: {{ $item['qty'] }}</p>
                                </div>
                            </div>
                            <p class="text-right font-medium text-gray-900 dark:text-white">
                                € {{ number_format($item['price'] * $item['qty'], 2, ',', '.') }}
                            </p>
                        </div>
                    @endforeach
                </div>
                <div class="mt-4 border-t pt-4 dark:border-gray-700">
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
            </div>
        </div>
    </div>
</x-layouts.app.admin>
