<?php

namespace RyanChandler\FilamentNavigation\Filament\Resources\NavigationResource\Pages;

use Filament\Forms\ComponentContainer;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Actions\Action;
use Filament\Pages\Actions\ButtonAction;
use Filament\Resources\Form;
use Filament\Resources\Pages\CreateRecord;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;
use RyanChandler\FilamentNavigation\Filament\Resources\NavigationResource;
use RyanChandler\FilamentNavigation\Filament\Resources\NavigationResource\Pages\Concerns\HandlesItemSorting;
use RyanChandler\FilamentNavigation\Filament\Resources\NavigationResource\Pages\Concerns\HandlesNavigationBuilder;

class EditNavigation extends EditRecord
{
    use HandlesNavigationBuilder;

    protected static string $resource = NavigationResource::class;

    protected function getActions(): array
    {
        return [
            Action::make('add')
                ->mountUsing(function (ComponentContainer $form) {
                    $form->fill($this->mountedItemData);
                })
                ->view('filament-navigation::hidden-action')
                ->form([
                    TextInput::make('label')
                        ->required(),
                    TextInput::make('url')
                        ->label('URL')
                        ->required(),
                    Select::make('target')
                        ->default('')
                        ->options([
                            '' => 'Same tab',
                            '_blank' => 'New tab',
                        ])
                        ->nullable(),
                ])
                ->modalWidth('md')
                ->action(function (array $data) {
                    if ($this->mountedItem) {
                        data_set($this, $this->mountedItem, array_merge(data_get($this, $this->mountedItem), $data));
                    } else {
                        $this->data['items'][(string) Str::uuid()] = [
                            ...$data,
                            ...['children' => []],
                        ];
                    }
                })
                ->modalButton('Save')
                ->label('Item')
        ];
    }
}
