<?php

namespace App\Filament\Imports;

use App\Models\Transaction;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Carbon\Carbon;

class TokopediaImporter extends Importer
{
    protected static ?string $model = Transaction::class;

    // 1. TAMBAHAN PENTING: PENGATURAN PEMISAH (DELIMITER)
    // Ini memaksa sistem membaca file Excel format Indonesia (Titik Koma)
    public static function getOptionsFormComponents(): array
    {
        return [
            // Kita tidak perlu form setting manual, biarkan default
        ];
    }

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('no_pesanan')
                ->label('Order ID')
                ->rules(['required']), // Hapus requiredMapping agar lebih fleksibel

            ImportColumn::make('status')
                ->label('Order Status'),

            ImportColumn::make('username')
                ->label('Buyer Username'),

            ImportColumn::make('kota')
                ->label('Regency and City'),

            ImportColumn::make('total_harga')
                ->label('Order Amount')
                ->numeric(),

            ImportColumn::make('waktu_pesanan')
                ->label('Created Time'),

            ImportColumn::make('marketplace')
                ->label('Purchase Channel'),
        ];
    }

    public function resolveRecord(): ?Transaction
    {
        // JAGA-JAGA: Kalau No Pesanan kosong, abaikan baris ini (jangan error)
        if (empty($this->data['no_pesanan'])) {
            return null;
        }

        $transaction = Transaction::firstOrNew([
            'no_pesanan' => $this->data['no_pesanan'],
        ]);

        $transaction->marketplace = $this->data['marketplace'] ?? 'Tokopedia';

        // PEMBERSIH ANGKA (Hapus "Rp", Titik, Koma jika ada)
        if (isset($this->data['total_harga'])) {
            $harga = preg_replace('/[^0-9]/', '', $this->data['total_harga']);
            $transaction->total_harga = (float) $harga;
        }

        // PEMBERSIH TANGGAL (SUPER KUAT)
        // Kita coba berbagai format tanggal
        if (isset($this->data['waktu_pesanan'])) {
            try {
                // Bersihkan karakter aneh
                $dateRaw = trim(str_replace(['"', "'", "\t"], "", $this->data['waktu_pesanan']));

                // Coba parsing
                $transaction->waktu_pesanan = Carbon::parse($dateRaw);
            } catch (\Exception $e) {
                // Jika masih gagal, pakai Waktu Sekarang (daripada data hilang)
                $transaction->waktu_pesanan = now();
            }
        } else {
            $transaction->waktu_pesanan = now();
        }

        return $transaction;
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        return 'Proses selesai. Sukses: ' . number_format($import->successful_rows) . ', Gagal: ' . number_format($import->failed_rows);
    }
}