<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // 1) Agregar lottery_name
        Schema::table('rifas', function (Blueprint $table) {
            $table->string('lottery_name', 100)->nullable()->after('descripcion');
        });

        // 2) Copiar datos existentes de lottery_provider -> lottery_name
        DB::table('rifas')->whereNotNull('lottery_provider')->update([
            'lottery_name' => DB::raw('lottery_provider'),
        ]);

        // 3) Re-crear índice compuesto usando lottery_name en vez de lottery_provider
        Schema::table('rifas', function (Blueprint $table) {
            // El índice anterior se llamaba 'rifas_lottery_idx' (provider + type)
            $table->dropIndex('rifas_lottery_idx');
            $table->index(['lottery_name', 'lottery_type'], 'rifas_lottery_idx');
        });

        // 4) Borrar la columna antigua
        Schema::table('rifas', function (Blueprint $table) {
            $table->dropColumn('lottery_provider');
        });
    }

    public function down(): void
    {
        // Revertir: volver a lottery_provider (si realmente lo necesitas)
        Schema::table('rifas', function (Blueprint $table) {
            $table->string('lottery_provider', 100)->nullable()->after('descripcion');
        });

        DB::table('rifas')->whereNotNull('lottery_name')->update([
            'lottery_provider' => DB::raw('lottery_name'),
        ]);

        Schema::table('rifas', function (Blueprint $table) {
            $table->dropIndex('rifas_lottery_idx');
            $table->index(['lottery_provider', 'lottery_type'], 'rifas_lottery_idx');
            $table->dropColumn('lottery_name');
        });
    }
};
