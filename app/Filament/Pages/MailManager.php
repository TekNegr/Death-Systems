<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Illuminate\Support\Facades\Http;

class MailManager extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-envelope';
    protected static ?string $navigationGroup = 'Gestion Mails';

    protected static string $view = 'filament.pages.mail-manager';

    public array $messages = [];

    public function mount()
    {
        $this->loadMessages();
    }

    public function loadMessages()
    {
        // Appel API Mailpit (modifie l'URL selon l'environnement)
        $response = Http::get('http://mailpit:8025/api/messages');


        if ($response->successful()) {
            $this->messages = $response->json();
        }
    }

    public function goToSendEmail()
    {
        // Replace 'send-email' with the correct route name or URL
        return redirect('/admin/send-email');
    }
}
