{{-- Quitamos el action-section para que la tabla ocupe todo el ancho --}}
<div class="mt-12 rounded-lg bg-white p-6 shadow-lg dark:bg-gray-800">
    <div class="overflow-x-auto">
        <table class="w-full text-gray-600 dark:text-gray-300">
            <thead class="border-b border-gray-300 dark:border-gray-700">
                <tr class="text-left">
                    <th class="px-4 py-2">Imagen</th>
                    <th class="w-full px-4 py-2">Nombre</th>
                    <th class="px-4 py-2 text-right">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-300 dark:divide-gray-700">
                @foreach ($this->categories as $category)
                    <tr>
                        <td class="px-4 py-3">
                            {{-- Mostramos la imagen de la categoría --}}
                            @if ($category->getFirstMediaUrl('categories'))
                                <img src="{{ $category->getFirstMediaUrl('categories') }}" alt="{{ $category->name }}"
                                    class="h-12 w-12 rounded-full object-cover">
                            @else
                                <div
                                    class="flex h-12 w-12 items-center justify-center rounded-full bg-gray-200 dark:bg-gray-700">
                                    <x-icon name="fa-solid fa-image" class="h-6 w-6 text-gray-400" />
                                </div>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            {{-- Mostramos el nombre sin enlace, ya que las acciones están en los botones --}}
                            <span class="font-medium text-gray-800 dark:text-gray-200">
                                {{ $category->name }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center justify-end space-x-4 font-semibold">
                                {{-- ¡Este es el enlace correcto que ahora sí funcionará! --}}
                                <a href="{{ route('admin.categories.subcategories', $category) }}"
                                    class="text-green-600 hover:text-green-800">Subcategorías</a>
                                <a href="{{ route('admin.categories.edit', $category) }}"
                                    class="text-blue-600 hover:text-blue-800">Editar</a>
                                <a wire:click="confirmDelete({{ $category->id }})"
                                    class="cursor-pointer text-red-600 hover:text-red-800">Eliminar</a>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
