<div class="mt-8">
    <div class="rounded-lg bg-white p-6 shadow-md dark:bg-gray-800" x-data="{ open: true }">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Gestión de Tallas</h2>
        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            Añade, edita o elimina las tallas disponibles para este producto.
        </p>

        {{-- Formulario para añadir una nueva talla --}}
        <div class="mt-6 rounded-md border border-gray-200 p-4 dark:border-gray-700">
            <h3 class="text-md font-medium text-gray-800 dark:text-gray-200">Asociar Talla Existente</h3>
            <form wire:submit="addSize" class="mt-4 flex items-end space-x-4">
                <div class="flex-1">
                    <label for="size_id"
                        class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Seleccionar
                        Talla</label>
                    <select wire:model="size_id" id="size_id"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-lime-500 focus:ring-lime-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                        <option value="">-- Elige una talla --</option>
                        @foreach ($allSizes as $size)
                            <option value="{{ $size->id }}">{{ $size->name }}</option>
                        @endforeach
                    </select>
                    @error('size_id')
                        <span class="mt-1 text-sm text-red-500">{{ $message }}</span>
                    @enderror
                </div>
                <div>
                    <button type="submit" wire:loading.attr="disabled" wire:target="addSize"
                        class="rounded-md border border-transparent bg-lime-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-lime-700 focus:outline-none focus:ring-2 focus:ring-lime-500 focus:ring-offset-2 disabled:opacity-50">
                        <span wire:loading.remove wire:target="addSize">Asociar Talla</span>
                        <span wire:loading wire:target="addSize"><i
                                class="fas fa-spinner mr-2 animate-spin"></i>Asociando...</span>
                    </button>
                </div>
            </form>
        </div>

        {{-- Lista de tallas ya asociadas al producto --}}
        @if ($productSizes->count())
            <div class="mt-6">
                <h3 class="text-md font-medium text-gray-800 dark:text-gray-200">Tallas Asociadas</h3>
                <ul class="mt-4 divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach ($productSizes as $size)
                        <li wire:key="size-{{ $size->id }}" class="py-4">
                            <div class="flex items-center justify-between">
                                <span
                                    class="text-sm font-medium text-gray-900 dark:text-white">{{ $size->name }}</span>
                                <button wire:click="openDeleteModal({{ $size->id }})"
                                    class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                            {{-- Aquí invocamos al nuevo componente hijo --}}
                            <div class="mt-4">
                                <livewire:admin.color-size :size="$size"
                                    wire:key="color-size-{{ $size->id }}" />
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Modal de Confirmación de Borrado --}}
        @if ($openModal)
            <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50" x-data>
                <div @click.away="$wire.set('openModal', false)"
                    class="mx-4 w-full max-w-md rounded-lg bg-white p-6 shadow-xl dark:bg-gray-800">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">Confirmar Eliminación</h3>
                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                        ¿Estás seguro de que quieres desasociar esta talla del producto? Los colores y stock asociados a
                        esta talla para este producto se eliminarán.
                        Esta acción no se puede deshacer.
                    </p>
                    <div class="mt-4 flex justify-end space-x-3">
                        <button @click="$wire.set('openModal', false)"
                            class="rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-lime-500 focus:ring-offset-2 dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-gray-600">
                            Cancelar
                        </button>
                        <button wire:click="removeSize" wire:loading.attr="disabled" wire:target="removeSize"
                            class="rounded-md border border-transparent bg-red-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 disabled:opacity-50">
                            <span wire:loading.remove wire:target="removeSize">
                                Eliminar
                            </span>
                            <span wire:loading wire:target="removeSize">
                                <i class="fas fa-spinner mr-2 animate-spin"></i>Eliminando...
                            </span>
                        </button>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
