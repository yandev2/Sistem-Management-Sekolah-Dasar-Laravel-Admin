<?php

namespace App\Filament\Resources\TingkatKelasModelResource\Pages;

use App\Filament\Resources\TingkatKelasModelResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTingkatKelasModel extends EditRecord
{
    protected static string $resource = TingkatKelasModelResource::class;
    protected static ?string $title = 'Edit Data';


    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
