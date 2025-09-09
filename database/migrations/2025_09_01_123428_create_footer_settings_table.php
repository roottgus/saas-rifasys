<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('footer_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();

            $table->string('brand_name')->nullable();
            $table->string('logo_path')->nullable();
            $table->string('description', 300)->nullable();

            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('website_url')->nullable();
            $table->string('address', 300)->nullable();

            // Redes: se pueden guardar como JSON (lista de {name, url, icon})
            $table->json('socials')->nullable();
            // Links rápidos: JSON [{name, url}]
            $table->json('quick_links')->nullable();

            $table->string('terms_url')->nullable();
            $table->string('privacy_url')->nullable();

            $table->text('custom_html')->nullable();

            $table->string('bg_color', 32)->nullable();
            $table->string('text_color', 32)->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('footer_settings');
    }
};