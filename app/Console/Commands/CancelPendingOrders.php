<?php

namespace App\Console\Commands;

use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CancelPendingOrders extends Command
{
    protected $signature = 'orders:cancel-pending';

    protected $description = 'Cancela las órdenes pendientes después de un tiempo y restaura el stock si es necesario.';

    public function handle()
    {
        $this->info('Iniciando la cancelación de órdenes pendientes...');

        $expirationTime = Carbon::now()->subMinutes(10);

        // Usamos chunkById para procesar las órdenes en lotes y no agotar la memoria
        // si hubiera miles de órdenes pendientes.
        Order::where('status', Order::PENDIENTE)
            ->where('created_at', '<=', $expirationTime)
            ->chunkById(100, function ($orders) {
                foreach ($orders as $order) {
                    // En este flujo de trabajo, el stock se descuenta al pagar,
                    // por lo que al anular un pedido PENDIENTE, no es necesario
                    // restaurar el stock. Simplemente cambiamos el estado.
                    // Si el stock se descontara al crear la orden, aquí iría la lógica de restauración.

                    try {
                        DB::beginTransaction();

                        $order->status = Order::ANULADO;
                        $order->save();

                        // (Opcional) Aquí se podría añadir una notificación al usuario.

                        DB::commit();

                        $this->line("Orden #{$order->id} cancelada correctamente.");
                        Log::info("Orden #{$order->id} cancelada automáticamente por inactividad.");

                    } catch (\Exception $e) {
                        DB::rollBack();
                        $this->error("Error al cancelar la orden #{$order->id}: " . $e->getMessage());
                        Log::error("Error al cancelar la orden #{$order->id}: " . $e->getMessage());
                    }
                }
            });

        $this->info('Proceso de cancelación de órdenes pendientes finalizado.');
    }
}