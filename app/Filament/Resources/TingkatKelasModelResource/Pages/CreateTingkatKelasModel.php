<?php

namespace App\Filament\Resources\TingkatKelasModelResource\Pages;

use App\Filament\Resources\TingkatKelasModelResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateTingkatKelasModel extends CreateRecord
{
    protected static string $resource = TingkatKelasModelResource::class;
    protected static ?string $title = 'Tambah Data';
}
