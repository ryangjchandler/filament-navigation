<?php

use Livewire\Livewire;
use Pest\Expectation;

use function Pest\Laravel\assertDatabaseHas;

use RyanChandler\FilamentNavigation\Filament\Resources\NavigationResource\Pages\CreateNavigation;

use RyanChandler\FilamentNavigation\Models\Navigation;

it('can create a navigation menu', function () {
    Livewire::test(CreateNavigation::class)
        ->set('data.name', 'Foo')
        ->call('create')
        ->assertHasNoErrors();

    assertDatabaseHas(Navigation::class, [
        'name' => 'Foo',
        'handle' => 'foo',
    ]);
});

it('can create a navigation menu with items', function () {
    Livewire::test(CreateNavigation::class)
        ->set('data.name', 'Foo')
        ->call('mountAction', 'item')
        ->set('mountedActionData', [
            'label' => 'Bar',
            'type' => 'external-link',
            'data' => [
                'url' => '/bar',
            ],
        ])
        ->call('callMountedAction')
        ->call('create')
        ->assertHasNoErrors()
        ->assertSuccessful();

    expect(Navigation::first())
        ->toBeInstanceOf(Navigation::class)
        ->name->toBe('Foo')
        ->handle->toBe('foo')
        ->items
            ->toHaveLength(1)
            ->sequence(
                fn (Expectation $item) => $item
                    ->toHaveKey('label', 'Bar')
                    ->toHaveKey('type', 'external-link')
                    ->data->toMatchArray([
                        'url' => '/bar',
                    ])
            );
});

it('can create a navigation menu with items and show its linked resource when option in config is true', function () {

    config(['filament-navigation.show_linked_resource' => true]);

    Livewire::test(CreateNavigation::class)
        ->set('data.name', 'Foo')
        ->call('mountAction', 'item')
        ->set('mountedActionData', [
            'label' => 'Baz',
            'type' => 'external-link',
            'data' => [
                'url' => '/baz',
            ],
        ])
        ->call('callMountedAction')
        ->call('create')
        ->assertSee([__('filament-navigation::filament-navigation.attributes.external-link'), __('filament-navigation::filament-navigation.items.resource-url') . ' /baz'])
        ->assertHasNoErrors()
        ->assertSuccessful();
});

it('can create a navigation menu with items and dont show its linked resource when option in config is false', function () {

    config(['filament-navigation.show_linked_resource' => false]);

    Livewire::test(CreateNavigation::class)
        ->set('data.name', 'Foo')
        ->call('mountAction', 'item')
        ->set('mountedActionData', [
            'label' => 'Baz',
            'type' => 'external-link',
            'data' => [
                'url' => '/baz',
            ],
        ])
        ->call('callMountedAction')
        ->call('create')
        ->assertDontSee([__('filament-navigation::filament-navigation.attributes.external-link'), __('filament-navigation::filament-navigation.items.resource-url') . ' /baz'])
        ->assertHasNoErrors()
        ->assertSuccessful();
});
