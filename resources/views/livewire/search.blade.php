<div class="relative flex-1" wire:click.outside="hideResults">
    <div
        class="relative flex items-center rounded-md bg-gray-50 shadow-sm focus-within:ring-2 focus-within:ring-lime-500 dark:bg-gray-800 dark:focus-within:ring-lime-600">
        {{-- Usaremos un input HTML estándar por ahora para asegurar la aplicación de estilos.
                 Si tienes un componente flux:input y quieres usarlo, asegúrate de que propaga las clases correctamente. --}}
        <form autocomplete="off">
            <input name="name" wire:model.live="searchTerm" type="text" placeholder="Buscar..."
                class="w-full min-w-0 flex-1 border-none bg-transparent px-3 py-2 pr-12 text-sm leading-tight text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-0 dark:text-gray-100 dark:placeholder-gray-400" />
            {{-- El botón es visual; la búsqueda se activa al escribir gracias a wire:model.live --}}
            <button type="text"
                class="absolute inset-y-0 right-0 z-10 flex items-center justify-center rounded-r-md border border-white bg-gray-400 px-3 text-white hover:bg-lime-300 focus:outline-none dark:border-gray-700 dark:bg-lime-600 dark:hover:bg-lime-700">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64" class="h-5 w-5 fill-current">
                    <path
                        d="M 28.300781 10.800781 C 17.100781 10.800781 7.9003906 20.000391 7.9003906 31.400391 C 7.9003906 42.800391 17.000781 52 28.300781 52 C 33.500781 52 38.200781 50.000781 41.800781 46.800781 L 43 48 L 41.900391 49.099609 C 41.300391 49.699609 41.300391 50.700781 41.900391 51.300781 L 50.900391 60.400391 C 51.200391 60.700391 51.6 60.900391 52 60.900391 C 52.4 60.900391 52.8 60.800391 53 60.400391 L 57 56.400391 C 57.5 55.700391 57.500391 54.799219 56.900391 54.199219 L 47.900391 45.099609 C 47.600391 44.799609 47.200781 44.699219 46.800781 44.699219 C 46.400781 44.699219 46.000781 44.799609 45.800781 45.099609 L 44.800781 46.199219 L 43.599609 45 C 46.799609 41.3 48.699219 36.600391 48.699219 31.400391 C 48.699219 20.000391 39.500781 10.800781 28.300781 10.800781 z M 28.300781 13.900391 C 37.900781 13.900391 45.699219 21.8 45.699219 31.5 C 45.699219 41.2 37.900781 49 28.300781 49 C 18.700781 49 10.900391 41.2 10.900391 31.5 C 10.900391 21.8 18.700781 13.900391 28.300781 13.900391 z M 28.400391 20.099609 C 23.600391 20.099609 19.400781 23.099609 17.800781 27.599609 C 17.500781 28.299609 17.9 29.100781 18.5 29.300781 C 18.6 29.300781 18.8 29.400391 19 29.400391 C 19.5 29.400391 20.000781 29 20.300781 28.5 C 21.500781 25 24.800391 22.699219 28.400391 22.699219 C 29.100391 22.699219 29.699219 22.100391 29.699219 21.400391 C 29.699219 20.700391 29.100391 20.099609 28.400391 20.099609 z M 18.900391 32.5 C 18.200391 32.5 17.599609 33.000781 17.599609 33.800781 L 17.599609 34 C 17.599609 34.7 18.100391 35.300781 18.900391 35.300781 C 19.600391 35.300781 20.199219 34.7 20.199219 34 L 20.199219 33.800781 C 20.199219 33.000781 19.700391 32.5 18.900391 32.5 z M 46.900391 48.300781 L 53.800781 55.300781 L 51.900391 57.199219 L 45 50.199219 L 46.900391 48.300781 z">
                    </path>
                </svg>
            </button>
        </form>
    </div>
    {{-- Mostrar resultados solo si hay un término de búsqueda --}}
    @if ($showResults)
        <div class="absolute z-10 mt-1 w-full">
            <div class="rounded-lg bg-white shadow-lg">
                <div class="max-h-96 space-y-1 overflow-y-auto px-4 py-3"> {{-- Ajustado space-y y añadido max-h con overflow --}}
                    @forelse ($products as $product)
                        <a href="{{ route('products.show', $product) }}" wire:click="hideResults"
                            class="flex items-center rounded-md p-2 hover:bg-gray-100 dark:hover:bg-gray-700">
                            @if ($product->getFirstMedia('products'))
                                <img class="h-16 w-20 flex-shrink-0 object-cover"
                                    src="{{ $product->getFirstMediaUrl('products') }}" alt="{{ $product->name }}">
                            @else
                                <div
                                    class="flex h-16 w-20 flex-shrink-0 items-center justify-center bg-gray-200 text-xs text-gray-400">
                                    Sin Imagen</div>
                            @endif
                            <div class="ml-4 text-gray-700 dark:text-gray-300">
                                <p class="text-lg font-semibold leading-5">{{ $product->name }}</p>


                                @if ($product->subcategory?->category?->name)
                                    <p class="text-sm">Categoría:
                                        {{ $product->subcategory->category->name }}</p>
                                @endif
                                <p class="text-sm">Precio:
                                    ${{ number_format($product->price, 2) }}
                                </p>

                            </div>
                        </a>
                    @empty
                        <div class="px-2 py-3 text-sm text-gray-500 dark:text-gray-400">
                            No se encontraron productos para "{{ $searchTerm }}".
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    @endif
</div>
