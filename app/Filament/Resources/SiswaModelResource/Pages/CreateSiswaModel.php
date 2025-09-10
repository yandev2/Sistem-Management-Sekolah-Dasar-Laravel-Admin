<?php

namespace App\Filament\Resources\SiswaModelResource\Pages;

use App\Filament\Resources\SiswaModelResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateSiswaModel extends CreateRecord
{
    protected static string $resource = SiswaModelResource::class;
    protected static ?string $title = 'Tambah Data';
}
