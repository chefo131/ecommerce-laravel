<x-layouts.app.admin>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
            Gestión de Pedidos
        </h2>
    </x-slot>

    <div class="container py-12">
        {{-- Tarjetas de Filtro de Estado --}}
        <section class="mb-8 grid grid-cols-1 gap-6 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-6">
            <a href="{{ route('admin.orders.index') }}"
                class="{{ request('status') ? 'bg-white dark:bg-gray-800' : 'bg-lime-200 dark:bg-lime-800' }} rounded-lg p-4 text-center shadow transition-transform duration-200 hover:scale-105">
                <p class="text-2xl font-bold">{{ $all }}</p>
                <p class="text-sm font-semibold uppercase">Todos</p>
            </a>
            <a href="{{ route('admin.orders.index') . '?status=1' }}"
                class="{{ request('status') == 1 ? 'bg-yellow-200 dark:bg-yellow-800' : 'bg-white dark:bg-gray-800' }} rounded-lg p-4 text-center shadow transition-transform duration-200 hover:scale-105">
                <p class="text-2xl font-bold">{{ $pendiente }}</p>
                <p class="text-sm font-semibold uppercase">Pendiente</p>
            </a>
            <a href="{{ route('admin.orders.index') . '?status=2' }}"
                class="{{ request('status') == 2 ? 'bg-green-200 dark:bg-green-800' : 'bg-white dark:bg-gray-800' }} rounded-lg p-4 text-center shadow transition-transform duration-200 hover:scale-105">
                <p class="text-2xl font-bold">{{ $pagado }}</p>
                <p class="text-sm font-semibold uppercase">Pagado</p>
            </a>
            <a href="{{ route('admin.orders.index') . '?status=3' }}"
                class="{{ request('status') == 3 ? 'bg-blue-200 dark:bg-blue-800' : 'bg-white dark:bg-gray-800' }} rounded-lg p-4 text-center shadow transition-transform duration-200 hover:scale-105">
                <p class="text-2xl font-bold">{{ $enviado }}</p>
                <p class="text-sm font-semibold uppercase">Enviado</p>
            </a>
            <a href="{{ route('admin.orders.index') . '?status=4' }}"
                class="{{ request('status') == 4 ? 'bg-purple-200 dark:bg-purple-800' : 'bg-white dark:bg-gray-800' }} rounded-lg p-4 text-center shadow transition-transform duration-200 hover:scale-105">
                <p class="text-2xl font-bold">{{ $entregado }}</p>
                <p class="text-sm font-semibold uppercase">Entregado</p>
            </a>
            <a href="{{ route('admin.orders.index') . '?status=5' }}"
                class="{{ request('status') == 5 ? 'bg-red-200 dark:bg-red-800' : 'bg-white dark:bg-gray-800' }} rounded-lg p-4 text-center shadow transition-transform duration-200 hover:scale-105">
                <p class="text-2xl font-bold">{{ $anulado }}</p>
                <p class="text-sm font-semibold uppercase">Anulado</p>
            </a>
        </section>

        {{-- Lista de Pedidos --}}
        <section class="rounded-lg bg-white p-6 shadow-lg dark:bg-gray-800">
            @if ($orders->count())
                <ul class="space-y-4">
                    @foreach ($orders as $order)
                        <li
                            class="rounded-md border p-4 transition-shadow duration-200 hover:shadow-md dark:border-gray-700">
                            <a href="{{ route('admin.orders.show', $order) }}" class="block">
                                <div class="flex flex-wrap items-center justify-between gap-4">
                                    <div>
                                        <p class="text-lg font-semibold text-gray-800 dark:text-gray-200">
                                            Orden #{{ $order->id }}
                                        </p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">
                                            {{ $order->created_at->format('d/m/Y H:i') }}
                                        </p>
                                    </div>
                                    <div>
                                        <span @class([
                                            'rounded-full px-3 py-1 text-xs font-semibold',
                                            'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/50 dark:text-yellow-300' =>
                                                $order->status == 1,
                                            'bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-300' =>
                                                $order->status == 2,
                                            'bg-blue-100 text-blue-800 dark:bg-blue-900/50 dark:text-blue-300' =>
                                                $order->status == 3,
                                            'bg-purple-100 text-purple-800 dark:bg-purple-900/50 dark:text-purple-300' =>
                                                $order->status == 4,
                                            'bg-red-100 text-red-800 dark:bg-red-900/50 dark:text-red-300' =>
                                                $order->status == 5,
                                        ])>
                                            {{ App\Models\Order::STATUS_LABELS[$order->status] }}
                                        </span>
                                    </div>
                                    <div>
                                        <p class="text-lg font-bold text-gray-900 dark:text-white">
                                            € {{ number_format($order->total, 2, ',', '.') }}
                                        </p>
                                    </div>
                                    <div class="text-gray-700 dark:text-gray-300">
                                        <i class="fa-solid fa-eye"></i>
                                        Ver
                                    </div>
                                </div>
                            </a>
                        </li>
                    @endforeach
                </ul>
            @else
                <div class="py-12 text-center text-gray-500 dark:text-gray-400">
                    No hay pedidos que coincidan con el estado seleccionado.
                </div>
            @endif
        </section>

        @if ($orders->hasPages())
            <div class="mt-8">
                {{ $orders->links() }}
            </div>
        @endif
    </div>
</x-layouts.app.admin>

