<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignId('rifa_id')->constrained('rifas')->cascadeOnDelete();
            $table->string('code')->unique();                       // código público (UUID/ULID)
            $table->string('customer_name')->nullable();
            $table->string('customer_phone', 30)->nullable();
            $table->string('customer_email')->nullable();
            $table->foreignId('payment_account_id')->nullable()->constrained('payment_accounts')->nullOnDelete();
            $table->enum('status', ['pending','submitted','paid','cancelled','expired'])->default('pending');
            $table->decimal('total_amount', 10, 2)->default(0);
            $table->string('voucher_path')->nullable();             // comprobante
            $table->dateTime('expires_at')->nullable();             // fin de la reserva
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['tenant_id','status']);
            $table->index('expires_at');
        });
    }
    public function down(): void { Schema::dropIfExists('orders'); }
};
