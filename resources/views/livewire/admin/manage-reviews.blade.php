<div>
    {{-- Asumimos que usas slots para el header en tu layout de admin --}}
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
            Gestión de Reseñas
        </h2>
    </x-slot>

    <div class="container py-12">
        {{-- Pestañas de navegación para filtrar por estado --}}
        <div class="mb-6">
            <div class="border-b border-gray-200 dark:border-gray-700">
                <nav class="-mb-px flex space-x-6" aria-label="Tabs">
                    <button wire:click="setStatus({{ \App\Models\Review::PENDIENTE }})" @class([
                        'whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm',
                        'border-lime-500 text-lime-600' => $status == \App\Models\Review::PENDIENTE,
                        'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300 dark:hover:border-gray-600' =>
                            $status != \App\Models\Review::PENDIENTE,
                    ])>
                        Pendientes
                        @if ($counters['pending'])
                            <span
                                class="ml-1 inline-flex items-center justify-center rounded-full bg-red-100 px-2.5 py-0.5 text-xs font-medium text-red-800 dark:bg-red-900 dark:text-red-200">{{ $counters['pending'] }}</span>
                        @endif
                    </button>

                    <button wire:click="setStatus({{ \App\Models\Review::APROBADO }})" @class([
                        'whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm',
                        'border-lime-500 text-lime-600' => $status == \App\Models\Review::APROBADO,
                        'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300 dark:hover:border-gray-600' =>
                            $status != \App\Models\Review::APROBADO,
                    ])>
                        Aprobadas
                        @if ($counters['approved'])
                            <span
                                class="ml-1 inline-flex items-center justify-center rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-800 dark:bg-green-900 dark:text-green-200">{{ $counters['approved'] }}</span>
                        @endif
                    </button>

                    <button wire:click="setStatus({{ \App\Models\Review::RECHAZADO }})" @class([
                        'whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm',
                        'border-lime-500 text-lime-600' => $status == \App\Models\Review::RECHAZADO,
                        'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300 dark:hover:border-gray-600' =>
                            $status != \App\Models\Review::RECHAZADO,
                    ])>
                        Rechazadas
                        @if ($counters['rejected'])
                            <span
                                class="ml-1 inline-flex items-center justify-center rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-medium text-gray-800 dark:bg-gray-700 dark:text-gray-200">{{ $counters['rejected'] }}</span>
                        @endif
                    </button>
                </nav>
            </div>
        </div>

        {{-- Listado de reseñas --}}
        <div class="space-y-6">
            @forelse ($reviews as $review)
                {{-- Usamos un componente para mantener este archivo más limpio y reutilizar el diseño. --}}
                <x-admin.review-card :review="$review" wire:key="review-{{ $review->id }}" />
            @empty
                <div class="rounded-lg bg-white p-6 text-center shadow-lg dark:bg-gray-800">
                    <p class="text-gray-500 dark:text-gray-400">No hay reseñas en esta categoría.</p>
                </div>
            @endforelse
        </div>

        @if ($reviews->hasPages())
            <div class="mt-8">
                {{ $reviews->links() }}
            </div>
        @endif
    </div>
</div>
