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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('contact');
            $table->string('phone');
            // Usamos los valores numéricos directamente para desacoplar la migración del modelo.
            // 1: PENDIENTE, 2: RECIBIDO, 3: ENVIADO, 4: ENTREGADO, 5: ANULADO
            $table->enum('status', ['1', '2', '3', '4', '5'])
                ->default('1');
            $table->enum('envio_type', [1, 2]);
            $table->float('shopping_cost');
            $table->float('total');
            $table->json('content');
            $table->foreignId('department_id')->constrained()->onDelete('cascade')->nullable();
            $table->foreignId('city_id')->constrained()->onDelete('cascade')->nullable();
            $table->foreignId('district_id')->constrained()->onDelete('cascade')->nullable();
            $table->string('address')->nullable();
            $table->string('references')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
