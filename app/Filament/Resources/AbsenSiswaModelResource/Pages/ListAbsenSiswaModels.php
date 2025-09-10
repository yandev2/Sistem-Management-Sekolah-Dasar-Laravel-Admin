<?php

namespace App\Filament\Resources\AbsenSiswaModelResource\Pages;

use App\Filament\Resources\AbsenSiswaModelResource;
use App\Filament\Resources\AbsenSiswaModelResource\Widgets\Widget1;
use Filament\Actions;
use Filament\Pages\Concerns\ExposesTableToWidgets;
use Filament\Resources\Pages\ListRecords;

class ListAbsenSiswaModels extends ListRecords
{
    protected static string $resource = AbsenSiswaModelResource::class;
    protected static ?string $title = 'Absensi Siswa';

    use ExposesTableToWidgets;


    protected function getHeaderWidgets(): array
    {
        return [
            Widget1::class
        ];
    }
    protected function getHeaderActions(): array
    {
        return [

            Actions\Action::make('download-pdf')
                ->label('Export PDF')
                ->color('danger')
                ->icon('heroicon-o-document-arrow-down')
                ->openUrlInNewTab()
                ->hidden(function () {
                    $filters = $this->getTable()->getFiltersForm()->getState();

                    return $filters['kelas']['value'] == null ? true : false;
                })
                ->url(function () {
                    $query = $this->getFilteredTableQuery();
                    $ids = $query->pluck('id')->toArray();
                    $idsParam = urlencode(json_encode($ids));
                    if (count($ids) > 0) {
                        return route('export.absen-siswa', ['type' => 'pdf', 'ids' => $idsParam]);
                    }
                }),
                
            Actions\Action::make('download-exel')
                ->label('Export exel')
                ->color('primary')
                ->icon('heroicon-o-document-arrow-down')
                ->openUrlInNewTab()
                ->hidden(function () {
                    $filters = $this->getTable()->getFiltersForm()->getState();

                    return $filters['kelas']['value'] == null ? true : false;
                })
                ->url(function () {
                    $query = $this->getFilteredTableQuery();
                    $ids = $query->pluck('id')->toArray();
                    $idsParam = urlencode(json_encode($ids));
                    if (count($ids) > 0) {
                        return route('export.absen-siswa', ['type' => 'exel', 'ids' => $idsParam]);
                    }
                }),

            Actions\CreateAction::make()
                ->label('Tambah Data')
                ->color('success'),
        ];
    }
}
