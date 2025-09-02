<x-layouts.guest>
    <div class="container mx-auto py-8 shadow-lg">
        <ul>
            @forelse ($products as $product)
                <x-product-list :product="$product" /> {{-- componente de blade con la información de productos --}}
            @empty
                <li class="rounded-lg bg-white shadow-2xl">
                    <div class="p-4">
                        <p class="text-lg font-semibold text-gray-700">
                            No se encontraron productos para "{{ $searchTerm }}".
                        </p>
                    </div>
                </li>
            @endforelse
        </ul>
        <div class="mt-4">
            {{ $products->links() }}
        </div>
    </div>
</x-layouts.guest>
