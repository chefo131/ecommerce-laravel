<div x-data>
    <div>

        <p class="text-xl text-gray-700">
            Talla:
        </p>
        <flux:select class="select-color mt-4 h-auto" wire:model.live="size_id" placeholder="">
            <flux:select.option value="" selected disabled>
                Seleccionar una talla...
            </flux:select.option>
            @foreach ($sizes as $size)
                <flux:select.option value="{{ $size->id }}">{{ $size->name }}</flux:select.option>
            @endforeach
        </flux:select>
    </div>

    <p class="my-4 text-gray-700">
        <span class="text-lg font-semibold">Stock:</span>
        {{-- $quantity siempre reflejará el stock relevante (total producto, talla, o talla/color) --}}
        {{ $quantity }}
    </p>

    {{-- Mostrar el desglose de stock por color SI una talla está seleccionada y tiene colores --}}
    @if ($size_id && count($colors) > 0)
        <p class="my-4 text-gray-700">
            <span class="text-lg font-semibold">Desglose de stock para la talla
                {{ $sizes->firstWhere('id', $size_id)->name ?? '' }}:</span>
        </p>
        <ul class="list-inside list-disc">
            {{-- Iteramos sobre la propiedad $colors del componente, que contiene los colores de la talla seleccionada --}}
            @foreach ($colors as $color_item)
                <li>
                    {{ __($color_item->name) }}:
                    {{-- Acceder de forma segura a la cantidad del pivote, por defecto 0 si no está disponible --}}
                    {{ $color_item->pivot ? $color_item->pivot->quantity ?? 0 : 0 }} unidades
                </li>
            @endforeach
        </ul>
    @endif


    <div>
        <p class="text-xl text-gray-700">
            Color:
        </p>
        <flux:select wire:model.live="color_id" class="select-color mt-4 h-auto w-full" placeholder="">
            <flux:select.option value="" selected disabled x-bind:disabled="!$wire.size_id">
                Seleccionar un color...
            </flux:select.option>
            @foreach ($colors as $color)
                <flux:select.option class="capitalize" value="{{ $color->id }}">{{ __($color->name) }}
                </flux:select.option>
            @endforeach
        </flux:select>
    </div>

    <div class="mt-4 flex items-center justify-center rounded bg-white shadow-2xl">
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
            <flux:button
                x-bind:disabled="!$wire.size_id || !$wire.color_id || $wire.quantity <= 0 || $wire.qty > $wire.quantity"
                wire:click="addItem" wire:loading.attr="disabled" wire:target="addItem"
                class="w-full cursor-pointer border-2 border-lime-400 bg-gray-500 hover:border-gray-700 hover:bg-lime-500"
                variant="primary">Agregar al carrito de compras
            </flux:button>
        </div>
    </div>
    {{-- **IMPORTANTE:** Mostrar la cantidad disponible --}}
    @if ($quantity > 0)
        <p class="mt-2 text-lg font-semibold text-gray-700">
            Stock: {{ $quantity }} unidades disponibles
            @if ($size_id && $color_id)
                para la talla y color seleccionados.
            @endif
        </p>
    @elseif ($size_id && $color_id && $quantity == 0)
        <p class="mt-2 text-sm text-red-600">
            No hay stock disponible para la talla y color seleccionados.
        </p>
    @endif
</div>
