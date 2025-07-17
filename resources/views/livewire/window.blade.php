<div 
    class="window draggable resizable border rounded-xl shadow-lg bg-white" 
    style="left: {{ $window['x'] }}px; top: {{ $window['y'] }}px; width: {{ $window['width'] }}px; height: {{ $window['height'] }}px; position: absolute;"
>
    {{-- Resize Handles --}}
    <div class="resize-handle-top" style="background-color: rgba(0,0,0,0.1); z-index: 20; top: 0; left: 0; right: 0; height: 6px;"></div>
    <div class="resize-handle-left" style="background-color: rgba(0,0,0,0.1); z-index: 20; top: 0; left: 0; bottom: 0; width: 6px;"></div>
    <div class="resize-handle-bottom" style="background-color: rgba(0,0,0,0.1); z-index: 20; left: 0; right: 0; bottom: 0; height: 6px;"></div>
    <div class="resize-handle-right" style="background-color: rgba(0,0,0,0.1); z-index: 20; top: 0; right: 0; bottom: 0; width: 6px;"></div>

    {{-- Window Header --}}
    <div 
        class="flex justify-between items-center bg-gray-800 text-white px-4 py-2 window-header cursor-move"
    >
        <div class="font-semibold">{{ $window['name'] ?? 'Nom de la fenêtre' }}</div>
        <div class="flex items-center space-x-2">
            <button class="window-close" wire:click="close" aria-label="Close window">&times;</button>
        </div>
    </div>

    {{-- Window Content --}}
    <div class="p-4 h-full bg-green-100 window-content">
        <p class="text-gray-800">
            Ceci est une zone de contenu test pour voir le rendu à l'intérieur de la fenêtre. Tu peux y placer ton composant ou tout autre contenu dynamique.
        </p>
    </div>
</div>

<script src="{{ asset('js/window-move-resize.js') }}" defer></script>
