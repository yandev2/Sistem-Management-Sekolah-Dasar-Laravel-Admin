<?php

namespace App\Filament\Resources\AbsenSiswaModelResource\Pages;

use App\Filament\Resources\AbsenSiswaModelResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateAbsenSiswaModel extends CreateRecord
{
    protected static string $resource = AbsenSiswaModelResource::class;
    protected static ?string $title = 'Tambah Data';
}
