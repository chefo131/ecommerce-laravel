<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Añadimos la columna para guardar el ID de la transacción (ej. de PayPal).
            // La hacemos nullable por si usamos otros métodos de pago que no generen un ID.
            // La colocamos después de la columna 'status' por orden lógico.
            $table->string('payment_id')->nullable()->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('payment_id');
        });
    }
};
