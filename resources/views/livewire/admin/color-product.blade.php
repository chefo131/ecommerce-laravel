<div class="rounded-lg bg-white p-6 shadow-md dark:bg-gray-800">
    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Gestión de Colores y Stock</h2>
    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
        Añade los colores disponibles para este producto y la cantidad de stock para cada uno.
    </p>

    {{-- Formulario para añadir un nuevo color --}}
    <div class="mt-6 rounded-md border border-gray-200 p-4 dark:border-gray-700">
        <h3 class="text-md font-medium text-gray-800 dark:text-gray-200">Añadir Nuevo Color</h3>
        <form wire:submit="saveColor" class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-3">
            {{-- Select de Colores --}}
            <div>
                <label for="color_id"
                    class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Color</label>
                <select wire:model.defer="color_id" id="color_id"
                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-lime-500 focus:ring-lime-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                    <option value="">Seleccionar</option>
                    @foreach ($allColors as $color)
                        <option value="{{ $color->id }}">{{ $color->name }}</option>
                    @endforeach
                </select>
                @error('color_id')
                    <span class="mt-1 text-sm text-red-500">{{ $message }}</span>
                @enderror
            </div>

            {{-- Input de Cantidad --}}
            <div>
                <label for="quantity"
                    class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Cantidad</label>
                <input wire:model.defer="quantity" type="number" id="quantity"
                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-lime-500 focus:ring-lime-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                    placeholder="Ej: 10">
                @error('quantity')
                    <span class="mt-1 text-sm text-red-500">{{ $message }}</span>
                @enderror
            </div>

            {{-- Botón de Añadir --}}
            <div class="self-end">
                {{-- Añadimos un estado de carga para dar feedback al usuario --}}
                <button type="submit" wire:loading.attr="disabled" wire:target="saveColor"
                    class="w-full rounded-md border border-transparent bg-lime-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-lime-700 focus:outline-none focus:ring-2 focus:ring-lime-500 focus:ring-offset-2 disabled:opacity-50">
                    {{-- El span que se muestra por defecto --}}
                    <span wire:loading.remove wire:target="saveColor">
                        Añadir Color
                    </span>
                    {{-- El span que se muestra durante la carga --}}
                    <span wire:loading wire:target="saveColor">
                        <i class="fas fa-spinner mr-2 animate-spin"></i>Guardando...
                    </span>
                </button>
            </div>
        </form>
    </div>

    {{-- Lista de colores ya asociados al producto --}}
    @if ($productColors->count())
        <div class="mt-6">
            <h3 class="text-md font-medium text-gray-800 dark:text-gray-200">Colores Agregados</h3>
            <ul class="mt-4 divide-y divide-gray-200 dark:divide-gray-700">
                @foreach ($productColors as $color)
                    <li wire:key="product-color-{{ $color->id }}" class="flex items-center justify-between py-3">
                        <div class="flex items-center">
                            <span class="mr-2 h-4 w-4 rounded-full"
                                style="background-color: {{ $color->hex_code ?? '#000000' }}; border: 1px solid #ccc;"></span>
                            <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $color->name }}</span>
                        </div>
                        <div class="flex items-center space-x-4">
                            <div class="w-24">
                                <input type="number" wire:change="updateQty({{ $color->id }}, $event.target.value)"
                                    {{-- ¡CORRECCIÓN CLAVE! Mostramos la cantidad desde el objeto 'pivot'. --}} value="{{ $color->pivot->quantity }}"
                                    class="w-full rounded-md border-gray-300 text-center text-sm shadow-sm focus:border-lime-500 focus:ring-lime-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                            </div>
                            <button wire:click="openDeleteModal({{ $color->id }})"
                                class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                    fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                        clip-rule="evenodd" />
                                </svg>
                            </button>
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
                    ¿Estás seguro de que quieres eliminar este color del producto? Esta acción no se puede deshacer.
                </p>
                <div class="mt-4 flex justify-end space-x-3">
                    <button @click="$wire.set('openModal', false)"
                        class="rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-lime-500 focus:ring-offset-2 dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-gray-600">
                        Cancelar
                    </button>
                    {{-- Aplicamos la misma lógica de estado de carga al botón de eliminar --}}
                    <button wire:click="deleteColor" wire:loading.attr="disabled" wire:target="deleteColor"
                        class="rounded-md border border-transparent bg-red-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 disabled:opacity-50">
                        <span wire:loading.remove wire:target="deleteColor">
                            Eliminar
                        </span>
                        <span wire:loading wire:target="deleteColor">
                            <i class="fas fa-spinner mr-2 animate-spin"></i>Eliminando...
                        </span>
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
