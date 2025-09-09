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
    Schema::table('payment_accounts', function (Blueprint $table) {
        // ¿Qué moneda acepta este método? Ejemplo: ['usd', 'ves']
        $table->json('monedas')->nullable()->after('activo'); // Puede ser ["usd"], ["ves"], ["usd","ves"]
        // Si acepta Bs, cuál es la tasa actual para la conversión
        $table->decimal('tasa_bs', 18, 4)->nullable()->after('monedas');
    });
}

public function down()
{
    Schema::table('payment_accounts', function (Blueprint $table) {
        $table->dropColumn(['monedas', 'tasa_bs']);
    });
}

};
