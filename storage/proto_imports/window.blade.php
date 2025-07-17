<div class="window draggable resizable"
style="left: {{ $window['x'] }}px; top: {{ $window['y'] }}px; width: {{ $window['width'] }}px; height: {{ $window['height'] }}px; z-index: {{ $window['zIndex'] }};"
>
    
    {{-- Resize Handles --}}
    <div class="resize-handle-top"></div>
    <div class="resize-handle-left"></div>
    <div class="resize-handle-bottom"></div>
    <div class="resize-handle-right"></div>

    
    {{-- Window Header --}}
    <div class="window-header">
        <h2 class="window-title">{{ $window['name'] ?? 'Default Title' }}</h2>
        <button class="window-close" wire:click="closeSelf">×</button>
    </div>

    {{-- Window Content --}}
    <div class="window-content h-5/6 p-4">
        @if (isset($error))
            <div class="text-red-500">{{ $error }}</div>
        @else
            @livewire($componentName, ['params' => $window['params']], key($window['id']))
        @endif
    </div>

    {{-- Window Footer --}}
</div>