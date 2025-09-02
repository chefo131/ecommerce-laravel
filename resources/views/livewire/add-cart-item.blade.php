<div x-data>
    <p class="mb-4 text-gray-700">
        <span class="mx-2 text-lg font-semibold">Stock:</span>{{ $quantity }}
    </p>
    <div class="flex items-center justify-center rounded bg-white shadow-2xl">
        <div class="m-4">
            <flux:button class="cursor-pointer hover:bg-lime-500" variant="primary" disabled
                x-bind:disabled="$wire.qty <= 1" wire:loading.attr="disabled" wire:target="decrement"
                wire:click="decrement">
                -
            </flux:button>

            <span class="m-2 text-gray-700">{{ $qty }}</span>

            <flux:button class="cursor-pointer hover:bg-lime-500" variant="primary"
                x-bind:disabled="$wire.qty >= $wire.quantity" wire:loading.attr="disabled" wire:target="increment"
                wire:click="increment">
                +
            </flux:button>
        </div>
        <div class="flex-1">
            <flux:button wire:click="addItem" wire:loading.attr="disabled" wire:target="addItem"
                x-bind:disabled="$wire.qty > $wire.quantity"
                class="w-full cursor-pointer border-2 border-lime-400 bg-gray-500 hover:bg-lime-500" variant="primary">
                Agregar al carrito de compras
            </flux:button>
        </div>
    </div>
    {{-- **IMPORTANTE:** Mostrar la cantidad disponible --}}
    @if ($quantity > 0)
        <p class="mt-2 text-lg font-semibold text-gray-700">
            Stock: {{ $quantity }} unidades disponibles.
        </p>
    @else
        {{-- $quantity == 0 --}}
        <p class="mt-2 text-sm text-red-600">
            No hay stock disponible para este producto.
        </p>
    @endif
</div>
