<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use App\Models\TrainingDialog;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;

class AiController extends Controller
{
    public function selectResource(Request $request)
    {
        $jobEmbedding = $request->input('job_embedding');
        $resourceEmbeddings = $request->input('resource_embeddings');

        Log::info('AIController - Received job embedding size: ' . count($jobEmbedding));
        Log::info('AIController - Received resource embeddings count: ' . count($resourceEmbeddings));

        try {
            $response = Http::post('http://uvicorn_ai:80/select-resource', [
                'job_embedding' => $jobEmbedding,
                'resource_embeddings' => $resourceEmbeddings,
            ]);

            Log::info('AIController - Response from AI service: ' . $response->body());

            if ($response->successful()) {
                return response()->json($response->json());
            } else {
                Log::error('AIController - AI service error: ' . $response->body());
                return response()->json(['error' => 'AI service error'], 500);
            }
        } catch (\Exception $e) {
            Log::error('AIController - Exception calling AI service: ' . $e->getMessage());
            return response()->json(['error' => 'Exception calling AI service'], 500);
        }
    }

    public function sendMessage(Request $request)
    {
        $prompt = $request->input('prompt');
        $maxLength = $request->input('max_length', 250);
        $modelType = $request->input('model_type', 'pretrained');
        $aiName = $request->input('ai_name', null);

        // Get authenticated user's name if available
        $userName = Auth::check() ? Auth::user()->name : 'User';

        $payload = [
            'prompt' => $prompt,
            'max_length' => $maxLength,
            'model_type' => $modelType,
            'ai_name' => $aiName,
            'user_name' => $userName,
        ];

        try {
            $response = Http::post('http://uvicorn_ai:80/generate-text', $payload);

            if ($response->successful()) {
                return response()->json(['generated_text' => $response->json()['generated_text'] ?? '']);
            } else {
                Log::error('AIController - generate-text error: ' . $response->body());
                return response()->json(['error' => 'AI service error'], 500);
            }
        } catch (\Exception $e) {
            Log::error('AIController - Exception calling generate-text: ' . $e->getMessage());
            return response()->json(['error' => 'Exception calling AI service'], 500);
        }
    }

    public function saveDialog(Request $request)
    {
        $userMessage = $request->input('user_message');
        $aiResponse = $request->input('ai_response');
        $recoveryAnswer = $request->input('recovery_answer');
        $score = $request->input('score');

        if ($userMessage && $aiResponse && $score) {
            try {
                TrainingDialog::create([
                    'user_message' => json_encode($userMessage),
                    'ai_response' => json_encode($aiResponse),
                    'recovery_answer' => json_encode($recoveryAnswer),
                    'score' => $score,
                ]);
                return response()->json(['message' => 'Dialog saved successfully']);
            } catch (\Exception $e) {
                Log::error('AIController - Error saving dialog: ' . $e->getMessage());
                return response()->json(['error' => 'Failed to save dialog'], 500);
            }
        } else {
            return response()->json(['error' => 'Missing required fields'], 400);
        }
    }

    public function toggleModel(Request $request)
    {
        $currentModel = $request->input('current_model', 'pretrained');
        $newModel = $currentModel === 'pretrained' ? 'custom' : 'pretrained';

        // You can store this state in session or database as needed
        // For now, just return the new model state
        return response()->json(['new_model' => $newModel]);
    }

    public function saveIdentity(Request $request)
    {
        $identityText = $request->input('identity_text');
        $path = base_path('scripts/identity.txt');

        try {
            file_put_contents($path, $identityText);
            return response()->json(['message' => 'AI Identity saved successfully']);
        } catch (\Exception $e) {
            Log::error('AIController - Failed to save AI Identity: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to save AI Identity'], 500);
        }
    }

    public function startSelfTraining()
    {
        $dialogs = TrainingDialog::all()->flatMap(function ($dialog) {
            if ($dialog->score > 2) {
                $dialogString = "User: {$dialog->user_message} AI: {$dialog->ai_response}";
                return [$dialogString];
            } elseif ($dialog->score <= 2 && $dialog->recovery_answer) {
                $dialogString = "User: {$dialog->user_message} AI: {$dialog->recovery_answer}";
                return [$dialogString];
            }
            return [];
        });

        $payload = [
            'training_dialogs' => $dialogs->values()->all(),
            'epochs' => 3,
            'batch_size' => 4,
        ];

        Log::info('AIController - Sending training dialogs to Python:', $payload);

        try {
            $response = Http::post('http://uvicorn_ai:80/self-train', $payload);

            if ($response->successful()) {
                return response()->json(['message' => 'Self-training completed successfully']);
            } else {
                Log::error('AIController - Self-training failed: ' . $response->body());
                return response()->json(['error' => 'Self-training failed'], 500);
            }
        } catch (\Exception $e) {
            Log::error('AIController - Self-training request failed: ' . $e->getMessage());
            return response()->json(['error' => 'Self-training request failed'], 500);
        }
    }
}
