<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Verificar y agregar columnas si no existen
        Schema::table('payment_accounts', function (Blueprint $table) {
            // Solo agregar usd_enabled si no existe
            if (!Schema::hasColumn('payment_accounts', 'usd_enabled')) {
                $table->boolean('usd_enabled')->default(true)->after('notes');
            }
            
            // Solo agregar bs_enabled si no existe
            if (!Schema::hasColumn('payment_accounts', 'bs_enabled')) {
                $table->boolean('bs_enabled')->default(false)->after('usd_enabled');
            }
        });

        // Modificar tasa_bs si es necesario (en una transacción separada)
        Schema::table('payment_accounts', function (Blueprint $table) {
            if (Schema::hasColumn('payment_accounts', 'tasa_bs')) {
                $table->decimal('tasa_bs', 18, 4)->nullable()->change();
            } else {
                $table->decimal('tasa_bs', 18, 4)->nullable()->after('monedas');
            }
        });

        // Agregar índices para mejorar performance
        Schema::table('payment_accounts', function (Blueprint $table) {
            // Verificar si los índices existen usando SQL directo
            $indexExists = DB::select("SHOW INDEX FROM payment_accounts WHERE Key_name = 'payment_accounts_tenant_id_activo_status_index'");
            
            if (empty($indexExists)) {
                $table->index(['tenant_id', 'activo', 'status'], 'payment_accounts_tenant_id_activo_status_index');
            }
            
            $indexExists2 = DB::select("SHOW INDEX FROM payment_accounts WHERE Key_name = 'payment_accounts_usd_enabled_bs_enabled_index'");
            
            if (empty($indexExists2)) {
                $table->index(['usd_enabled', 'bs_enabled'], 'payment_accounts_usd_enabled_bs_enabled_index');
            }
        });

        // Sincronizar datos existentes
        $this->syncExistingData();
    }

    public function down()
    {
        Schema::table('payment_accounts', function (Blueprint $table) {
            // Verificar si los índices existen antes de eliminarlos
            $indexExists = DB::select("SHOW INDEX FROM payment_accounts WHERE Key_name = 'payment_accounts_tenant_id_activo_status_index'");
            if (!empty($indexExists)) {
                $table->dropIndex('payment_accounts_tenant_id_activo_status_index');
            }
            
            $indexExists2 = DB::select("SHOW INDEX FROM payment_accounts WHERE Key_name = 'payment_accounts_usd_enabled_bs_enabled_index'");
            if (!empty($indexExists2)) {
                $table->dropIndex('payment_accounts_usd_enabled_bs_enabled_index');
            }
        });
    }

    /**
     * Sincroniza los datos existentes con los nuevos campos
     */
    private function syncExistingData()
    {
        DB::table('payment_accounts')->get()->each(function ($account) {
            $monedas = json_decode($account->monedas, true) ?? [];
            
            if (!is_array($monedas)) {
                $monedas = [];
            }
            
            $monedas = array_map('strtolower', $monedas);
            
            $updates = [];
            
            // Si el campo monedas tiene 'usd', habilitar usd_enabled
            if (in_array('usd', $monedas)) {
                $updates['usd_enabled'] = true;
            }
            
            // Si el campo monedas tiene 'ves' o 'bs', habilitar bs_enabled
            if (in_array('ves', $monedas) || in_array('bs', $monedas)) {
                $updates['bs_enabled'] = true;
            }
            
            // Si hay tasa pero bs_enabled está en false, habilitarlo
            if ($account->tasa_bs && $account->tasa_bs > 0) {
                $updates['bs_enabled'] = true;
            }
            
            if (!empty($updates)) {
                DB::table('payment_accounts')
                    ->where('id', $account->id)
                    ->update($updates);
            }
        });
        
        // Log de resultados
        $total = DB::table('payment_accounts')->count();
        $withBs = DB::table('payment_accounts')->where('bs_enabled', true)->count();
        $withUsd = DB::table('payment_accounts')->where('usd_enabled', true)->count();
        
        \Log::info("Migración completada: {$total} cuentas procesadas. {$withBs} con Bs habilitado, {$withUsd} con USD habilitado.");
    }
};