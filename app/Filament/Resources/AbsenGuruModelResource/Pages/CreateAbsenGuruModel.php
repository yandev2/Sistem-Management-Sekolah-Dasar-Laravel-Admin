<?php

namespace App\Filament\Resources\AbsenGuruModelResource\Pages;

use App\Filament\Resources\AbsenGuruModelResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateAbsenGuruModel extends CreateRecord
{
    protected static string $resource = AbsenGuruModelResource::class;
    protected static ?string $title = 'Tambah Data';
}
