<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Log;
// use Log
use App\Livewire\DesktopManager;


class AppButton extends Component
{
    public function createWindow()
    {
        // $this->dispatch('createWindow')->to(DesktopManager::class);
        Log::info('AppButton - Dispatching createWindow event');
    }

    public function render()
    {
        return view('livewire.app-button');
    }
}
