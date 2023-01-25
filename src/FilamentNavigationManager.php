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

    public function addItemType(string $name, array | Closure $fields = [], string | null $slug = null): static
    {

        $slug = empty($slug) ? Str::slug($name): $slug;

        $this->itemTypes[$slug] = [
            'name' => $name,
            'fields' => $fields,
        ];

        return $this;
    }

    public function get(string $handle): ?Navigation
    {
        return static::getModel()::firstWhere('handle', $handle);
    }

    public function getItemTypes(): array
    {
        return array_merge([
            'external-link' => [
                'name' => __('filament-navigation::filament-navigation.attributes.external-link'),
                'fields' => [
                    TextInput::make('url')
                        ->label(__('filament-navigation::filament-navigation.attributes.url'))
                        ->required(),
                    Select::make('target')
                        ->label(__('filament-navigation::filament-navigation.attributes.target'))
                        ->options([
                            '' => __('filament-navigation::filament-navigation.select-options.same-tab'),
                            '_blank' => __('filament-navigation::filament-navigation.select-options.new-tab'),
                        ])
                        ->default(''),
                ],
            ],
        ], $this->itemTypes);
    }

    public static function getModel(): string
    {
        return config('filament-navigation.navigation_model') ?? Navigation::class;
    }
}
