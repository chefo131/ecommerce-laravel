<div>
    {{-- Estado de Carga: Se muestra mientras Livewire procesa inicialmente --}}
    {{-- .delay.longer evita que el spinner aparezca y desaparezca muy rápido en conexiones veloces --}}
    <div wire:loading.delay.longer class="flex min-h-[300px] items-center justify-center py-10"> {{-- Ajusta min-height según sea necesario --}}
        {{-- Spinner SVG simple --}}
        <svg class="h-12 w-12 animate-spin text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none"
            viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor"
                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
            </path>
        </svg>
    </div>
    {{-- Contenido Principal: Se muestra cuando Livewire ha terminado de cargar --}}
    <div wire:loading.remove.delay.longer>
        @if ($products->isNotEmpty())
            {{-- Usamos Alpine (x-data) para que, en cuanto este div se inicialice (x-init),
                 llame a nuestra función de Glider, pasándose a sí mismo ($el) como argumento.
                 Esto es más robusto que depender solo de eventos globales. --}}
            {{-- ELIMINAMOS x-init para dejar que app.js controle la inicialización y evitar conflictos. --}}
            <div class="glider-contain" data-glider-container>
                <ul class="glider" wire:ignore> {{-- IMPORTANTE: Evita que Livewire interfiera con Glider --}}
                    @foreach ($products as $product)
                        <li class="rounded-lg bg-white shadow sm:mr-4">
                            <article>
                                {{-- Aplicar aspect-ratio dinámicamente o un valor fijo --}}
                                {{-- Usamos un aspect-ratio fijo y object-cover para un diseño consistente --}}
                                <figure class="aspect-[4/3] overflow-hidden bg-gray-200">
                                    @if ($product->getFirstMedia('products'))
                                        <img class="h-full w-full object-cover"
                                            src="{{ $product->getFirstMediaUrl('products') }}"
                                            alt="{{ $product->name }}">
                                    @else
                                        {{-- Placeholder si el producto no tiene imagen --}}
                                        <div class="flex h-full w-full items-center justify-center bg-gray-200">
                                            <svg class="h-12 w-12 text-gray-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                </path>
                                            </svg>
                                        </div>
                                    @endif
                                </figure>
                            </article>
                            <div class="px-6 py-4">
                                {{-- Aplicamos min-h-14 (3.5rem) para reservar espacio para dos líneas de texto (text-lg tiene line-height: 1.75rem) --}}
                                <h1 class="min-h-14 text-lg font-semibold text-gray-700 dark:text-lime-500">
                                    {{-- TODO: Enlace a la página del producto --}}
                                    <a href="{{ route('products.show', $product) }}">
                                        {{ Str::limit($product->name, 20) }}
                                    </a>

                                </h1>
                                <p class="font-bold text-gray-700 dark:text-lime-500">€ {{ $product->price }}</p>
                            </div>
                        </li>
                    @endforeach
                </ul>

                <button aria-label="Previous" class="glider-prev">«</button>
                <button aria-label="Next" class="glider-next">»</button>
                <div role="tablist" class="dots"></div>
            </div>
        @else
            <div class="py-10 text-center text-gray-500">
                No hay productos disponibles en esta categoría por el momento.
            </div>
        @endif
    </div>
</div>
