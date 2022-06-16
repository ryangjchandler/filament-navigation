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
    
    protected array $scripts = [
        'alpine-tooltip' => 'https://cdn.jsdelivr.net/npm/@ryangjchandler/alpine-tooltip@1.x.x/dist/cdn.min.js',
    ];
    
    protected array $styles = [
        'alpine-tooltip' => 'https://unpkg.com/tippy.js@6/dist/tippy.css',
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
}
