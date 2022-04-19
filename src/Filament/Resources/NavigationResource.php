<?php

namespace RyanChandler\FilamentNavigation\Filament\Resources;

use Closure;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\View;
use Filament\Forms\Components\ViewField;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;
use RyanChandler\FilamentNavigation\Models\Navigation;

class NavigationResource extends Resource
{
    protected static ?string $model = Navigation::class;

    protected static ?string $navigationIcon = 'heroicon-o-menu';

    protected static bool $showTimestamps = true;

    public static function disableTimestamps(bool $condition = true): void
    {
        static::$showTimestamps = ! $condition;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make([
                    TextInput::make('name')
                        ->reactive()
                        ->afterStateUpdated(function (?string $state, Closure $set) {
                            if (! $state) {
                                return;
                            }

                            $set('handle', Str::slug($state));
                        })
                        ->required(),
                    ViewField::make('items')
                        ->default([])
                        ->view('filament-navigation::navigation-builder'),
                ])
                    ->columnSpan([
                        12,
                        'lg' => 8,
                    ]),
                Group::make([
                    Card::make([
                        TextInput::make('handle')
                            ->required()
                            ->unique(column: 'handle', ignoreRecord: true),
                        View::make('filament-navigation::card-divider')
                            ->visible(static::$showTimestamps),
                        Placeholder::make('created_at')
                            ->visible(static::$showTimestamps)
                            ->content(fn (?Navigation $record) => $record ? $record->created_at->format(config('tables.date_time_format')) : new HtmlString('&mdash;')),
                        Placeholder::make('updated_at')
                            ->visible(static::$showTimestamps)
                            ->content(fn (?Navigation $record) => $record ? $record->updated_at->format(config('tables.date_time_format')) : new HtmlString('&mdash;')),
                    ]),
                ])
                    ->columnSpan([
                        12,
                        'lg' => 4,
                    ]),
            ])
            ->columns(12);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('handle')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([

            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => NavigationResource\Pages\ListNavigations::route('/'),
            'create' => NavigationResource\Pages\CreateNavigation::route('/create'),
            'edit' => NavigationResource\Pages\EditNavigation::route('/{record}'),
        ];
    }
}
