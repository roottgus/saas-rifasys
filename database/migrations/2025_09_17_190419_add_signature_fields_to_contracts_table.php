<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('contracts', function (Blueprint $table) {
            $table->string('signature_image_path')->nullable()->after('file_path'); // ruta PNG
            $table->string('signature_name')->nullable()->after('signature_image_path');
            $table->timestamp('signature_signed_at')->nullable()->after('signature_name');
            // opcional: ip / user agent
            $table->string('signature_ip')->nullable()->after('signature_signed_at');
            $table->string('signature_ua')->nullable()->after('signature_ip');
        });
    }

    public function down(): void
    {
        Schema::table('contracts', function (Blueprint $table) {
            $table->dropColumn([
                'signature_image_path',
                'signature_name',
                'signature_signed_at',
                'signature_ip',
                'signature_ua',
            ]);
        });
    }
};