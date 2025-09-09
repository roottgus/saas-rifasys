<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('special_prizes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignId('rifa_id')->constrained('rifas')->cascadeOnDelete();
            $table->string('title');                 // Premio especial
            $table->string('lottery_name');          // Lotería
            $table->string('lottery_type')->nullable(); // Tipo (opcional)
            $table->dateTime('draw_at')->nullable(); // Fecha/hora del sorteo (puede igualar a ends_at)
            $table->timestamps();

            $table->index(['rifa_id','draw_at']);
        });
    }

    public function down(): void {
        Schema::dropIfExists('special_prizes');
    }
};
