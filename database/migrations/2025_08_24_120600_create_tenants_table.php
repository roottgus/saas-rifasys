<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('tenants', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();     // ej: miempresa
            $table->string('domain')->nullable(); // opcional si usas subdominios completos
            $table->json('branding_json')->nullable(); // colores, logo, modo
            $table->string('status')->default('active'); // active|paused
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('tenants');
    }
};
