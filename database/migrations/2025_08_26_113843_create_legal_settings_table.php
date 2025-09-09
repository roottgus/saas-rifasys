<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('legal_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->string('titulo_terminos')->default('Términos y Condiciones');
            $table->longText('terminos')->nullable();
            $table->string('titulo_politicas')->default('Política de Privacidad');
            $table->longText('politicas')->nullable();
            $table->string('titulo_devoluciones')->default('Política de Devoluciones');
            $table->longText('devoluciones')->nullable();
            $table->timestamps();
            $table->unique('tenant_id');
        });
    }
    public function down(): void {
        Schema::dropIfExists('legal_settings');
    }
};
