<div
    id="window-{{ $windowId }}"
    class="window"
    style="position: absolute; left: {{ $x }}px; top: {{ $y }}px; width: {{ $width }}px; height: {{ $height }}px; background: white; border-radius: 0.5rem; box-shadow: 0 2px 8px rgba(0,0,0,0.2); overflow: hidden;"
    wire:ignore
    data-window-id="{{ $windowId }}"
>
    <div class="window-header" style="cursor: move; background: #f1f5f9; padding: 0.5rem;">
        <span>{{ $appName }}</span>
        <button style="float: right;" wire:click="close">✕</button>
    </div>
    <div class="window-content" style="padding: 1rem; color: red; font-weight: bold;">
        {{ $appName }} not available yet
    </div>
</div>
