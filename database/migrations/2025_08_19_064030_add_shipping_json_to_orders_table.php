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
             // 1. Añadimos la nueva columna JSON que guardará la "foto" de la dirección.
            // La nombro 'envio' como en tu tutorial. La coloco después de 'user_id' por orden.
            $table->json('envio')->nullable()->after('user_id');

            // 2. (Opcional, pero recomendado) Si ya no vas a usar las columnas antiguas,
            // es una buena práctica eliminarlas para mantener la tabla limpia.
            // ¡OJO! Asegúrate de que tu lógica ya no las necesita.
            // Primero, debemos eliminar las restricciones de llave foránea.
            $table->dropForeign(['district_id']);
            $table->dropForeign(['city_id']);
            $table->dropForeign(['department_id']);

            // 3. (CORRECCIÓN) Ahora sí, eliminamos las columnas.
            // También eliminamos 'address' y 'references' que formaban parte de la dirección.
            $table->dropColumn(['department_id', 'city_id', 'district_id', 'address', 'references']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
             // 1. Volvemos a crear las columnas antiguas por si necesitamos revertir.
            $table->foreignId('department_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('city_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('district_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('address')->nullable();
            $table->string('references')->nullable();

            // 2. Eliminamos la columna JSON que habíamos creado.
            $table->dropColumn('envio');
        });
    }
};
