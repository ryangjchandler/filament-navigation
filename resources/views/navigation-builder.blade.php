<x-forms::field-wrapper
    :id="$getId()"
    :label="$getLabel()"
    :label-sr-only="$isLabelHidden()"
    :helper-text="$getHelperText()"
    :hint="$getHint()"
    :hint-icon="$getHintIcon()"
    :required="$isRequired()"
    :state-path="$getStatePath()"
    class="filament-navigation"
>
    <div wire:key="navigation-items-wrapper">
        <div class="space-y-2">
            @foreach($getState() as $uuid => $item)
                <x-filament-navigation::nav-item :statePath="$getStatePath() . '.' . $uuid" :item="$item" :moveUp="!$loop->first" :moveDown="!$loop->last" :indent="!$loop->first && $loop->count > 1" />
            @endforeach
        </div>
    </div>

    <div class="flex justify-end">
        <x-filament::button wire:click="mountAction('add')" type="button" size="sm" color="secondary">
            Add Item
        </x-filament::button>
    </div>
</x-forms::field-wrapper>
