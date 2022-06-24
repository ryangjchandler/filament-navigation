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
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;
use RyanChandler\FilamentNavigation\Facades\FilamentNavigation;

trait HandlesNavigationBuilder
{
    public ?string $activeLocale = null;

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
        $languages = (new Collection(Config::get('filament-navigation.supported-locales', [Config::get('app.locale', 'en')])))
            ->mapWithKeys(fn (string $locale): array => [$locale => locale_get_display_name($locale, app()->getLocale())])
            ->toArray();

        $this->activeLocale = $this->activeLocale ?? Config::get('app.locale', array_keys($languages)[0]);

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
                    Select::make('activeLocale')
                        ->options($languages)
                        ->default($this->activeLocale)
                        ->disablePlaceholderSelection()
                        ->afterStateUpdated(function (\Closure $set, $state, $livewire) {
                            $this->activeLocale = $state;

                            $value = Arr::get($this->mountedItemData, 'label.' . $state, '');
                            $set('label', $value);
                        })
                        ->reactive(),
                    TextInput::make('label')
                        ->required()
                        ->afterStateHydrated(function (TextInput $component, $record) use ($languages) {
                            $path = implode('.', [$this->mountedItem, 'label']);
                            if (! is_array(data_get($this, $path))) {
                                data_set($this, $path, [
                                    $this->activeLocale => data_get($this, $path, ''),
                                ]);
                            }

                            $component->state(data_get($this, implode('.', [$path, $this->activeLocale])));
                        })
                        ->afterStateUpdated(function (\Closure $get, $state) {
                            Arr::set($this->mountedItemData, 'label.' . $this->activeLocale, $state);
                        })
                        ->reactive(),
                    Select::make('type')
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
                        data_set($this, $this->mountedItem, array_merge(
                            data_get($this, $this->mountedItem),
                            array_merge($data, ['label' => $this->mountedItemData['label']])
                        ));

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
                ->modalButton('Save')
                ->label('Item'),
        ];
    }
}
