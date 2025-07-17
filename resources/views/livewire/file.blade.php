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
    <div class="window-content" style="flex-grow: 1; padding: 1rem; overflow-y: auto; box-sizing: border-box; height: calc(100% - 3rem);">
        @if($fileType === 'project' && $project)
            <h1 class="text-5xl font-extrabold mb-6">{{ $project->title }}</h1>
            <div style="display: flex; gap: 1rem; height: calc(100% - 6rem); min-height: 400px;">
                <div style="flex: 1; overflow-y: auto; max-height: 100%;">
                    <h3 class="font-semibold mb-2">Project Description</h3>
                    <p style="white-space: pre-wrap;">{{ $project->description }}</p>
                </div>
                <div style="flex: 1;">
                    <h3 class="font-semibold mb-2">Project Photos</h3>
                    @if(!empty($project->pictures) && count($project->pictures) > 0)
                        <div class="carousel" style="position: relative; width: 100%; height: 300px; overflow: hidden; border-radius: 0.5rem; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                            @foreach($project->pictures as $index => $picture)
                                <img
                                    src="{{ $picture['url'] ?? $picture }}"
                                    alt="Project Photo {{ $index + 1 }}"
                                    style="width: 100%; height: 300px; object-fit: contain; display: {{ $index === 0 ? 'block' : 'none' }};"
                                    class="carousel-image"
                                    data-index="{{ $index }}"
                                />
                            @endforeach
                            <button type="button" class="carousel-prev" style="position: absolute; top: 50%; left: 10px; transform: translateY(-50%); background: rgba(0,0,0,0.3); color: white; border: none; border-radius: 50%; width: 30px; height: 30px; cursor: pointer;">‹</button>
                            <button type="button" class="carousel-next" style="position: absolute; top: 50%; right: 10px; transform: translateY(-50%); background: rgba(0,0,0,0.3); color: white; border: none; border-radius: 50%; width: 30px; height: 30px; cursor: pointer;">›</button>
                        </div>
                    @else
                        <p>No photos available yet.</p>
                    @endif

                    <h3 class="font-semibold mt-4 mb-2">Project Links</h3>
                    @if(!empty($project->links) && count($project->links) > 0)
                        <ul class="list-disc list-inside">
                            @foreach($project->links as $link)
                                <li>
                                    <a href="{{ $link['url'] ?? '#' }}" target="_blank" class="text-blue-600 hover:underline">
                                        {{ $link['label'] ?? $link['url'] ?? 'Link' }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p>No links available yet.</p>
                    @endif
                </div>
            </div>
        @elseif($fileType === 'photo' && $photoUrl)
            <img src="{{ $photoUrl }}" alt="Photo" class="max-w-full max-h-full object-contain" />
        @elseif($fileType === 'resume' && $resumePath)
            <iframe src="{{ $resumePath }}" style="width: 100%; height: 100%; border: none;"></iframe>
            <div class="mt-4">
                <a href="{{ $resumePath }}" download class="inline-block px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">
                    Download Resume
                </a>
            </div>
        @else
            <p>No file to display.</p>
        @endif
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const carousel = document.querySelector('.carousel');
            if (!carousel) return;

            const images = carousel.querySelectorAll('.carousel-image');
            let currentIndex = 0;

            const showImage = (index) => {
                images.forEach((img, i) => {
                    img.style.display = i === index ? 'block' : 'none';
                });
            };

            const prevBtn = carousel.querySelector('.carousel-prev');
            const nextBtn = carousel.querySelector('.carousel-next');

            prevBtn.addEventListener('click', () => {
                currentIndex = (currentIndex - 1 + images.length) % images.length;
                showImage(currentIndex);
            });

            nextBtn.addEventListener('click', () => {
                currentIndex = (currentIndex + 1) % images.length;
                showImage(currentIndex);
            });
        });
    </script>
</div>
