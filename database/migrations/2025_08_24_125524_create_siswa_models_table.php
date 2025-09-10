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
        Schema::create('siswa_models', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('nis');
            $table->string('nisn');
            $table->text('nama_siswa');
            $table->enum('jenis_kelamin', ['Laki-Laki', 'Perempuan']);
            $table->longText('tempat_lahir');
            $table->date('tanggal_lahir');
            $table->text('agama');
            $table->longText('alamat');
            $table->year('tahun_masuk');
            $table->string('nik')->nullable()->default(null);
            $table->string('foto')->nullable()->default(null);
            $table->string('no_kk')->nullable()->default(null);
            $table->text('nama_orang_tua')->nullable()->default(null);
            $table->foreignId('id_kelas')->constrained('kelas_models')->noActionOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('siswa_models');
    }
};
