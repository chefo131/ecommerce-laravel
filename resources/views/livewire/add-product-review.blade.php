<div class="mt-6 rounded-lg bg-white p-6 shadow-md dark:bg-gray-800">
    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">Escribe tu reseña</h3>
    <p class="text-sm text-gray-600 dark:text-gray-400">Comparte tu opinión sobre este producto.</p>

    <form wire:submit="saveReview" class="mt-4 space-y-4">
        {{-- Calificación con estrellas --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Calificación</label>
            <div class="flex items-center space-x-1">
                @for ($i = 1; $i <= 5; $i++)
                    <i wire:click="$set('rating', {{ $i }})"
                        class="fa-solid fa-star {{ $rating >= $i ? 'text-yellow-400' : 'text-gray-300 dark:text-gray-600' }} cursor-pointer text-2xl transition-colors duration-150">
                    </i>
                @endfor
            </div>
            <x-input-error for="rating" class="mt-2" />
        </div>

        {{-- Comentario --}}
        <div>
            <x-label for="comment" value="Tu opinión" />
            <x-textarea id="comment" wire:model="comment" class="mt-1 block w-full" rows="4"
                placeholder="Cuéntanos qué te ha parecido el producto..."></x-textarea>
            <x-input-error for="comment" class="mt-2" />
        </div>

        {{-- Botón de enviar --}}
        <div class="flex justify-end">
            <x-button type="submit" wire:loading.attr="disabled">
                <span wire:loading.remove wire:target="saveReview">Enviar Reseña</span>
                <span wire:loading wire:target="saveReview">Enviando...</span>
            </x-button>
        </div>
    </form>
</div>
