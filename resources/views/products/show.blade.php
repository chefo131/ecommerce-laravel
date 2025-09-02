<x-layouts.app>
    <div class="container py-4 md:py-8">
        {{-- Estructura principal: 1 columna en móvil, 2 en pantallas medianas y grandes --}}
        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">

            {{-- Columna Izquierda: Galería de Imágenes --}}
            <div>
                {{-- Solo mostramos el slider si el producto tiene imágenes --}}
                @if ($product->getMedia('products')->count())
                    <div class="flexslider rounded-lg bg-white shadow">
                        <ul class="slides">
                            @foreach ($product->getMedia('products') as $media)
                                <li data-thumb="{{ $media->getUrl('thumb') }}">
                                    <img src="{{ $media->getUrl() }}" />
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>

            {{-- Columna Derecha: Información del Producto y Compra --}}
            <div>
                <h1 class="text-2xl font-bold text-gray-700 md:text-3xl">{{ $product->name }}</h1>
                {{-- Hacemos que este contenedor sea flexible, en columna en móvil y en fila en pantallas pequeñas+ --}}
                <div class="mt-2 flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                    <p class="text-base font-semibold text-gray-700 md:text-lg">Marca: {{ $product->brand->name }}</p>
                    <div class="flex items-center">
                        <ul class="flex text-sm">
                            <li><i class="fa-solid fa-star text-yellow-400"></i></li>
                            <li><i class="fa-solid fa-star text-yellow-400"></i></li>
                            <li><i class="fa-solid fa-star text-yellow-400"></i></li>
                            <li><i class="fa-solid fa-star text-yellow-400"></i></li>
                            <li><i class="fa-solid fa-star text-yellow-400"></i></li>
                        </ul>
                        <span class="ml-2 text-xs text-gray-700 md:text-sm">(24 reseñas)</span>
                    </div>
                </div>

                <p class="mt-4 text-xl font-semibold text-gray-700 md:text-2xl">€{{ $product->price }}</p>

                <div class="mt-6 rounded-lg bg-white p-4 shadow md:p-6">
                    {{-- Carga condicional del componente de añadir al carrito --}}
                    @if ($product->subcategory?->category?->features['size'])
                        @livewire('add-cart-item-size', ['product' => $product])
                    @elseif ($product->subcategory?->category?->features['color'])
                        {{-- Suponiendo que tienes un componente para solo color --}}
                        @livewire('add-cart-item-color', ['product' => $product])
                    @else
                        @livewire('add-cart-item', ['product' => $product])
                    @endif
                </div>

                <div class="mt-6 rounded-lg bg-white p-4 shadow md:p-6">
                    <h2 class="mb-2 text-lg font-bold text-gray-700">Descripción</h2>
                    <div class="prose">
                        {!! $product->description !!}
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- Script para inicializar FlexSlider --}}
    @push('scripts')
        <script>
            $(window).on('load', function() {
                $('.flexslider').flexslider({
                    animation: "slide",
                    controlNav: "thumbnails"
                });
            });
        </script>
    @endpush

</x-layouts.app>
