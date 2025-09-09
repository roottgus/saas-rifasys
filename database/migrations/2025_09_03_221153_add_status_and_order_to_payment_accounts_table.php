<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payment_accounts', function (Blueprint $table) {
            $table->enum('status', ['activo', 'inactivo'])->default('activo')->after('id');
            $table->unsignedSmallInteger('orden')->default(0)->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('payment_accounts', function (Blueprint $table) {
            $table->dropColumn(['status', 'orden']);
        });
    }
};
