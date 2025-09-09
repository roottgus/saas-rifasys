<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('payment_accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->enum('tipo', ['transferencia','zelle','usdt','paypal','stripe'])->default('transferencia');
            $table->string('etiqueta')->default('Cuenta principal'); // cómo se muestra al cliente
            // Campos genéricos (según tipo algunos podrán quedar vacíos)
            $table->string('banco')->nullable();
            $table->string('titular')->nullable();
            $table->string('documento')->nullable();     // CI/RIF
            $table->string('numero')->nullable();        // cuenta/IBAN
            $table->string('iban')->nullable();
            $table->string('email')->nullable();         // Zelle/PayPal
            $table->string('wallet')->nullable();        // USDT
            $table->string('red')->nullable();           // TRC20/ERC20
            $table->text('instrucciones')->nullable();   // texto libre que verá el cliente
            $table->boolean('requiere_voucher')->default(true); // para pagos manuales
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('payment_accounts'); }
};
