<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPersistence;
use Livewire\Attributes\On;
use App\Livewire\Window;
use Illuminate\Support\Str;

class Desktop extends Component
{

    public array $windows = [];
    public int $zCounter = 1;

    public function mount()
    {
        $this->openWindow('Terminal'); // Open the dashboard by defaul
    }

    #[On('call-window')]
    public function openWindow(string $name, array $params = [])
    {
        // Check if the window is already open
        foreach ($this->windows as $window) {
            if ($window['name'] === $name) {
                // Bring the window to the front
                $window['zIndex'] = $this->zCounter++;
                return;
            }
        }

        // If not, create a new window
        $this->windows[] = [
            'id' => Str::uuid()->toString(),
            'name' => $name,
            'params' => $params,
            'zIndex' => $this->zCounter++,
            'x' => 150,
            'y' => 150,
            'width' => 600,
            'height' => 400,
        ];  
    }
    
    #[On('closeWindow')]
    public function closeWindow($id)
    {
        // Find the window by ID and remove it from the array
        $this->windows = array_filter($this->windows, function ($window) use ($id) {
            return $window['id'] !== $id;
        });
    }

    public function render()
    {
        return view('livewire.desktop');
    }
}
