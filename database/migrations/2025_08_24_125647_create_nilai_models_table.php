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
        Schema::create('nilai_models', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('id_siswa')->constrained('siswa_models')->noActionOnDelete();
            $table->foreignId('id_mapel')->constrained('mapel_models')->noActionOnDelete();
            $table->foreignId('id_guru')->constrained('users')->noActionOnDelete();
            $table->string('jenis_nilai');
            $table->decimal('nilai', 5, 2);
            $table->enum('semester', ['Ganjil', 'Genap']);
            $table->string('tahun_ajaran');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nilai_models');
    }
};
