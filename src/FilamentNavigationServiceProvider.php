<?php

namespace RyanChandler\FilamentNavigation;

use Filament\PluginServiceProvider;
use RyanChandler\FilamentNavigation\Filament\Resources\NavigationResource;

class FilamentNavigationServiceProvider extends PluginServiceProvider
{
    public static string $name = 'filament-navigation';

    protected array $resources = [
        NavigationResource::class,
    ];

    public function packageBooted(): void
    {
        $this->loadMigrationsFrom([
            __DIR__ . '/../database/migrations',
        ]);

        parent::packageBooted();
    }
}
