<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\StatsKehadiran;
use Filament\Pages\Page;
use Filament\Widgets\AccountWidget;

class Dashboard extends \Filament\Pages\Dashboard
{
    protected static ?string $navigationIcon = 'heroicon-o-squares-2x2';

    protected static ?string $navigationLabel = 'Dashboard';
    public function getHeaderWidgetsColumns(): int | array
    {
        return 3;
    }
    public function getColumns(): int | string | array
    {

        return 3;
    }
    public function getFooterWidgetsColumns(): int | array
    {
        return 1;
    }

    public function getWidgets(): array
    {
        return [
            AccountWidget::class,
            StatsKehadiran::class

        ];
    }


    protected function getFooterWidgets(): array
    {
        return [];
    }
}
