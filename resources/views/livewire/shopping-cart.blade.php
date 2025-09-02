<div>
    {{-- Contenedor principal con padding y estilos base --}}
    <section class="container rounded-lg bg-white p-6 text-gray-700 shadow-lg">
        {{-- El carrito esta lleno --}}
        @if (Cart::count())
            <h1 class="mb-6 text-lg font-semibold text-gray-700">Carro de compras</h1>

            {{-- Vista de Escritorio (Tabla) - Oculta en pantallas pequeñas (lg) --}}
            <div class="hidden lg:block">
                <table class="w-full table-auto">
                    <thead class="border-b border-gray-200">
                        <tr class="text-left text-sm text-gray-500">
                            <th class="w-1/2 px-4 py-2">Producto</th>
                            <th class="px-4 py-2 text-center">Precio</th>
                            <th class="px-4 py-2 text-center">Cantidad</th>
                            <th class="px-4 py-2 text-center">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach (Cart::content() as $item)
                            <tr>
                                <td class="p-4">
                                    <div class="flex items-center">
                                        <img class="h-16 w-16 object-cover" src="{{ $item->options->image }}"
                                            alt="{{ $item->name }}">
                                        <div class="ml-4">
                                            <p class="font-bold">{{ $item->name }}</p>
                                            @if (isset($item->options->color) && $item->options->color)
                                                <span class="text-sm">Color: {{ __($item->options->color) }}</span>
                                            @endif
                                            @if (isset($item->options->size) && $item->options->size)
                                                <span class="mx-1 text-sm">-</span>
                                                <span class="text-sm"> {{ $item->options->size }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="p-4 text-center">
                                    € {{ $item->price }}
                                </td>
                                <td class="p-4">
                                    <div class="flex items-center justify-center">
                                        @if ($item->options->size)
                                            @livewire('update-cart-item-size', ['rowId' => $item->rowId], key($item->rowId))
                                        @elseif ($item->options->color)
                                            @livewire('update-cart-item-color', ['rowId' => $item->rowId], key($item->rowId))
                                        @else
                                            @livewire('update-cart-item', ['rowId' => $item->rowId], key($item->rowId))
                                        @endif
                                        <a class="ml-4 cursor-pointer text-gray-500 hover:text-red-600"
                                            wire:click="delete('{{ $item->rowId }}')" wire:loading.class="text-red-600"
                                            wire:target="delete('{{ $item->rowId }}')">
                                            <i class="fa-solid fa-trash"></i>
                                        </a>
                                    </div>
                                </td>
                                <td class="p-4 text-center">
                                    € {{ $item->price * $item->qty }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Vista Móvil (Tarjetas) - Visible solo en pantallas pequeñas (hasta lg) --}}
            <div class="lg:hidden">
                <ul class="space-y-4">
                    @foreach (Cart::content() as $item)
                        <li class="rounded-lg bg-white shadow">
                            <article class="flex p-4">
                                <img class="h-20 w-20 flex-shrink-0 object-cover" src="{{ $item->options->image }}"
                                    alt="{{ $item->name }}">
                                <div class="ml-4 flex-1">
                                    <p class="font-bold">{{ $item->name }}</p>
                                    <p class="text-sm">€ {{ $item->price }}</p>
                                    @if (isset($item->options->color) && $item->options->color)
                                        <p class="text-xs">Color: {{ __($item->options->color) }}</p>
                                    @endif
                                    @if (isset($item->options->size) && $item->options->size)
                                        <p class="text-xs">Talla: {{ $item->options->size }}</p>
                                    @endif
                                </div>
                                <div class="ml-2 text-right">
                                    <p class="text-lg font-semibold">€ {{ $item->price * $item->qty }}</p>
                                </div>
                            </article>
                            <div class="flex items-center justify-between bg-gray-50 p-2">
                                @if ($item->options->size)
                                    @livewire('update-cart-item-size', ['rowId' => $item->rowId], key('mobile-size-' . $item->rowId))
                                @elseif ($item->options->color)
                                    @livewire('update-cart-item-color', ['rowId' => $item->rowId], key('mobile-color-' . $item->rowId))
                                @else
                                    @livewire('update-cart-item', ['rowId' => $item->rowId], key('mobile-item-' . $item->rowId))
                                @endif
                                <a class="cursor-pointer text-gray-500 hover:text-red-600"
                                    wire:click="delete('{{ $item->rowId }}')" wire:loading.class="text-red-600"
                                    wire:target="delete('{{ $item->rowId }}')">
                                    <i class="fa-solid fa-trash"></i>
                                </a>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>

            <div class="mt-4 border-t border-gray-200 pt-4">
                <a class="inline-block cursor-pointer text-sm text-gray-600 hover:text-red-600" wire:click="destroy">
                    <i class="fa-solid fa-trash"></i>
                    Borrar carrito de compras
                </a>
            </div>
        @else
            {{-- El carrito esta vacio --}}
            <div class="relative p-6">
                <img src="{{ asset('storage/images/escaparate-fondo.jpg') }}" alt="Escaparate de tienda"
                    class="rounded-4xl absolute inset-0 h-full w-full object-cover py-1 opacity-40 shadow-2xl blur-none">
                <div class="relative z-10 flex flex-col items-center justify-center">
                    <x-cart-shopping size="w-24 h-24" color="lime-700" />
                    <p class="mt-4 text-lg font-semibold text-gray-700">Tu carrito está vacío</p>
                    <a href="{{ route('tienda') }}"
                        class="hober:text-blue-600 mt-4 font-bold text-blue-900 hover:underline">
                        Explorar productos
                    </a>
                    <x-link-shopping />
                </div>
            </div>
        @endif
    </section>

    @if (Cart::count())
        <div class="mt-4 rounded-lg bg-white px-6 py-4 shadow-lg">
            <div class="flex flex-col items-center justify-between sm:flex-row">
                <div class="mb-4 sm:mb-0">
                    <p class="text-gray-700">
                        <span class="text-lg font-bold">Total:</span>
                        € {{ Cart::subTotal() }}
                    </p>
                </div>
                <div>
                    <flux:button href="{{ route('orders.create') }}"
                        class="w-full cursor-pointer border-2 border-lime-400 bg-gray-500 hover:bg-lime-500 sm:w-auto"
                        variant="primary">
                        Completar el pedido
                    </flux:button>
                </div>
            </div>
        </div>
    @endif
</div>
