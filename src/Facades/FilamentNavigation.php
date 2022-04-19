<?php

namespace RyanChandler\FilamentNavigation\Facades;

use Illuminate\Support\Facades\Facade;
use RyanChandler\FilamentNavigation\FilamentNavigationManager;

/**
 * @method static \RyanChandler\FilamentNavigation\FilamentNavigationManager addItemType(string $name, array | \Closure $fields = [])
 * @method static array getItemTypes()
 * @method static \RyanChandler\FilamentNavigation\Models\Navigation|null get(string $handle)
 */
class FilamentNavigation extends Facade
{
    protected static function getFacadeAccessor()
    {
        return FilamentNavigationManager::class;
    }
}
