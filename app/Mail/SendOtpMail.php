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
        return new Envelope(
            subject: 'Your PhilNITS Prep verification code',
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
                'minutes' => OtpService::EXPIRATION_MINUTES,
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
