
<div class="terminal w-full h-full bg-black {{ $dsMode ? 'ds-mode text-red-400 font-' : 'text-green-400' }}   flex flex-col" >

    {{-- Output Area --}}
    <div class="output h-full flex-1 overflow-y-auto p-4" id="terminal-output" style="flex-grow: 1;">
    
        @foreach ($history as $entry)
            <p>{{ $entry }}</p>
        @endforeach
    </div>
    
    {{-- Input Area --}}
    <div class="input bg-gray-800 p-2">
        <form wire:submit.prevent="executeCommand">
            <input 
                type="text" 
                wire:model="input" 
                class="w-full bg-black border-none outline-none p-2" 
                placeholder="{{ $dsMode ? 'Speak to DEATHSTAR...' : 'Type a command...' }}"
                autofocus
            />
        </form>
    </div>
</div>

<script>
    document.addEventListener('scroll-terminal', () => {
        const output = document.getElementById('terminal-output');
        if (output) {
            output.scrollTop = output.scrollHeight;
        }
    });
</script>
