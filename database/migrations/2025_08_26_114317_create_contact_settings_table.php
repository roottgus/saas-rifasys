<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('contact_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
            // WhatsApp
            $table->string('whatsapp_phone', 30)->nullable();   // Ej: +584121234567
            $table->string('whatsapp_message', 160)->nullable(); // Texto por defecto para el link
            $table->boolean('show_whatsapp_widget')->default(true);
            // Contacto
            $table->string('email')->nullable();
            $table->string('website_url')->nullable();
            $table->string('address')->nullable();
            // Redes
            $table->string('instagram_url')->nullable();
            $table->string('facebook_url')->nullable();
            $table->string('tiktok_url')->nullable();
            $table->string('youtube_url')->nullable();
            $table->string('telegram_url')->nullable();
            $table->timestamps();

            $table->unique('tenant_id'); // 1 registro por tenant
        });
    }
    public function down(): void {
        Schema::dropIfExists('contact_settings');
    }
};
