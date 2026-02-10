<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // --- TAMBAHKAN BARIS INI ---
        // Ini memaksa database agar "tidak kaku" (Strict Mode OFF)
        config(['database.connections.mysql.strict' => false]);
    }
}