<?php

namespace App\Mail;

use App\Models\Hearing;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class JudgeHearingNotification extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Hearing $hearing)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Judicial Docket Alert: Case #' . ($this->hearing->legalCase?->case_number ?? 'N/A'),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.judge_hearing_notification',
        );
    }
}
