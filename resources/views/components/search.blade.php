<div class="relative flex-1">
    <div class="relative">

        <input {{-- Asumiendo que flux:input fue temporalmente reemplazado o no es el problema de memoria --}} type="text" placeholder="Buscar..."
            class="w-full border-none bg-transparent px-2 py-0.5 pr-10 text-sm leading-tight text-gray-900 placeholder-gray-500 focus:ring-0 dark:text-gray-100 dark:placeholder-gray-400"
            wire:model.live="searchTerm" {{-- Asegúrate que esto es searchTerm --}} wire:keydown.enter="performSearch" />
        <button type="button" wire:click="performSearch"
            class="absolute inset-y-0 right-0 z-10 flex w-10 cursor-pointer items-center justify-center text-gray-400 transition duration-150 ease-in-out hover:text-lime-500 focus:outline-none dark:text-gray-500 dark:hover:text-lime-400">

            {{-- Reemplazado <x-search> por un SVG inline para evitar la recursión --}}
            <svg class="h-8 w-8" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
            </svg>
        </button>
    </div>
</div>
