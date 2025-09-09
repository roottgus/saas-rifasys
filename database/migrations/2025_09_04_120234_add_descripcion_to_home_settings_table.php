<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('home_settings', function (Blueprint $table) {
            $table->text('descripcion')->nullable()->after('subtitulo');
        });
    }
    public function down(): void {
        Schema::table('home_settings', function (Blueprint $table) {
            $table->dropColumn('descripcion');
        });
    }
};
