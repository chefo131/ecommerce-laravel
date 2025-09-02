<div>
    <form wire:submit.prevent="save" class="space-y-6">
        <div class="rounded-lg bg-white p-6 shadow-lg dark:bg-gray-800">

            {{-- Encabezado --}}
            <div class="mb-6 border-b border-gray-200 pb-5 dark:border-gray-700">
                <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                    Editar Categoría: {{ $category->name }}
                </h2>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    Modifica la información de la categoría.
                </p>
            </div>

            {{-- Formulario --}}
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                {{-- Nombre --}}
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Nombre</label>
                    <input type="text" wire:model.live="name"
                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-lime-500 focus:ring-lime-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:focus:border-lime-500 dark:focus:ring-lime-500">
                    @error('name')
                        <span class="mt-1 text-xs text-red-500">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Slug --}}
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Slug</label>
                    <input type="text" wire:model="slug" readonly
                        class="block w-full cursor-not-allowed rounded-md border-gray-300 bg-gray-100 shadow-sm dark:border-gray-600 dark:bg-gray-900 dark:text-gray-400">
                    @error('slug')
                        <span class="mt-1 text-xs text-red-500">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Icono --}}
                <div class="md:col-span-2">
                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Icono (ej: fa-solid
                        fa-mobile-screen)</label>
                    <input type="text" wire:model="icon"
                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-lime-500 focus:ring-lime-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:focus:border-lime-500 dark:focus:ring-lime-500">
                    @error('icon')
                        <span class="mt-1 text-xs text-red-500">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Marcas --}}
                <div class="md:col-span-2">
                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Marcas
                        Asociadas</label>
                    <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 md:grid-cols-4">
                        @foreach ($allBrands as $brand)
                            <label class="flex items-center rounded-lg border border-gray-200 p-3 dark:border-gray-700">
                                <input type="checkbox" wire:model="brands" value="{{ $brand->id }}"
                                    class="h-4 w-4 rounded border-gray-300 bg-gray-100 text-lime-600 focus:ring-2 focus:ring-lime-500 dark:border-gray-600 dark:bg-gray-700 dark:ring-offset-gray-800 dark:focus:ring-lime-600">
                                <span
                                    class="ml-3 text-sm font-medium text-gray-900 dark:text-gray-300">{{ $brand->name }}</span>
                            </label>
                        @endforeach
                    </div>
                    @error('brands')
                        <span class="mt-1 text-xs text-red-500">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Características (Color y Talla) --}}
                <div class="space-y-4 md:col-span-2">
                    <h3 class="text-md font-medium text-gray-800 dark:text-gray-200">Características del Producto</h3>
                    <div
                        class="flex items-center justify-between rounded-lg border border-gray-200 p-3 dark:border-gray-700">
                        <p class="text-sm text-gray-700 dark:text-gray-300">¿Los productos de esta categoría usan color?
                        </p>
                        <div class="flex space-x-4">
                            <label class="flex items-center">
                                <input wire:model.defer="features.color" type="radio" value="1"
                                    name="features_color"
                                    class="h-4 w-4 rounded border-gray-300 text-lime-600 focus:ring-lime-500">
                                <span class="ml-2 text-sm">Sí</span>
                            </label>
                            <label class="flex items-center">
                                <input wire:model.defer="features.color" type="radio" value="0"
                                    name="features_color"
                                    class="h-4 w-4 rounded border-gray-300 text-lime-600 focus:ring-lime-500">
                                <span class="ml-2 text-sm">No</span>
                            </label>
                        </div>
                    </div>
                    <div
                        class="flex items-center justify-between rounded-lg border border-gray-200 p-3 dark:border-gray-700">
                        <p class="text-sm text-gray-700 dark:text-gray-300">¿Los productos de esta categoría usan talla?
                        </p>
                        <div class="flex space-x-4">
                            <label class="flex items-center">
                                <input wire:model.defer="features.size" type="radio" value="1"
                                    name="features_size"
                                    class="h-4 w-4 rounded border-gray-300 text-lime-600 focus:ring-lime-500">
                                <span class="ml-2 text-sm">Sí</span>
                            </label>
                            <label class="flex items-center">
                                <input wire:model.defer="features.size" type="radio" value="0"
                                    name="features_size"
                                    class="h-4 w-4 rounded border-gray-300 text-lime-600 focus:ring-lime-500">
                                <span class="ml-2 text-sm">No</span>
                            </label>
                        </div>
                    </div>
                </div>

                {{-- Imagen --}}
                <div class="md:col-span-2">
                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Imagen</label>
                    <div class="flex items-center space-x-4">
                        {{-- Previsualización de la imagen nueva o la existente --}}
                        <div class="flex-shrink-0">
                            @if ($image)
                                {{-- Muestra la previsualización de la nueva imagen --}}
                                <img src="{{ $image->temporaryUrl() }}" alt="Previsualización"
                                    class="h-20 w-20 rounded-lg object-cover">
                            @elseif ($category->getFirstMediaUrl('categories'))
                                {{-- Muestra la imagen actual de la categoría --}}
                                <img src="{{ $category->getFirstMediaUrl('categories', 'thumb') }}"
                                    alt="{{ $category->name }}" class="h-20 w-20 rounded-lg object-cover">
                            @else
                                {{-- Placeholder si no hay imagen --}}
                                <div
                                    class="flex h-20 w-20 items-center justify-center rounded-lg bg-gray-200 dark:bg-gray-600">
                                    <i class="fa-solid fa-image text-3xl text-gray-400"></i>
                                </div>
                            @endif
                        </div>
                        <div class="flex-grow">
                            <input type="file" wire:model="image" accept="image/*"
                                class="block w-full cursor-pointer rounded-lg border border-gray-300 bg-gray-50 text-sm text-gray-900 focus:outline-none dark:border-gray-600 dark:bg-gray-700 dark:text-gray-400 dark:placeholder-gray-400">
                            @error('image')
                                <span class="mt-1 text-xs text-red-500">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            {{-- Acciones --}}
            <div class="flex items-center justify-between pt-5">
                {{-- Botón para volver al listado --}}
                <a href="{{ route('admin.categories.index') }}" wire:navigate
                    class="rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-semibold text-gray-700 shadow-sm hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700">
                    Cancelar
                </a>
                {{-- Botón para guardar --}}
                <button type="submit" wire:loading.attr="disabled" wire:target="save, image"
                    class="rounded-md bg-lime-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-lime-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-lime-600 disabled:opacity-50">
                    <span wire:loading.remove wire:target="save, image">Guardar Cambios</span>
                    <span wire:loading wire:target="save, image">Guardando...</span>
                </button>
            </div>
        </div>
    </form>
</div>
