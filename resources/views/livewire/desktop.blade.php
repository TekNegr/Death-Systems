<div class="desktop w-full h-full relative">
    {{-- Debugging output --}}
    <pre class="absolute top-0 left-0 bg-gray-200 text-black p-2 z-50">{{ json_encode($windows, JSON_PRETTY_PRINT) }}</pre>


    {{-- Debbugging event dispatch --}}

    {{-- Render all opened windows --}}
    @foreach ($windows as $window)
        @if ($window['visible'])
            <livewire:window 
                :title="$window['title']" 
                :view="$window['view']" 
                :key="$window['id']" 
                wire:key="{{ $window['id'] }}" 
            />
        @endif
    @endforeach

    {{-- Taskbar --}}
    <div class="taskbar fixed bottom-0 left-0 w-full bg-gray-800 p-2 flex space-x-2">
        @foreach ($apps as $key => $app)
            <button 
                class="taskbar-button bg-gray-700 text-white px-4 py-2 rounded"
                wire:click="openWindow('{{ $key }}')"
            >
                {{ $app['title'] }}
            </button>
        @endforeach
    </div>
</div>
