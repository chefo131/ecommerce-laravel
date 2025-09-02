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
        // Primero, eliminamos la clave foránea y la columna 'product_id' de la tabla 'sizes'
        Schema::table('sizes', function (Blueprint $table) {
            // El nombre de la clave foránea puede variar. Laravel lo genera automáticamente.
            // Si da error, revisa el nombre en tu gestor de BD. Suele ser 'sizes_product_id_foreign'.
            $table->dropForeign(['product_id']);
            $table->dropColumn('product_id');
        });

        // Ahora, creamos la tabla pivote 'product_size'
        Schema::create('product_size', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('size_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Aquí no implementaremos el 'down' para simplificar, ya que es una refactorización.
    }
};
