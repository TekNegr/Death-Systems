<?php

namespace App\Livewire;

class Error extends Window
{
    public $appName;

    public function mount($windowId, $appKey, $x, $y, $width, $height, $isOpen, $fileType = null, $fileId = null)
    {
        parent::mount($windowId, $appKey, $x, $y, $width, $height, $isOpen, $fileType = null, $fileId = null);
        
    }

    public function render()
    {
        return view('livewire.error');
    }
}
