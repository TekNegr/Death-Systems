<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

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
}
