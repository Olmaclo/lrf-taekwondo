<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class CoachRejectedMail extends Mailable
{
    public function __construct(public User $coach) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Votre demande de compte coach — LRF Taekwondo',
        );
    }

    public function content(): Content
    {
        return new Content(view: 'emails.coach-rejected');
    }
}
