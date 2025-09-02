@props(['category']) {{-- Definimos que este componente espera una propiedad 'category' --}}

 {{-- Columna Derecha: Contenido de la categoría (inicialmente la primera) --}}
 <div class="col-span-3 bg-gray-200 dark:bg-gray-800 h-full overflow-y-auto p-6">
    @if($category) {{-- Verificamos si se pasó una categoría al componente --}}
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="md:col-span-1">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-3 text-center md:text-left">
                    Subcategorías de {{ $category->name }}
                </h3>
                <ul class="space-y-2">
                    @forelse ($category->subcategories as $subcategory)
                        <li>
                            <a href="#" class="text-sm text-gray-700 dark:text-gray-300 hover:text-lime-600 dark:hover:text-lime-400 block text-center md:text-left font-medium">
                                {{ $subcategory->name }}
                            </a>
                        </li>
                    @empty
                        <li>
                            <p class="text-sm text-gray-500 dark:text-gray-400 text-center md:text-left">No hay subcategorías.</p>
                        </li>
                    @endforelse
                </ul>
            </div>
            <div class="md:col-span-3">
                @if($category->image)
                    <img class="h-64 w-full object-cover object-center rounded-md shadow-md" src="{{ Storage::url($category->image) }}" alt="{{ $category->name }}">
                @else
                    <div class="h-64 w-full bg-gray-300 dark:bg-gray-700 flex items-center justify-center rounded-md shadow-md">
                        <p class="text-gray-500 dark:text-gray-400">Imagen no disponible</p>
                    </div>
                @endif
            </div>
        </div>
    @else
        <div class="flex items-center justify-center h-full">
            <p class="text-gray-600 dark:text-gray-400 text-lg">No se ha proporcionado una categoría para mostrar.</p>
        </div>
    @endif
</div>