<x-layouts.app :title="__('Pagar Orden')">
    <div class="container py-8">
        {{-- Aquí llamamos al componente de Livewire y le pasamos la orden --}}
        @livewire('payment-order', ['order' => $order])
    </div>
</x-layouts.app>
