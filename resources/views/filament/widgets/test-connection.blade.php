<x-filament-widgets::widget>
    <x-filament::card>
        <h2 class="text-lg font-semibold mb-4">Python Connection Test</h2>

        <p class="mb-2">
            <strong>Python Service Status:</strong>
            <span>{{ $connectionStatus ?? 'unknown' }}</span>
        </p>

        <button wire:click="testConnection" class="inline-block px-4 py-2 bg-primary-600 text-white rounded hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500">
            Test Connection
        </button>
    </x-filament::card>
</x-filament-widgets::widget>
