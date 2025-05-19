<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Forms;
use Illuminate\Support\Facades\Mail;
use Filament\Notifications\Notification;

class SendEmail extends Page implements Forms\Contracts\HasForms
{
    use Forms\Concerns\InteractsWithForms;

    public ?string $to = null;
    public ?string $subject = null;
    public ?string $body = null;

    protected static ?string $navigationIcon = 'heroicon-o-paper-airplane';
    protected static ?string $navigationGroup = 'Gestion Mails';

    protected static string $view = 'filament.pages.send-email';

    protected function getFormSchema(): array
    {
        return [
            Forms\Components\TextInput::make('to')
                ->label("Destinataire")
                ->email()
                ->required(),

            Forms\Components\TextInput::make('subject')
                ->label("Sujet")
                ->required(),

            Forms\Components\Textarea::make('body')
                ->label("Message")
                ->rows(6)
                ->required(),
        ];
    }
    
    public function send(): void
    {
        $data = $this->form->getState();

        $response = \App\Http\Controllers\MailController::sendEmail($data['to'], $data['subject'], $data['body']);

        if (isset($response['messageId'])) {
            \Filament\Notifications\Notification::make()
                ->success()
                ->title('Mail envoyé avec succès !')
                ->send();
        } else {
            \Filament\Notifications\Notification::make()
                ->danger()
                ->title('Erreur lors de l\'envoi du mail.')
                ->body($response['message'] ?? 'Une erreur inconnue est survenue.')
                ->send();
        }
        redirect('/admin/mail-manager');

    }
}