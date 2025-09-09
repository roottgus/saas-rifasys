<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
            $table->foreignId('rifa_id')->constrained('rifas')->cascadeOnDelete();
            $table->unsignedInteger('numero');
            $table->decimal('price', 10, 2)->default(0);
            $table->timestamps();

            $table->unique(['order_id','numero']);
        });
    }
    public function down(): void { Schema::dropIfExists('order_items'); }
};
