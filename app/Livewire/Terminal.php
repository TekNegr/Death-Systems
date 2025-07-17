<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Log;
use App\Http\Controllers\AiController;
use Illuminate\Http\Request;

class Terminal extends Window
{
    public $input = '';
    public $history = [];
    public $communicationActive = false; // Track communication state with custom model
    protected ?AiController $aiController = null;
    public $textColor;

    public function mount(...$params)
    {
        parent::mount(...$params);
        $this->textColor = '#0f0';
    }

    public function submitCommand()
    {
        if (trim($this->input) === '') {
            return;
        }

        $input = $this->input;

        // Handle commands first
        $this->handleCommand($input);

        $this->input = '';
        $this->x =  $this->x;
        $this->y =  $this->y;
    }

    public function handleCommand($command)
    {
        // Handle specific commands here
        switch ($command) {
            case 'clear':
                $this->clearHistory();
                break;
            case 'help':
                $this->history[] = [
                    'input' => $command,
                    'answer' => "Available commands: d-summon, d-dismiss, clear, help",
                ];
                break;
            case 'd-summon':
                if ($this->communicationActive) {
                    $this->history[] = [
                        'input' => $command,
                        'answer' => "Custom model communication already active.",
                    ];
                } else {
                    $this->communicationActive = true;
                    $this->textColor = '#f00'; // Change text color to green when communication is active
                    $this->history[] = [
                        'input' => $command,
                        'answer' => "Custom model communication activated.",
                    ];
                }
                break;
            case 'd-dismiss':
                if ($this->communicationActive) {
                    $this->communicationActive = false;
                    $this->textColor = '#0f0'; // Change text color to red when communication is inactive
                    $this->history[] = [
                        'input' => $command,
                        'answer' => "Custom model communication deactivated.",
                    ];
                } else {
                    $this->history[] = [
                        'input' => $command,
                        'answer' => "Custom model communication is not active.",
                    ];
                }
                break;
            default:
                if ($this->communicationActive) {
                    if ($this->aiController === null) {
                        $this->aiController = new AiController();
                    }
                    // Prepare a fake request object to pass parameters directly
                    $request = Request::create('/fake-url', 'POST', [
                        'prompt' => $command,
                        'max_length' => 250,
                        'model_type' => 'custom',
                        'ai_name' => null,
                    ]);
                    $response = $this->aiController->sendMessage($request);
                    if ($response->getStatusCode() === 200) {
                        $data = $response->getData(true);
                        $answer = $data['generated_text'] ?? 'No response from AI.';
                    } else {
                        $answer = 'Error: Unable to get response from AI service.';
                    }
                    $this->history[] = [
                        'input' => $command,
                        'answer' => $answer,
                    ];
                } else {
                    $this->history[] = [
                        'input' => $command,
                        'answer' => "Communication inactive. Use 'd-summon' to activate.",
                    ];
                }
        }
    }

    public function clearHistory()
    {
        $this->history = [];
        Log::info('Terminal history cleared.');
    }

    public function render()
    {
        return view('livewire.terminal');
    }
}
