<?php

namespace App\Livewire;

use App\Http\Controllers\MailController;
use Illuminate\Support\Facades\Log;

class Contact extends Window
{
    public $name = '';
    public $email = '';
    public $message = '';
    public $feedback = '';

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'message' => 'required|string|max:2000',
        ];
    }

    public function mount(...$params)
    {
        parent::mount(...$params);
        // Set minimum size for Contact window
        if ($this->width < 690) {
            $this->width = 690;
        }
        if ($this->height < 440) {
            $this->height = 440;
        }
    }

    public function send()
    {
        $validatedData = $this->validate();

        $to = env('MAIL_FROM_ADDRESS');
        $subject = 'Contact Form Message from ' . $validatedData['name'];

        $response = MailController::sendTemplatedEmail($to, $subject, 'emails.contact-message', [
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'message' => $validatedData['message'],
        ]);

        if (isset($response['messageId'])) {
            Log::info('Contact form message sent successfully', [
                'to' => $to,
                'subject' => $subject,
                'messageId' => $response['messageId'],
            ]);
            $this->feedback = 'Message sent successfully!';
            $this->reset(['name', 'email', 'message']);
        } else {
            Log::error('Failed to send contact form message', [
                'to' => $to,
                'subject' => $subject,
                'response' => $response,
            ]);
            $this->feedback = 'Failed to send message. Please try again later.';
        }
    }

    public function render()
    {
        return view('livewire.contact');
    }
}
