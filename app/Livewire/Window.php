<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;

class Window extends Component
{
    public $windowId;
    public $appKey;
    public $x;
    public $y;
    public $width;
    public $height;
    public $isOpen;
    public $fileType;
    public $fileId;
    
    public function mount($windowId, $appKey, $x, $y, $width, $height, $isOpen, $fileType = null, $fileId = null)
    {
        $this->windowId = $windowId;
        $this->appKey = $appKey;
        $this->x = $x;
        $this->y = $y;
        $this->width = $width;
        $this->height = $height;
        $this->isOpen = $isOpen;
        $this->fileType = $fileType;
        $this->fileId = $fileId;
    }

    public function close()
    {
        $this->isOpen = false;
        $this->dispatch('closeWindow', id: $this->windowId);
    }

    // #[On('updateWindowPosition')]
    // public function updatePosition($windowId, $x, $y)
    // {
    //     if ($windowId == $this->windowId) {
    //         $this->x = $x;
    //         $this->y = $y;
    //     }
    // }

    public function render()
    {
        return view('livewire.window');
    }
}
