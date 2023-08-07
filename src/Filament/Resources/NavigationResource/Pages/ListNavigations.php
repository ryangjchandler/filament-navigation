<?php

namespace RyanChandler\FilamentNavigation\Filament\Resources\NavigationResource\Pages;

use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use RyanChandler\FilamentNavigation\FilamentNavigation;

class ListNavigations extends ListRecords
{
    public static function getResource(): string
    {
        return FilamentNavigation::get()->getResource();
    }

    protected function getActions(): array
    {
        return [
            CreateAction::make('create'),
        ];
    }
}
