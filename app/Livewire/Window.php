<?php

namespace App\Livewire;

use Livewire\Component;

class Window extends Component
{
    public $windowId;
    public $appKey;
    public $x;
    public $y;
    public $width;
    public $height;
    public $isOpen;


    public function mount($windowId, $appKey, $x = 50, $y = 50, $width = 400, $height = 300, $isOpen = true)
    {
        $this->windowId = $windowId;
        $this->appKey = $appKey;
        $this->x = $x;
        $this->y = $y;
        $this->width = $width;
        $this->height = $height;
        $this->isOpen = $isOpen;
    }

    public function updatePosition($id, $x, $y)
    {
        if ($id === $this->windowId) {
            $this->x = $x;
            $this->y = $y;
            $this->dispatch('updateWindowPosition', id: $id, x: $x, y: $y);
        }
    }

    public function updateSize($id, $width, $height)
    {
        if ($id === $this->windowId) {
            $this->width = $width;
            $this->height = $height;
            $this->dispatch('updateWindowSize', id: $id, width: $width, height: $height);
        }
    }

    public function close()
    {
        $this->isOpen = false;
        $this->dispatch('closeWindow', id: $this->windowId);
    }

    // New methods to handle updates from JS
    public function updatePositionFromJs($x, $y)
    {
        $this->x = $x;
        $this->y = $y;
    }

    public function updateSizeFromJs($width, $height)
    {
        $this->width = $width;
        $this->height = $height;
    }

    public function render()
    {
        $window = [
            'id' => $this->windowId,
            'appKey' => $this->appKey,
            'x' => $this->x,
            'y' => $this->y,
            'width' => $this->width,
            'height' => $this->height,
            'isOpen' => $this->isOpen,
        ];
        return view('livewire.window', compact('window'));
    }
}
