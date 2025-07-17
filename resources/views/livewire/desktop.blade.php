<div id="desktop-container" style="position: relative; width: 100vw; height: calc(100vh - 64px); overflow: hidden; background-color: #1e293b;">
    @foreach ($windows as $window)
        @livewire($window['appKey'], [
            'windowId' => $window['id'],
            'appKey' => $window['appKey'],
            'x' => $window['x'],
            'y' => $window['y'],
            'width' => $window['width'],
            'height' => $window['height'],
            'isOpen' => $window['isOpen'],
            'appName' => $window['appName'] ?? null,
            'fileType' => $window['fileType'] ?? null,
            'fileId' => $window['fileId'] ?? null,
        ], key($window['id']))
    @endforeach
</div>
