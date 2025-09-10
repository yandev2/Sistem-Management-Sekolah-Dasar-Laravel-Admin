<?php

namespace App\Filament\Resources\JadwalModelResource\Pages;

use App\Filament\Resources\JadwalModelResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditJadwalModel extends EditRecord
{
    protected static string $resource = JadwalModelResource::class;
    protected static ?string $title = 'Ubah Data';

    protected function getSavedNotification(): ?Notification
    {
        $record = $this->record->fresh(['kelas']);
        $kelas = $record->kelas->nama_kelas ?? '';

        return Notification::make()
            ->success()
            ->title('Berhasil')
            ->body("Jadwal pelajaran pada Kelas {$kelas} berhasil diperbarui");
    }
    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
