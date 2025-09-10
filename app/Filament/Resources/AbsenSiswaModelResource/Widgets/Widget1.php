<?php

namespace App\Filament\Resources\AbsenSiswaModelResource\Widgets;

use App\Filament\Resources\AbsenSiswaModelResource\Pages\ListAbsenSiswaModels;
use App\Models\AbsenSiswaModel;
use EightyNine\FilamentAdvancedWidget\AdvancedStatsOverviewWidget as BaseWidget;
use EightyNine\FilamentAdvancedWidget\AdvancedStatsOverviewWidget\Stat;
use Filament\Widgets\Concerns\InteractsWithPageTable;

class Widget1 extends BaseWidget
{

    use InteractsWithPageTable;

    protected function getTablePage(): string
    {
        return ListAbsenSiswaModels::class;
    }
    protected function getStats(): array
    {

        $hadir = $this->getPageTableQuery()->where('status', 'H')->count();;
        $izin = $this->getPageTableQuery()->where('status', 'I')->count();
        $sakit =  $this->getPageTableQuery()->where('status', 'S')->count();
        $alpa =  $this->getPageTableQuery()->where('status', 'A')->count();

        return [
            Stat::make('Total Users', $hadir)
                ->backgroundColor('white')
                ->icon('heroicon-o-folder-open')
                ->label('Hadir')
                ->description('jumlah siswa hadir')
                ->textColor('success', 'black', 'success')
                ->iconColor('success')
                ->chart([10, 10])
                ->chartColor('success'),
            Stat::make('Total Users', $izin)
                ->backgroundColor('white')
                ->icon('heroicon-o-folder-open')
                ->label('Izin')
                ->description('jumlah siswa izin')
                ->textColor('info', 'black', 'info')
                ->iconColor('info')
                ->chart([10, 10])
                ->chartColor('info'),
            Stat::make('Total Users', $sakit)
                ->backgroundColor('white')
                ->icon('heroicon-o-folder-open')
                ->label('Sakit')
                ->description('jumlah siswa sakit')
                ->textColor('primary', 'black', 'primary')
                ->iconColor('primary')
                ->chart([10, 10])
                ->chartColor('primary'),
            Stat::make('Total Users', $alpa)
                ->backgroundColor('white')
                ->icon('heroicon-o-folder-open')
                ->label('Alpa')
                ->description('jumlah siswa alpa')
                ->textColor('danger', 'black', 'danger')
                ->iconColor('danger')
                ->chart([10, 10])
                ->chartColor('danger'),
        ];
    }
}

//php artisan make:filament-advanced-widget Widget1 --resource=SiswaModelResource