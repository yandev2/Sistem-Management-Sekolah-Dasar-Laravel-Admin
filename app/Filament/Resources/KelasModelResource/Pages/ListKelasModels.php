<?php

namespace App\Filament\Resources\KelasModelResource\Pages;

use App\Filament\Resources\KelasModelResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListKelasModels extends ListRecords
{
    protected static string $resource = KelasModelResource::class;
    protected static ?string $title = 'Kelas';


    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Tambah Data')
                ->color('success'),
        ];
    }
}
