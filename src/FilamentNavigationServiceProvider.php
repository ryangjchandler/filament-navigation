<?php

namespace RyanChandler\FilamentNavigation;

use Filament\PluginServiceProvider;
use RyanChandler\FilamentNavigation\Filament\Resources\NavigationResource;

class FilamentNavigationServiceProvider extends PluginServiceProvider
{
    public static string $name = 'filament-navigation';

    protected array $styles = [
        'navigation-styles' => __DIR__ . '/../resources/dist/plugin.css',
    ];

    protected array $beforeCoreScripts = [
        'navigation-scripts' => __DIR__ . '/../resources/dist/plugin.js',
    ];

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

    protected function getResources(): array
    {
        return [
            config('filament-navigation.navigation_resource') ?? NavigationResource::class,
        ];
    }
}
