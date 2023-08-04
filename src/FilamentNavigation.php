<?php

namespace RyanChandler\FilamentNavigation;

use Filament\Contracts\Plugin;
use Filament\Panel;
use Filament\PluginServiceProvider;
use RyanChandler\FilamentNavigation\Filament\Resources\NavigationResource;

class FilamentNavigation implements Plugin
{
    public static string $name = 'filament-navigation';

    protected array $styles = [
        'navigation-styles' => __DIR__ . '/../resources/dist/plugin.css',
    ];

    protected array $beforeCoreScripts = [
        'navigation-scripts' => __DIR__ . '/../resources/dist/plugin.js',
    ];

    public function getId(): string
    {
        return 'navigation';
    }

    public function register(Panel $panel): void
    {
        $panel
            ->resources($this->getResources());
    }

    public function boot(Panel $panel): void
    {
        //
    }

    public static function make(): static
    {
        return new static();
    }

    protected function getResources(): array
    {
        return [
            config('filament-navigation.navigation_resource') ?? NavigationResource::class,
        ];
    }
}
