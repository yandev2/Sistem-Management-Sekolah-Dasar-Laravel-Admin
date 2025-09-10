<?php

namespace App\Filament\Resources\SiswaModelResource\Pages;

use App\Filament\Resources\SiswaModelResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSiswaModel extends EditRecord
{
    protected static string $resource = SiswaModelResource::class;
    protected static ?string $title = 'Ubah Data';
    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
