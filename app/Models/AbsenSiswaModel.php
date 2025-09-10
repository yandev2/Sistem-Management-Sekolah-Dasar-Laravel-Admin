<?php

namespace App\Models;

use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\ValidationException;

class AbsenSiswaModel extends Model
{
    protected $fillable = [
        'id_siswa',
        'tanggal',
        'status',
        'keterangan',
    ];

    public function siswa()
    {
        return $this->belongsTo(SiswaModel::class, 'id_siswa');
    }

    protected static function booted()
    {
        static::creating(function ($absen) {
            $exists = self::where('id_siswa', $absen->id_siswa)
                ->whereDate('tanggal', $absen->tanggal)
                ->exists();
            if ($exists) {
                Notification::make()
                    ->title("Data absen untuk siswa ini pada tanggal tersebut sudah ada.")
                    ->danger()
                    ->send();
                throw ValidationException::withMessages([]);
            }
        });
    }
}
