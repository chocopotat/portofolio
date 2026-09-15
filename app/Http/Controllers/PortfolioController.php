<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Twilio\Rest\Client;

/**
 * Portfolio Contact Form Controller
 * 
 * Handles contact form submissions with validation and email notification
 * Lightweight, clean, and production-ready
 */
class PortfolioController extends Controller
{
    /**
     * Normalize a WhatsApp number for wa.me or Twilio.
     */
    protected function normalizeWhatsAppNumber(?string $number, bool $forTwilio = false): ?string
    {
        if (empty($number)) {
            return null;
        }

        $number = preg_replace('/\s+/', '', (string) $number);
        $number = preg_replace('/[^0-9+]/', '', $number);

        if ($number === '') {
            return null;
        }

        if (str_contains($number, 'whatsapp:')) {
            $number = str_replace('whatsapp:', '', $number);
        }

        if (str_starts_with($number, '+')) {
            return $forTwilio ? 'whatsapp:' . $number : ltrim($number, '+');
        }

        $digits = preg_replace('/\D+/', '', $number);
        if ($digits === '') {
            return null;
        }

        if (str_starts_with($digits, '0')) {
            $digits = '62' . substr($digits, 1);
        }

        if ($forTwilio) {
            return 'whatsapp:' . '+' . $digits;
        }

        return $digits;
    }

    /**
     * Build a WhatsApp message payload.
     */
    protected function buildWhatsAppMessage(array $data): string
    {
        return "Halo, saya {$data['name']}. Saya mengirim pesan dari portfolio website.\n\nEmail: {$data['email']}\nSubjek: {$data['subject']}\n\nPesan:\n{$data['message']}";
    }