{{-- Este es el código que tenías antes, lo dejamos comentado como referencia --}}
{{-- <x-layouts.app.admin>
    <div class="container py-8">

        <section class="grid grid-cols-1 gap-6 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-6">
            <a href="{{ route('admin.orders.index') }}"
                class="{{ !request('status') ? 'ring-2 ring-indigo-500' : '' }} rounded-lg bg-white px-4 py-6 text-center shadow-lg transition-transform duration-200 hover:scale-105">
                <p class="text-2xl font-semibold text-gray-700">
                    {{ $all }}
                </p>
                <p class="mt-1 font-semibold uppercase text-gray-400">Todos</p>
            </a>

            <a href="{{ route('admin.orders.index') . '?status=1' }}"
                class="{{ request('status') == 1 ? 'ring-2 ring-white' : '' }} rounded-lg bg-red-500 px-4 py-6 text-center shadow-lg transition-transform duration-200 hover:scale-105">
                <p class="text-2xl font-semibold text-white">
                    {{ $pendiente }}
                </p>
                <p class="mt-1 font-semibold uppercase text-white">Pendiente</p>
            </a>

            <a href="{{ route('admin.orders.index') . '?status=2' }}"
                class="{{ request('status') == 2 ? 'ring-2 ring-white' : '' }} rounded-lg bg-gray-500 px-4 py-6 text-center shadow-lg transition-transform duration-200 hover:scale-105">
                <p class="text-2xl font-semibold text-white">
                    {{ $pagado }}
                </p>
                <p class="mt-1 font-semibold uppercase text-white">Pagado</p>
            </a>

            <a href="{{ route('admin.orders.index') . '?status=3' }}"
                class="{{ request('status') == 3 ? 'ring-2 ring-white' : '' }} rounded-lg bg-yellow-500 px-4 py-6 text-center shadow-lg transition-transform duration-200 hover:scale-105">
                <p class="text-2xl font-semibold text-white">
                    {{ $enviado }}
                </p>
                <p class="mt-1 font-semibold uppercase text-white">Enviado</p>
            </a>

            <a href="{{ route('admin.orders.index') . '?status=4' }}"
                class="{{ request('status') == 4 ? 'ring-2 ring-white' : '' }} rounded-lg bg-pink-500 px-4 py-6 text-center shadow-lg transition-transform duration-200 hover:scale-105">
                <p class="text-2xl font-semibold text-white">
                    {{ $entregado }}
                </p>
                <p class="mt-1 font-semibold uppercase text-white">Entregado</p>
            </a>

            <a href="{{ route('admin.orders.index') . '?status=5' }}"
                class="{{ request('status') == 5 ? 'ring-2 ring-white' : '' }} rounded-lg bg-green-500 px-4 py-6 text-center shadow-lg transition-transform duration-200 hover:scale-105">
                <p class="text-2xl font-semibold text-white">
                    {{ $anulado }}
                </p>
                <p class="mt-1 font-semibold uppercase text-white">Anulado</p>
            </a>
        </section>

        @if ($orders->count())
            <section class="mt-8 rounded-lg bg-white px-6 py-8 shadow-lg">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                    ID
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                    Fecha
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                    Usuario
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                    Total
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                    Estado
                                </th>
                                <th scope="col" class="relative px-6 py-3">
                                    <span class="sr-only">Ver</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            @foreach ($orders as $order)
                                <tr>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-900">{{ $order->id }}
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-900">
                                        {{ $order->created_at->format('d/m/Y') }}</td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-900">
                                        {{ $order->user->name }}</td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-900">{{ $order->total }}
                                        &euro;</td>
                                    <td class="whitespace-nowrap px-6 py-4">
                                        @php
                                            $statusInfo = match ($order->status) {
                                                1 => ['class' => 'bg-red-100 text-red-800', 'text' => 'Pendiente'],
                                                2 => ['class' => 'bg-gray-100 text-gray-800', 'text' => 'Pagado'],
                                                3 => ['class' => 'bg-yellow-100 text-yellow-800', 'text' => 'Enviado'],
                                                4 => ['class' => 'bg-pink-100 text-pink-800', 'text' => 'Entregado'],
                                                5 => ['class' => 'bg-green-100 text-green-800', 'text' => 'Anulado'],
                                                default => [
                                                    'class' => 'bg-gray-100 text-gray-800',
                                                    'text' => 'Desconocido',
                                                ],
                                            };
                                        @endphp
                                        <span
                                            class="{{ $statusInfo['class'] }} inline-flex rounded-full px-2 text-xs font-semibold leading-5">
                                            {{ $statusInfo['text'] }}
                                        </span>
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-right text-sm font-medium">
                                        <a href="{{ route('admin.orders.show', $order) }}"
                                            class="text-indigo-600 hover:text-indigo-900">Ver</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if ($orders->hasPages())
                    <div class="mt-4 px-6 py-4">
                        {{ $orders->links() }}
                    </div>
                @endif

            </section>
        @else
            <div class="mt-8 rounded-lg bg-white px-6 py-8 shadow-lg">
                <div class="flex items-center justify-center">
                    <div class="text-center">
                        <i class="fa-solid fa-box-open fa-3x text-gray-400"></i>
                        <p class="mt-4 text-lg font-semibold text-gray-700">No hay órdenes que coincidan con el filtro.
                        </p>
                        <p class="mt-2 text-gray-500">Intenta seleccionar otro estado o revisa más tarde.</p>
                    </div>
                </div>
            </div>
        @endif

    </div>
</x-layouts.app.admin> --}}
