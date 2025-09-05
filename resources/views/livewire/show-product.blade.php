<div class="container py-8">
    <div class="grid grid-cols-1 gap-8 md:grid-cols-2">

        {{-- Columna Izquierda: Imagen del Producto --}}
        <div>
            <figure class="overflow-hidden rounded-lg shadow-lg">
                <img class="aspect-[1/1] w-full object-cover" src="{{ $product->getFirstMediaUrl('products') }}"
                    alt="{{ $product->name }}">
            </figure>
        </div>

        {{-- Columna Derecha: Detalles del Producto --}}
        <div>
            <h1 class="text-3xl font-bold text-gray-800 dark:text-white">{{ $product->name }}</h1>
            <div class="mt-2 flex items-center">
                <p class="text-2xl font-semibold text-gray-700 dark:text-gray-300">€{{ $product->price }}</p>
                <p class="ml-4 text-sm text-gray-500 dark:text-gray-400">Marca: {{ $product->brand->name }}</p>
            </div>

            <div class="mt-6 rounded-lg bg-white p-6 shadow-md dark:bg-gray-800">
                {{-- Componente para añadir al carrito --}}
                @if ($product->subcategory->category->size)
                    @livewire('add-cart-item-size', ['product' => $product], key('add-cart-size-' . $product->id))
                @elseif($product->subcategory->category->color)
                    @livewire('add-cart-item-color', ['product' => $product], key('add-cart-color-' . $product->id))
                @else
                    @livewire('add-cart-item', ['product' => $product], key('add-cart-' . $product->id))
                @endif
            </div>

            <div class="mt-6">
                <h2 class="text-xl font-bold text-gray-700 dark:text-white">Descripción</h2>
                <div class="prose mt-2 text-gray-600 dark:text-gray-300">
                    {!! $product->description !!}
                </div>
            </div>
        </div>
    </div>

    {{-- SECCIÓN DE RESEÑAS (REESTRUCTURADA Y CORREGIDA) --}}
    <div class="mt-12">
        <h2 class="mb-4 text-2xl font-bold text-gray-700 dark:text-white">Reseñas del Producto</h2>

        {{-- FORMULARIO PARA AÑADIR RESEÑA (si el usuario puede) --}}
        @auth
            @if ($product->canBeReviewed())
                {{-- ¡OJO! El nombre del componente es 'add-product-review', no 'add-review' --}}
                @livewire('add-product-review', ['product' => $product], key('add-review-' . $product->id))
            @endif
        @endauth

        {{-- LISTADO DE RESEÑAS EXISTENTES --}}
        @if ($product->approvedReviews->isNotEmpty())
            <div class="mt-6 space-y-6">
                @foreach ($product->approvedReviews as $review)
                    <x-review-card :review="$review" wire:key="review-{{ $review->id }}" />
                @endforeach
            </div>
        @else
            {{-- MENSAJE SI NO HAY RESEÑAS --}}
            {{-- No mostramos este mensaje si el formulario ya está visible para el usuario --}}
            @if (!auth()->check() || !$product->canBeReviewed())
                <div class="mt-6 rounded-lg bg-white p-6 text-center shadow-md dark:bg-gray-800">
                    <p class="text-gray-600 dark:text-gray-400">Este producto aún no tiene reseñas. ¡Sé el primero en
                        opinar!</p>
                </div>
            @endif
        @endif
    </div>
</div>
