<?php

namespace App\Filament\Widgets;

use App\Models\Transaction;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model; // Tambahkan ini

class TopCustomers extends BaseWidget
{
    protected static ?int $sort = 3;
    protected int|string|array $columnSpan = 'full';
    protected static ?string $heading = 'Top 10 Pelanggan Paling Sering Order';

    // Matikan auto-refresh agar tidak berat
    protected static ?string $pollingInterval = null;

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Transaction::query()
                    ->select('username', DB::raw('count(*) as total_order'), DB::raw('sum(total_harga) as total_belanja'))
                    ->where('marketplace', 'Shopee')
                    ->whereNotNull('username')
                    ->groupBy('username')
                    ->orderByDesc('total_order')
                    ->limit(10)
            )
            ->columns([
                Tables\Columns\TextColumn::make('username')
                    ->label('Username')
                    ->weight('bold')
                    ->icon('heroicon-m-user'),

                Tables\Columns\TextColumn::make('total_order')
                    ->label('Frekuensi')
                    ->alignCenter()
                    ->badge()
                    ->color('success'),

                Tables\Columns\TextColumn::make('total_belanja')
                    ->label('Total Belanja')
                    ->money('IDR')
                    ->alignRight(),
            ])
            ->paginated(false);
    }

    // --- INI SOLUSI PERBAIKANNYA ---
    // Fungsi ini memaksa Filament menggunakan 'username' sebagai ID unik
    public function getTableRecordKey(Model $record): string
    {
        return $record->username;
    }
}