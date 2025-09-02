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
        Schema::table('brands', function (Blueprint $table) {
            // Añadimos la columna 'slug' después de la columna 'name'.
            // La hacemos única para evitar URLs duplicadas.
            $table->string('slug')->unique()->after('name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('brands', function (Blueprint $table) {
            // Esto permite revertir la migración si es necesario.
            $table->dropColumn('slug');

        });
    }
};
