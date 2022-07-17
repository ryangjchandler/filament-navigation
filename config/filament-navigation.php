<?php
// config for RyanChandler/FilamentNavigation
return [

    /*
    |--------------------------------------------------------------------------
    | Filament Navigation Db Engine
    |--------------------------------------------------------------------------
    |
    | Available options : 'classical', 'orbit'
    |
    | With this settings you could choose to use :
    | - classical db driver like mysql, postgresql (use 'classical')
    | - or Orbit (use 'orbit')
    |
    | By default, the plugin use the 'classical' one
    |
    */    
    'db_engine' => env('FILAMENT_NAVIGATION_DB_ENGINE', 'classical')
];