    /**
     * Display the portfolio landing page
     * 
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('portfolio');
    }

    /**
     * Handle contact form submission via API
     * 
     * AJAX/Fetch endpoint that:
     * - Validates incoming data
     * - Sends notification email
     * - Returns JSON response (no page reload)
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     * 
     * @throws \Illuminate\Validation\ValidationException
     */
    public function submitContact(Request $request)
    {
        // ========================================
        // 1. VALIDATE INCOMING DATA
        // ========================================
        
        try {
            $validated = $request->validate([
                'name' => [
                    'required',
                    'string',
                    'min:2',
                    'max:100',
                    'regex:/^[\pL\s\'\-]+$/u', // Only letters, spaces, hyphens, apostrophes
                ],
                'email' => [
                    'required',
                    'email:rfc,dns', // Strict email validation with DNS check
                    'max:255',
                ],
                'subject' => [
                    'required',
                    'string',
                    'min:5',
                    'max:200',
                ],
                'message' => [
                    'required',
                    'string',
                    'min:10',
                    'max:5000',
                ],
            ], [
                'name.required' => 'Please provide your name',
                'name.regex' => 'Name can only contain letters, spaces, hyphens, and apostrophes',
                'email.required' => 'Please provide your email address',
                'email.email' => 'Please provide a valid email address',
                'subject.required' => 'Please provide a subject',
                'subject.min' => 'Subject must be at least 5 characters',
                'message.required' => 'Please provide a message',
                'message.min' => 'Message must be at least 10 characters',
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        }

        // ========================================
        // 2. SANITIZE INPUT
        // ========================================
        
        $validated['name'] = strip_tags($validated['name']);
        $validated['email'] = strtolower(trim($validated['email']));
        $validated['subject'] = strip_tags($validated['subject']);
        $validated['message'] = strip_tags($validated['message']);

        // ========================================
        // 3. RATE LIMITING (Optional but Recommended)
        // ========================================
        
        $rateLimitKey = 'portfolio_contact_' . $request->ip();
        
        if (cache()->has($rateLimitKey)) {
            return response()->json([
                'success' => false,
                'message' => 'Please wait before sending another message',
            ], 429);
        }

        cache()->put($rateLimitKey, true, 3600);

        $recipientWhatsApp = $this->normalizeWhatsAppNumber(env('WHATSAPP_PHONE_NUMBER'));
        $whatsappUrl = null;
        if (!empty($recipientWhatsApp)) {
            $text = urlencode($this->buildWhatsAppMessage($validated));
            $whatsappUrl = 'https://wa.me/' . $recipientWhatsApp . '?text=' . $text;
        }

        $emailSent = false;
        $whatsappSent = false;

        // ========================================
        // 4. SEND NOTIFICATION EMAIL
        // ========================================
        
        try {
            $mailPassword = (string) env('MAIL_PASSWORD', '');
            $gmailPlaceholder = str_contains($mailPassword, 'your_gmail') || str_contains($mailPassword, 'app_password') || trim($mailPassword) === '';

            if ($gmailPlaceholder) {
                Log::warning('SMTP mail is not configured. Skipping email send and continuing with WhatsApp fallback.');
            } else {
                Mail::send('emails.contact-notification', [
                    'name' => $validated['name'],
                    'email' => $validated['email'],
                    'subject' => $validated['subject'],
                    'message' => $validated['message'],
                ], function ($mail) use ($validated) {
                    $mail->to(config('app.contact_email', 'admin@example.com'))
                         ->replyTo($validated['email'])
                         ->subject('Portfolio Contact: ' . $validated['subject']);
                });

                Mail::send('emails.contact-confirmation', [
                    'name' => $validated['name'],
                ], function ($mail) use ($validated) {
                    $mail->to($validated['email'])
                         ->subject('Thank you for reaching out!');
                });

                $emailSent = true;
            }
        } catch (\Exception $e) {
            Log::error('Contact form email failed: ' . $e->getMessage());
        }

        // ========================================
        // 5. SEND DIRECT WHATSAPP MESSAGE VIA TWILIO (IF CREDENTIALS CONFIGURED)
        // ========================================
        
        $accountSid = env('TWILIO_ACCOUNT_SID') ?: env('TWILIO_SID');
        $authToken = env('TWILIO_AUTH_TOKEN');
        $from = env('TWILIO_WHATSAPP_FROM') ?: env('TWILIO_WHATSAPP_NUMBER');

        if (!empty($accountSid) && !empty($authToken) && !empty($from) && !empty($recipientWhatsApp)) {
            try {
                $client = new Client($accountSid, $authToken);

                $client->messages->create(
                    'whatsapp:' . '+' . ltrim($recipientWhatsApp, '+'),
                    [
                        'from' => $this->normalizeWhatsAppNumber($from, true),
                        'body' => $this->buildWhatsAppMessage($validated),
                    ]
                );

                $whatsappSent = true;
                Log::info('WhatsApp message sent via Twilio', ['to' => $recipientWhatsApp]);
            } catch (\Exception $e) {
                Log::error('Twilio WhatsApp message failed: ' . $e->getMessage());
            }
        }

        return response()->json([
            'success' => true,
            'message' => $whatsappSent ? 'Message sent successfully! I\'ll get back to you within 24 hours.' : 'Pesan berhasil dibuat. Silakan lanjutkan ke WhatsApp.',
            'email_sent' => $emailSent,
            'whatsapp_sent' => $whatsappSent,
            'whatsapp_url' => $whatsappUrl,
        ], 200);
    }

    /**
     * Send WhatsApp message via Twilio when credentials are configured.
     * Falls back to wa.me URL if Twilio is not available.
     */
    protected function sendWhatsAppNotification(array $data): array
    {
        $recipient = $this->normalizeWhatsAppNumber(env('WHATSAPP_PHONE_NUMBER'));
        $accountSid = env('TWILIO_ACCOUNT_SID') ?: env('TWILIO_SID');
        $authToken = env('TWILIO_AUTH_TOKEN');
        $from = env('TWILIO_WHATSAPP_FROM') ?: env('TWILIO_WHATSAPP_NUMBER');

        if (!empty($accountSid) && !empty($authToken) && !empty($from) && !empty($recipient)) {
            try {
                $client = new Client($accountSid, $authToken);
                $messageBody = $this->buildWhatsAppMessage($data);

                $client->messages->create(
                    'whatsapp:' . '+' . ltrim($recipient, '+'),
                    [
                        'from' => $this->normalizeWhatsAppNumber($from, true),
                        'body' => $messageBody,
                    ]
                );

                return [
                    'sent' => true,
                    'url' => null,
                ];
            } catch (\Throwable $e) {
                Log::error('WhatsApp send failed: ' . $e->getMessage());
            }
        }

        $whatsappUrl = null;
        if (!empty($recipient)) {
            $text = urlencode($this->buildWhatsAppMessage($data));
            $whatsappUrl = 'https://wa.me/' . $recipient . '?text=' . $text;
        }

        return [
            'sent' => false,
            'url' => $whatsappUrl,
        ];
    }
}
