# Upgrade Guide

## Upgrading from v0.x to v1.0

Starting with version v1.0, this package now only supports Filament v3.x.

Follow these steps to update the package for Filament v3.x.

1. Update the package version in your `composer.json`.
2. Run `composer update`.
3. Register the plugin inside of your project's `PanelProvider`.

```php
use RyanChandler\FilamentNavigation\FilamentNavigation;

public function panel(Panel $panel): Panel
{
    return $panel
        ->plugin(FilamentNavigation::make())
        // ...
}
```

4. Publish the plugin assets.

```sh
php artisan filament:assets
```

5. Open your panel and check that the resource has been registered and existing navigation menus are showing.
