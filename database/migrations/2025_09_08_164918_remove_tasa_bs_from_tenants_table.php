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
    Schema::table('tenants', function (Blueprint $table) {
        $table->dropColumn('tasa_bs');
    });
}

public function down()
{
    Schema::table('tenants', function (Blueprint $table) {
        $table->decimal('tasa_bs', 18, 4)->nullable()->after('moneda_principal');
    });
}

};
