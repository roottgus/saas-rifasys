<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('payment_accounts', function (Blueprint $table) {
            // Si ya existen, comenta estas líneas para evitar error de duplicado
            $table->boolean('usd_enabled')->default(true)->after('notes');
            $table->boolean('bs_enabled')->default(false)->after('usd_enabled');
            // OJO: NO añadimos tasa_bs aquí si ya la tienes creada
        });
    }

    public function down(): void
    {
        Schema::table('payment_accounts', function (Blueprint $table) {
            $table->dropColumn(['usd_enabled', 'bs_enabled']);
        });
    }
};

