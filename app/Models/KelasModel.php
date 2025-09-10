<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KelasModel extends Model
{
    protected $fillable = [
        'id_tingkat',
        'id_wali_kelas',
        'nama_kelas',
    ];

    public function tingkatKelas()
    {
        return $this->belongsTo(TingkatKelasModel::class, 'id_tingkat');
    }

    public function waliKelas()
    {
        return $this->belongsTo(User::class, 'id_wali_kelas');
    }

    public function siswa()
    {
        return $this->hasMany(SiswaModel::class, 'id_kelas');
    }
    public function jadwal()
    {
        return $this->hasMany(JadwalModel::class, 'id_kelas');
    }
    public function getNamaKelasAttribute($value)
    {
        $values  = $this->tingkatKelas?->tingkat  . ' ' . $value;
        return $values;
    }
}
