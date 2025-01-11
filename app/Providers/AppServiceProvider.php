<?php

namespace App\Providers;

use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\EditAction;
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
        CreateAction::configureUsing(fn($action) => $action->slideOver());
        EditAction::configureUsing(fn($action) => $action->slideOver());
    }
}
