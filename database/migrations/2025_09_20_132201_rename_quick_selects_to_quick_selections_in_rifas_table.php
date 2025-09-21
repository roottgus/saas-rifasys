<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('rifas', function (Blueprint $table) {
            $table->renameColumn('quick_selects', 'quick_selections');
        });
    }

    public function down()
    {
        Schema::table('rifas', function (Blueprint $table) {
            $table->renameColumn('quick_selections', 'quick_selects');
        });
    }
};
