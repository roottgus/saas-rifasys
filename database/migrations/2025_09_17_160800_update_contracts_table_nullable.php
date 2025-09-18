<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('contracts', function (\Illuminate\Database\Schema\Blueprint $table) {
        // Hacer tenant_id nullable
        $table->foreignId('tenant_id')->nullable()->change();
        // Hacer raffle_name nullable
        $table->string('raffle_name')->nullable()->change();
        // Agregar archivos de cédula y permiso si no existen
        if (!Schema::hasColumn('contracts', 'cedula_file')) {
            $table->string('cedula_file')->nullable()->after('raffle_name');
        }
        if (!Schema::hasColumn('contracts', 'conalot_permit_file')) {
            $table->string('conalot_permit_file')->nullable()->after('cedula_file');
        }
    });
}

    /**
     * Reverse the migrations.
     */
    public function down()
{
    Schema::table('contracts', function (\Illuminate\Database\Schema\Blueprint $table) {
        if (Schema::hasColumn('contracts', 'cedula_file')) {
            $table->dropColumn('cedula_file');
        }
        if (Schema::hasColumn('contracts', 'conalot_permit_file')) {
            $table->dropColumn('conalot_permit_file');
        }
        // Puedes revertir nullable aquí si lo deseas
    });
}

};
