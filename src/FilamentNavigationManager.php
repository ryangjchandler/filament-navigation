<?php

namespace RyanChandler\FilamentNavigation;

use Closure;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Illuminate\Support\Str;
use Illuminate\Support\Traits\Macroable;
use RyanChandler\FilamentNavigation\Models\Navigation;

class FilamentNavigationManager
{
    use Macroable;

    protected array $itemTypes = [];

    public function addItemType(string $name, array | Closure $fields = []): static
    {
        $this->itemTypes[Str::slug($name)] = [
            'name' => 'name',
            'fields' => $fields,
        ];

        return $this;
    }

    public function get(string $handle): ?Navigation
    {
        return Navigation::firstWhere('handle', $handle);
    }

    public function getItemTypes(): array
    {
        return array_merge([
            'external-link' => [
                'name' => 'External link',
                'fields' => [
                    TextInput::make('url')
                        ->label('URL')
                        ->required(),
                    Select::make('target')
                        ->options([
                            '' => 'Same tab',
                            '_blank' => 'New tab',
                        ])
                        ->default(''),
                ],
            ],
        ], $this->itemTypes);
    }
}
