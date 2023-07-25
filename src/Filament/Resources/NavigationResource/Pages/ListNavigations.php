<?php

namespace RyanChandler\FilamentNavigation\Filament\Resources\NavigationResource\Pages;

use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use RyanChandler\FilamentNavigation\FilamentNavigationPlugin;

class ListNavigations extends ListRecords
{
    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    public static function getResource(): string
    {
        return FilamentNavigationPlugin::get()->getNavigationResource();
    }
}
