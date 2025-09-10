<?php

namespace App\Models;

use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\ValidationException;

class AbsenGuruModel extends Model
{
    protected $fillable = [
        'id_guru',
        'tanggal',
        'absen_masuk',
        'absen_keluar',
        'durasi',
        'device_id',
        'keterangan',
    ];

    public function guru()
    {
        return $this->belongsTo(User::class, 'id_guru');
    }

    protected static function booted()
    {
        static::creating(function ($absen) {
            $exists = self::where('id_guru', $absen->id_guru)
                ->whereDate('tanggal', $absen->tanggal)
                ->exists();
            if ($exists) {
                Notification::make()
                    ->title("Data absen untuk guru ini pada tanggal tersebut sudah ada.")
                    ->danger()
                    ->send();
                throw ValidationException::withMessages(['Data absen untuk guru ini pada tanggal tersebut sudah ada.']);
            }
        });

        static::updating(function ($absen) {
            if (!is_null($absen->getOriginal('absen_keluar'))) {
                Notification::make()
                    ->title("Data absen tidak dapat diubah karena absen_keluar sudah terisi")
                    ->danger()
                    ->send();
                throw ValidationException::withMessages([]);
            }
        });
    }
}
