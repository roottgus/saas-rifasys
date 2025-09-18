<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('contracts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained();
            $table->string('contract_number')->unique();
            $table->string('client_name');
            $table->string('client_id_number');
            $table->string('client_email');
            $table->string('client_phone')->nullable();
            $table->string('client_address')->nullable();
            $table->string('raffle_name');
            $table->string('status')->default('pending');
            $table->timestamp('signed_at')->nullable();
            $table->text('disclaimer_accepted_text')->nullable();
            $table->timestamp('disclaimer_accepted_at')->nullable();
            $table->ipAddress('disclaimer_accepted_ip')->nullable();
            $table->string('file_path')->nullable();
            $table->uuid('uuid')->unique();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('contracts');
    }
};
