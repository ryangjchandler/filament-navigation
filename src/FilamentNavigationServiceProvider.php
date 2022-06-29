<?php

namespace RyanChandler\FilamentNavigation;

use Filament\PluginServiceProvider;
use RyanChandler\FilamentNavigation\Filament\Resources\NavigationResource;
use Spatie\LaravelPackageTools\Package;

class FilamentNavigationServiceProvider extends PluginServiceProvider
{
    public static string $name = 'filament-navigation';

    protected array $resources = [
        NavigationResource::class,
    ];

    protected function getStyles(): array
    {
        return [
            asset('vendor/filament-navigation/plugin.css'),
        ];
    }

    public function packageConfigured(Package $package): void
    {
        $package->hasAssets();
    }

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

        parent::packageBooted();
    }

    public function boot()
    {
        parent::boot();

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/Models/Orbit/Navigation.php' => 'app/Models/Navigation.php',
            ], 'filament-navigation-model-with-orbit');
            $this->publishes([
                __DIR__ . '/Models/Classical/Navigation.php' => 'app/Models/Navigation.php',
            ], 'filament-navigation-model-classical');
        }
    }
}
