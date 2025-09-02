<div class="mt-8">
    <h2 class="mb-4 text-2xl font-bold text-gray-700">Escribe una reseña</h2>

    <div class="rounded-lg bg-white p-6 shadow-md">
        <form wire:submit.prevent="save">
            <div class="mb-4">
                <label for="rating" class="block text-sm font-medium text-gray-700">Calificación</label>
                {{-- Sistema de estrellas simple --}}
                <div class="mt-1 flex items-center">
                    @for ($i = 1; $i <= 5; $i++)
                        <label for="rating-{{ $i }}" class="cursor-pointer">
                            <input type="radio" id="rating-{{ $i }}" name="rating"
                                value="{{ $i }}" wire:model.defer="rating" class="sr-only">
                            <svg class="h-8 w-8" fill="{{ $i <= $rating ? 'currentColor' : 'none' }}"
                                viewBox="0 0 24 24" stroke="currentColor"
                                wire:click="$set('rating', {{ $i }})">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.196-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118L2.05 10.1c-.783-.57-.38-1.81.588-1.81h4.914a1 1 0 00.95-.69L11.049 2.927z" />
                            </svg>
                        </label>
                    @endfor
                </div>
                <x-input-error for="rating" class="mt-2" />
            </div>

            <div class="mb-4">
                <label for="comment" class="block text-sm font-medium text-gray-700">Comentario</label>
                <textarea id="comment" wire:model.defer="comment" rows="4"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                    placeholder="Escribe tu opinión sobre el producto..."></textarea>
                <x-input-error for="comment" class="mt-2" />
            </div>

            <div class="flex justify-end">
                <button type="submit"
                    class="rounded-md bg-blue-600 px-4 py-2 text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                    Publicar Reseña
                </button>
            </div>
        </form>
    </div>
</div>
