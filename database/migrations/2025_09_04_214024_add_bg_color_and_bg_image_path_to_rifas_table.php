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
    Schema::table('rifas', function (Blueprint $table) {
        $table->string('bg_color', 16)->nullable()->after('banner_path');
        $table->string('bg_image_path')->nullable()->after('bg_color');
    });
}


    /**
     * Reverse the migrations.
     */
    public function down()
{
    Schema::table('rifas', function (Blueprint $table) {
        $table->dropColumn(['bg_color', 'bg_image_path']);
    });
}

};
