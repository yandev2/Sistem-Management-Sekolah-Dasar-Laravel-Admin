<?php

namespace App\Filament\Resources\AbsenSiswaModelResource\Pages;

use App\Filament\Resources\AbsenSiswaModelResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;

class EditAbsenSiswaModel extends EditRecord
{
    protected static string $resource = AbsenSiswaModelResource::class;
    protected static ?string $title = 'Ubah Data';


    protected function getSavedNotification(): ?Notification
    {
        $record = $this->record->fresh(['siswa']);
        $siswa = $record->siswa->nama_siswa ?? 'Siswa';
        $tanggal = $record->tanggal ?? now()->format('Y-m-d');
        
        return Notification::make()
            ->success()
            ->title('Berhasil')
            ->body("Absensi {$siswa} pada {$tanggal} berhasil diperbarui");
    }
    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
