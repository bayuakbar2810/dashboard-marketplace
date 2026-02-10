<?php

namespace App\Filament\Widgets;

use App\Models\Transaction;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class TopGudangChart extends ChartWidget
{
    protected static ?string $heading = 'Distribusi Pesanan per Gudang (Shopee)';

    // URUTAN KEDUA (Di bawah kotak angka)
    protected static ?int $sort = 2;

    // PENTING: AGAR GRAFIK LEBAR PENUH (TIDAK GEPENG)
    protected int|string|array $columnSpan = 'full';

    protected function getData(): array
    {
        $data = Transaction::select('nama_gudang', DB::raw('count(*) as total'))
            ->where('marketplace', 'Shopee')
            ->whereNotNull('nama_gudang')
            ->where('nama_gudang', '!=', '')
            ->groupBy('nama_gudang')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Pesanan',
                    'data' => $data->pluck('total'),
                    'backgroundColor' => '#3b82f6',
                ],
            ],
            'labels' => $data->pluck('nama_gudang'),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}