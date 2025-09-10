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
        Schema::create('absen_guru_models', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('id_guru')->constrained('users')->noActionOnDelete();
            $table->date('tanggal');
            $table->string('device_id')->nullable()->default(null);
            $table->enum('absen_masuk', ['H', 'I']);
            $table->enum('absen_keluar', ['H', 'I'])->nullable()->default(null);
            $table->string('durasi')->nullable()->default(null);
            $table->longText('keterangan')->nullable()->default('tidak ada keterangan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('absen_guru_models');
    }
};
