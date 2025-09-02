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

        {{-- BLOQUE DE DEPURACIÓN (NIVEL 2) --}}
        @auth
            <div
                class="my-4 rounded-lg border-2 border-purple-500 bg-purple-100 p-4 dark:border-purple-400 dark:bg-purple-900/30">
                <h3 class="font-bold text-purple-800 dark:text-purple-200">--- ZONA DE DEPURACIÓN (NIVEL 2) ---</h3>
                <p class="text-sm text-purple-700 dark:text-purple-300">Usuario autenticado: {{ auth()->user()->name }} (ID:
                    <strong class="text-lg">{{ auth()->user()->id }}</strong>)
                </p>
                <hr class="my-2 border-purple-300">
                <p class="text-sm text-purple-700 dark:text-purple-300">Buscando pedidos para el usuario con ID: <strong
                        class="text-lg">{{ auth()->user()->id }}</strong></p>
                <p class="text-sm text-purple-700 dark:text-purple-300">Condición: `status` debe ser
                    `{{ \App\Models\Order::ENTREGADO }}`</p>
                <p class="text-sm text-purple-700 dark:text-purple-300">Condición: El pedido debe contener el producto con
                    ID `{{ $product->id }}`</p>
                <hr class="my-2 border-purple-300">
                @php
                    $orderCount = auth()
                        ->user()
                        ->orders()
                        ->where('status', \App\Models\Order::ENTREGADO)
                        ->whereHas('products', fn($query) => $query->where('product_id', $product->id))
                        ->count();
                @endphp
                <p class="font-semibold text-purple-800 dark:text-purple-200">Resultado de la búsqueda: <strong
                        class="text-lg">{{ $orderCount }}</strong> órdenes encontradas que cumplen todas las condiciones.
                </p>

                @if ($orderCount == 0)
                    <p class="mt-2 text-xs text-purple-600 dark:text-purple-400">
                        <strong>Pista:</strong> Si el resultado es 0, ve a phpMyAdmin, busca la tabla `orders`, encuentra la
                        orden #51 y comprueba si el valor en la columna `user_id` es realmente `{{ auth()->user()->id }}`.
                        ¡Es muy probable que no coincidan!
                    </p>
                @endif
                <h3 class="mt-2 font-bold text-purple-800 dark:text-purple-200">--- FIN DE DEPURACIÓN ---</h3>
            </div>
        @endauth

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
                    <div class="rounded-lg bg-white p-4 shadow dark:bg-gray-800">
                        <div class="mb-2 flex items-center">
                            <div
                                class="mr-3 flex h-10 w-10 items-center justify-center rounded-full bg-blue-500 font-bold text-white">
                                {{ $review->user->initials() }}
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900 dark:text-white">{{ $review->user->name }}</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ $review->created_at->format('d/m/Y') }}</p>
                            </div>
                        </div>
                        <div class="mb-2 flex items-center">
                            @for ($i = 1; $i <= 5; $i++)
                                <svg class="{{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }} h-5 w-5"
                                    fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                            @endfor
                        </div>
                        <p class="text-gray-600 dark:text-gray-300">{{ $review->comment }}</p>
                    </div>
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
