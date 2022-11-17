<?php

namespace RyanChandler\FilamentNavigation\Filament\Resources\NavigationResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use RyanChandler\FilamentNavigation\Filament\Resources\NavigationResource;
use RyanChandler\FilamentNavigation\Filament\Resources\NavigationResource\Pages\Concerns\HandlesNavigationBuilder;

class CreateNavigation extends CreateRecord
{
    use HandlesNavigationBuilder;

    public static function getResource(): string
    {
        return config('filament-navigation.navigation_resource') ?? NavigationResource::class;
    }
}
