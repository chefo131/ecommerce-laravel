<div>
    {{-- Card --}}
    <div class="mb-6 rounded-lg bg-white shadow-lg">
        <div class="flex items-center justify-between px-6 py-2">
            <h1 class="font-semibold uppercase text-gray-700">{{ $category->name }}</h1>
            <div class="grid grid-cols-2 gap-2 divide-x divide-gray-700 border border-gray-700 text-gray-700">
                <i wire:click="$set('view', 'grid')"
                    class="fa-solid fa-border-all {{ $view == 'grid' ? 'text-lime-500' : '' }} cursor-pointer p-3"></i>
                <i wire:click="$set('view', 'list')"
                    class="fa-solid fa-list {{ $view == 'list' ? 'text-lime-500' : '' }} cursor-pointer p-3 hover:text-gray-500"></i>
            </div>
        </div>
    </div>

    {{-- Subcategorías y marcas --}}

    <div class="mt-4 grid grid-cols-1 gap-6 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5">
        <aside>
            <h2 class="mb-4 text-center font-semibold">Subcategorías</h2>
            <ul class="space-y-1">
                @foreach ($category->subcategories as $subcategory)
                    <li wire:click="$set('subcategoriaSeleccionada', '{{ $subcategory->slug }}')"
                        @class([
                            'cursor-pointer block px-3 py-1 text-sm rounded-md transition-colors capitalize',
                            'bg-lime-200 text-lime-800 font-semibold' =>
                                $subcategoriaSeleccionada == $subcategory->slug,
                            'text-gray-600 hover:bg-gray-100 hover:text-lime-700' =>
                                $subcategoriaSeleccionada != $subcategory->slug,
                        ])>
                        {{ $subcategory->name }}
                    </li>
                @endforeach
            </ul>
            <h2 class="mb-4 mt-4 text-center font-semibold">Marcas</h2>
            <ul class="divide-y divide-gray-700">
                @foreach ($category->brands as $brand)
                    <li class="py-2 text-sm">
                        <a class="{{ $marca == $brand->name ? 'text-lime-700 font-semibold' : '' }} cursor-pointer capitalize hover:text-lime-700"
                            wire:click="$set('marca', '{{ $brand->name }}')">
                            {{ $brand->name }}
                        </a>
                    </li>
                @endforeach
            </ul>
            <flux:button variant="primary" wire:click="limpiar" class="mt-4 cursor-pointer hover:bg-lime-700">
                Eliminar filtros
            </flux:button>
        </aside>

        {{-- Productos grid --}}

        <div class="md:col-span-2 lg:col-span-4">
            @if ($view == 'grid')
                <ul class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-4">
                    @foreach ($products as $product)
                        <li class="rounded-lg bg-white shadow sm:mr-4">
                            <article>
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
                                <h1 class="min-h-14 text-lg font-semibold">

                                    <a href="{{ route('products.show', $product) }}"> {{-- TODO: Enlace a la página del producto --}}
                                        {{ Str::limit($product->name, 20) }}
                                    </a>

                                </h1>
                                <p class="font-bold text-gray-700">€ {{ $product->price }}</p>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @else
                {{-- Productos list --}}
                <ul class="space-y-6">
                    @foreach ($products as $product)
                        <x-product-list :product="$product" /> {{-- componente de blade con la información de productos --}}
                    @endforeach
                </ul>
            @endif
            <div class="mt-4">
                {{ $products->links() }}
            </div>
        </div>
    </div>
</div>
