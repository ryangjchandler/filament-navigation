<?php

namespace RyanChandler\FilamentNavigation;

use Filament\Support\Assets\Css;
use Filament\Support\Assets\Js;
use Filament\Support\Facades\FilamentAsset;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class FilamentNavigationServiceProvider extends PackageServiceProvider
{
    public static string $name = 'filament-navigation';

    public function packageRegistered(): void
    {
        $this->app->scoped(FilamentNavigationManager::class);

        parent::packageRegistered();
    }

    public function packageBooted(): void
    {
        $this->loadMigrationsFrom([
            __DIR__ . '/../database/migrations',
        ]);

        FilamentAsset::register([
            Css::make('navigation-styles', __DIR__.'/../resources/dist/plugin.css'),
            Js::make('navigation-scripts', __DIR__.'/../resources/dist/plugin.js'),
        ], 'filament-navigation');
    }

    public function configurePackage(Package $package): void
    {
        $package
            ->name(static::$name)
            ->hasAssets()
            ->hasTranslations()
            ->hasViews('filament-navigation');
    }
}
