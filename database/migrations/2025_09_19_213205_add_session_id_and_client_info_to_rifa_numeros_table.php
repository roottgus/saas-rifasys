<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('rifa_numeros', function (Blueprint $table) {
            if (!Schema::hasColumn('rifa_numeros', 'session_id')) {
                $table->string('session_id')->nullable()->index();
            }
            if (!Schema::hasColumn('rifa_numeros', 'client_info')) {
                $table->json('client_info')->nullable();
            }
        });
    }

    public function down()
    {
        Schema::table('rifa_numeros', function (Blueprint $table) {
            if (Schema::hasColumn('rifa_numeros', 'session_id')) {
                $table->dropColumn('session_id');
            }
            if (Schema::hasColumn('rifa_numeros', 'client_info')) {
                $table->dropColumn('client_info');
            }
        });
    }
};
