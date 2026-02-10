<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Actions\ImportAction;
use App\Filament\Imports\TokopediaImporter;
use App\Filament\Widgets\StatsOverview;
use App\Filament\Widgets\RekapBulanan;

class LaporanTokopedia extends BaseDashboard
{
    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';
    protected static ?string $navigationLabel = 'Laporan Tokopedia';
    protected static ?string $title = 'Laporan Tokopedia';
    protected static ?int $navigationSort = 3;
    protected static string $routePath = 'laporan-tokopedia';

    protected function getHeaderActions(): array
    {
        return [
            ImportAction::make('upload_tokopedia')
                ->label('Upload CSV Tokopedia')
                ->importer(TokopediaImporter::class)
                ->color('success')
                ->icon('heroicon-m-arrow-up-tray'),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            StatsOverview::class,
        ];
    }

    protected function getFooterWidgets(): array
    {
        return [
            RekapBulanan::class,
        ];
    }

    public function getWidgetData(): array
    {
        return [
            'marketplace' => 'Tokopedia',
        ];
    }
}