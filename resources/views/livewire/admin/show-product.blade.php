<div class="p-4 sm:p-6 lg:p-8">
    <div class="p-4 sm:p-6 lg:p-8">
        <div class="sm:flex sm:items-center">
            <div class="sm:flex-auto">
                <h1 class="text-xl font-semibold text-gray-900 dark:text-white">Productos</h1>
                <p class="mt-2 text-sm text-gray-700 dark:text-gray-300">
                    Un listado de todos los productos en tu tienda.
                </p>
            </div>
            <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
                {{-- Este botón será para el futuro CRUD --}}
                <a href="{{ route('admin.products.create') }}"
                    class="inline-flex items-center justify-center rounded-md border border-transparent bg-lime-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-lime-700 focus:outline-none focus:ring-2 focus:ring-lime-500 focus:ring-offset-2 sm:w-auto">
                    Añadir Producto
                </a>
            </div>
        </div>

        <div class="mb-4 mt-6">
            <input wire:model.live.debounce.300ms="searchTerm" type="text"
                placeholder="Buscar productos por nombre..."
                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-lime-500 focus:ring-lime-500 sm:text-sm dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200">
        </div>

        {{-- Este es codigo que sustituye al componente de Tailwindcss del tutorial de Laravel --}}
        <x-table-responsive>
            @if ($products->count())
                <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
                    <table class="min-w-full divide-y divide-gray-300 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-800">
                            <tr>
                                <th scope="col"
                                    class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6 dark:text-white">
                                    Imagen</th>
                                <th scope="col"
                                    class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6 dark:text-white">
                                    ID</th>
                                <th scope="col"
                                    class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-white">
                                    Nombre</th>
                                <th scope="col"
                                    class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-white">
                                    Categoría</th>
                                <th scope="col"
                                    class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-white">
                                    Subcategoría</th>
                                <th scope="col"
                                    class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-white">
                                    Estado</th>
                                <th scope="col"
                                    class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-white">
                                    Precio</th>
                                <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6">
                                    <span class="sr-only">Editar</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white dark:divide-gray-600 dark:bg-gray-700">
                            @foreach ($products as $product)
                                <tr>
                                    <td class="whitespace-nowrap py-2 pl-4 pr-3 text-sm sm:pl-6">
                                        {{-- getFirstMedia() es más eficiente que getMedia()->count() y devuelve null si no hay imagen --}}
                                        @if ($product->getFirstMedia('products'))
                                            {{-- getFirstMediaUrl() es el método correcto de Medialibrary para obtener la URL.
                                                 'thumb' es una conversión que podemos definir más adelante para optimizar. --}}
                                            <img src="{{ $product->getFirstMediaUrl('products', 'thumb') }}"
                                                alt="{{ $product->name }}" class="h-12 w-12 rounded-md object-cover">
                                        @else
                                            {{-- Placeholder si no hay imagen --}}
                                            <div
                                                class="flex h-12 w-12 items-center justify-center rounded-md bg-gray-200 dark:bg-gray-600">
                                                <svg class="h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24"
                                                    stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                            </div>
                                        @endif
                                    </td>
                                    <td
                                        class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6 dark:text-white">
                                        {{ $product->id }}</td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-gray-300">
                                        {{ $product->name }}</td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-gray-300">
                                        {{ $product->subcategory->category->name }}</td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-gray-300">
                                        {{ $product->subcategory->name }}</td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm">
                                        @php
                                            // Usamos match para una sintaxis más limpia y moderna (requiere PHP 8.0+)
                                            $statusInfo = match ((int) $product->status) {
                                                1 => [
                                                    'text' => 'Borrador',
                                                    'classes' =>
                                                        'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/50 dark:text-yellow-300',
                                                ],
                                                2 => [
                                                    'text' => 'Publicado',
                                                    'classes' =>
                                                        'bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-300',
                                                ],
                                                default => [
                                                    'text' => 'Desconocido',
                                                    'classes' =>
                                                        'bg-gray-100 text-gray-800 dark:bg-gray-600 dark:text-gray-200',
                                                ],
                                            };
                                        @endphp
                                        <span
                                            class="{{ $statusInfo['classes'] }} inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium">
                                            {{ $statusInfo['text'] }}
                                        </span>
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-gray-300">
                                        €{{ $product->price }}</td>
                                    <td
                                        class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                                        <a href="{{ route('admin.products.edit', $product) }}"
                                            class="text-lime-600 hover:text-lime-900 dark:text-lime-400 dark:hover:text-lime-200">Editar<span
                                                class="sr-only">, {{ $product->name }}</span>
                                        </a>
                                        <button wire:click="confirmDelete({{ $product->id }})"
                                            class="ml-4 font-medium text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300"
                                            title="Eliminar producto">
                                            Eliminar
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @if ($products->hasPages())
                    <div class="mt-4">
                        {{ $products->links() }}
                    </div>
                @endif
            @else
                <div class="rounded-md bg-yellow-50 p-4 dark:bg-yellow-900/20">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd"
                                    d="M8.485 2.495c.673-1.167 2.357-1.167 3.03 0l6.28 10.875c.673 1.167-.17 2.625-1.516 2.625H3.72c-1.347 0-2.189-1.458-1.515-2.625L8.485 2.495zM10 5a.75.75 0 01.75.75v3.5a.75.75 0 01-1.5 0v-3.5A.75.75 0 0110 5zm0 9a1 1 0 100-2 1 1 0 000 2z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-yellow-800 dark:text-yellow-300">Atención</h3>
                            <div class="mt-2 text-sm text-yellow-700 dark:text-yellow-200">
                                <p>No hay productos para mostrar. ¡Añade el primero!</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </x-table-responsive>
    </div>
</div>
