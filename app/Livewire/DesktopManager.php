<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;


class DesktopManager extends Component
{
    public $creatingWindow = false;

    #[On('createWindow')]
    public function handleCreateWindow()
    {
        $this->creatingWindow = true;
        Log::info('DesktopManager - Creating a new window', ['user_id' => optional(Auth::user())->id]); 
        
    }

    public function render()
    {
        return view('livewire.desktop-manager');
    }
}
