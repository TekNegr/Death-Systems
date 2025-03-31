<div class="window draggable resizable">
    {{-- Window Header --}}
    <div class="window-header">
        <h2 class="window-title">{{ $title ?? 'Default Title' }}</h2>
        <button class="window-close" wire:click="closeWindow">×</button>
    </div>

    {{-- Window Content --}}
    <div class="window-content p-4">
        {{ $content ?? 'Default Content' }}
    </div>

    {{-- Window Footer --}}
</div>