<h1 class="mb-4 text-2xl">Carrito de compras</h1>

@php
    $cart = session('cart', []);
@endphp

@if (count($cart) === 0)
    <p>No hay productos en el carrito</p>
@else
    <ul class="space-y-4">
        @foreach ($cart as $item)
            <li class="rounded border p-4">
                <p><strong>Producto ID:</strong> {{ $item['id'] }}</p>
                <p><strong>Cantidad:</strong> {{ $item['cantidad'] }}</p>
                @if (!empty($item['color_id']))
                    <p><strong>Color:</strong> {{ $item['color_id'] }}</p>
                @endif
                @if (!empty($item['size_id']))
                    <p><strong>Talla:</strong> {{ $item['size_id'] }}</p>
                @endif
            </li>
        @endforeach
    </ul>
@endif
