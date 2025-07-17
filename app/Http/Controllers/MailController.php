<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Log;

class MailController extends Controller
{
    /**
     * Send an email using Brevo REST API.
     *
     * @param string $to Recipient email address
     * @param string $subject Email subject
     * @param string $body Email body content
     * @return array API response
     */
    public static function sendEmail(string $to, string $subject, string $body): array
    {
        $apiKey = env('BREVO_API_KEY');
        $fromEmail = env('MAIL_FROM_ADDRESS');
        $fromName = env('MAIL_FROM_NAME', 'Your App');
        Log::info('Sending email via Brevo', [
            'to' => $to,
            'subject' => $subject,
            'body' => $body,
            'fromEmail' => $fromEmail,
            'fromName' => $fromName,
            'apiKey' => $apiKey,
        ]);

        $response = Http::withHeaders([
            'api-key' => $apiKey,
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ])->post('https://api.brevo.com/v3/smtp/email', [
            'sender' => [
                'name' => $fromName,
                'email' => $fromEmail,
            ],
            'to' => [
                [
                    'email' => $to,
                ],
            ],
            'subject' => $subject,
            'htmlContent' => nl2br(e($body)),
        ]);

        if (!$response->successful()) {
            // Log the error response for debugging
            Log::error('Brevo API error', [
                'status' => $response->status(),
                'body' => $response->body(),
                'to' => $to,
                'subject' => $subject,
            ]);
            return ['error' => 'Failed to send email'];
        }
        else {
            Log::info('Email sent successfully', [
                'to' => $to,
                'subject' => $subject,
                'response' => $response->json(),
            ]);
        }


        return $response->json();
    }

    /**
     * Send a templated email using Brevo REST API.
     *
     * @param string $to Recipient email address
     * @param string $templateName Template identifier (e.g., 'new_application', 'follow_up')
     * @param array $templateData Data to populate the template placeholders
     * @return array API response
     */
    public static function sendTemplatedEmail(string $to, string $subject, string $view, array $data = []): array
    {
        $apiKey = env('BREVO_API_KEY');
        $fromEmail = env('MAIL_FROM_ADDRESS');
        $fromName = env('MAIL_FROM_NAME', 'Your App');

        // Define templates content here or fetch from DB/config
        $htmlContent = View::make($view, $data)->render();

        $response = Http::withHeaders([
            'api-key' => $apiKey,
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ])->post('https://api.brevo.com/v3/smtp/email', [
            'sender' => [
                'name' => $fromName,
                'email' => $fromEmail,
            ],
            'to' => [
                [
                    'email' => $to,
                ],
            ],
            'subject' => $subject,
            'htmlContent' => $htmlContent,
        ]);

        return $response->json();
    }
}
