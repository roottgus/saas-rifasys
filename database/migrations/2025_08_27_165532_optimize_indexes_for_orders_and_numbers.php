<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Acelera: encontrar reservas vencidas
        Schema::table('rifa_numeros', function (Blueprint $table) {
            // Para consultas: WHERE estado='reservado' AND reservado_hasta < NOW()
            $table->index(['estado', 'reservado_hasta'], 'rn_estado_reservado_hasta_idx');
        });

        // Acelera: órdenes pendientes por expirar
        Schema::table('orders', function (Blueprint $table) {
            // Para consultas: WHERE status IN (...) AND expires_at < NOW()
            $table->index(['status', 'expires_at'], 'orders_status_expires_idx');
        });

        // Acelera: match (rifa_id, numero) entre order_items y rifa_numeros
        Schema::table('order_items', function (Blueprint $table) {
            $table->index(['rifa_id', 'numero'], 'oi_rifa_numero_idx');
        });
    }

    public function down(): void
    {
        Schema::table('rifa_numeros', function (Blueprint $table) {
            $table->dropIndex('rn_estado_reservado_hasta_idx');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropIndex('orders_status_expires_idx');
        });

        Schema::table('order_items', function (Blueprint $table) {
            $table->dropIndex('oi_rifa_numero_idx');
        });
    }
};
