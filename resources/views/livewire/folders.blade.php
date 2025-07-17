<div
    id="window-{{ $windowId }}"
    class="window"
    style="position: absolute; left: {{ $x }}px; top: {{ $y }}px; width: 800px; height: 600px; background: white; border-radius: 0.5rem; box-shadow: 0 2px 8px rgba(0,0,0,0.2); overflow: hidden;"
    data-window-id="{{ $windowId }}"
>
    <div class="window-header" style="cursor: move; background: #f1f5f9; padding: 0.5rem;">
        <span>{{ $appKey }}</span>
        <button style="float: right;" wire:click="close">✕</button>
    </div>
    <div class="window-content" style="flex-grow: 1; padding: 1rem; overflow-y: auto; box-sizing: border-box; max-height: 100%; height: calc(100% - 3rem);">
        <h2 class="text-xl font-semibold mb-4">Projects</h2>
        @if($projects->isEmpty())
            <p>No projects found.</p>
        @else
            <ul class="space-y-4 overflow-y-auto max-h-full" style="max-height: calc(100% - 3rem);">
                @foreach($projects as $project)
                    <li class="border rounded p-4 bg-white shadow">
                        <h3 class="text-lg font-bold cursor-pointer text-indigo-600 hover:underline" wire:click="openProjectFile({{ $project->id }})">{{ $project->title }}</h3>
                        @if($project->description)
                            <p class="mt-2 text-gray-700">{{ $project->description }}</p>
                        @endif
                        <div class="mt-2 text-sm text-gray-500">
                            @if($project->status)
                                <span class="inline-block mr-4">Status: {{ $project->status }}</span>
                            @endif
                            @if($project->start_date)
                                <span class="inline-block mr-4">Start: {{ \Illuminate\Support\Carbon::parse($project->start_date)->format('d, M, Y') }}</span>
                            @endif
                            @if($project->end_date)
                                <span class="inline-block">End: {{ \Illuminate\Support\Carbon::parse($project->end_date)->format('d, M, Y') }}</span>
                            @endif
                        </div>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
</div>
