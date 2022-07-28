# Build structured navigation menus in Filament.

[![Latest Version on Packagist](https://img.shields.io/packagist/v/ryangjchandler/filament-navigation.svg?style=flat-square)](https://packagist.org/packages/ryangjchandler/filament-navigation)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/ryangjchandler/filament-navigation/run-tests?label=tests)](https://github.com/ryangjchandler/filament-navigation/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/workflow/status/ryangjchandler/filament-navigation/Check%20&%20fix%20styling?label=code%20style)](https://github.com/ryangjchandler/filament-navigation/actions?query=workflow%3A"Check+%26+fix+styling"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/ryangjchandler/filament-navigation.svg?style=flat-square)](https://packagist.org/packages/ryangjchandler/filament-navigation)

This plugin for Filament provides a `Navigation` resource that allows to build structural navigation menus with ease.

## Installation

Begin by installing this package via Composer:

```sh
composer require ryangjchandler/filament-navigation
```

Run Migration:

```sh
php artisan migrate
```

Publish the package's assets:

```sh
php artisan vendor:publish --tag="filament-navigation-assets"
```

## Usage

The `NavigationResource` is automatically registered with Filament so no configuration is required to start using it.

If you wish to customise the navigation group, sort or icon, you can use the respective `NavigationResource::navigationGroup()`, `NavigationResource::navigationSort()` and `NavigationResource::navigationIcon()` methods.

### Data structure

Each navigation menu is required to have a `name` and `handle`. The `handle` should be unique and used to retrieve the menu.

Items are stored inside of a JSON column called `items`. This is a recursive data structure that looks like:

```json
[
    {
        "label": "Home",
        "type": "external-link",
        "data": {
            "url": "/",
            "target": "_blank",
        },
        "children": [
            // ...
        ],
    }
]
```

The recursive structure makes it really simple to render nested menus / dropdowns:

```blade
<ul>
    @foreach($menu->items as $item)
        <li>
            {{ $item['label'] }}

            @if($item['children'])
                <ul>
                    {{-- Render the item's children here... --}}
                </ul>
            @endif
        </li>
    @endforeach
</ul>
```

### Retrieving a navigation object

To retrieve a navigation object, provide the handle to the `RyanChandler\FilamentNavigation\Facades\FilamentNavigation::get()` method.

```php
use RyanChandler\FilamentNavigation\Facades\FilamentNavigation;

$menu = FilamentNavigation::get('main-menu');
```

### Custom item types

Out of the box, this plugin comes with a single "item type" called "External link". This item type expects a URL to be provided and an optional "target" (same tab or new tab).

It's possible to extend the plugin with custom item types. Custom item types have a name and an array of Filament field objects that will be displayed inside of the "Item" modal.

This API allows you to deeply integrate navigation menus with your application's own entities and models.

```php
use RyanChandler\FilamentNavigation\Facades\FilamentNavigation;

FilamentNavigation::addItemType('Post link', [
    Select::make('post_id')
        ->searchable()
        ->options(function () {
            return Post::pluck('title', 'id');
        })
]);
```

All custom fields for the item type can be found inside of the `data` property on the item.

### The `Navigation` field type

This plugin also provides a custom Filament field that can be used to search and select a navigation menu inside other forms and resources.

```php
use RyanChandler\FilamentNavigation\Filament\Fields\NavigationSelect;

->schema([
    NavigationSelect::make('navigation_id'),
])
```

By default, this field will not be searchable and the value for each option will be the menu `id`.

To make the field searchable, call the `->searchable()` method.

```php
->schema([
    NavigationSelect::make('navigation_id')
        ->searchable(),
])
```

If you wish to change the value for each option, call the `->optionValue()` method.

```php
->schema([
    NavigationSelect::make('navigation_id')
        ->optionValue('handle'),
])
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Ryan Chandler](https://github.com/ryangjchandler)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
