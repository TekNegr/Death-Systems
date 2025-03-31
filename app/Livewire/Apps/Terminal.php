<?php

namespace App\Livewire\Apps;

use App\Livewire\Window;
use App\Livewire\Desktop;

use Livewire\Component;
use App\Http\Controllers\AIController;

class Terminal extends Component
{
    public array $params = [];
    public array $history = [];
    public bool $dsMode ; 
    public string $input = '';

    public function mount(array $params = [])
    {
        $this->params = $params;

        // Initialize DS-mode if passed in params
        $this->dsMode = $params['dsMode'] ?? false;

        // Add startup message to history
        $this->history[] = $params['startupMessage'] ?? 'Terminal launched...';

        // If DS-mode is active, add a special message
        if ($this->dsMode) {
            $this->history[] = 'Kneel before DEATHSTAR.';
        }
    }

    public function executeCommand()
    {
        $command = trim($this->input);
        if ($command === '') {
            return;
        }


        // Add the command to the history
        $this->history[] = ['from' => 'user', 'content' => $command];
        // Handle the command (for now, just echo it back)
        $response = $this->handleCommand($command);
        $this->history[] = $response;

        // Clear the input field
        $command = '';
        $this->reset('command');

        // Scroll to the bottom of the output area
        $this->dispatch('scroll-terminal');
    }

    protected function handleCommand(string $command): string
    {
        if ($this->dsMode) {
            if ($command === 'exit') {
                $this->dsMode = false; // Exit DS-mode
                $aiController = new AIController();
                return $this->aiController->endMessage();
            }
            return $this->handleDSModeCommand($command);
        }
        // Placeholder for chatbot or command handling logic
        switch (strtolower($command)) {
            case 'help':
                return 'Available commands: help, clear, exit, open-*, summon-DS';
            case 'clear':
                $this->history = [];
                return '';
            case 'exit':
                $this->dispatch('close-terminal');
                return 'Exiting terminal...';
            case 'summon-ds':
                $this->history = [];
                $this->dsMode = true;
                
                // Start the AI conversation
                $aiController = new AIController();
                $initialResponse = $aiController->startMessage();
                $this->history[] = $initialResponse;

                return '';
                    
            default:
                if (str_starts_with($command, 'open-')) {
                    $windowName = substr($command, 5);
                    $this->dispatch('call-window', name: $windowName);
                    return "Opening window: {$windowName}";
                }
                return "Unknown command: {$command}";
        }
    }
    
    protected function handleDSModeCommand(string $command): string
    {
        // Call the AIController to get a response
        $aiController = new AIController();
        return $aiController->getResponse($command);
    }

    public function render()
    {
        return view('livewire.apps.terminal');
    }
}
