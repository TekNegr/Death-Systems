<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Livewire\WithPagination;
use App\Http\Controllers\AiController;
use Illuminate\Support\Facades\Log;
use Filament\Notifications\Notification;

class AITraining extends Page
{
    use WithPagination;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.a-i-training';

    public $userMessage = '';
    public $aiResponse = '';
    public $recoveryAnswer = '';
    public $rating = null;
    public $aiIdentity = '';
    public $currentModel = 'pretrained'; // 'pretrained' or 'custom'

    protected string $identityFilePath = 'scripts/identity.txt';

    protected ?AiController $aiController = null;

    public function mount()
    {
        $this->aiController = new AiController();
        $this->loadIdentity();
    }

    public function loadIdentity()
    {
        $path = base_path($this->identityFilePath);
        if (file_exists($path)) {
            $this->aiIdentity = file_get_contents($path);
        } else {
            $this->aiIdentity = "You are Amadeus, a helpful AI assistant.";
        }
    }

    public function saveIdentity()
    {
        if ($this->aiController === null) {
            $this->aiController = new AiController();
        }
        $request = request()->merge(['identity_text' => $this->aiIdentity]);
        $response = $this->aiController->saveIdentity($request);
        if ($response->getStatusCode() === 200) {
            Notification::make()
                ->title('AI Identity saved successfully')
                ->success()
                ->send();
        } else {
            Notification::make()
                ->title('Failed to save AI Identity')
                ->danger()
                ->send();
        }
    }

    public function toggleModel()
    {
        if ($this->aiController === null) {
            $this->aiController = new AiController();
        }
        $request = request()->merge(['current_model' => $this->currentModel]);
        $response = $this->aiController->toggleModel($request);
        if ($response->getStatusCode() === 200) {
            $data = $response->getData(true);
            $this->currentModel = $data['new_model'] ?? $this->currentModel;
            Notification::make()
                ->title("Switched to {$this->currentModel} model")
                ->success()
                ->send();
        } else {
            Notification::make()
                ->title('Failed to switch model')
                ->danger()
                ->send();
        }
    }

    public function sendMessage()
    {
        if ($this->aiController === null) {
            $this->aiController = new AiController();
        }
        $aiModelName = env('AI_MODEL_NAME', null);
        $request = request()->merge([
            'prompt' => $this->userMessage,
            'max_length' => 250,
            'model_type' => $this->currentModel,
            'ai_name' => $aiModelName,
        ]);
        $response = $this->aiController->sendMessage($request);
        if ($response->getStatusCode() === 200) {
            $data = $response->getData(true);
            $this->aiResponse = $data['generated_text'] ?? '';
        } else {
            $this->aiResponse = 'Error: Unable to get response from AI service.';
        }
    }

    public function saveDialog()
    {
        if ($this->aiController === null) {
            $this->aiController = new AiController();
        }
        $request = request()->merge([
            'user_message' => $this->userMessage,
            'ai_response' => $this->aiResponse,
            'recovery_answer' => $this->recoveryAnswer,
            'score' => $this->rating,
        ]);
        $response = $this->aiController->saveDialog($request);
        if ($response->getStatusCode() === 200) {
            $this->reset(['userMessage', 'aiResponse', 'recoveryAnswer', 'rating']);
            session()->flash('success', 'Dialog saved successfully.');
        } else {
            Notification::make()
                ->title('Failed to save dialog')
                ->danger()
                ->send();
        }
    }

    public function startSelfTraining()
    {
        if ($this->aiController === null) {
            $this->aiController = new AiController();
        }
        $response = $this->aiController->startSelfTraining();
        if ($response->getStatusCode() === 200) {
            Notification::make()
                ->title('Self-training completed successfully')
                ->success()
                ->send();
        } else {
            Notification::make()
                ->title('Self-training failed')
                ->danger()
                ->send();
        }
    }
}
