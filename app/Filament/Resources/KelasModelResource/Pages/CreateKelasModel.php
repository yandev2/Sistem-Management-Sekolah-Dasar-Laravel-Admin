<?php

namespace App\Filament\Resources\KelasModelResource\Pages;

use App\Filament\Resources\KelasModelResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateKelasModel extends CreateRecord
{
    protected static string $resource = KelasModelResource::class;
    protected static ?string $title = 'Tambah Data';
}
