<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // Quita índice antiguo si existe
        try {
            DB::statement('ALTER TABLE rifas DROP INDEX rifas_lottery_idx');
        } catch (\Throwable $e) {
            // ignorar si no existe
        }

        // Renombra la columna si todavía se llama lottery_provider
        if (Schema::hasColumn('rifas', 'lottery_provider')) {
            // Para renameColumn necesitas doctrine/dbal:
            // composer require doctrine/dbal
            Schema::table('rifas', function (Blueprint $table) {
                $table->renameColumn('lottery_provider', 'lottery_name');
            });
        }

        // Crea el índice nuevo (name + type)
        Schema::table('rifas', function (Blueprint $table) {
            if (Schema::hasColumn('rifas', 'lottery_name') && Schema::hasColumn('rifas', 'lottery_type')) {
                $table->index(['lottery_name', 'lottery_type'], 'rifas_lottery_idx');
            }
        });
    }

    public function down(): void
    {
        // Revierte el índice nuevo
        try {
            DB::statement('ALTER TABLE rifas DROP INDEX rifas_lottery_idx');
        } catch (\Throwable $e) {}

        // Renombra de vuelta si aplica
        if (Schema::hasColumn('rifas', 'lottery_name')) {
            Schema::table('rifas', function (Blueprint $table) {
                $table->renameColumn('lottery_name', 'lottery_provider');
            });
        }

        // Índice antiguo (provider + type)
        Schema::table('rifas', function (Blueprint $table) {
            if (Schema::hasColumn('rifas', 'lottery_provider') && Schema::hasColumn('rifas', 'lottery_type')) {
                $table->index(['lottery_provider', 'lottery_type'], 'rifas_lottery_idx');
            }
        });
    }
};
