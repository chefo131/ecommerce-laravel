<x-layouts.app.admin>
    <div>
        <h1 class="text-2xl font-bold text-gray-700 dark:text-gray-200">
            Gestión de Categorías
        </h1>

        {{-- El título se puede quedar, pero el contenido principal ahora será el componente Livewire --}}
        @livewire('admin.create-category')

        {{-- Componente para listar y gestionar las categorías existentes --}}
        @livewire('admin.manage-categories')
    </div>
</x-layouts.app.admin>
