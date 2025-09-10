<?php

namespace App\Models;

use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\ValidationException;

class JadwalModel extends Model
{
    protected $fillable = [
        'id_kelas',
        'id_mapel',
        'hari',
        'jam_masuk',
        'jam_keluar',
    ];

    public function kelas()
    {
        return $this->belongsTo(KelasModel::class, 'id_kelas');
    }

    public function mapel()
    {
        return $this->belongsTo(MapelModel::class, 'id_mapel');
    }

    protected static function booted()
    {
        static::saving(function ($jadwal) {
            $exists = self::where('id_kelas', $jadwal->id_kelas)
                ->where('hari', $jadwal->hari)
                ->where('id', '!=', $jadwal->id ?? 0)
                ->where(function ($query) use ($jadwal) {
                    $query->where(function ($q) use ($jadwal) {
                        $q->whereTime('jam_masuk', '<', $jadwal->jam_keluar)
                            ->whereTime('jam_keluar', '>', $jadwal->jam_masuk);
                    })
                        ->orWhere('id_mapel', $jadwal->id_mapel);
                })
                ->exists();

            if ($exists) {
                Notification::make()
                    ->title("Jadwal bentrok dengan kelas {$jadwal->kelas->nama_kelas} pada hari {$jadwal->hari}")
                    ->danger()
                    ->send();
                throw ValidationException::withMessages([
                    'jam_mulai' => 'Jadwal bentrok dengan jadwal lain di hari yang sama.',
                ]);
            }
        });
    }
}
