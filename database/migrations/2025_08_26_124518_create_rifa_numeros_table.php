<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('rifa_numeros', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignId('rifa_id')->constrained('rifas')->cascadeOnDelete();
            $table->unsignedInteger('numero'); // 1..N
            $table->enum('estado', ['disponible','reservado','pagado'])->default('disponible');
            $table->dateTime('reservado_hasta')->nullable(); // para hold temporal
            $table->timestamps();

            $table->unique(['rifa_id','numero']); // cada número es único dentro de su rifa
            $table->index(['rifa_id','estado']);
        });
    }
    public function down(): void { Schema::dropIfExists('rifa_numeros'); }
};
