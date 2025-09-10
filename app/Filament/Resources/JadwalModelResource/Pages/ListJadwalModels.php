<?php

namespace App\Filament\Resources\JadwalModelResource\Pages;

use App\Filament\Resources\JadwalModelResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListJadwalModels extends ListRecords
{
    protected static string $resource = JadwalModelResource::class;
    protected static ?string $title = 'Jadwal Pelajaran';

    protected function getHeaderActions(): array
    {
        return [
              Actions\Action::make('download-pdf')
                  ->label('Export PDF')
                 ->color('danger')
                  ->icon('heroicon-o-document-arrow-down')
                   ->openUrlInNewTab()
                  ->hidden(function () {
                      $filters = $this->tableFilters;
                     return empty($filters['kelas']['values'] ?? []);
                 })
                  ->url(function () {
                     $query = $this->getFilteredTableQuery();
                      $ids = $query->pluck('id')->toArray();
                    $idsParam = urlencode(json_encode($ids));
                     if (count($ids) > 0) {
                          return route('export.jadwal', ['ids' => $idsParam]);
                      }
                   }),
            Actions\CreateAction::make()
                ->label('Tambah Data')
                ->color('success'),
        ];
    }
}
