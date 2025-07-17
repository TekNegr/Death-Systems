<?php

namespace App\Livewire;

use Livewire\Component;
use App\Livewire\Desktop;

class Window extends Component
{
    public array $window;
    
    
    public function mount(array $window)
    {
        $this->window = $window;
    }
    
    public function closeSelf()
    {
        $this->dispatch('closeWindow', id: $this->window['id'] ); 
    }


    public function render()
    {
        $componentName = 'apps.' . strtolower($this->window['name'] ?? 'default');
        if (!class_exists('App\\Livewire\\Apps\\' . ucfirst($this->window['name']))) {
            return view('livewire.window', [
                'error' => "Component '{$componentName}' not found.",
            ]);
        }
    
        return view('livewire.window', [
            'componentName' => $componentName,
        ]);
    }
}
