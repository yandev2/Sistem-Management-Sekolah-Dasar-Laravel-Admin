<?php

namespace App\Filament\Resources\AbsenGuruModelResource\Pages;

use App\Filament\Resources\AbsenGuruModelResource;
use App\Filament\Resources\AbsenGuruModelResource\Widgets\Widget1;
use Filament\Actions;
use Filament\Pages\Concerns\ExposesTableToWidgets;
use Filament\Resources\Pages\ListRecords;

class ListAbsenGuruModels extends ListRecords
{
    protected static string $resource = AbsenGuruModelResource::class;
    protected static ?string $title = 'Absensi Guru';
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

                    return $filters['created_at']['Dari tanggal'] == null ? true : false;
                })
                ->url(function () {
                    $query = $this->getFilteredTableQuery();
                    $ids = $query->pluck('id')->toArray();
                    $idsParam = urlencode(json_encode($ids));
                    if (count($ids) > 0) {
                        return route('export.absen-guru', ['type' => 'pdf', 'ids' => $idsParam]);
                    }
                }),
            Actions\Action::make('download-exel')
                ->label('Export Exel')
                ->color('primary')
                ->icon('heroicon-o-document-arrow-down')
                ->openUrlInNewTab()
                ->hidden(function () {
                    $filters = $this->getTable()->getFiltersForm()->getState();

                    return $filters['created_at']['Dari tanggal'] == null ? true : false;
                })
                ->url(function () {
                    $query = $this->getFilteredTableQuery();
                    $ids = $query->pluck('id')->toArray();
                    $idsParam = urlencode(json_encode($ids));
                    if (count($ids) > 0) {
                        return route('export.absen-guru', ['type' => 'exel', 'ids' => $idsParam]);
                    }
                }),
            Actions\CreateAction::make()
                ->label('Tambah Data')
                ->color('success'),
        ];
    }
}
