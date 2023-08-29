<?php

namespace App\Providers;

use App\Services\CartService;
use Filament\Support\Colors\Color;
use Filament\Support\Facades\FilamentColor;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;
use Spatie\ModelInfo\ModelFinder;

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
        Model::unguard();
        Model::shouldBeStrict(! app()->isProduction());
        Relation::enforceMorphMap(ModelFinder::all()->all());
        FilamentColor::register([
            'primary' => Color::Neutral,
        ]);
        $this->app->singleton(CartService::class, CartService::class);
    }
}
