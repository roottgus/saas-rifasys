<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        if (! Schema::hasColumn('rifa_numeros', 'reservado_hasta')) {
            Schema::table('rifa_numeros', function (Blueprint $table) {
                $table->timestamp('reservado_hasta')->nullable()->after('estado');
            });
        }
    }
    public function down(): void {
        if (Schema::hasColumn('rifa_numeros', 'reservado_hasta')) {
            Schema::table('rifa_numeros', function (Blueprint $table) {
                $table->dropColumn('reservado_hasta');
            });
        }
    }
};
