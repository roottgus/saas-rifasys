<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('rifas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->string('titulo');
            $table->string('slug');
            $table->string('banner_path')->nullable();
            $table->text('descripcion')->nullable();
            $table->decimal('precio', 10, 2)->default(0);
            $table->unsignedInteger('total_numeros')->default(100);
            $table->unsignedSmallInteger('min_por_compra')->default(1);
            $table->unsignedSmallInteger('max_por_compra')->default(10);
            $table->enum('estado', ['borrador','activa','pausada','finalizada'])->default('borrador');
            $table->dateTime('starts_at')->nullable();
            $table->dateTime('ends_at')->nullable();
            $table->timestamps();

            $table->unique(['tenant_id','slug']); // slug único por tenant
        });
    }
    public function down(): void { Schema::dropIfExists('rifas'); }
};
