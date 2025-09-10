<?php

namespace App\Filament\Resources\MapelModelResource\Pages;

use App\Filament\Resources\MapelModelResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMapelModel extends EditRecord
{
    protected static string $resource = MapelModelResource::class;
    protected static ?string $title = 'Ubah Data';


    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
