<div x-data>

    <p class="text-xl text-gray-700">Color:</p>
    <flux:select class="select-color mt-4 h-auto" wire:model.live="color_id" placeholder="">
        <flux:select.option value="" selected disabled>Seleccionar un color...
        </flux:select.option>
        @foreach ($colors as $color)
            <flux:select.option value="{{ $color->id }}">{{ __($color->name) }}</flux:select.option>
        @endforeach
    </flux:select>

    <p class="my-4 text-gray-700">
        <span class="text-lg font-semibold">Stock:</span>
        @if ($quantity)
            {{ $quantity }}
        @else
            {{ $product->stock }}
        @endif
    </p>

    <div class="flex items-center justify-center rounded bg-white shadow-2xl">
        <div class="m-4">
            <flux:button class="cursor-pointer hover:bg-lime-500" variant="primary"
                x-bind:disabled="$wire.qty <= 1 || $wire.quantity <= 0" wire:loading.attr="disabled"
                wire:target="decrement" wire:click="decrement">
                -
            </flux:button>
            <span class="m-2 text-gray-700">{{ $qty }}</span>
            <flux:button class="cursor-pointer hover:bg-lime-500" variant="primary"
                x-bind:disabled="$wire.qty >= $wire.quantity || $wire.quantity <= 0" wire:loading.attr="disabled"
                wire:target="increment" wire:click="increment">
                +
            </flux:button>


        </div>
        <div class="flex-1">
            <flux:button x-bind:disabled="!$wire.color_id || $wire.qty > $wire.quantity" wire:click="addItem"
                wire:loading.attr="disabled" wire:target="addItem"
                class="w-full cursor-pointer border-2 border-lime-400 bg-gray-500 hover:border-gray-700 hover:bg-lime-500"
                variant="primary">Agregar al carrito de compras
            </flux:button>
        </div>
    </div>

    {{-- **IMPORTANTE:** Mostrar la cantidad disponible --}}
    @if ($quantity > 0)
        <p class="mt-2 text-lg font-semibold text-gray-700">
            Stock: {{ $quantity }} unidades disponibles para este color.
        </p>
    @elseif ($color_id != '' && $quantity == 0)
        <p class="mt-2 text-sm text-red-600">
            No hay stock disponible para este color.
        </p>
    @endif
</div>
