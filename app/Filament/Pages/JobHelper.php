<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Notifications\Notification;
use App\Models\Entreprise;
use App\Models\JobOffer;
use App\Services\EmbeddingService;
use App\Services\SelectorService;
use App\Services\MotivationLetterService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class JobHelper extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationGroup = 'Job Applications';
    protected static ?string $title = 'Job Helper';

    protected static string $view = 'filament.pages.job-helper';

    public $items = [];
    public $motivationLetter = '';

    protected SelectorService $selectorService;
    protected MotivationLetterService $motivationLetterService;

    public function mount()
    {
        $this->selectorService = app(SelectorService::class);
        $this->motivationLetterService = app(MotivationLetterService::class);

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
                'company_name' => $item->company ?? '',
                'description' => $item->description,
            ];
        });

        $this->items = $entreprises->concat($jobOffers)->toArray();
    }

    public function generateMotivationLetter(string $type, int $id, string $templateType, string $applicantName)
    {
        if ($type === 'Entreprise') {
            $entity = Entreprise::find($id);
            $targetData = [
                'company_name' => $entity->company_name ?? $entity->name,
                'work_domain' => $entity->work_domain ?? '',
            ];
        } else {
            $entity = JobOffer::find($id);
            $targetData = [
                'company_name' => $entity->company ?? '',
                'work_domain' => '', // optionally add domain if available
                'job_description' => $entity->description ?? '',
            ];
        }

        if (!$entity) {
            Notification::make()
                ->title('Error')
                ->body('Entity not found.')
                ->danger()
                ->send();
            return;
        }

        // Use selector service to get best matches
        $selectedItems = $this->selectorService->selectBestMatches($targetData);

        // Prepare data for motivation letter
        $data = [
            'company_name' => $targetData['company_name'],
            'skills' => implode(', ', $selectedItems['skills']),
            'experiences' => implode(', ', $selectedItems['experiences']),
            'formations' => implode(', ', $selectedItems['formations']),
            'applicant_name' => $applicantName,
        ];

        // Generate motivation letter
        $this->motivationLetter = $this->motivationLetterService->generateLetter($templateType, $data);

        Notification::make()
            ->title('Motivation Letter Generated')
            ->body('Motivation letter has been generated successfully.')
            ->success()
            ->send();
    }
}
