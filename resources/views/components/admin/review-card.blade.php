@props(['review'])

<div class="rounded-lg bg-white p-6 shadow-lg dark:bg-gray-800">
    <div class="flex items-start">
        <div class="flex-1">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-900 dark:text-white">
                        {{ $review->user->name }}
                    </p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">
                        {{ $review->created_at->diffForHumans() }}
                    </p>
                </div>
                <div class="flex items-center">
                    @for ($i = 1; $i <= 5; $i++)
                        <i
                            class="fa-solid fa-star {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}"></i>
                    @endfor
                </div>
            </div>
            <p class="mt-2 text-sm text-gray-600 dark:text-gray-300">
                <span class="font-semibold">Producto:</span>
                <a href="{{ route('products.show', $review->product) }}" target="_blank"
                    class="text-lime-600 hover:underline">
                    {{ $review->product->name }}
                </a>
            </p>
            <p class="mt-2 text-gray-700 dark:text-gray-200">
                {{ $review->comment }}
            </p>
        </div>
    </div>

    @if ($review->status == \App\Models\Review::PENDIENTE)
        <div class="mt-4 flex justify-end space-x-3 border-t border-gray-200 pt-4 dark:border-gray-700">
            <button wire:click="reject({{ $review->id }})" wire:loading.attr="disabled"
                class="rounded-md bg-red-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                Rechazar
            </button>
            <button wire:click="approve({{ $review->id }})" wire:loading.attr="disabled"
                class="rounded-md bg-green-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                Aprobar
            </button>
        </div>
    @endif
</div>
