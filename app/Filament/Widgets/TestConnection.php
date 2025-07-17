<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Http;

class TestConnection extends Widget
{
    protected static string $view = 'filament.widgets.test-connection';

    public string $connectionStatus = 'unknown';

    public function mount(): void
    {
        $this->refreshStatus();
    }

    public function refreshStatus(): void
    {
        try {
            $response = Http::get('http://uvicorn_ai:80/test-connection');
            if ($response->successful() && isset($response->json()['message']) && $response->json()['message'] === 'Python-Interface connected!') {
                $this->connectionStatus = 'online';
            } else {
                $this->connectionStatus = 'offline';
            }
        } catch (\Exception $e) {
            $this->connectionStatus = 'offline';
        }
    }

    public function testConnection(): void
    {
        $this->refreshStatus();

        if ($this->connectionStatus === 'online') {
            Notification::make()
                ->title('Python service status')
                ->body('Python service is online')
                ->success()
                ->send();
        } else {
            Notification::make()
                ->title('Python service status')
                ->body('Python service is offline')
                ->danger()
                ->send();
        }
    }
}
