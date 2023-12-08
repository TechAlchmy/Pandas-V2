<?php

namespace App\Providers;

use App\Models\EnvVar;
use App\Services\CartService;
use Carbon\CarbonImmutable;
use Filament\Support\Colors\Color;
use Filament\Support\Facades\FilamentColor;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\ServiceProvider;
use Spatie\ModelInfo\ModelFinder;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        if ($this->app->environment('local')) {
            $this->app->register(\Laravel\Telescope\TelescopeServiceProvider::class);
            $this->app->register(TelescopeServiceProvider::class);
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Date::use(CarbonImmutable::class);
        Model::unguard();
        Model::shouldBeStrict(!app()->isProduction());
        Relation::enforceMorphMap(ModelFinder::all()->reject(fn ($model) => in_array($model, [EnvVar::class]))->all());
        FilamentColor::register([
            'primary' => Color::Neutral
        ]);
        $this->app->singleton(CartService::class, CartService::class);
    }
}
