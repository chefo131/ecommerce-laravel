<div>
    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Estado del Producto</h2>
    <p class="mb-4 mt-1 text-sm text-gray-600 dark:text-gray-400">
        Controla la visibilidad del producto en la tienda.
    </p>

    <div class="flex items-center">
        {{-- El interruptor (toggle switch) --}}
        <button wire:click="updateStatus" type="button"
            class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-lime-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800"
            :class="{ 'bg-lime-600': {{ $status == 2 ? 'true' : 'false' }}, 'bg-gray-200 dark:bg-gray-600': {{ $status == 1 ? 'true' : 'false' }} }"
            role="switch" aria-checked="{{ $status == 2 ? 'true' : 'false' }}">
            <span class="sr-only">Cambiar estado</span>
            <span aria-hidden="true"
                class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"
                :class="{ 'translate-x-5': {{ $status == 2 ? 'true' : 'false' }}, 'translate-x-0': {{ $status == 1 ? 'true' : 'false' }} }"></span>
        </button>

        {{-- Etiqueta que muestra el estado actual --}}
        <span class="ml-3 text-sm font-medium text-gray-900 dark:text-gray-200">
            @if ($status == 2)
                Publicado
            @else
                Borrador
            @endif
        </span>
    </div>
</div>
