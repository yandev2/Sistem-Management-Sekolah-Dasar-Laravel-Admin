<?php

namespace App\Filament\Resources\AbsenGuruModelResource\Widgets;

use App\Filament\Resources\AbsenGuruModelResource\Pages\ListAbsenGuruModels;
use App\Models\AbsenGuruModel;
use App\Models\User;
use EightyNine\FilamentAdvancedWidget\AdvancedStatsOverviewWidget as BaseWidget;
use EightyNine\FilamentAdvancedWidget\AdvancedStatsOverviewWidget\Stat;
use Filament\Widgets\Concerns\InteractsWithPageTable;

class Widget1 extends BaseWidget
{
    use InteractsWithPageTable;

     protected function getTablePage(): string
    {
        return ListAbsenGuruModels::class;
    }
    protected function getStats(): array
    {
        $hadir =  $this->getPageTableQuery()->where('absen_masuk', 'H')->count();
        $izin =  $this->getPageTableQuery()->where('absen_masuk', 'I')->count();
        $jumlah = User::role('guru', 'api')->count();
        return [
            Stat::make('Total Users', $hadir)
                ->backgroundColor('white')
                ->icon('heroicon-o-folder-open')
                ->label('Hadir')
                ->description('jumlah guru hadir hari ini')
                ->textColor('success', 'black', 'success')
                ->iconColor('success')
                ->chart([10, 10])
                ->chartColor('success'),
            Stat::make('Total Users', $izin)
                ->backgroundColor('white')
                ->icon('heroicon-o-folder-open')
                ->label('Izin')
                ->description('jumlah guru izin hari ini')
                ->textColor('info', 'black', 'info')
                ->iconColor('info')
                ->chart([10, 10])
                ->chartColor('info'),
            Stat::make('Total Users', $jumlah)
                ->backgroundColor('white')
                ->icon('heroicon-o-folder-open')
                ->label('Guru')
                ->description('jumlah guru')
                ->textColor('primary', 'black', 'primary')
                ->iconColor('primary')
                ->chart([10, 10])
                ->chartColor('primary'),
        ];
    }
}

//php artisan make:filament-advanced-widget Widget1 --resource=AbsenGuruModelResource

//php artisan make:filament-page Dashboard

