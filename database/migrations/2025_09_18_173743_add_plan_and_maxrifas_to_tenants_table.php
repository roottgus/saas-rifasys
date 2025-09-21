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
        $table->string('plan')->default('plus')->after('status'); // o nullable si quieres, pero default mejor
        $table->unsignedInteger('max_rifas')->nullable()->after('plan');
    });
}

public function down()
{
    Schema::table('tenants', function (Blueprint $table) {
        $table->dropColumn(['plan', 'max_rifas']);
    });
}

};
