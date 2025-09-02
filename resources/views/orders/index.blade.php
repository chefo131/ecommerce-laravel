<x-layouts.app :title="__('Mis Pedidos')">

    <div class="container py-12">

        <section class="mb-8 grid grid-cols-2 gap-4 sm:grid-cols-3 md:grid-cols-5 md:gap-6">
            {{-- Tarjeta Pendiente --}}
            <a href="{{ route('orders.index') . '?status=1' }}"
                class="rounded-lg bg-red-100 px-4 py-4 text-center shadow-lg transition-transform duration-200 hover:scale-105 dark:bg-red-800/50">
                <p class="text-2xl font-bold text-red-700 dark:text-red-300">{{ $pendiente }}</p>
                <p class="mt-1 text-sm font-semibold uppercase text-red-600 dark:text-red-200">Pendientes</p>
            </a>

            {{-- Tarjeta Pagado --}}
            <a href="{{ route('orders.index') . '?status=2' }}"
                class="rounded-lg bg-yellow-100 px-4 py-4 text-center shadow-lg transition-transform duration-200 hover:scale-105 dark:bg-yellow-800/50">
                <p class="text-2xl font-bold text-yellow-700 dark:text-yellow-300">{{ $pagado }}</p>
                <p class="mt-1 text-sm font-semibold uppercase text-yellow-600 dark:text-yellow-200">Pagados</p>
            </a>

            {{-- Tarjeta Enviado --}}
            <a href="{{ route('orders.index') . '?status=3' }}"
                class="rounded-lg bg-blue-100 px-4 py-4 text-center shadow-lg transition-transform duration-200 hover:scale-105 dark:bg-blue-800/50">
                <p class="text-2xl font-bold text-blue-700 dark:text-blue-300">{{ $enviado }}</p>
                <p class="mt-1 text-sm font-semibold uppercase text-blue-600 dark:text-blue-200">Enviados</p>
            </a>

            {{-- Tarjeta Entregado --}}
            <a href="{{ route('orders.index') . '?status=4' }}"
                class="rounded-lg bg-green-100 px-4 py-4 text-center shadow-lg transition-transform duration-200 hover:scale-105 dark:bg-green-800/50">
                <p class="text-2xl font-bold text-green-700 dark:text-green-300">{{ $entregado }}</p>
                <p class="mt-1 text-sm font-semibold uppercase text-green-600 dark:text-green-200">Entregados</p>
            </a>

            {{-- Tarjeta Anulado --}}
            <a href="{{ route('orders.index') . '?status=5' }}"
                class="rounded-lg bg-gray-100 px-4 py-4 text-center shadow-lg transition-transform duration-200 hover:scale-105 dark:bg-gray-700/50">
                <p class="text-2xl font-bold text-gray-700 dark:text-gray-300">{{ $anulado }}</p>
                <p class="mt-1 text-sm font-semibold uppercase text-gray-600 dark:text-gray-200">Anulados</p>
            </a>
        </section>

        @if ($orders->count())
            <section class="rounded-lg bg-white p-6 shadow-lg dark:bg-gray-800">
                <h2 class="mb-4 text-xl font-semibold text-gray-800 dark:text-gray-200">
                    Listado de Pedidos
                </h2>
                <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach ($orders as $order)
                        <li class="py-4">
                            <a href="{{ route('orders.show', $order) }}"
                                class="block rounded-md p-4 transition hover:bg-gray-50 dark:hover:bg-gray-700/50">
                                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                                    <div class="flex items-center gap-4">
                                        <div>
                                            @php
                                                $statusInfo = match ($order->status) {
                                                    1 => [
                                                        'text' => 'Pendiente',
                                                        'icon' => 'fa-solid fa-clock',
                                                        'color' => 'text-red-500',
                                                        'bg' => 'bg-red-100 dark:bg-red-900/50',
                                                    ],
                                                    2 => [
                                                        'text' => 'Pagado',
                                                        'icon' => 'fa-solid fa-credit-card',
                                                        'color' => 'text-yellow-500',
                                                        'bg' => 'bg-yellow-100 dark:bg-yellow-900/50',
                                                    ],
                                                    3 => [
                                                        'text' => 'Enviado',
                                                        'icon' => 'fa-solid fa-truck-fast',
                                                        'color' => 'text-blue-500',
                                                        'bg' => 'bg-blue-100 dark:bg-blue-900/50',
                                                    ],
                                                    4 => [
                                                        'text' => 'Entregado',
                                                        'icon' => 'fa-solid fa-circle-check',
                                                        'color' => 'text-green-500',
                                                        'bg' => 'bg-green-100 dark:bg-green-900/50',
                                                    ],
                                                    5 => [
                                                        'text' => 'Anulado',
                                                        'icon' => 'fa-solid fa-circle-xmark',
                                                        'color' => 'text-gray-500',
                                                        'bg' => 'bg-gray-100 dark:bg-gray-600/50',
                                                    ],
                                                    default => [
                                                        'text' => 'Desconocido',
                                                        'icon' => 'fa-solid fa-question-circle',
                                                        'color' => 'text-gray-400',
                                                        'bg' => 'bg-gray-100 dark:bg-gray-600/50',
                                                    ],
                                                };
                                            @endphp
                                            <span
                                                class="{{ $statusInfo['bg'] }} {{ $statusInfo['color'] }} flex h-12 w-12 items-center justify-center rounded-full">
                                                <x-icon name="{{ $statusInfo['icon'] }}" class="h-6 w-6" />
                                            </span>
                                        </div>
                                        <div>
                                            <p class="font-semibold text-gray-800 dark:text-gray-100">
                                                Orden: #{{ $order->id }}
                                            </p>
                                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                                {{ $order->created_at->format('d/m/Y') }}
                                            </p>
                                        </div>
                                    </div>

                                    <div class="text-right">
                                        <p class="font-bold text-gray-800 dark:text-white">
                                            {{ $statusInfo['text'] }}
                                        </p>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">
                                            € {{ number_format($order->total, 2, ',', '.') }}
                                        </p>
                                    </div>

                                    <div class="hidden sm:block">
                                        <x-icon name="fa-solid fa-chevron-right" class="h-5 w-5 text-gray-400" />
                                    </div>
                                </div>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </section>

            @if ($orders->hasPages())
                <div class="mt-6 px-4">
                    {{ $orders->links() }}
                </div>
            @endif
        @else
            <div class="rounded-lg bg-white p-8 text-center shadow-lg dark:bg-gray-800">
                <p class="text-gray-700 dark:text-gray-200">No tienes pedidos en este estado.</p>
            </div>
        @endif
    </div>
</x-layouts.app>
