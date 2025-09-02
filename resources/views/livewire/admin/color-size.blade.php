<div class="mt-4 rounded-md border border-gray-200 p-4 dark:border-gray-700">
    <h3 class="text-md font-medium text-gray-800 dark:text-gray-200">Colores para la talla: <span
            class="font-bold">{{ $size->name }}</span></h3>

    {{-- Formulario para añadir color y cantidad --}}
    <form wire:submit="save" class="mt-4 flex items-end space-x-4">
        <div class="flex-1">
            <label for="color_id_{{ $size->id }}"
                class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Color</label>
            <select wire:model="color_id" id="color_id_{{ $size->id }}"
                class="w-full rounded-md border-gray-300 shadow-sm focus:border-lime-500 focus:ring-lime-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                <option value="">Seleccionar color</option>
                @foreach ($allColors as $color)
                    <option value="{{ $color->id }}">{{ $color->name }}</option>
                @endforeach
            </select>
            @error('color_id')
                <span class="mt-1 text-sm text-red-500">{{ $message }}</span>
            @enderror
        </div>
        <div class="w-32">
            <label for="quantity_{{ $size->id }}"
                class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Cantidad</label>
            <input wire:model="quantity" type="number" id="quantity_{{ $size->id }}"
                class="w-full rounded-md border-gray-300 shadow-sm focus:border-lime-500 focus:ring-lime-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                placeholder="Stock">
            @error('quantity')
                <span class="mt-1 text-sm text-red-500">{{ $message }}</span>
            @enderror
        </div>
        <div>
            <button type="submit" wire:loading.attr="disabled" wire:target="save"
                class="rounded-md border border-transparent bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-50">
                <span wire:loading.remove wire:target="save">Añadir</span>
                <span wire:loading wire:target="save"><i
                        class="fas fa-spinner mr-2 animate-spin"></i>Añadiendo...</span>
            </button>
        </div>
    </form>

    {{-- Lista de colores ya asociados a esta talla --}}
    @if ($sizeColors->count())
        <div class="mt-6">
            <h4 class="text-sm font-medium text-gray-800 dark:text-gray-200">Colores y Stock Agregados</h4>
            <ul class="mt-2 divide-y divide-gray-200 dark:divide-gray-700">
                @foreach ($sizeColors as $color)
                    <li wire:key="size-color-{{ $color->id }}" class="flex items-center justify-between py-2">
                        <div class="flex items-center">
                            <span class="mr-2 h-4 w-4 rounded-full"
                                style="background-color: {{ $color->hex_code }}; border: 1px solid #ccc;"></span>
                            <span class="text-sm text-gray-900 dark:text-white">{{ $color->name }}</span>
                        </div>
                        <div class="flex items-center space-x-4">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Cantidad:
                                {{ $color->pivot->quantity }}</span>
                            <button wire:click="openDeleteModal({{ $color->id }})"
                                class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300">
                                <i class="fas fa-trash"></i>
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
                    ¿Estás seguro de que quieres eliminar este color de esta talla? Se perderá el stock asociado.
                </p>
                <div class="mt-4 flex justify-end space-x-3">
                    <button @click="$wire.set('openModal', false)"
                        class="rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-lime-500 focus:ring-offset-2 dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-gray-600">
                        Cancelar
                    </button>
                    <button wire:click="delete" wire:loading.attr="disabled" wire:target="delete"
                        class="rounded-md border border-transparent bg-red-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 disabled:opacity-50">
                        Eliminar
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
