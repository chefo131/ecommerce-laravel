<div x-data="{ open: false }" class="">
    {{-- El span actúa como el disparador (trigger) para abrir/cerrar --}}
    <span class="relative inline-block cursor-pointer" @click="open = !open">
        {{-- Contenedor del icono y el badge --}}
        <a
            class="flex h-10 w-10 items-center justify-center rounded-md border border-lime-500 transition duration-150 ease-in-out hover:border-lime-400 hover:bg-lime-200 dark:border-lime-600 dark:hover:border-lime-500 dark:hover:bg-lime-700">
            {{-- Usamos el componente Blade del icono del carrito --}}
            <x-cart class="text-dark-700 h-10 w-10 items-center justify-center dark:text-lime-200" />
        </a>
        @if (Cart::count())
            <span
                class="absolute right-0 top-0 inline-block h-6 w-6 -translate-y-1/2 translate-x-1/2 transform rounded-full bg-red-600 p-1.5 text-center text-xs font-bold leading-none text-red-100">{{ Cart::count() }}</span>
        @else
            <span
                class="absolute right-0 top-0 inline-block h-2 w-2 -translate-y-1/2 translate-x-1/2 transform rounded-full bg-red-600"></span>
        @endif

        {{-- Badge (contador) - Eventualmente será dinámico con Livewire --}}

    </span>

    {{-- El contenido del dropdown --}}
    <div x-show="open" x-cloak @click.away="open = false"
        class="scroll-cart absolute right-0 z-10 mt-2 w-64 rounded-md bg-white shadow-lg dark:bg-zinc-800">
        {{-- Añadido z-10 por si acaso --}}
        <!-- Contenido del carrito aquí - Eventualmente vendrá de $this en DropdownCart.php -->
        <ul>
            @forelse (Cart::content() as $item)
                <li class="flex w-auto border-b border-lime-400 p-2">
                    <img class="h-15 w-20 object-cover" src="{{ $item->options->image }}" alt="{{ $item->name }}">
                    <article class="flex-1 p-2.5">
                        <h1 class="font-bold">{{ $item->name }}</h1>
                        <div>
                            <p>Cant: {{ $item->qty }}</p>
                            @isset($item->options['color'])
                                <p> Color: {{ __($item->options['color']) }}</p>
                            @endisset
                            @isset($item->options['size'])
                                <p> Talla: {{ __($item->options['size']) }}</p>
                            @endisset
                        </div>
                        <p>€ {{ $item->price }}</p>
                        </h1>
                    </article>
                </li>
            @empty
                <li>
                    <p class="p-4">Tu carrito está vacío.</p>
                </li>
            @endforelse
        </ul>
        @if (Cart::count())
            <div class="px-3 py-2">
                <p class="mt-2 text-lg text-gray-700">
                    <span class="font-bold">Total:</span>
                    € {{ Cart::subtotal() }}
                </p>
                <flux:button href="{{ route('shopping-cart') }}" variant="primary"
                    class="w-full cursor-pointer border-2 border-lime-400 bg-gray-500 hover:bg-lime-500">
                    Ir al carrito de compras
                </flux:button>
            </div>
        @endif
    </div>
</div>
