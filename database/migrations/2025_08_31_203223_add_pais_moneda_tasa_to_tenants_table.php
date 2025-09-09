<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tenants', function (Blueprint $table) {
            $table->string('pais')->nullable()->after('name');
            $table->string('moneda_principal')->default('USD')->after('pais');
            $table->decimal('tasa_bs', 18, 2)->nullable()->after('moneda_principal');
        });
    }

    public function down(): void
    {
        Schema::table('tenants', function (Blueprint $table) {
            $table->dropColumn(['pais', 'moneda_principal', 'tasa_bs']);
        });
    }
};