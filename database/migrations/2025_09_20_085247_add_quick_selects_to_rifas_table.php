<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('rifas', function (Blueprint $table) {
            $table->json('quick_selects')->nullable()->after('max_por_compra');
        });
    }

    public function down()
    {
        Schema::table('rifas', function (Blueprint $table) {
            $table->dropColumn('quick_selects');
        });
    }
};
