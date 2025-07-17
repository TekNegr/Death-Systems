<?php 



namespace App\Filament\Widgets;

use Filament\Widgets\Widget;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Artisan;
use App\Models\TrainingDialog;
use App\Http\Controllers\AiController;
use Filament\Notifications\Notification;

class AiChecker extends Widget
{
    protected static string $view = 'filament.widgets.ai-checker';

    public int $trainingDialogCount = 0;
    public bool $trainedModelExists = false;

    public function mount(): void
    {
        $this->checkTrainedModel();
        $this->countTrainingDialogs();
    }

    public function checkTrainedModel(): void
    {
        $path = base_path('scripts/trained_model');
        $this->trainedModelExists = File::exists($path) && File::isDirectory($path);
    }

    public function countTrainingDialogs(): void
    {
        $this->trainingDialogCount = TrainingDialog::count();
    }

    public function seedAndTrain(): void
    {
        try {
            Artisan::call('db:seed', [
                '--class' => 'DatabaseSeeder',
                '--force' => true,
            ]);

            $aiController = app(AiController::class);
            $response = $aiController->startSelfTraining();

            if (method_exists($response, 'getStatusCode') && $response->getStatusCode() === 200) {
                $this->trainedModelExists = true;
                Notification::make()
                    ->title('AI Training started successfully')
                    ->success()
                    ->send();

              
            } elseif (isset($response['error'])) {
                Notification::make()
                    ->title('AI Training error')
                    ->body($response['error'])
                    ->danger()
                    ->send();
            } else {
                Notification::make()
                    ->title('AI Training error')
                    ->body('Unknown error occurred.')
                    ->danger()
                    ->send();
            } 
        } catch (\Exception $e) {
            Notification::make()
                ->title('AI Training error')
                ->body('An error occurred: ' . $e->getMessage())
                ->danger()
                ->send();
        }
    }

    
}
