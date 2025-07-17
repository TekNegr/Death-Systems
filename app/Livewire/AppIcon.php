<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Log;
use Livewire\Component;
use App\Services\AppIconService;
use App\Livewire\Desktop;

class AppIcon extends Component
{
    public $color;
    public $name;
    public $appKey;
    public $svgLogo;

    protected $appIconService;

    public function __construct($id = null)
    {
        $this->appIconService = new AppIconService();
    }

    public function mount($appKey = 'default')
    {
        $this->appKey = $appKey;
        $appData = $this->appIconService->getAppData($appKey);

        if ($appData) {
            $this->color = $appData['color'];
            $this->name = $appData['name'];
            $this->svgLogo = $appData['svg'];
        } else {
            $this->color = '#9ca3af';
            $this->name = 'App';
            $this->svgLogo = null;
        }
    }

    public function createApp()
    {
        Log::info("App creation initiated: {$this->name}");
        $this->dispatch('AppIconClick', appKey : $this->appKey);
    }
    
    public function render()
    {
        return view('livewire.app-icon');
    }
}
