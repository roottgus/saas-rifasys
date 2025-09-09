<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('order_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignId('actor_id')->nullable()->constrained('users')->nullOnDelete(); // null = sistema
            $table->string('action', 40);     // reserved | submitted | paid | cancelled | expired | info_updated
            $table->text('notes')->nullable();
            $table->json('meta')->nullable(); // payload opcional (números, importes, etc.)
            $table->timestamps();

            $table->index(['order_id','action']);
            $table->index(['tenant_id','action']);
        });
    }
    public function down(): void {
        Schema::dropIfExists('order_logs');
    }
};
