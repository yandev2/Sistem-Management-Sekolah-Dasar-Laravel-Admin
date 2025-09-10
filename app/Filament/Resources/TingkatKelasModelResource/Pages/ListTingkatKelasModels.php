<?php

namespace App\Filament\Resources\TingkatKelasModelResource\Pages;

use App\Filament\Resources\TingkatKelasModelResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTingkatKelasModels extends ListRecords
{
    protected static string $resource = TingkatKelasModelResource::class;
    protected static ?string $title = 'Tingkat Kelas';


    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->label('Tambah Data')
            ->color('success'),
        ];
    }
}
