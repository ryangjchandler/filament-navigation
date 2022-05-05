@props(['item', 'statePath', 'nested' => false, 'moveUp' => false, 'moveDown' => false, 'indent' => false])

<div
    wire:key="{{ $statePath }}"
    class="space-y-2"
>
    <div class="relative group">
        @if ($nested)
            <span class="absolute -ml-3 text-xs text-gray-300 -translate-y-1/2 top-1/2">
                &ndash;
            </span>
        @endif

        <button
            type="button"
            wire:click="editItem('{{ $statePath }}')"
            @class([
                'appearance-none w-full bg-white rounded-lg border border-gray-300 px-3 py-2 text-left',
                'dark:bg-gray-700 dark:border-gray-600' => config('filament.dark_mode'),
            ])
        >
            {{ $item['label'] }}
        </button>

        <div @class([
            'absolute top-0 right-0 h-6 divide-x rounded-bl-lg rounded-tr-lg border-gray-300 border-b border-l overflow-hidden rtl:border-l-0 rtl:border-r rtl:right-auto rtl:left-0 rtl:rounded-bl-none rtl:rounded-br-lg rtl:rounded-tr-none rtl:rounded-tl-lg hidden opacity-0 group-hover:opacity-100 group-hover:flex transition ease-in-out duration-250',
            'dark:border-gray-600 dark:divide-gray-600' => config('filament.dark_mode'),
        ])>
            <button
                x-init
                x-tooltip.raw.duration.0="Add child"
                type="button"
                wire:click="addChild('{{ $statePath }}')"
                class="p-1"
                title="Add child"
            >
                <x-heroicon-o-plus class="w-3 h-3 text-gray-500 hover:text-gray-900" />
            </button>

            @if ($moveUp)
                <button
                    x-init
                    x-tooltip.raw.duration.0="Move up"
                    type="button"
                    wire:click="moveItemUp('{{ $statePath }}')"
                    class="p-1"
                    title="Move up"
                >
                    <x-heroicon-o-arrow-up class="w-3 h-3 text-gray-500 hover:text-gray-900" />
                </button>
            @endif

            @if ($moveDown)
                <button
                    x-init
                    x-tooltip.raw.duration.0="Move down"
                    type="button"
                    wire:click="moveItemDown('{{ $statePath }}')"
                    class="p-1"
                    title="Move down"
                >
                    <x-heroicon-o-arrow-down class="w-3 h-3 text-gray-500 hover:text-gray-900" />
                </button>
            @endif

            @if ($indent)
                <button
                    x-init
                    x-tooltip.raw.duration.0="Indent"
                    type="button"
                    wire:click="indentItem('{{ $statePath }}')"
                    class="p-1"
                    title="Indent"
                >
                    <x-heroicon-o-arrow-right class="w-3 h-3 text-gray-500 hover:text-gray-900" />
                </button>
            @endif

            @if ($nested)
                <button
                    x-init
                    x-tooltip.raw.duration.0="Dedent"
                    type="button"
                    wire:click="dedentItem('{{ $statePath }}')"
                    class="p-1"
                    title="Dedent"
                >
                    <x-heroicon-o-arrow-left class="w-3 h-3 text-gray-500 hover:text-gray-900" />
                </button>
            @endif

            <button
                x-init
                x-tooltip.raw.duration.0="Remove"
                type="button"
                wire:click="removeItem('{{ $statePath }}')"
                class="p-1"
                title="Remove"
            >
                <x-heroicon-o-trash class="w-3 h-3 text-danger-500 hover:text-danger-900" />
            </button>
        </div>
    </div>

    <div class="ml-3 space-y-2">
        <div class="pl-3 border-l border-gray-300"
            wire:key="{{ $statePath }}-children">
            @foreach ($item['children'] as $uuid => $child)
                <x-filament-navigation::nav-item :statePath="$statePath . '.children.' . $uuid"
                    :item="$child"
                    :moveUp="!$loop->first"
                    :moveDown="!$loop->last"
                    :indent="!$loop->first && $loop->count > 1"
                    nested />
            @endforeach
        </div>
    </div>
</div>
