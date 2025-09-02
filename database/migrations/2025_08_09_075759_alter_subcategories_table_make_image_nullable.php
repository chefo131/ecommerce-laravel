<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('subcategories', function (Blueprint $table) {
            // Cambiamos la columna 'image' para que pueda ser nula
            $table->string('image')->nullable()->change();
        });
    }

    public function down(): void
    {
        // (Opcional) revertir el cambio
        Schema::table('subcategories', function (Blueprint $table) {
            $table->string('image')->nullable(false)->change();
        });
    }
};

