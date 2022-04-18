<?php

namespace RyanChandler\FilamentNavigation\Filament\Resources;

use Closure;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ViewField;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Illuminate\Support\HtmlString;
use RyanChandler\FilamentNavigation\Models\Navigation;

class NavigationResource extends Resource
{
    protected static ?string $model = Navigation::class;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make([
                    TextInput::make('name')
                        ->required(),
                    ViewField::make('items')
                        ->view('filament-navigation::navigation-builder'),
                ])
                    ->columnSpan(8),
                Group::make([
                    Card::make([
                        Placeholder::make('created_at')
                            ->content(fn (?Navigation $record) => $record ? $record->created_at->format('Y-m-d') : new HtmlString('&mdash;')),
                        Placeholder::make('updated_at')
                            ->content(fn (?Navigation $record) => $record ? $record->updated_at->format('Y-m-d') : new HtmlString('&mdash;')),
                    ])
                ])
                    ->columnSpan(4)
            ])
            ->columns(12);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

            ])
            ->filters([

            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => NavigationResource\Pages\ListNavigations::route('/'),
            'create' => NavigationResource\Pages\CreateNavigation::route('/create'),
        ];
    }
}
