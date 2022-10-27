<?php

namespace RyanChandler\FilamentNavigation\Filament\Resources\NavigationResource\Pages\Concerns;

use Closure;
use Filament\Forms\ComponentContainer;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;

use Filament\Pages\Actions\Action;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use RyanChandler\FilamentNavigation\Facades\FilamentNavigation;

trait HandlesNavigationBuilder
{
    public $mountedItem;

    public $mountedItemData = [];

    public $mountedChildTarget;

    public function addChild(string $statePath)
    {
        $this->mountedChildTarget = $statePath;

        $this->mountAction('item');
    }

    public function removeItem(string $statePath)
    {
        $uuid = Str::afterLast($statePath, '.');

        $parentPath = Str::beforeLast($statePath, '.');
        $parent = data_get($this, $parentPath);

        data_set($this, $parentPath, Arr::except($parent, $uuid));
    }

    public function indentItem(string $statePath)
    {
        $item = data_get($this, $statePath);
        $uuid = Str::afterLast($statePath, '.');

        $parentPath = Str::beforeLast($statePath, '.');
        $parent = data_get($this, $parentPath);

        $keys = array_keys($parent);
        $position = array_search($uuid, $keys);

        $previous = $parent[$keys[$position - 1]];

        if (! isset($previous['children'])) {
            $previous['children'] = [];
        }

        $previous['children'][(string) Str::uuid()] = $item;
        $parent[$keys[$position - 1]] = $previous;

        data_set($this, $parentPath, Arr::except($parent, $uuid));
    }

    public function dedentItem(string $statePath)
    {
        $item = data_get($this, $statePath);
        $uuid = Str::afterLast($statePath, '.');

        $parentPath = Str::beforeLast($statePath, '.');
        $parent = data_get($this, $parentPath);

        $pathToMoveInto = Str::of($statePath)->beforeLast('.')->rtrim('.children')->beforeLast('.');
        $pathToMoveIntoData = data_get($this, $pathToMoveInto);

        $pathToMoveIntoData[(string) Str::uuid()] = $item;
        data_set($this, $pathToMoveInto, $pathToMoveIntoData);

        data_set($this, $parentPath, Arr::except($parent, $uuid));
    }

    public function moveItemUp(string $statePath)
    {
        $parentPath = Str::beforeLast($statePath, '.');
        $uuid = Str::afterLast($statePath, '.');

        $parent = data_get($this, $parentPath);
        $hasMoved = false;

        uksort($parent, function ($_, $b) use ($uuid, &$hasMoved) {
            if ($b === $uuid && ! $hasMoved) {
                $hasMoved = true;

                return 1;
            }

            return 0;
        });

        data_set($this, $parentPath, $parent);
    }

    public function moveItemDown(string $statePath)
    {
        $parentPath = Str::beforeLast($statePath, '.');
        $uuid = Str::afterLast($statePath, '.');

        $parent = data_get($this, $parentPath);
        $hasMoved = false;

        uksort($parent, function ($a, $_) use ($uuid, &$hasMoved) {
            if ($a === $uuid && ! $hasMoved) {
                $hasMoved = true;

                return 1;
            }

            return 0;
        });

        data_set($this, $parentPath, $parent);
    }

    public function editItem(string $statePath)
    {
        $this->mountedItem = $statePath;
        $this->mountedItemData = Arr::except(data_get($this, $statePath), 'children');

        $this->mountAction('item');
    }

    public function createItem()
    {
        $this->mountedItem = null;
        $this->mountedItemData = [];
        $this->mountedActionData = [];

        $this->mountAction('item');
    }

    protected function getActions(): array
    {
        return [
            Action::make('item')
                ->mountUsing(function (ComponentContainer $form) {
                    if (! $this->mountedItem) {
                        return;
                    }

                    $form->fill($this->mountedItemData);
                })
                ->view('filament-navigation::hidden-action')
                ->form([
                    TextInput::make('label')
                        ->label(__('filament-navigation::filament-navigation.items-modal.label'))
                        ->required(),
                    Select::make('type')
                        ->label(__('filament-navigation::filament-navigation.items-modal.type'))
                        ->options(function () {
                            $types = FilamentNavigation::getItemTypes();

                            return array_combine(array_keys($types), Arr::pluck($types, 'name'));
                        })
                        ->afterStateUpdated(function ($state, Select $component): void {
                            if (! $state) {
                                return;
                            }

                            // NOTE: This chunk of code is a workaround for Livewire not letting
                            //       you entangle to non-existent array keys, which wire:model
                            //       would normally let you do.
                            $component
                                ->getContainer()
                                ->getComponent(fn (Component $component) => $component instanceof Group)
                                ->getChildComponentContainer()
                                ->fill();
                        })
                        ->reactive(),
                    Group::make()
                        ->statePath('data')
                        ->schema(function (Closure $get) {
                            $type = $get('type');

                            return FilamentNavigation::getItemTypes()[$type]['fields'] ?? [];
                        }),
                ])
                ->modalWidth('md')
                ->action(function (array $data) {
                    if ($this->mountedItem) {
                        data_set($this, $this->mountedItem, array_merge(data_get($this, $this->mountedItem), $data));

                        $this->mountedItem = null;
                        $this->mountedItemData = [];
                    } elseif ($this->mountedChildTarget) {
                        $children = data_get($this, $this->mountedChildTarget . '.children', []);

                        $children[(string) Str::uuid()] = [
                            ...$data,
                            ...['children' => []],
                        ];

                        data_set($this, $this->mountedChildTarget . '.children', $children);

                        $this->mountedChildTarget = null;
                    } else {
                        $this->data['items'][(string) Str::uuid()] = [
                            ...$data,
                            ...['children' => []],
                        ];
                    }

                    $this->mountedActionData = [];
                })
                ->modalButton(__('filament-navigation::filament-navigation.items-modal.btn'))
                ->label(__('filament-navigation::filament-navigation.items-modal.title')),
        ];
    }
}
