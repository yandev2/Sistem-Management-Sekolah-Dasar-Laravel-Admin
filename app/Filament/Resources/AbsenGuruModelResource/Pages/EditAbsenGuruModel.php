<?php

namespace App\Filament\Resources\AbsenGuruModelResource\Pages;

use App\Filament\Resources\AbsenGuruModelResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditAbsenGuruModel extends EditRecord
{
    protected static string $resource = AbsenGuruModelResource::class;
    protected static ?string $title = 'Ubah Data';

    protected function getSavedNotification(): ?Notification
    {
        $record = $this->record->fresh(['guru']);
        $siswa = $record->guru->name ?? '';
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
