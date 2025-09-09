<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // 1) Crear 'notes' si no existe
        if (! Schema::hasColumn('payment_accounts', 'notes')) {
            Schema::table('payment_accounts', function (Blueprint $table) {
                // La agregamos cerca de donde estaba 'instrucciones'
                $table->text('notes')->nullable()->after('red');
            });
        }

        // 2) Copiar datos desde 'instrucciones' si existe
        if (Schema::hasColumn('payment_accounts', 'instrucciones')) {
            DB::statement("
                UPDATE payment_accounts
                SET notes = instrucciones
                WHERE (notes IS NULL OR notes = '') AND instrucciones IS NOT NULL
            ");

            // 3) Intentar eliminar 'instrucciones' (opcional). Si falla, la dejamos.
            try {
                Schema::table('payment_accounts', function (Blueprint $table) {
                    $table->dropColumn('instrucciones');
                });
            } catch (\Throwable $e) {
                try {
                    DB::statement("ALTER TABLE payment_accounts DROP COLUMN instrucciones");
                } catch (\Throwable $e2) {
                    // Si no se puede, la dejamos y no pasa nada.
                }
            }
        }
    }

    public function down(): void
    {
        // Reversión: volver a crear 'instrucciones' y copiar de 'notes'
        if (! Schema::hasColumn('payment_accounts', 'instrucciones')) {
            Schema::table('payment_accounts', function (Blueprint $table) {
                $table->text('instrucciones')->nullable()->after('red');
            });
        }

        if (Schema::hasColumn('payment_accounts', 'notes')) {
            DB::statement("
                UPDATE payment_accounts
                SET instrucciones = notes
                WHERE (instrucciones IS NULL OR instrucciones = '') AND notes IS NOT NULL
            ");

            // Intentar eliminar 'notes' (si quieres una reversión limpia)
            try {
                Schema::table('payment_accounts', function (Blueprint $table) {
                    $table->dropColumn('notes');
                });
            } catch (\Throwable $e) {
                try {
                    DB::statement("ALTER TABLE payment_accounts DROP COLUMN notes");
                } catch (\Throwable $e2) {
                    //
                }
            }
        }
    }
};
