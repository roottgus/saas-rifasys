<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('home_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->string('banner_path')->nullable();       // storage/app/public/banners/...
            $table->string('titulo')->default('Participa ahora');
            $table->string('subtitulo')->nullable();
            $table->string('cta_label')->default('Elegir mis números');
            $table->dateTime('countdown_at')->nullable();    // fecha/hora del sorteo
            $table->string('time_zone')->default('America/Caracas');
            $table->timestamps();
            $table->unique('tenant_id');
        });
    }
    public function down(): void {
        Schema::dropIfExists('home_settings');
    }
};
