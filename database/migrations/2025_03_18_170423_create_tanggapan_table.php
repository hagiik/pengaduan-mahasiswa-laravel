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
        Schema::create('tanggapan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengaduan_id')->constrained('pengaduan')->onDelete('cascade');
            $table->longText('isi_tanggapan');
            $table->foreignId('status_id')->constrained('status_pengaduan')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade')->nullable(); //ini dari pelapor
            $table->unsignedBigInteger('penanggap_id')->nullable();
            $table->string('gambar_tanggapan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tanggapan');
    }
};
