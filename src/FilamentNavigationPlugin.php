<?php

namespace RyanChandler\FilamentNavigation;

use Filament\Contracts\Plugin;
use Filament\Panel;
use RyanChandler\FilamentNavigation\Filament\Resources\NavigationResource;
use RyanChandler\FilamentNavigation\Models\Navigation;

class FilamentNavigationPlugin implements Plugin
{
    protected string $navigationResource = NavigationResource::class;
    protected string $navigationModel = Navigation::class;

    public function getId(): string
    {
        return 'filament-navigation';
    }

    public function register(Panel $panel): void
    {
        $panel->resources([
            $this->getNavigationResource(),
        ]);
    }

    public static function make(): static
    {
        return app(static::class);
    }

    public function navigationResource(string $resource): static
    {
        $this->navigationResource = $resource;

        return $this;
    }

    public function getNavigationResource(): string
    {
        return $this->navigationResource;
    }

    public function navigationModel(string $model): static
    {
        $this->navigationModel = $model;

        return $this;
    }

    public function getNavigationModel(): string
    {
        return $this->navigationModel;
    }

    public static function get(): static
    {
        return filament(app(static::class)->getId());
    }

    public function boot(Panel $panel): void
    {
        //
    }
}
