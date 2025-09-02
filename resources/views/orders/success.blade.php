<x-layouts.app :title="__('Compra Exitosa')">

    <div class="container py-12">
        <div class="mx-auto max-w-2xl rounded-lg bg-white p-8 text-center shadow-lg dark:bg-gray-800">
            <div class="mb-6">
                <x-icon name="fa-solid fa-check-circle" class="mx-auto h-20 w-20 text-green-500" />
            </div>

            <h1 class="text-3xl font-bold text-gray-800 dark:text-white">
                ¡Gracias por tu compra!
            </h1>

            <p class="mt-4 text-gray-600 dark:text-gray-300">
                Tu pago para la <span class="font-semibold">Orden #{{ $order->id }}</span> se ha procesado
                correctamente.
            </p>

            <p class="mt-2 text-gray-600 dark:text-gray-300">
                En breve recibirás un correo electrónico con los detalles de tu pedido.
            </p>

            <div class="mt-8 border-t border-gray-200 pt-6 dark:border-gray-700">
                <p class="text-lg font-semibold text-gray-700 dark:text-gray-200">
                    Total Pagado: <span class="text-lime-600 dark:text-lime-400">€
                        {{ number_format($order->total, 2, ',', '.') }}</span>
                </p>
            </div>

            <div class="mt-8 flex flex-col items-center justify-center gap-4 sm:flex-row">
                <a href="{{ route('orders.index') }}"
                    class="w-full rounded-lg bg-lime-600 px-6 py-3 text-base font-semibold text-white shadow-md transition hover:bg-lime-700 focus:outline-none focus:ring-2 focus:ring-lime-500 focus:ring-opacity-75 sm:w-auto">
                    Ver mis Pedidos
                </a>
                <a href="{{ route('home') }}"
                    class="w-full rounded-lg bg-gray-200 px-6 py-3 text-base font-semibold text-gray-700 shadow-md transition hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-opacity-75 sm:w-auto dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-gray-600">
                    Seguir Comprando
                </a>
            </div>
        </div>
    </div>

</x-layouts.app>
