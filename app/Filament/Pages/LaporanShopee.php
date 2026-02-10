<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Actions\ImportAction;
use App\Filament\Imports\TransactionImporter;
use App\Filament\Widgets\StatsOverview;
use App\Filament\Widgets\RekapBulanan;

class LaporanShopee extends BaseDashboard
{
    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';
    protected static ?string $navigationLabel = 'Laporan Shopee';
    protected static ?string $title = 'Laporan Shopee';
    protected static ?int $navigationSort = 2;
    protected static string $routePath = 'laporan-shopee';

    protected function getHeaderActions(): array
    {
        return [
            ImportAction::make('upload_shopee')
                ->label('Upload CSV Shopee')
                ->importer(TransactionImporter::class)
                ->color('warning')
                ->icon('heroicon-m-arrow-up-tray'),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            StatsOverview::class, // Hanya panggil nama class
        ];
    }

    protected function getFooterWidgets(): array
    {
        return [
            RekapBulanan::class, // Hanya panggil nama class
        ];
    }

    // Fungsi ini yang akan mengirimkan filter 'Shopee' ke widget
    public function getWidgetData(): array
    {
        return [
            'marketplace' => 'Shopee',
        ];
    }
}