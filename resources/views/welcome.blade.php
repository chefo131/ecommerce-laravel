<x-layouts.app :title="__('Inicio - Tu Tienda Online')">

    {{--
    NOTA: El banner de "pedidos pendientes" se ha movido a la barra de navegación
    (resources/views/livewire/navigation.blade.php) y se gestiona de forma centralizada
    con un View Composer (App/View/Composers/NavigationComposer.php).
    Esto evita tener que repetir la lógica en cada página y soluciona el error
    de la variable no definida.
    --}}

    <div class="container py-8">
        @if ($categories->count())
            @foreach ($categories as $category)
                <section class="mb-6">
                    <h1 class="mb-3 text-2xl font-semibold uppercase text-gray-800 dark:text-gray-200">
                        {{ $category->name }}
                    </h1>
                    @livewire('category-products', ['category' => $category], key('category-' . $category->id))
                </section>
            @endforeach
        @endif
    </div>
</x-layouts.app>
