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
        Schema::create('absen_siswa_models', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('id_siswa')->constrained('siswa_models')->cascadeOnDelete();
            $table->date('tanggal');
            $table->enum('status', ['H', 'I', 'S', 'A']);
            $table->longText('keterangan')->nullable()->default(null);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('absen_siswa_models');
    }
};
