<div id="desktop-container" style="position: relative; width: 100vw; height: 100vh; overflow: hidden; background-color: #1e293b;">
    @foreach ($windows as $window)
        @livewire('window', [
            'windowId' => $window['id'],
            'appKey' => $window['appKey'],
            'x' => $window['x'],
            'y' => $window['y'],
            'width' => $window['width'],
            'height' => $window['height'],
            'isOpen' => $window['isOpen'],
        ], key($window['id']))
    @endforeach
</div>
