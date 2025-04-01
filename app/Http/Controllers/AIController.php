<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AIController extends Controller
{
    public $fastApiUrl = 'http://127.0.0.1:5000/chat';

    public function startMessage(): string
    {
        return "Who dares summon me?";
    }

    public function getResponse(string $request):string
    {
        // $message = $request->input('message');

    try {
        $response = Http::timeout(10)->post($this->fastApiUrl, [
            'message' => $request,
        ]);

        if ($response->successful()) {
            return $response->json()['response'];
        } else {
            return response()->json(['error' => 'Erreur API'], 500);
        }
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
    }

    public function endMessage(): string
    {
        return "You have been warned. Do not summon me again.";
    }

    
}
