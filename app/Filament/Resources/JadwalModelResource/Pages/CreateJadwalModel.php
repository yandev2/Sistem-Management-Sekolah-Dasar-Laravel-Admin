<?php

namespace App\Filament\Resources\JadwalModelResource\Pages;

use App\Filament\Resources\JadwalModelResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateJadwalModel extends CreateRecord
{
    protected static string $resource = JadwalModelResource::class;
    protected static ?string $title = 'Tambah Data';
}
