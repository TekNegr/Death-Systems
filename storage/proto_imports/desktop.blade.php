<div class="desktop w-full h-full relative">
    {{-- Debugging output --}}
    {{-- <pre class="absolute top-0 left-0 bg-gray-200 text-black p-2 z-50">{{ json_encode($windows, JSON_PRETTY_PRINT) }}</pre> --}}


    {{-- Debbugging event dispatch --}}

    {{-- Render all opened windows --}}
    @foreach ($windows as $window)
        <livewire:window 
            :window="$window"
            :key="$window['id']"
        />
    @endforeach

    {{-- Taskbar --}}
    <div class="taskbar fixed bottom-0 left-0 w-full bg-gray-800 p-2 flex space-x-2">
        
        <button 
            class="text-white text-sm bg-gray-700 px-3 py-1 rounded hover:bg-gray-600"
            wire:click="$dispatch('call-window', { name: 'terminal', params: { startupMessage: 'Bienvenue dans le Terminal!' } })"
        >
            Terminal
        </button>

    </div>
</div>
