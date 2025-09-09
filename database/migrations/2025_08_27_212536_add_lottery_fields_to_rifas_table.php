<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('rifas', function (Blueprint $table) {
            $table->string('lottery_provider', 100)->nullable()->after('descripcion'); // ej. loteria_tachira
            $table->string('lottery_type', 100)->nullable()->after('lottery_provider'); // ej. triple_a
            $table->dateTime('draw_at')->nullable()->after('ends_at');                  // fecha/hora del sorteo
            $table->string('external_draw_ref', 120)->nullable()->after('draw_at');     // código del sorteo oficial
            $table->index(['lottery_provider', 'lottery_type'], 'rifas_lottery_idx');
            $table->index('draw_at', 'rifas_draw_at_idx');
        });
    }

    public function down(): void
    {
        Schema::table('rifas', function (Blueprint $table) {
            $table->dropIndex('rifas_lottery_idx');
            $table->dropIndex('rifas_draw_at_idx');
            $table->dropColumn(['lottery_provider','lottery_type','draw_at','external_draw_ref']);
        });
    }
};
