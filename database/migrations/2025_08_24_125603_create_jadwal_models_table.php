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
        Schema::create(
            'jadwal_models',
            function (Blueprint $table) {
                $table->id();
                $table->timestamps();
                $table->foreignId('id_kelas')->constrained('kelas_models')->noActionOnDelete();
                $table->foreignId('id_mapel')->constrained('mapel_models')->noActionOnDelete();
                $table->enum('hari', ['SENIN', 'SELASA', 'RABU', 'KAMIS', 'JUMAT', 'SABTU']);
                $table->time('jam_masuk');
                $table->time('jam_keluar');
            }
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jadwal_models');
    }
};
