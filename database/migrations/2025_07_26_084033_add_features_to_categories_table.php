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
        Schema::table('categories', function (Blueprint $table) {
               // Añadimos una columna JSON para guardar características como color, talla, etc.
            // El valor por defecto será un objeto JSON que indica que no tiene ni color ni talla.
            $table->json('features')->default('{"color": false, "size": false}');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
             $table->dropColumn('features');
        });
    }
};
