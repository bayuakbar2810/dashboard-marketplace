<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Pages\Dashboard\Concerns\HasFiltersAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section; // Pastikan ini dari Forms, bukan Infolists
use Filament\Forms\Form;

class Dashboard extends BaseDashboard
{
    use HasFiltersAction;

    /**
     * Konfigurasi Form Filter Tanggal
     */
    public function filtersForm(Form $form): Form
    {
        return $form
            ->schema([
                // Pastikan Section ini digunakan dalam konteks Form
                Section::make('Filter Tanggal Analisa')
                    ->schema([
                        DatePicker::make('startDate')
                            ->label('Dari Tanggal')
                            ->default(now()->startOfMonth()),
                        DatePicker::make('endDate')
                            ->label('Sampai Tanggal')
                            ->default(now()),
                    ])
                    ->columns(2),
            ]);
    }
}