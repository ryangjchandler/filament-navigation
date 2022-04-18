<?php

namespace RyanChandler\FilamentNavigation\Facades;

use Illuminate\Support\Facades\Facade;
use RyanChandler\FilamentNavigation\FilamentNavigationManager;

class FilamentNavigation extends Facade
{
    protected static function getFacadeAccessor()
    {
        return FilamentNavigationManager::class;
    }
}
