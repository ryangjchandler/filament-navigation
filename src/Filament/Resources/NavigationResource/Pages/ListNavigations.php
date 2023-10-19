<?php

namespace RyanChandler\FilamentNavigation\Filament\Resources\NavigationResource\Pages;

use Filament\Actions\CreateAction;
use Filament\Facades\Filament;
use Filament\Resources\Pages\ListRecords;
use RyanChandler\FilamentNavigation\Filament\Resources\NavigationResource;
use RyanChandler\FilamentNavigation\FilamentNavigation;

class ListNavigations extends ListRecords
{
    public static function getResource(): string
    {
        if (Filament::hasPlugin('navigation')) {
            return FilamentNavigation::get()->getResource();
        }

        return NavigationResource::class;
    }

    protected function getActions(): array
    {
        return [
            CreateAction::make('create'),
        ];
    }
}
