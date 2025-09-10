<?php

namespace App\Filament\Resources\MapelModelResource\Pages;

use App\Filament\Resources\MapelModelResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateMapelModel extends CreateRecord
{
    protected static string $resource = MapelModelResource::class;
    protected static ?string $title = 'Tambah Data';
}
