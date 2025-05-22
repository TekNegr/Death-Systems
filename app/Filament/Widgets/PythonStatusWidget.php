<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

class PythonStatusWidget extends Widget
{
    protected static string $view = 'filament.widgets.python-status-widget';

    public $status = 'unknown';

    public function mount()
    {
        $this->checkStatus();
    }

    public function checkStatus()
    {
        try {
            $response = file_get_contents('http://uvicorn_ai:80/test-connection');
            if ($response) {
                $this->status = 'online';
            } else {
                $this->status = 'offline';
            }
        } catch (\Exception $e) {
            $this->status = 'offline';
        }
    }

    public function refreshStatus()
    {
        $this->checkStatus();
    }
}
