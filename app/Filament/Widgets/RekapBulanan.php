<?php

namespace App\Filament\Widgets;

use Filament\Widgets\TableWidget as BaseWidget;
use Filament\Tables;
use Filament\Tables\Table;
use App\Models\Transaction;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Illuminate\Support\Facades\DB;

class RekapBulanan extends BaseWidget
{
    use InteractsWithPageFilters;

    protected static ?string $heading = 'Rincian Penjualan per Marketplace';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Transaction::query()
                    ->select(
                        'marketplace',
                        DB::raw("COUNT(*) as total_order"),
                        DB::raw("SUM(total_harga) as total_omzet")
                    )
                    // PENGAMAN: Cek dulu apakah array filters dan kuncinya ada
                    ->when(
                        $this->filters['startDate'] ?? null,
                        fn($q, $date) => $q->whereDate('waktu_pesanan', '>=', $date)
                    )
                    ->when(
                        $this->filters['endDate'] ?? null,
                        fn($q, $date) => $q->whereDate('waktu_pesanan', '<=', $date)
                    )
                    ->groupBy('marketplace')
            )
            ->columns([
                Tables\Columns\TextColumn::make('marketplace')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'Shopee' => 'warning',
                        'Tokopedia' => 'success',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('total_order')
                    ->label('Jumlah Pesanan')
                    ->alignCenter(),
                Tables\Columns\TextColumn::make('total_omzet')
                    ->label('Total Omzet')
                    ->money('IDR'),
            ]);
    }
}