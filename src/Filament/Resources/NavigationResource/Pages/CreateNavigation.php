<?php

namespace RyanChandler\FilamentNavigation\Filament\Resources\NavigationResource\Pages;

use Filament\Facades\Filament;
use Filament\Resources\Pages\CreateRecord;
use RyanChandler\FilamentNavigation\Filament\Resources\NavigationResource;
use RyanChandler\FilamentNavigation\Filament\Resources\NavigationResource\Pages\Concerns\HandlesNavigationBuilder;
use RyanChandler\FilamentNavigation\FilamentNavigation;

class CreateNavigation extends CreateRecord
{
    use HandlesNavigationBuilder;

    public static function getResource(): string
    {
        if (Filament::hasPlugin('navigation')) {
            return FilamentNavigation::get()->getResource();
        }

        return NavigationResource::class;
    }
}
