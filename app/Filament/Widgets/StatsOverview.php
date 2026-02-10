<?php

namespace App\Filament\Widgets;

use App\Models\Transaction;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Illuminate\Support\Carbon;

class StatsOverview extends BaseWidget
{
    // Trait wajib agar widget bisa membaca filter dari halaman Dashboard
    use InteractsWithPageFilters;

    protected function getStats(): array
    {
        // 1. Mengambil rentang tanggal dari filter Dashboard
        $startDate = $this->filters['startDate'] ?? null;
        $endDate = $this->filters['endDate'] ?? null;

        // 2. Membuat query dasar dengan filter tanggal
        $baseQuery = Transaction::query()
            ->when($startDate, fn($q) => $q->whereDate('waktu_pesanan', '>=', $startDate))
            ->when($endDate, fn($q) => $q->whereDate('waktu_pesanan', '<=', $endDate));

        // 3. Menghitung data spesifik Shopee
        $shopeeQuery = (clone $baseQuery)->where('marketplace', 'Shopee');
        $shopeeOrder = $shopeeQuery->count();
        $shopeeOmzet = $shopeeQuery->sum('total_harga');

        // 4. Menghitung data spesifik Tokopedia
        $tokopediaQuery = (clone $baseQuery)->where('marketplace', 'Tokopedia');
        $tokopediaOrder = $tokopediaQuery->count();
        $tokopediaOmzet = $tokopediaQuery->sum('total_harga');

        // 5. Menghitung Gabungan
        $totalOmzet = $baseQuery->sum('total_harga');

        return [
            // Stat Shopee
            Stat::make('Order Shopee', $shopeeOrder . ' Pesanan')
                ->description('Omzet: Rp ' . number_format($shopeeOmzet, 0, ',', '.'))
                ->descriptionIcon('heroicon-m-shopping-bag')
                ->color('warning'),

            // Stat Tokopedia
            Stat::make('Order Tokopedia', $tokopediaOrder . ' Pesanan')
                ->description('Omzet: Rp ' . number_format($tokopediaOmzet, 0, ',', '.'))
                ->descriptionIcon('heroicon-m-shopping-cart')
                ->color('success'),

            // Stat Gabungan (Total)
            Stat::make('Total Omzet Gabungan', 'Rp ' . number_format($totalOmzet, 0, ',', '.'))
                ->description('Total dari semua marketplace')
                ->descriptionIcon('heroicon-m-banknotes')
                ->chart([7, 3, 10, 5, 15, 8, 20]) // Grafik tren sederhana
                ->color('info'),
        ];
    }
}