<x-filament-panels::page>
<div>
    <form wire:submit.prevent="sendMessage" class="space-y-6">
        <textarea
            wire:model.defer="userMessage"
            placeholder="Enter your message to the AI"
            rows="4"
            class="w-full border rounded p-2 text-gray-700"
        ></textarea>

        <div class="flex space-x-4 mt-2">
            <x-filament::button type="submit" color="primary" class="flex-1">
                Send
            </x-filament::button>

            @if ($aiResponse)
                <x-filament::button
                    wire:click="saveDialog"
                    color="success"
                    class="flex-1"
                >
                    Save
                </x-filament::button>
            @endif
        </div>
    </form>

    <div class="mt-6">
        <label for="aiIdentity" class="block font-semibold mb-2">AI Identity:</label>
        <textarea
            wire:model.defer="aiIdentity"
            id="aiIdentity"
            rows="3"
            class="w-full border rounded p-2 text-gray-700"
            placeholder="Enter AI identity prompt"
        ></textarea>
        <x-filament::button wire:click="saveIdentity" color="secondary" class="mt-2">
            Save AI Identity
        </x-filament::button>
    </div>

    <div class="mt-6 flex items-center space-x-4">
        <div>
            <span class="font-semibold">Current Model:</span>
            <span class="ml-2 text-blue-600 capitalize">{{ $currentModel }}</span>
        </div>
        <x-filament::button wire:click="toggleModel" color="secondary" size="sm">
            Switch Model
        </x-filament::button>
    </div>

    @if ($aiResponse)
        <x-filament::card class="mt-6">
            <h3 class="font-semibold mb-2">AI Response:</h3>
            <p class="whitespace-pre-wrap">{{ $aiResponse }}</p>

            <div class="mt-4 flex items-center space-x-2">
                <label for="rating" class="mb-0 block font-semibold">Rate the accuracy:</label>
                <select
                    wire:model="rating"
                    id="rating"
                    class="border text-gray-700 rounded p-1"
                    placeholder="Select rating"
                >
                    <option value="">Select rating</option>
                    <option value="1">1 - Poor</option>
                    <option value="2">2 - Fair</option>
                    <option value="3">3 - Good</option>
                    <option value="4">4 - Very Good</option>
                    <option value="5">5 - Excellent</option>
                </select>
            </div>

            <div class="mt-4">
                <label for="recoveryAnswer" class="mb-0 block font-semibold">Recovery Answer (optional):</label>
                <textarea
                    wire:model.defer="recoveryAnswer"
                    id="recoveryAnswer"
                    rows="3"
                    class="w-full border rounded p-2 text-gray-700"
                    placeholder="Enter recovery answer for poor responses"
                ></textarea>
            </div>
        </x-filament::card>
    @endif

    <div class="mt-8">
        <x-filament::button wire:click="startSelfTraining" color="secondary">
            Start Self-Training
        </x-filament::button>
    </div>
</div>
</x-filament-panels::page>
