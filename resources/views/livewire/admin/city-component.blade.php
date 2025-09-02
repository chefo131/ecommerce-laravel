<div class="p-4 sm:p-6 lg:p-8">
    <div class="sm:flex sm:items-center">
        <div class="sm:flex-auto">
            {{-- El título muestra dinámicamente el nombre del departamento --}}
            <h1 class="text-xl font-semibold text-gray-900 dark:text-white">Ciudades del Departamento: <span
                    class="text-lime-500">{{ $department->name }}</span></h1>
            <p class="mt-2 text-sm text-gray-700 dark:text-gray-300">
                Un listado de todas las ciudades asociadas a este departamento.
            </p>
        </div>
        <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
            <button wire:click="create" type="button"
                class="inline-flex items-center justify-center rounded-md border border-transparent bg-lime-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-lime-700 focus:outline-none focus:ring-2 focus:ring-lime-500 focus:ring-offset-2 sm:w-auto">
                Añadir Ciudad
            </button>
        </div>
    </div>

    <div class="mt-6">
        <input type="text" wire:model.live.debounce.300ms="search" placeholder="Buscar ciudades..."
            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-lime-500 focus:ring-lime-500 sm:text-sm dark:border-gray-600 dark:bg-gray-800 dark:text-white">
    </div>

    <div class="mt-8 flex flex-col">
        <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="inline-block min-w-full py-2 align-middle md:px-6 lg:px-8">
                <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
                    @if ($cities->count())
                        <table class="min-w-full divide-y divide-gray-300 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-800">
                                <tr>
                                    <th scope="col"
                                        class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6 dark:text-white">
                                        ID</th>
                                    <th scope="col"
                                        class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-white">
                                        Nombre</th>
                                    <th scope="col"
                                        class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-white">
                                        Costo de Envío</th>
                                    <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6">
                                        <span class="sr-only">Acciones</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 bg-white dark:divide-gray-600 dark:bg-gray-700">
                                @foreach ($cities as $city)
                                    <tr>
                                        <td
                                            class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6 dark:text-white">
                                            {{ $city->id }}</td>
                                        <td
                                            class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-gray-300">
                                            {{ $city->name }}</td>
                                        <td
                                            class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-gray-300">
                                            {{ $city->cost }}</td>
                                        <td
                                            class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                                            {{-- ¡ESTE ES EL NUEVO ENLACE! --}}
                                            <a href="{{ route('admin.cities.show', $city) }}"
                                                class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-200">Distritos</a>

                                            <a href="#" wire:click.prevent="edit({{ $city->id }})"
                                                class="ml-4 text-lime-600 hover:text-lime-900 dark:text-lime-400 dark:hover:text-lime-200">Editar</a>
                                            <button wire:click="confirmDelete({{ $city->id }})"
                                                class="ml-4 text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-200">
                                                Eliminar
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @if ($cities->hasPages())
                            <div class="bg-white px-4 py-3 dark:bg-gray-700">
                                {{ $cities->links() }}
                            </div>
                        @endif
                    @else
                        <div class="px-6 py-4 text-gray-500 dark:text-gray-300">
                            No se encontraron ciudades para este departamento.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Modal para Crear/Editar Ciudad --}}
    <x-dialog-modal wire:model.live="showEditModal">
        <x-slot name="title">
            {{ $editing ? 'Editar Ciudad' : 'Crear Nueva Ciudad' }}
        </x-slot>

        <x-slot name="content">
            <form id="city-form" wire:submit.prevent="save">
                <div class="space-y-4">
                    <div>
                        <x-label for="name" value="Nombre de la Ciudad" />
                        <x-input id="name" type="text" class="mt-1 block w-full" wire:model.defer="name"
                            placeholder="Ej: Madrid" />
                        <x-input-error for="name" class="mt-2" />
                    </div>
                    <div>
                        <x-label for="cost" value="Costo de Envío" />
                        <x-input id="cost" type="number" step="0.01" class="mt-1 block w-full"
                            wire:model.defer="cost" placeholder="Ej: 5.99" />
                        <x-input-error for="cost" class="mt-2" />
                    </div>
                </div>
            </form>
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="$set('showEditModal', false)" wire:loading.attr="disabled">
                Cancelar
            </x-secondary-button>

            <x-button type="submit" form="city-form" wire:loading.attr="disabled" class="ml-3">
                Guardar Ciudad
            </x-button>
        </x-slot>
    </x-dialog-modal>
</div>
