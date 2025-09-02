<div>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
            Subcategorías de: {{ $category->name }}
        </h2>
    </x-slot>

    <div class="p-4 sm:p-6 lg:p-8">
        {{-- Formulario para crear/editar subcategorías --}}
        <div class="mb-8 rounded-lg bg-white p-6 shadow-lg dark:bg-gray-800">
            <h3 class="mb-4 text-lg font-semibold text-gray-900 dark:text-gray-100">
                {{ $editingSubcategory ? 'Editar Subcategoría' : 'Crear Nueva Subcategoría' }}
            </h3>

            @if (session()->has('message'))
                <div class="mb-4 rounded-lg bg-green-100 p-4 text-sm text-green-700 dark:bg-green-200 dark:text-green-800"
                    role="alert">
                    {{ session('message') }}
                </div>
            @endif

            @if (session()->has('error'))
                <div class="mb-4 rounded-lg bg-red-100 p-4 text-sm text-red-700 dark:bg-red-200 dark:text-red-800"
                    role="alert">
                    {{ session('error') }}
                </div>
            @endif

            <form wire:submit.prevent="save">
                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                    {{-- Nombre de la subcategoría --}}
                    <div>
                        <x-label for="name">Nombre</x-label>
                        <x-input id="name" type="text" class="mt-1 w-full" wire:model.live="name" />
                        <x-input-error for="name" class="mt-2" />
                    </div>

                    {{-- Slug de la subcategoría --}}
                    <div>
                        <x-label for="slug">Slug</x-label>
                        <x-input id="slug" type="text" class="mt-1 w-full" wire:model="slug" readonly
                            disabled />
                        <x-input-error for="slug" class="mt-2" />
                    </div>
                </div>

                <div class="mt-6 grid grid-cols-1 gap-6 md:grid-cols-2">
                    {{-- ¿Necesita especificar color? --}}
                    <div>
                        <p class="mb-2 font-medium text-gray-700 dark:text-gray-300">¿Esta subcategoría necesita
                            especificar color?</p>
                        <div class="flex items-center space-x-4">
                            <label class="flex items-center">
                                <input type="radio" wire:model="hasColor" value="1"
                                    class="form-radio text-indigo-600" @disabled(!$category->features['color'])>
                                <span class="ml-2 text-gray-700 dark:text-gray-300">Sí</span>
                            </label>
                            <label class="flex items-center">
                                <input type="radio" wire:model="hasColor" value="0"
                                    class="form-radio text-indigo-600" @disabled(!$category->features['color'])>
                                <span class="ml-2 text-gray-700 dark:text-gray-300">No</span>
                            </label>
                        </div>
                        @if (!$category->features['color'])
                            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">La categoría padre
                                '{{ $category->name }}' no tiene la característica de color habilitada.</p>
                        @endif
                        <x-input-error for="hasColor" class="mt-2" />
                    </div>

                    {{-- ¿Necesita especificar talla? --}}
                    <div>
                        <p class="mb-2 font-medium text-gray-700 dark:text-gray-300">¿Esta subcategoría necesita
                            especificar talla?</p>
                        <div class="flex items-center space-x-4">
                            <label class="flex items-center">
                                <input type="radio" wire:model="hasSize" value="1"
                                    class="form-radio text-indigo-600" @disabled(!$category->features['size'])>
                                <span class="ml-2 text-gray-700 dark:text-gray-300">Sí</span>
                            </label>
                            <label class="flex items-center">
                                <input type="radio" wire:model="hasSize" value="0"
                                    class="form-radio text-indigo-600" @disabled(!$category->features['size'])>
                                <span class="ml-2 text-gray-700 dark:text-gray-300">No</span>
                            </label>
                        </div>
                        @if (!$category->features['size'])
                            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">La categoría padre
                                '{{ $category->name }}' no tiene la característica de talla habilitada.</p>
                        @endif
                        <x-input-error for="hasSize" class="mt-2" />
                    </div>
                </div>

                <div class="mt-6 flex items-center justify-end space-x-4">
                    @if ($editingSubcategory)
                        <x-secondary-button wire:click="cancelEdit" type="button">
                            Cancelar
                        </x-secondary-button>
                    @endif
                    <x-button type="submit">
                        {{ $editingSubcategory ? 'Actualizar Subcategoría' : 'Crear Subcategoría' }}
                    </x-button>
                </div>
            </form>
        </div>

        {{-- Tabla de subcategorías existentes --}}
        <div class="rounded-lg bg-white p-6 shadow-lg dark:bg-gray-800">
            <h3 class="mb-4 text-lg font-semibold text-gray-900 dark:text-gray-100">
                Listado de Subcategorías
            </h3>
            @if ($subcategories->count())
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">
                                    Nombre</th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">
                                    Slug</th>
                                <th scope="col"
                                    class="px-6 py-3 text-center text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">
                                    Color</th>
                                <th scope="col"
                                    class="px-6 py-3 text-center text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">
                                    Talla</th>
                                <th scope="col" class="relative px-6 py-3">
                                    <span class="sr-only">Acciones</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white dark:divide-gray-700 dark:bg-gray-800">
                            @foreach ($subcategories as $subcategory)
                                <tr>
                                    <td class="whitespace-nowrap px-6 py-4">
                                        <div class="text-sm text-gray-900 dark:text-gray-100">{{ $subcategory->name }}
                                        </div>
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4">
                                        <div class="text-sm text-gray-500 dark:text-gray-400">{{ $subcategory->slug }}
                                        </div>
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-center text-sm text-gray-500">
                                        @if ($subcategory->color)
                                            <span
                                                class="inline-flex rounded-full bg-green-100 px-2 text-xs font-semibold leading-5 text-green-800">Sí</span>
                                        @else
                                            <span
                                                class="inline-flex rounded-full bg-red-100 px-2 text-xs font-semibold leading-5 text-red-800">No</span>
                                        @endif
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-center text-sm text-gray-500">
                                        @if ($subcategory->size)
                                            <span
                                                class="inline-flex rounded-full bg-green-100 px-2 text-xs font-semibold leading-5 text-green-800">Sí</span>
                                        @else
                                            <span
                                                class="inline-flex rounded-full bg-red-100 px-2 text-xs font-semibold leading-5 text-red-800">No</span>
                                        @endif
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-right text-sm font-medium">
                                        <a href="#" wire:click.prevent="edit({{ $subcategory->id }})"
                                            class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">Editar</a>
                                        <a href="#" wire:click.prevent="confirmDelete({{ $subcategory->id }})"
                                            class="ml-4 text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300"
                                            wire:key="delete-{{ $subcategory->id }}">Eliminar</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                    {{ $subcategories->links() }}
                </div>
            @else
                <div class="p-4 text-center text-gray-500 dark:text-gray-400">
                    No hay subcategorías para esta categoría.
                </div>
            @endif
        </div>
    </div>

    {{-- Modal de Confirmación de Borrado (usando el componente de Jetstream) --}}
    @if ($deletingSubcategory)
        <x-dialog-modal wire:model.live="showDeleteModal">
            <x-slot name="title">
                Eliminar Subcategoría
            </x-slot>

            <x-slot name="content">
                ¿Estás seguro de que quieres eliminar la subcategoría
                "<strong>{{ $deletingSubcategory->name }}</strong>"? Esta acción no se puede deshacer.
            </x-slot>

            <x-slot name="footer">
                <x-secondary-button wire:click="$set('showDeleteModal', false)" wire:loading.attr="disabled">
                    Cancelar
                </x-secondary-button>

                <x-danger-button class="ml-3" wire:click="deleteSubcategory" wire:loading.attr="disabled">
                    Eliminar Subcategoría
                </x-danger-button>
            </x-slot>
        </x-dialog-modal>
    @endif
</div>
