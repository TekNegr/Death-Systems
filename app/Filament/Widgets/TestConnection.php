<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TestConnection extends Widget
{
    protected static string $view = 'filament.widgets.test-connection';

    protected static ?string $pollingInterval = '5s';

    public ?bool $connectionStatus = null;

    public function testConnection()
    {
        Log::info('TestConnection - testConnection called');

        try {
            $response = Http::get('http://uvicorn_ai:80/test-connection');
            if ($response->successful()) {
                $this->connectionStatus = true;
                Notification::make()
                    ->title('Success')
                    ->body('Connection successful.')
                    ->success()
                    ->send();
            } else {
                $this->connectionStatus = false;
                Notification::make()
                    ->title('Error')
                    ->body('Connection failed.')
                    ->danger()
                    ->send();
            }
        } catch (\Exception $e) {
            $this->connectionStatus = false;
            Log::error('TestConnection - Exception during connection test: ' . $e->getMessage());
            Notification::make()
                ->title('Error')
                ->body('Exception during connection test.')
                ->danger()
                ->send();
        }
    }
}
