<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Livewire\WithPagination;
use App\Models\TrainingDialog;
use Illuminate\Support\Facades\Http;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class AITraining extends Page
{
    use WithPagination;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.a-i-training';

    public $userMessage = '';
    public $aiResponse = '';
    public $recoveryAnswer = '';
    public $rating = null;

    public function sendMessage()
    {
        // Call the AI service to get response
        $response = Http::post('http://uvicorn_ai:80/generate-text', [
            'prompt' => $this->userMessage,
            'max_length' => 100,
        ]);

        if ($response->successful()) {
            $this->aiResponse = $response->json()['generated_text'] ?? '';
        } else {
            $this->aiResponse = 'Error: Unable to get response from AI service.';
        }
    }

    public function saveDialog()
    {
        if ($this->userMessage && $this->aiResponse && $this->rating) {
            Log::info('Saving TrainingDialog:', [
                'user_message' => $this->userMessage,
                'ai_response' => $this->aiResponse,
                'recovery_answer' => $this->recoveryAnswer,
                'score' => $this->rating,
            ]);
            TrainingDialog::create([
                'user_message' => json_encode($this->userMessage),
                'ai_response' => json_encode($this->aiResponse),
                'recovery_answer' => json_encode($this->recoveryAnswer),
                'score' => $this->rating,
            ]);

            $this->reset(['userMessage', 'aiResponse', 'recoveryAnswer', 'rating']);
            session()->flash('success', 'Dialog saved successfully.');
        }
    }

    public function startSelfTraining()
    {
        // Notify training start using Filament notification
        Notification::make()
            ->title('Self-training started')
            ->success()
            ->send();

        // Retrieve saved training dialogs and filter/replicate based on score
        $dialogs = \App\Models\TrainingDialog::all()->flatMap(function ($dialog) {
            // Use AI response if score > 2, else use recovery answer if available
            if ($dialog->score > 2) {
                $dialogString = "User: {$dialog->user_message} AI: {$dialog->ai_response}";
                return [$dialogString];
            } elseif ($dialog->score <= 2 && $dialog->recovery_answer) {
                $dialogString = "User: {$dialog->user_message} AI: {$dialog->recovery_answer}";
                return [$dialogString];
            }
            // If score <= 2 and no recovery answer, skip
            return [];
        });

        $payload = [
            'training_dialogs' => $dialogs->values()->all(),
            'epochs' => 3,
            'batch_size' => 4,
        ];

        Log::info('Sending training dialogs to Python:', $payload);

        try {
            $response = Http::post('http://uvicorn_ai:80/self-train', $payload);
        }
        catch (\Exception $e) {
            Log::error('Self-training request failed: ' . $e->getMessage());
            Notification::make()
                ->title('Self-training failed')
                ->body('Exception: ' . $e->getMessage())
                ->danger()
                ->send();
            return;
        }

        if ($response->successful()) {
            Notification::make()
                ->title('Self-training completed successfully')
                ->success()
                ->send();
        } else {
            $errorMessage = $response->body();
            Log::error('Self-training failed: ' . $errorMessage);
            Notification::make()
                ->title('Self-training failed')
                ->body($errorMessage)
                ->danger()
                ->send();
        }
    }
}
