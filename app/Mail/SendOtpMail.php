<?php

namespace App\Mail;

use App\Services\OtpService;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendOtpMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        public string $otpCode,
        public string $userName
    ) {}

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $subject = app(\App\Services\AppSettings::class)->get('mail_verification_subject', 'Your Paxi verification code');
        // Ensure security rule: never put the code in the subject
        $cleanSubject = str_replace($this->otpCode, '[CODE]', $subject);

        $senderName = app(\App\Services\AppSettings::class)->get('mail_sender_name', config('mail.from.name', 'Paxi'));
        $fromAddress = config('mail.from.address', 'hello@paxi.app');

        return new Envelope(
            from: new \Illuminate\Mail\Mailables\Address($fromAddress, $senderName),
            subject: $cleanSubject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        $firstName = explode(' ', trim($this->userName))[0] ?? $this->userName;

        return new Content(
            view: 'emails.otp',
            with: [
                'name' => $firstName,
                'code' => $this->otpCode,
                'minutes' => OtpService::getExpirationMinutes(),
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
