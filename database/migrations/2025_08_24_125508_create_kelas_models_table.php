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
        Schema::create('kelas_models', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('nama_kelas');
            $table->foreignId('id_tingkat')->constrained('tingkat_kelas_models')->noActionOnDelete();
            $table->foreignId('id_wali_kelas')->constrained('users')->noActionOnDelete();
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kelas_models');
    }
};
