<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Agrega la columna sin depender de "after('email')"
        if (! Schema::hasColumn('tenants', 'notify_email')) {
            Schema::table('tenants', function (Blueprint $table) {
                $table->string('notify_email', 191)->nullable();
                $table->index('notify_email');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('tenants', 'notify_email')) {
            Schema::table('tenants', function (Blueprint $table) {
                $table->dropIndex(['notify_email']);
                $table->dropColumn('notify_email');
            });
        }
    }
};
