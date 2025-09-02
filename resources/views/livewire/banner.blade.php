{{-- 
    Banner para notificar al usuario de órdenes pendientes.
    (Nota: Mover los estilos a un archivo CSS es una excelente idea para mantener el código limpio)
--}}
<div class="flex items-center justify-center rounded-lg bg-orange-100 p-4 text-sm text-orange-700 shadow-lg"
    role="alert">
    <svg class="mr-3 h-5 w-5 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
        aria-hidden="true">
        <path fill-rule="evenodd"
            d="M8.485 2.495c.673-1.167 2.357-1.167 3.03 0l6.28 10.875c.673 1.167-.17 2.625-1.516 2.625H3.72c-1.347 0-2.189-1.458-1.515-2.625L8.485 2.495zM10 5a.75.75 0 01.75.75v3.5a.75.75 0 01-1.5 0v-3.5A.75.75 0 0110 5zm0 9a1 1 0 100-2 1 1 0 000 2z"
            clip-rule="evenodd" />
    </svg>
    <span class="sr-only">Información</span>
    <div>
        <span class="font-medium">¡Atención!</span> Tienes una o más órdenes pendientes de pago.
        <a href="{{ route('orders.index') . '?status=' . \App\Models\Order::PENDIENTE }}"
            class="ml-2 inline-block font-semibold text-orange-800 underline hover:text-orange-900">
            Ver mis pedidos pendientes
        </a>
    </div>
</div>
