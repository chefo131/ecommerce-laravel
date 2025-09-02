<div>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
            Categoría: {{ $category->name }}
        </h2>
    </x-slot>

    <div class="container py-12">
        {{-- Formulario para agregar subcategorías --}}
        <x-form-section submit="save" class="mb-6">
            <x-slot name="title">
                Agregar nueva subcategoría
            </x-slot>

            <x-slot name="description">
                Complete la información necesaria para poder agregar una nueva subcategoría a la categoría
                "{{ $category->name }}".
            </x-slot>

            <x-slot name="form">
                <div class="col-span-6 sm:col-span-4">
                    <x-label>
                        Nombre
                    </x-label>
                    <x-input wire:model.live="subcategory.name" type="text" class="mt-1 w-full" />
                    <x-input-error for="subcategory.name" />
                </div>

                <div class="col-span-6 sm:col-span-4">
                    <x-label>
                        Slug
                    </x-label>
                    <x-input wire:model="subcategory.slug" type="text"
                        class="mt-1 w-full bg-gray-100 dark:bg-gray-700" disabled />
                    <x-input-error for="subcategory.slug" />
                </div>

                <div class="col-span-6 sm:col-span-4">
                    <div class="flex items-center">
                        <p class="text-sm text-gray-700 dark:text-gray-300">¿Esta subcategoría necesita especificar
                            color?</p>
                        <div class="ml-auto">
                            <label class="mr-2">
                                <input wire:model.defer="subcategory.color" type="radio" value="1"
                                    name="color">
                                <span class="ml-1">Sí</span>
                            </label>
                            <label>
                                <input wire:model.defer="subcategory.color" type="radio" value="0"
                                    name="color">
                                <span class="ml-1">No</span>
                            </label>
                        </div>
                    </div>
                    <x-input-error for="subcategory.color" />
                </div>

                <div class="col-span-6 sm:col-span-4">
                    <div class="flex items-center">
                        <p class="text-sm text-gray-700 dark:text-gray-300">¿Esta subcategoría necesita especificar
                            talla?</p>
                        <div class="ml-auto">
                            <label class="mr-2">
                                <input wire:model.defer="subcategory.size" type="radio" value="1" name="size">
                                <span class="ml-1">Sí</span>
                            </label>
                            <label>
                                <input wire:model.defer="subcategory.size" type="radio" value="0" name="size">
                                <span class="ml-1">No</span>
                            </label>
                        </div>
                    </div>
                    <x-input-error for="subcategory.size" />
                </div>
            </x-slot>

            <x-slot name="actions">
                <x-action-message class="mr-3" on="saved">
                    Subcategoría agregada.
                </x-action-message>

                <x-button>
                    Agregar
                </x-button>
            </x-slot>
        </x-form-section>

        {{-- Lista de subcategorías --}}
        <x-action-section>
            <x-slot name="title">
                Lista de subcategorías
            </x-slot>

            <x-slot name="description">
                Aquí encontrará todas las subcategorías pertenecientes a esta categoría.
            </x-slot>

            <x-slot name="content">
                <table class="w-full text-gray-600 dark:text-gray-300">
                    <thead class="border-b border-gray-300 dark:border-gray-700">
                        <tr class="text-left">
                            <th class="w-full py-2">Nombre</th>
                            <th class="py-2">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-300 dark:divide-gray-700">
                        @foreach ($category->subcategories as $subcategory)
                            <tr>
                                <td class="py-2">
                                    {{ $subcategory->name }}
                                </td>
                                <td class="py-2">
                                    <div class="flex divide-x divide-gray-300 font-semibold dark:divide-gray-700">
                                        <a class="cursor-pointer pr-2 hover:text-blue-600">Editar</a>
                                        <a class="cursor-pointer pl-2 hover:text-red-600">Eliminar</a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </x-slot>
        </x-action-section>
    </div>
</div>
