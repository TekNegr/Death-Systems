<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPersistence;
use Livewire\Attributes\On;

class Desktop extends Component
{


    public $listeners = ['windowClosed' => 'closeWindow']; // Listen for closeWindow event

    public array $closedWindows = [];
    public $windows = []; // Array to track opened windows
    public $apps = [ // Dictionary of all possible apps
        'dashboard' => [
            'title' => 'Dashboard',
            'view' => 'windows.dashboard',
        ],
        'settings' => [
            'title' => 'Settings',
            'view' => 'windows.settings',
        ],
        'profile' => [
            'title' => 'Profile',
            'view' => 'windows.ds-profile',
        ],
    ];

    public function mount()
    {
        $this->openWindow('profile'); // Open the dashboard by defaul
    }

    
    public function openWindow($appKey)
    {
        if (isset($this->apps[$appKey])) {
            $app = $this->apps[$appKey];

            // Add a new window to the list
            $this->windows[] = [
                'id' => uniqid(), // Unique ID for the window
                'title' => $app['title'],
                'view' => $app['view'],
                'visible' => true,
            ];
            // session()->put('windows', $this->windows);
        } else {
            // Handle the case where the app is not found
            session()->flash('error', 'App not found.');
        }
    }
    
    #[On('closeWindow')]
    public function closeWindow($id)
    {
        // This function will be changed due to impossibility of closing windows

       echo "Closing window with ID: $id";
        // Find the window by ID and set its visibility to false
        foreach ($this->windows as $key => $window) {
            if ($window['id'] === $id) {
                unset($this->windows[$key]);
                break;
            }
        }
    }

    public function render()
    {
        return view('livewire.desktop');
    }
}
