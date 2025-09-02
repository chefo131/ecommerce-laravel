@props(['submit'])

<div {{ $attributes->merge(['class' => 'md:grid md:grid-cols-3 md:gap-6']) }}>
    {{-- Columna de la izquierda para el título y la descripción --}}
    <div class="md:col-span-1">
        <div class="px-4 sm:px-0">
            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ $title }}</h3>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                {{ $description }}
            </p>
        </div>
    </div>

    {{-- Columna de la derecha para el formulario --}}
    <div class="mt-5 md:col-span-2 md:mt-0">
        <form wire:submit.prevent="{{ $submit }}">
            <div class="rounded-md bg-white px-4 py-5 shadow sm:p-6 dark:bg-gray-800">
                <div class="grid grid-cols-6 gap-6">
                    {{ $form }}
                </div>
            </div>

            @if (isset($actions))
                <div
                    class="flex items-center justify-end rounded-b-md bg-gray-50 px-4 py-3 text-right shadow sm:px-6 dark:bg-gray-900">
                    {{ $actions }}
                </div>
            @endif
        </form>
    </div>
</div>
