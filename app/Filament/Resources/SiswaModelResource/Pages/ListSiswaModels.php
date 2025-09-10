<?php

namespace App\Filament\Resources\SiswaModelResource\Pages;

use App\Filament\Resources\SiswaModelResource;
use App\Filament\Resources\SiswaModelResource\Widgets\Widget1;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSiswaModels extends ListRecords
{
    protected static string $resource = SiswaModelResource::class;
    protected static ?string $title = 'Siswa';

    protected function getHeaderWidgets(): array
    {
        return [
            Widget1::class
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Tambah Data')
                ->color('success'),
        ];
    }
}
