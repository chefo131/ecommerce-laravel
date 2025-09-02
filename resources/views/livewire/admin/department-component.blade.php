<div class="p-4 sm:p-6 lg:p-8">
    <div class="sm:flex sm:items-center">
        <div class="sm:flex-auto">
            <h1 class="text-xl font-semibold text-gray-900 dark:text-white">Departamentos (Provincias)</h1>
            <p class="mt-2 text-sm text-gray-700 dark:text-gray-300">
                Un listado de todos los departamentos de la tienda.
            </p>
        </div>
        <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
            <button wire:click="create" type="button"
                class="inline-flex items-center justify-center rounded-md border border-transparent bg-lime-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-lime-700 focus:outline-none focus:ring-2 focus:ring-lime-500 focus:ring-offset-2 sm:w-auto">
                Añadir Departamento
            </button>
        </div>
    </div>

    <div class="mt-8 flex flex-col">
        <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="inline-block min-w-full py-2 align-middle md:px-6 lg:px-8">

                <div class="mb-4">
                    <input wire:model.live.debounce.300ms="search" type="text" placeholder="Buscar departamentos..."
                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-lime-500 focus:ring-lime-500 sm:text-sm dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200">
                </div>

                <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
                    <table class="min-w-full divide-y divide-gray-300 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-800">
                            <tr>
                                <th scope="col"
                                    class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6 dark:text-white">
                                    ID</th>
                                <th scope="col"
                                    class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-white">
                                    Nombre</th>
                                <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6">
                                    <span class="sr-only">Acciones</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white dark:bg-gray-700">
                            @forelse ($departments as $department)
                                <tr>
                                    <td
                                        class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6 dark:text-white">
                                        {{ $department->id }}
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-gray-300">
                                        {{ $department->name }}
                                    </td>
                                    <td
                                        class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                                        {{-- ¡ESTE ES EL NUEVO ENLACE! --}}
                                        <a href="{{ route('admin.departments.show', $department) }}"
                                            class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-200">
                                            Ciudades
                                        </a>
                                        <button wire:click="edit({{ $department->id }})"
                                            class="ml-4 text-lime-600 hover:text-lime-900 dark:text-lime-400 dark:hover:text-lime-200">
                                            Editar
                                        </button>
                                        <button wire:click="confirmDelete({{ $department->id }})"
                                            class="ml-4 text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-200">
                                            Eliminar
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3"
                                        class="whitespace-nowrap px-3 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                                        No se encontraron departamentos.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($departments->hasPages())
                    <div class="mt-4 px-4">
                        {{ $departments->links() }}
                    </div>
                @endif

            </div>
        </div>
    </div>

    {{-- Modal para Crear/Editar --}}
    <x-dialog-modal wire:model.live="showEditModal">
        <x-slot name="title">
            <span class="text-gray-900 dark:text-white">
                {{ $editing ? 'Editar Departamento' : 'Crear Nuevo Departamento' }}
            </span>
        </x-slot>

        <x-slot name="content">
            <div class="space-y-4">
                <div>
                    <label for="name"
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nombre</label>
                    <input type="text" id="name" wire:model="name"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-lime-500 focus:ring-lime-500 sm:text-sm dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200"
                        placeholder="Ej. Madrid">
                    @error('name')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="$set('showEditModal', false)" class="mr-2">
                Cancelar
            </x-secondary-button>

            <x-button wire:click="save" wire:loading.attr="disabled">
                <span wire:loading.remove wire:target="save">
                    Guardar
                </span>
                <span wire:loading wire:target="save">
                    Guardando...
                </span>
            </x-button>
        </x-slot>
    </x-dialog-modal>
</div>
