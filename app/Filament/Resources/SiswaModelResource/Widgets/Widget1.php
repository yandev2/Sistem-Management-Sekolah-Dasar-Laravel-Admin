<?php

namespace App\Filament\Resources\SiswaModelResource\Widgets;

use App\Models\SiswaModel;
use EightyNine\FilamentAdvancedWidget\AdvancedStatsOverviewWidget as BaseWidget;
use EightyNine\FilamentAdvancedWidget\AdvancedStatsOverviewWidget\Stat;

class Widget1 extends BaseWidget
{
    protected function getStats(): array
    {
        $jumlah = SiswaModel::count();
        $laki_laki = SiswaModel::where('jenis_kelamin', 'Laki-Laki')->count();
        $perempuan = SiswaModel::where('jenis_kelamin', 'Perempuan')->count();
        return [
            Stat::make('Total Users', $laki_laki . ' Siswa ')
                ->backgroundColor('white')
                ->icon('heroicon-o-folder-open')
                ->label('Siswa Laki Laki')
                ->description('jumlah siswa laki laki')
                ->textColor('success', 'black', 'success')
                ->iconColor('success')
                ->chart([10, 10])
                ->chartColor('success'),
            Stat::make('Total Users', $perempuan . ' Siswa')
                ->backgroundColor('white')
                ->icon('heroicon-o-folder-open')
                ->label('Siswa Perempuan')
                ->description('jumlah siswa perempuan')
                ->textColor('danger', 'black', 'danger')
                ->iconColor('danger')
                ->chart([10, 10])
                ->chartColor('danger'),
        ];
    }
}
