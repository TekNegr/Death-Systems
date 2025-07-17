<div
    id="window-{{ $windowId }}"
    class="window"
    style="position: absolute; left: {{ $x }}px; top: {{ $y }}px; width: {{ $width }}px; height: {{ $height }}px; background: white; border-radius: 0.5rem; box-shadow: 0 2px 8px rgba(0,0,0,0.2); overflow: hidden;"
    data-window-id="{{ $windowId }}"
>
    <div class="window-header" style="cursor: move; background: #f1f5f9; padding: 0.5rem;">
        <span>{{ $appKey }}</span>
        <button style="float: right;" wire:click="close">✕</button>
    </div>
    <div class="window-content" style="flex-grow: 1; padding: 1rem; overflow-y: auto; box-sizing: border-box;">
        <form wire:submit.prevent="send" class="space-y-4">
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                <input type="text" id="name" wire:model.defer="name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
                @error('name') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" id="email" wire:model.defer="email" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
                @error('email') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="message" class="block text-sm font-medium text-gray-700">Message</label>
                <textarea id="message" wire:model.defer="message" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                @error('message') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <button type="submit" class="inline-flex justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                    Send
                </button>
            </div>

            @if ($feedback)
                <div class="mt-2 text-sm font-medium text-green-600">
                    {{ $feedback }}
                </div>
            @endif
        </form>
    </div>
</div>
