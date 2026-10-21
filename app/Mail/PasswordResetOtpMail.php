<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PasswordResetOtpMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $userName;
    public string $otp;
    public string $appName;

    public function __construct(string $userName, string $otp)
    {
        $this->userName = $userName;
        $this->otp = $otp;
        $this->appName = setting('app_name', config('app.name', 'Soil Testing Laboratory'));
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Your Password Reset OTP - {$this->appName}",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.password_reset_otp',
            with: [
                'userName' => $this->userName,
                'otp' => $this->otp,
                'appName' => $this->appName,
            ],
        );
    }
}
