<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On; 
use Illuminate\Support\Facades\Log;

class Desktop extends Component
{
    public $windows = [];

    #[On('AppIconClick')]
    public function addWindow($appKey)
    {
        $appName = config('d-apps')[$appKey]['name'] ?? $appKey;
        $componentName = $appKey;

        // Check if Livewire component class exists, fallback to 'error' if not
        if (!class_exists('App\\Livewire\\' . ucfirst($componentName))) {
            Log::error("Component {$componentName} not found. Using default error component.");
            $componentName = 'error';
        } else {
            Log::info("Component {$componentName} found. Proceeding to create window.");
        }

        Log::info("Event Received. Creating window: {$appKey}");
        $windows = $this->windows;
        $windows[] = [
            'id' => uniqid(),
            'appKey' => strtolower($componentName),
            'appName' => $appName,
            'x' => 50,
            'y' => 50,
            'width' => 400,
            'height' => 300,
            'isOpen' => true,
        ];
        $this->windows = $windows;
        Log::info("Window created: " . end($this->windows)['id']);
        Log::info("Current windows: " . count($this->windows));
        Log::info("Current windows data: " . json_encode($this->windows));
        Log::info("End of event handling.");
    }

    #[On('openFile')]
    public function openFile($params)
    {
        Log::info("Desktop - Opening file with params: " . json_encode($params));
        $appKey = 'file';
        $appName = 'File';

        $windows = $this->windows;
        $windows[] = [
            'id' => uniqid(),
            'appKey' => $appKey,
            'appName' => $appName,
            'x' => 50,
            'y' => 50,
            'width' => 600,
            'height' => 400,
            'isOpen' => true,
            'fileType' => $params['fileType'] ?? null,
            'fileId' => $params['fileId'] ?? null,
        ];
        $this->windows = $windows;
        Log::info("Desktop - File window created: " . end($this->windows)['id'] . " for file type: " . ($params['fileType'] ?? 'unknown') . " and file ID: " . ($params['fileId'] ?? 'none'));
    }

    #[On('closeWindow')]
    public function closeWindow($id)
    {
        $this->windows = array_filter($this->windows, fn($window) => $window['id'] !== $id);
    }

    #[On('updateWindowPosition')]
    public function updateWindowPosition($id, $x, $y)
    {
        foreach ($this->windows as &$window) {
            if ($window['id'] === $id) {
                $window['x'] = $x;
                $window['y'] = $y;
                break;
            }
        }
    }

    #[On('updateWindowSize')]
    public function updateWindowSize($id, $width, $height)
    {
        foreach ($this->windows as &$window) {
            if ($window['id'] === $id) {
                $window['width'] = $width;
                $window['height'] = $height;
                break;
            }
        }
    }

    public function render()
    {
        return view('livewire.desktop');
    }
}
