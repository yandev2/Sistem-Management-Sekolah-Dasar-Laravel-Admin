<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MapelModel extends Model
{
      protected $fillable = [
        'kode_mapel',
        'nama_mapel',
        'id_tingkat',
    ];

    public function tingkatKelas()
    {
        return $this->belongsTo(TingkatKelasModel::class, 'id_tingkat');
    }
}
