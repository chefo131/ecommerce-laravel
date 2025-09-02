<div class="flex items-center" x-data>
    <flux:button class="cursor-pointer hover:bg-lime-500" variant="primary" disabled x-bind:disabled="$wire.qty <= 1"
        wire:loading.attr="disabled" wire:target="decrement" wire:click="decrement">
        -
    </flux:button>

    <span class="m-2 text-gray-700">{{ $qty }}</span>

    <flux:button class="cursor-pointer hover:bg-lime-500" variant="primary" x-bind:disabled="$wire.qty >= $wire.quantity"
        wire:loading.attr="disabled" wire:target="increment" wire:click="increment">
        +
    </flux:button>
</div>
