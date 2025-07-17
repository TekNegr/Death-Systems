<x-filament-panels::page>
    <x-filament::card>
        <div class="space-y-4">
            <h1 class="text-2xl font-bold">List of Companies and job offers </h1>
            
            @foreach ($this->items as $item)
                <div class="p-4 border rounded-lg flex flex-col md:flex-row md:items-center">
                    <div class="flex-grow">
                        <div class="text-lg font-semibold">
                            {{ $item['title'] }} ({{ $item['type'] }})
                        </div>
                        <div class="text-sm text-gray-600">
                            Company: {{ $item['company_name'] ?? 'N/A' }}
                        </div>
                        <div class="text-sm text-gray-600">
                            {{ $item['name'] }}
                        </div>
                        <div class="mt-1 text-sm text-gray-700">
                            {{ $item['description'] }}
                        </div>
                    </div>
                    <div class="mt-4 md:mt-0 md:ml-4 flex-shrink-0 ">
                        <x-filament::button wire:click="sendForEmbedding('{{ $item['type'] }}', {{ $item['id'] }})">
                            Create Motivation Letter
                        </x-filament::button>

                    </div>
                </div>
            @endforeach
        </div>
    </x-filament::card>

    <x-filament::card>
        // Spot for the Motivation Letter
    </x-filament::card>
</x-filament-panels::page>
