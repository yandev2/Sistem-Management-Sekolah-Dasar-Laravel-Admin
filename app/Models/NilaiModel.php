<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NilaiModel extends Model
{
    protected $fillable = [
        'id_siswa',
        'id_mapel',
        'id_guru',
        'jenis_nilai',
        'nilai',
        'semester',
        'tahun_ajaran',
    ];

    public function siswa()
    {
        return $this->belongsTo(SiswaModel::class, 'id_siswa');
    }

    public function mapel()
    {
        return $this->belongsTo(MapelModel::class, 'id_mapel');
    }

    public function guru()
    {
        return $this->belongsTo(User::class, 'id_guru');
    }
}
