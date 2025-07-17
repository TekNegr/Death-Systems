<div
    id="window-{{ $windowId }}"
    class="window"
    style="position: absolute; left: {{ $x }}px; top: {{ $y }}px; width: {{ $width }}px; height: {{ $height }}px; background: black; border-radius: 0.5rem; box-shadow: 0 2px 8px rgba(0,0,0,0.8); color: #00ff00; font-family: monospace; display: flex; flex-direction: column; overflow: hidden;"
    data-window-id="{{ $windowId }}"
>
    <div class="window-header" style="cursor: move; background: #111; padding: 0.5rem; color: {{$textColor}};">
        <span>{{ $appKey }}</span>
        <button style="float: right; background: transparent; border: none; color: {{$textColor}};" wire:click="close">✕</button>
    </div>
    <pre class="window-content" style="flex-grow: 1; padding: 0; color: {{$textColor}}; margin: 0; overflow-y: auto; white-space: pre-wrap; background: black;">
{!! implode("\n", collect($history)->map(function($entry) {
    $out = '&gt; ' . e($entry['input']);
    if ($entry['answer']) $out .= "\n" . e($entry['answer']);
    return $out;
})->toArray()) !!}
    </pre>
    <form id="terminal-form" wire:submit.prevent="submitCommand" style="padding: 0.5rem; background: #111; display: flex;">
        <input
            type="text"
            wire:model.defer="input"
            placeholder="Enter command..."
            style="flex-grow: 1; background: black; border: 1px solid {{$textColor}}; color: {{$textColor}}; font-family: monospace; padding: 0.25rem 0.5rem;"
            autocomplete="off"
        />
        <button type="submit" style="background: {{$textColor}}; color: black; border: none; padding: 0 1rem; margin-left: 0.5rem; cursor: pointer;">Enter</button>
    </form>

    <script>
        document.getElementById('terminal-form').addEventListener('submit', function(event) {
            event.preventDefault();
            const windowElement = document.getElementById('window-{{ $windowId }}');
            const left = parseFloat(windowElement.style.left) || 0;
            const top = parseFloat(windowElement.style.top) || 0;
            Livewire.dispatch('updateWindowPosition', id: '{{ $windowId }}', x: left, y: top);
            // Livewire.emit('submitCommand');
        });
    </script>
</div>

