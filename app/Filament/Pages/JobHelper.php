<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Notifications\Notification;
use App\Models\Entreprise;
use App\Models\JobOffer;
use App\Services\EmbeddingService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class JobHelper extends Page
{    
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationGroup = 'Job Applications';
    protected static ?string $title = 'Job Helper';

    protected static string $view = 'filament.pages.job-helper';

    public $items = [];

    public function mount()
    {
        $entreprises = Entreprise::all()->map(function ($item) {
            return [
                'id' => $item->id,
                'type' => 'Entreprise',
                'title' => 'Spontaneous Application',
                'name' => $item->name,
                'company_name' => $item->company_name,
                'description' => $item->work_domain,
            ];
        });

        $jobOffers = JobOffer::all()->map(function ($item) {
            return [
                'id' => $item->id,
                'type' => 'JobOffer',
                'title' => $item->title,
                'name' => $item->title,
                'company_name' => $item->company ? $item->company : '',
                'description' => $item->description,
            ];
        });

        $this->items = $entreprises->concat($jobOffers)->toArray();
    }


    public function testConnection()
    {
        Log::info('JobHelper - testConnection called');

        try {
            $response = Http::get('http://uvicorn_ai:80/test-connection');
            if ($response->successful()) {
                Notification::make()
                    ->title('Success')
                    ->body('Connection successful.')
                    ->success()
                    ->send();
            } else {
                Notification::make()
                    ->title('Error')
                    ->body('Connection failed.')
                    ->danger()
                    ->send();
            }
        } catch (\Exception $e) {
            Log::error('JobHelper - Exception during connection test: ' . $e->getMessage());
            Notification::make()
                ->title('Error')
                ->body('Exception during connection test.')
                ->danger()
                ->send();
        }
    }

    public function sendForEmbedding($type, $id)
    {
        Log::info("JobHelper - sendForEmbedding called with type: $type, id: $id");

        $embeddingService = app(EmbeddingService::class);

        if ($type === 'Entreprise') {
            $entity = Entreprise::find($id);
            $jobTitle = 'Spontaneous Application';
            $jobDescription = $entity->work_domain ?? '';
        } else {
            $entity = JobOffer::find($id);
            $jobTitle = $entity->title ?? '';
            $jobDescription = $entity->description ?? '';
        }

        if (!$entity) {
            Log::error("Entity not found for type: $type, id: $id");
            Notification::make()
                ->title('Error')
                ->body('Entity not found.')
                ->danger()
                ->send();
            return;
        }

        // Directly retrieve skills, experiences, and formations here instead of EmbeddingService
        $skills = \App\Models\Skills::all()->map(function ($skill) {
            return $skill->name . ' ' . ($skill->description ?? '');
        })->toArray();

        $formations = \App\Models\Formation::all()->map(function ($formation) {
            return $formation->school . ' ' . ($formation->degree ?? '') . ' ' . ($formation->field_of_study ?? '');
        })->toArray();

        $experiences = \App\Models\Experience::all()->map(function ($experience) {
            return $experience->company . ' ' . $experience->position;
        })->toArray();

        try {
            $response = Http::post('http://localhost:80/select-emphasis', [
                'job_title' => $jobTitle,
                'job_description' => $jobDescription,
                'skills' => $skills,
                'experiences' => $experiences,
                'formations' => $formations,
            ]);

            Log::info('JobHelper - Response from AI selector: ' . $response->body());

            if ($response->successful()) {
                $result = $response->json();
                Notification::make()
                    ->title('Emphasis Selection Result')
                    ->body("Skill: {$result['skill']['selected_text']} (score: {$result['skill']['similarity_score']}), " .
                           "Experience: {$result['experience']['selected_text']} (score: {$result['experience']['similarity_score']}), " .
                           "Formation: {$result['formation']['selected_text']} (score: {$result['formation']['similarity_score']})")
                    ->success()
                    ->send();
            } else {
                Log::error('AI selector service error: ' . $response->body());
                Notification::make()
                    ->title('Error')
                    ->body('AI selector service error.')
                    ->danger()
                    ->send();
            }
        } catch (\Exception $e) {
            Log::error('JobHelper - Exception calling AI selector: ' . $e->getMessage());
            Notification::make()
                ->title('Error')
                ->body('Exception calling AI selector.')
                ->danger()
                ->send();
        }
    }
}
