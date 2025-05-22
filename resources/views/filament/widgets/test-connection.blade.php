<x-filament-widgets::widget>
    <x-filament::section>
        <x-filament::card>
            <h1>Python Connection Test</h1>
            <br>
            <x-filament::button wire:click="testConnection" color="primary">
                @if ($connectionStatus === true)
                    {{ __('Python Up') }}
                @elseif ($connectionStatus === false)
                    {{ __('Python Down') }}
                @else
                    {{ __('Test Connection') }}
                @endif
            </x-filament::button>
        </x-filament::card>
    </x-filament::section>
</x-filament-widgets::widget>
