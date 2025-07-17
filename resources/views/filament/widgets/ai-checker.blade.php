<x-filament-widgets::widget>
    <x-filament::card>
        <h2 class="text-lg font-semibold mb-4">AI Status</h2>

        <p class="mb-2">
            <strong>Trained Model Exists:</strong>
            @if ($trainedModelExists)
                <span class="text-green-600 font-bold">Yes</span>
            @else
                <span class="text-red-600 font-bold">No</span>
            @endif
        </p>

        <p class="mb-4">
            <strong>Training Dialogs Count:</strong> {{ $trainingDialogCount }}
        </p>

        <button wire:click="seedAndTrain" class="inline-block px-4 py-2 bg-primary-600 text-white rounded hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500">
            Seed Database and Train AI
        </button>
    </x-filament::card>
</x-filament-widgets::widget>
