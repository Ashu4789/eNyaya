<?php

namespace App\Mail;

use App\Models\Hearing;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AdvocateHearingNotification extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Hearing $hearing)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Hearing Schedule & Case Agenda: Case #' . ($this->hearing->legalCase?->case_number ?? 'N/A'),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.advocate_hearing_notification',
        );
    }
}
