<?php

namespace App\Providers;

use Filament\Tables\Columns\Column;
use Filament\Tables\Columns\TextColumn;
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
        TextColumn::configureUsing(
            fn (TextColumn $column) => $column->placeholder('Неизвестно')
        );
    }
}
