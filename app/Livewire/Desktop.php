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
        
        Log::info("Event Recieved. Creating window: {$appKey}");
        $windows = $this->windows;
        $windows[] = [
            'id' => uniqid(),
            'appKey' => $appKey,
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
