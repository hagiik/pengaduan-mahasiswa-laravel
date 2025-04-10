<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('fakultas_id')->nullable()->constrained('fakultas')->nullOnDelete()->after('nimd');
            $table->foreignId('prodi_id')->nullable()->constrained('prodi')->nullOnDelete()->after('fakultas_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['fakultas_id']);
            $table->dropForeign(['prodi_id']);
            $table->dropColumn(['fakultas_id', 'prodi_id']);
        });
    }
};
