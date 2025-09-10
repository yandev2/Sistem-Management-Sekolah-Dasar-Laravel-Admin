<?php

namespace App\Filament\Resources\KelasModelResource\Pages;

use App\Filament\Resources\KelasModelResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditKelasModel extends EditRecord
{
    protected static string $resource = KelasModelResource::class;
        protected static ?string $title = 'Ubah Data';


    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
