<x-layouts.guest>
    <div class="container py-8">
        <figure class="mb-4">
            {{-- Usamos el método de MediaLibrary para obtener la URL de la imagen --}}
            @if ($category->getFirstMediaUrl('categories'))
                <img class="h-80 w-full object-cover object-center" src="{{ $category->getFirstMediaUrl('categories') }}"
                    alt="{{ $category->name }}">
            @endif
        </figure>
        @livewire('category-filter', ['category' => $category])
    </div>
</x-layouts.guest>
