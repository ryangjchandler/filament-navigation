<?php

namespace RyanChandler\FilamentNavigation\Filament\Resources\NavigationResource\Pages\Concerns;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

use function Filament\Forms\array_move_after;
use function Filament\Forms\array_move_before;

trait HandlesNavigationBuilder
{
    public $mountedItem;

    public $mountedItemData = [];

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

        $this->mountAction('add');
    }
}
