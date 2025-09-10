<?php

namespace App\Filament\Widgets;

use App\Models\AbsenGuruModel;
use App\Models\AbsenSiswaModel;
use App\Models\KelasModel;
use App\Models\SiswaModel;
use App\Models\User;
use EightyNine\FilamentAdvancedWidget\AdvancedStatsOverviewWidget as BaseWidget;
use EightyNine\FilamentAdvancedWidget\AdvancedStatsOverviewWidget\Stat;

class StatsKehadiran extends BaseWidget
{
    protected function getStats(): array
    {
        $total = SiswaModel::count();
        $hadir = AbsenSiswaModel::whereDate('created_at', now())->where('status', 'H')->count();

        $total1 = User::count();
        $hadir1 = AbsenGuruModel::whereDate('created_at', now())->where('absen_masuk', 'H')->count();

        $jumlahKelas = KelasModel::count();
        return [
            Stat::make('Total Users', $hadir . ' dari ' . $total . ' siswa')
                ->backgroundColor('white')
                ->icon('heroicon-o-folder-open')
                ->label('Kehadiran')
                ->description('jumlah kehadiran siswa hari ini')
                ->textColor('primary', 'black', 'primary')
                ->iconColor('primary')
                ->chart([10, 10])
                ->chartColor('primary'),
            Stat::make('Total Users', $hadir1 . ' dari ' . $total1 . ' guru')
                ->backgroundColor('white')
                ->icon('heroicon-o-folder-open')
                ->label('Kehadiran')
                ->description('jumlah kehadiran guru hari ini')
                ->textColor('info', 'black', 'info')
                ->iconColor('info')
                ->chart([10, 10])
                ->chartColor('info'),
            Stat::make('Total Users', $jumlahKelas)
                ->backgroundColor('white')
                ->icon('heroicon-o-folder-open')
                ->label('Kelas')
                ->description('jumlah keseluruhan kelas')
                ->textColor('danger', 'black', 'danger')
                ->iconColor('danger')
                ->chart([10, 10])
                ->chartColor('danger'),
        ];
    }
}
