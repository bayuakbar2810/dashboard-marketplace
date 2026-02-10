<?php

namespace App\Filament\Imports;

use App\Models\Transaction;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import; // <--- PENTING: Jangan lupa baris ini
use Carbon\Carbon;

class TransactionImporter extends Importer
{
    protected static ?string $model = Transaction::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('no_pesanan')
                ->label('No. Pesanan')
                ->rules(['required']),

            ImportColumn::make('status')->label('Status Pesanan'),
            ImportColumn::make('username')->label('Username (Pembeli)'),
            ImportColumn::make('kota')->label('Kota/Kabupaten'),
            ImportColumn::make('total_harga')->label('Total Pembayaran')->numeric(),
            ImportColumn::make('waktu_pesanan')->label('Waktu Pesanan Dibuat'),

            // KOLOM BARU SHOPEE
            ImportColumn::make('nama_gudang')->label('Nama Gudang'),
            ImportColumn::make('alasan_batal')->label('Alasan Pembatalan'),
            ImportColumn::make('waktu_pickup')->label('Waktu Pengiriman Diatur'),
        ];
    }

    public function resolveRecord(): ?Transaction
    {
        if (empty($this->data['no_pesanan'])) {
            return null;
        }

        $transaction = Transaction::firstOrNew([
            'no_pesanan' => $this->data['no_pesanan'],
        ]);

        $transaction->marketplace = 'Shopee';
        $transaction->nama_gudang = $this->data['nama_gudang'] ?? null;
        $transaction->alasan_batal = $this->data['alasan_batal'] ?? null;

        // Parsing Waktu Pickup
        if (isset($this->data['waktu_pickup'])) {
            try {
                $transaction->waktu_pickup = Carbon::parse($this->data['waktu_pickup']);
            } catch (\Exception $e) {
            }
        }

        // Parsing Waktu Pesanan
        if (isset($this->data['waktu_pesanan'])) {
            try {
                $transaction->waktu_pesanan = Carbon::parse($this->data['waktu_pesanan']);
            } catch (\Exception $e) {
                $transaction->waktu_pesanan = now();
            }
        }

        // Bersihkan Harga (Hapus Titik & Rp)
        if (isset($this->data['total_harga'])) {
            $transaction->total_harga = (float) preg_replace('/[^0-9]/', '', $this->data['total_harga']);
        }

        return $transaction;
    }

    // PERBAIKAN DI SINI: Gunakan 'Import $import' bukan 'Importer $import'
    public static function getCompletedNotificationBody(Import $import): string
    {
        return 'Import Shopee Selesai! ' . number_format($import->successful_rows) . ' data berhasil masuk.';
    }
}