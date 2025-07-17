<div
    id="window-{{ $windowId }}"
    class="window"
    style="position: absolute; left: {{ $x }}px; top: {{ $y }}px; width: {{ $width }}px; height: {{ $height }}px; background: white; border-radius: 0.5rem; box-shadow: 0 2px 8px rgba(0,0,0,0.2); overflow: hidden;"
   
    data-window-id="{{ $windowId }}"
>
    <input type="hidden" wire:model="x" />
    <input type="hidden" wire:model="y" />
    <input type="hidden" wire:model="width" />
    <input type="hidden" wire:model="height" />

    <div class="window-header" style="cursor: move; background: #f1f5f9; padding: 0.5rem;">
        <span>{{ $appKey }}</span>
        <button style="float: right;" wire:click="close">✕</button>
    </div>
         <div class="window-content" style="flex-grow: 1; padding: 1rem; overflow-y: auto; box-sizing: border-box;">
            @if ($appKey)
                @livewire($appKey, ['fileType' => $fileType, 'fileId' => $fileId], key($windowId . '-' . $fileType . '-' . $fileId))
            @else
                Window {{ $windowId }}
            @endif
         </div>
</div>
