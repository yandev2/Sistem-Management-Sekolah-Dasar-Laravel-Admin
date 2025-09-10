<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiswaModel extends Model
{
    protected $fillable = [
        'nis',
        'nisn',
        'nama_siswa',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'agama',
        'alamat',
        'tahun_masuk',
        'nik',
        'no_kk',
        'nama_orang_tua',
        'id_kelas',
        'foto',
    ];

    public function kelas()
    {
        return $this->belongsTo(KelasModel::class, 'id_kelas');
    }
}
