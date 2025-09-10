<?php

namespace App\Filament\Resources\MapelModelResource\Pages;

use App\Filament\Resources\MapelModelResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMapelModels extends ListRecords
{
    protected static string $resource = MapelModelResource::class;
    protected static ?string $title = 'Mata Pelajaran';


    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Tambah Data')
                ->color('success'),
        ];
    }
}
