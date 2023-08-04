<?php

namespace RyanChandler\FilamentNavigation;

use Filament\Contracts\Plugin;
use Filament\Panel;
use RyanChandler\FilamentNavigation\Filament\Resources\NavigationResource;

class FilamentNavigation implements Plugin
{
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
