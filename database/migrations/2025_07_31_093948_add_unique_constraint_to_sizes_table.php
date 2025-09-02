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
        Schema::table('sizes', function (Blueprint $table) {
            $table->unique('name'); // <-- ¡Añadimos la regla de oro!

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sizes', function (Blueprint $table) {
            // Para que la migración sea completamente reversible, aquí definimos la operación inversa.
            // Si en 'up' añadimos un índice único, en 'down' lo eliminamos.
            // El nombre del índice lo genera Laravel como 'nombretabla_nombrecolumna_unique'.
            $table->dropUnique('sizes_name_unique');
        });
    }
};
