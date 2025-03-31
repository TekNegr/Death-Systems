<?php

namespace App\Livewire;

use Livewire\Component;
use App\Livewire\Desktop;

class Window extends Component
{
    public $id;
    public $title = 'Window';
    public $width = 400;
    public $height = 200;
    public $x = 0, $y = 0;
    public $dragging = false;
    public $resizing = false;
    public $resizer = '';
    public $minWidth = 200;
    public $minHeight = 200;
    public $maxWidth = 800;
    public $maxHeight = 600;
    public $minX = 0;
    public $minY = 0;
    public $maxX = 0; // Define later
    public $maxY = 0; // Define later
    public $view = 'default-view'; // Default view to render inside the window
    public $visible = true;
    
    public function render()
    {
        return view('livewire.window', [
            'viewContent' => view($this->view)->render()
        ]);
    }

    public function openWindow($view)
    {
        $this->$view = $view;
        $this->visible = true;
    }

    public function closeWindow()
    {
        $this->visible = false;
        $this->emit('windowClosed', $this->id); // Emit an event to notify the parent component
    }
}
